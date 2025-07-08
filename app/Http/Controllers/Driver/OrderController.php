<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function dashboard()
    {
        $driver = Auth::user();
        $activeOrder = $driver->driverOrders()
            ->whereIn('status', ['accepted', 'picked', 'on_ride', 'delivering'])
            ->latest()
            ->first();

        $orderHistory = $driver->driverOrders()
            ->where('status', 'done')
            ->latest()
            ->take(5)
            ->get();

        return view('driver.dashboard', compact('driver', 'activeOrder', 'orderHistory'));
    }

    public function index()
    {
        $orders = Auth::user()->driverOrders()->latest()->paginate(10);
        return view('driver.orders.index', compact('orders'));
    }

    public function availableOrders()
    {
        $orders = Order::where('status', 'waiting')
            ->whereDoesntHave('driver')
            ->latest()
            ->paginate(10);

        return view('driver.orders.available', compact('orders'));
    }

    public function acceptOrder(Request $request, Order $order)
    {
        if ($order->status !== 'waiting') {
            return back()->with('error', 'Order is no longer available');
        }

        if (Auth::user()->driverOrders()->whereIn('status', ['accepted', 'picked'])->exists()) {
            return back()->with('error', 'You already have an active order');
        }

        $order->update([
            'driver_id' => Auth::id(),
            'status' => 'accepted',
        ]);

        $order->logs()->create(['status' => 'accepted']);

        return redirect()->route('driver.orders.show', $order)
            ->with('success', 'Order accepted successfully');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:picked,on_ride,delivering,done',
        ]);

        $order->update(['status' => $validated['status']]);
        $order->logs()->create(['status' => $validated['status']]);

        return back()->with('success', 'Order status updated');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('driver.orders.show', compact('order'));
    }
}