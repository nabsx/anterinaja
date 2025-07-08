<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\DistanceService;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $distanceService;
    protected $pricingService;

    public function __construct(DistanceService $distanceService, PricingService $pricingService)
    {
        $this->distanceService = $distanceService;
        $this->pricingService = $pricingService;
    }

    public function dashboard()
    {
        $user = Auth::user();
        $activeOrder = $user->orders()
            ->whereIn('status', ['waiting', 'accepted', 'picked', 'on_ride', 'delivering'])
            ->latest()
            ->first();

        $orderHistory = $user->orders()
            ->whereIn('status', ['done', 'cancelled'])
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('activeOrder', 'orderHistory'));
    }

    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        return view('user.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('user.orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:ride,delivery',
            'jemput_lat' => 'required|numeric',
            'jemput_lng' => 'required|numeric',
            'tujuan_lat' => 'required|numeric',
            'tujuan_lng' => 'required|numeric',
            'alamat_jemput' => 'required|string',
            'alamat_tujuan' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        $distance = $this->distanceService->calculateDistance(
            $validated['jemput_lat'],
            $validated['jemput_lng'],
            $validated['tujuan_lat'],
            $validated['tujuan_lng']
        );

        if (!$distance) {
            return back()->with('error', 'Failed to calculate distance. Please try again.');
        }

        $ongkir = $this->pricingService->calculatePrice($distance);

        $order = Order::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'jemput_lat' => $validated['jemput_lat'],
            'jemput_lng' => $validated['jemput_lng'],
            'tujuan_lat' => $validated['tujuan_lat'],
            'tujuan_lng' => $validated['tujuan_lng'],
            'alamat_jemput' => $validated['alamat_jemput'],
            'alamat_tujuan' => $validated['alamat_tujuan'],
            'deskripsi' => $validated['deskripsi'],
            'status' => 'waiting',
            'ongkir' => $ongkir,
            'jarak_km' => $distance,
        ]);

        return redirect()->route('user.orders.show', $order)->with('success', 'Order created successfully');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('user.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);
        
        $order->update(['status' => 'cancelled']);
        $order->logs()->create(['status' => 'cancelled']);
        
        return back()->with('success', 'Order cancelled successfully');
    }
}