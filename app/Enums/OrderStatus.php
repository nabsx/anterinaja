<?php

namespace App\Enums;

enum OrderStatus: string
{
    case WAITING = 'waiting';
    case ACCEPTED = 'accepted';
    case PICKED = 'picked';
    case DELIVERING = 'delivering';
    case DONE = 'done';
    case CANCELLED = 'cancelled';
}