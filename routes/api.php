<?php
// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PriceCalculatorController;

Route::post('/calculate-price', [PriceCalculatorController::class, 'calculate']);