<?php
// app/Services/DistanceService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DistanceService
{
    private $osrmUrl = 'http://router.project-osrm.org/route/v1/driving';
    private $tarifPerKm = 2000; // Rp 2.000 per km

    public function calculateDistance($fromLat, $fromLng, $toLat, $toLng)
    {
        try {
            $url = "{$this->osrmUrl}/{$fromLng},{$fromLat};{$toLng},{$toLat}?overview=false";
            
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['routes'][0]['distance'])) {
                    $distanceMeters = $data['routes'][0]['distance'];
                    $distanceKm = $distanceMeters / 1000;
                    
                    return [
                        'distance_km' => round($distanceKm, 2),
                        'distance_meters' => $distanceMeters,
                        'duration' => $data['routes'][0]['duration'] ?? 0,
                    ];
                }
            }
            
            // Fallback to haversine formula if OSRM fails
            return $this->haversineDistance($fromLat, $fromLng, $toLat, $toLng);
            
        } catch (\Exception $e) {
            Log::error('Distance calculation error: ' . $e->getMessage());
            return $this->haversineDistance($fromLat, $fromLng, $toLat, $toLng);
        }
    }

    public function calculateOngkir($distanceKm)
    {
        return ceil($distanceKm) * $this->tarifPerKm;
    }

    private function haversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        $distance = $earthRadius * $c;
        
        return [
            'distance_km' => round($distance, 2),
            'distance_meters' => $distance * 1000,
            'duration' => 0,
        ];
    }
}