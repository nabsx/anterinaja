<?php

namespace App\Enums;

enum OrderType: string
{
    case RIDE = 'ride';
    case DELIVERY = 'delivery';
}