<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DistanceService
{
    protected $baseUrl = 'http://router.project-osrm.org/route/v1/driving/';

    public function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $response = Http::get($this->baseUrl."{$lng1},{$lat1};{$lng2},{$lat2}?overview=false");

        if ($response->successful()) {
            $data = $response->json();
            if ($data['code'] === 'Ok' && count($data['routes']) > 0) {
                return $data['routes'][0]['distance'] / 1000; // Convert to km
            }
        }

        return null;
    }
}