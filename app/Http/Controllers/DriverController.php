<?php
// app/Http/Controllers/DriverController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Driver;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function dashboard()
    {
        $driver = Driver::where('user_id', Auth::id())->first();
        $activeOrder = Order::where('driver_id', Auth::id())
            ->whereIn('status', ['accepted', 'picked', 'delivering'])
            ->first();

        $availableOrders = Order::waiting()->limit(10)->get();

        return view('driver.dashboard', compact('driver', 'activeOrder', 'availableOrders'));
    }

    public function toggleOnlineStatus()
    {
        $driver = Driver::where('user_id', Auth::id())->first();
        $driver->update(['is_online' => !$driver->is_online]);

        return response()->json(['status' => $driver->is_online]);
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $driver = Driver::where('user_id', Auth::id())->first();
        $driver->update([
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        return response()->json(['success' => true]);
    }

    public function acceptOrder($id)
    {
        $success = $this->orderService->acceptOrder($id, Auth::id());

        if ($success) {
            return redirect()->route('driver.dashboard')
                ->with('success', 'Order berhasil diterima!');
        }

        return redirect()->route('driver.dashboard')
            ->with('error', 'Gagal menerima order!');
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:picked,delivering,done',
        ]);

        $success = $this->orderService->updateOrderStatus($id, $request->status, $request->notes);

        if ($success) {
            return redirect()->route('driver.dashboard')
                ->with('success', 'Status order berhasil diupdate!');
        }

        return redirect()->route('driver.dashboard')
            ->with('error', 'Gagal mengupdate status order!');
    }

    public function orderHistory()
    {
        $orders = Order::where('driver_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('driver.order-history', compact('orders'));
    }
}