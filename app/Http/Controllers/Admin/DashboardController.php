<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_drivers' => User::where('role', 'driver')->count(),
            'total_orders' => Order::count(),
            'revenue' => Order::where('status', 'done')->sum('ongkir'),
        ];

        $recentOrders = Order::with(['user', 'driver'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}