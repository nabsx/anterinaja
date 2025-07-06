<?php
// app/Http/Controllers/Api/PriceCalculatorController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DistanceService;
use Illuminate\Http\Request;

class PriceCalculatorController extends Controller
{
    protected $distanceService;

    public function __construct(DistanceService $distanceService)
    {
        $this->distanceService = $distanceService;
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'pickup_lat' => 'required|numeric',
            'pickup_lng' => 'required|numeric',
            'dest_lat' => 'required|numeric',
            'dest_lng' => 'required|numeric',
        ]);

        $distance = $this->distanceService->calculateDistance(
            $request->pickup_lat,
            $request->pickup_lng,
            $request->dest_lat,
            $request->dest_lng
        );

        $price = $this->distanceService->calculateOngkir($distance['distance_km']);

        return response()->json([
            'success' => true,
            'distance_km' => $distance['distance_km'],
            'price' => $price,
            'duration' => $distance['duration'],
        ]);
    }
}