<?php
// database/migrations/2024_01_01_000002_create_orders_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', ['ride', 'delivery']);
            
            // Pickup location
            $table->decimal('jemput_lat', 10, 8);
            $table->decimal('jemput_lng', 11, 8);
            $table->text('alamat_jemput');
            
            // Destination location
            $table->decimal('tujuan_lat', 10, 8);
            $table->decimal('tujuan_lng', 11, 8);
            $table->text('alamat_tujuan');
            
            // Order details
            $table->text('deskripsi')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->decimal('ongkir', 10, 2);
            $table->enum('status', ['waiting', 'accepted', 'picked', 'delivering', 'done', 'cancelled'])->default('waiting');
            
            // Additional fields for delivery
            $table->string('receiver_name')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->text('package_description')->nullable();
            
            // Timestamps
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('picked_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};