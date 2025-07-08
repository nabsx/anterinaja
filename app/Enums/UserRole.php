<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case DRIVER = 'driver';
    case USER = 'user';
}