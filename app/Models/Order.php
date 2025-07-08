<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'driver_id', 'type', 
        'jemput_lat', 'jemput_lng', 'tujuan_lat', 'tujuan_lng',
        'alamat_jemput', 'alamat_tujuan', 'deskripsi', 
        'status', 'ongkir', 'jarak_km'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }
}