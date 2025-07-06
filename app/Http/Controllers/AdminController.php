<?php
// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Driver;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_drivers' => User::where('role', 'driver')->count(),
            'total_orders' => Order::count(),
            'active_orders' => Order::active()->count(),
            'online_drivers' => Driver::online()->count(),
        ];

        $recentOrders = Order::with(['user', 'driver'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }

    public function users()
    {
        $users = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function drivers()
    {
        $drivers = User::where('role', 'driver')
            ->with('driver')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.drivers', compact('drivers'));
    }

    public function orders()
    {
        $orders = Order::with(['user', 'driver'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.orders', compact('orders'));
    }

    public function showOrder($id)
    {
        $order = Order::with(['user', 'driver', 'logs'])
            ->findOrFail($id);

        return view('admin.order-detail', compact('order'));
    }
}