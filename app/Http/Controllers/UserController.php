<?php
// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function dashboard()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('user.dashboard', compact('orders'));
    }

    public function createOrder()
    {
        return view('user.create-order');
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'type' => 'required|in:ride,delivery',
            'jemput_lat' => 'required|numeric',
            'jemput_lng' => 'required|numeric',
            'alamat_jemput' => 'required|string',
            'tujuan_lat' => 'required|numeric',
            'tujuan_lng' => 'required|numeric',
            'alamat_tujuan' => 'required|string',
            'deskripsi' => 'nullable|string',
            'receiver_name' => 'required_if:type,delivery',
            'receiver_phone' => 'required_if:type,delivery',
            'package_description' => 'required_if:type,delivery',
        ]);

        $orderData = $request->all();
        $orderData['user_id'] = Auth::id();

        $order = $this->orderService->createOrder($orderData);

        return redirect()->route('user.orders.show', $order->id)
            ->with('success', 'Order berhasil dibuat!');
    }

    public function showOrder($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['driver', 'logs'])
            ->findOrFail($id);

        return view('user.order-detail', compact('order'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.orders', compact('orders'));
    }
}