<?php
// app/Services/OrderService.php
namespace App\Services;

use App\Models\Order;
use App\Models\Driver;
use App\Models\OrderLog;
use App\Services\DistanceService;

class OrderService
{
    protected $distanceService;

    public function __construct(DistanceService $distanceService)
    {
        $this->distanceService = $distanceService;
    }

    public function createOrder($data)
    {
        // Calculate distance and ongkir
        $distance = $this->distanceService->calculateDistance(
            $data['jemput_lat'],
            $data['jemput_lng'],
            $data['tujuan_lat'],
            $data['tujuan_lng']
        );

        $ongkir = $this->distanceService->calculateOngkir($distance['distance_km']);

        $order = Order::create([
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'jemput_lat' => $data['jemput_lat'],
            'jemput_lng' => $data['jemput_lng'],
            'alamat_jemput' => $data['alamat_jemput'],
            'tujuan_lat' => $data['tujuan_lat'],
            'tujuan_lng' => $data['tujuan_lng'],
            'alamat_tujuan' => $data['alamat_tujuan'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'distance_km' => $distance['distance_km'],
            'ongkir' => $ongkir,
            'receiver_name' => $data['receiver_name'] ?? null,
            'receiver_phone' => $data['receiver_phone'] ?? null,
            'package_description' => $data['package_description'] ?? null,
        ]);

        $this->logOrderStatus($order, 'waiting', 'Order created');

        return $order;
    }

    public function acceptOrder($orderId, $driverId)
    {
        $order = Order::find($orderId);
        
        if (!$order || $order->status !== 'waiting') {
            return false;
        }

        $driver = Driver::where('user_id', $driverId)->first();
        
        if (!$driver || !$driver->is_online || $driver->status !== 'available') {
            return false;
        }

        $order->update([
            'driver_id' => $driverId,
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        $driver->update(['status' => 'busy']);

        $this->logOrderStatus($order, 'accepted', 'Order accepted by driver');

        return true;
    }

    public function updateOrderStatus($orderId, $status, $notes = null)
    {
        $order = Order::find($orderId);
        
        if (!$order) {
            return false;
        }

        $timestampField = null;
        
        switch ($status) {
            case 'picked':
                $timestampField = 'picked_at';
                break;
            case 'done':
                $timestampField = 'delivered_at';
                // Set driver back to available
                if ($order->driver_id) {
                    Driver::where('user_id', $order->driver_id)->update(['status' => 'available']);
                }
                break;
        }

        $updateData = ['status' => $status];
        if ($timestampField) {
            $updateData[$timestampField] = now();
        }

        $order->update($updateData);

        $this->logOrderStatus($order, $status, $notes);

        return true;
    }

    private function logOrderStatus($order, $status, $notes = null)
    {
        OrderLog::create([
            'order_id' => $order->id,
            'status' => $status,
            'notes' => $notes,
        ]);
    }
}