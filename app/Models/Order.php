<?php
// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'driver_id',
        'type',
        'jemput_lat',
        'jemput_lng',
        'alamat_jemput',
        'tujuan_lat',
        'tujuan_lng',
        'alamat_tujuan',
        'deskripsi',
        'distance_km',
        'ongkir',
        'status',
        'receiver_name',
        'receiver_phone',
        'package_description',
        'accepted_at',
        'picked_at',
        'delivered_at',
    ];

    protected $casts = [
        'jemput_lat' => 'decimal:8',
        'jemput_lng' => 'decimal:8',
        'tujuan_lat' => 'decimal:8',
        'tujuan_lng' => 'decimal:8',
        'distance_km' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'accepted_at' => 'datetime',
        'picked_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['waiting', 'accepted', 'picked', 'delivering']);
    }

    public function isRide()
    {
        return $this->type === 'ride';
    }

    public function isDelivery()
    {
        return $this->type === 'delivery';
    }
}