<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard driver
     */
    public function index()
    {
        $driver = Auth::user();
        
        // Order aktif driver
        $activeOrder = Order::where('driver_id', $driver->id)
            ->whereIn('status', ['accepted', 'picked', 'on_ride', 'delivering'])
            ->latest()
            ->first();

        // Riwayat order
        $orderHistory = Order::where('driver_id', $driver->id)
            ->where('status', 'done')
            ->latest()
            ->take(5)
            ->get();

        // Statistik driver
        $stats = [
            'total_orders' => Order::where('driver_id', $driver->id)->count(),
            'completed_orders' => Order::where('driver_id', $driver->id)
                                ->where('status', 'done')
                                ->count(),
            'total_earnings' => Order::where('driver_id', $driver->id)
                                ->where('status', 'done')
                                ->sum('ongkir'),
            'avg_rating' => $driver->rating,
        ];

        return view('driver.dashboard', compact(
            'driver',
            'activeOrder',
            'orderHistory',
            'stats'
        ));
    }

    /**
     * Update status online/offline driver
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'is_online' => 'required|boolean'
        ]);

        Auth::user()->update([
            'is_online' => $request->is_online
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'is_online' => $request->is_online
        ]);
    }

    /**
     * Update lokasi driver
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'nullable|string'
        ]);

        Auth::user()->update([
            'current_lat' => $request->latitude,
            'current_lng' => $request->longitude,
            'current_address' => $request->address
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully'
        ]);
    }
}