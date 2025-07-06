<?php
// app/Models/Driver.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lat',
        'lng',
        'is_online',
        'status',
        'vehicle_type',
        'vehicle_plate',
        'license_number',
        'rating',
        'total_orders',
    ];

    protected $casts = [
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'is_online' => 'boolean',
        'rating' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'driver_id', 'user_id');
    }

    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}