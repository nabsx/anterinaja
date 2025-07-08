<?php

namespace App\Services;

class PricingService
{
    protected $pricePerKm = 2000; // Rp2.000 per km

    public function calculatePrice($distanceKm)
    {
        return ceil($distanceKm) * $this->pricePerKm;
    }
}