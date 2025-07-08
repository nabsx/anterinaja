<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Vehicle Info
            $table->string('vehicle_type');
            $table->string('vehicle_number');
            $table->string('vehicle_color')->nullable();
            $table->string('vehicle_model')->nullable();
            
            // Driver Status
            $table->boolean('is_online')->default(false);
            $table->enum('status', ['available', 'busy', 'inactive'])->default('available');
            
            // Location Data
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('location_address')->nullable();
            
            // Driver Stats
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_rides')->default(0);
            $table->integer('total_deliveries')->default(0);
            
            // Documents
            $table->string('driver_license')->nullable();
            $table->string('vehicle_registration')->nullable();
            $table->string('insurance')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['is_online', 'status']);
            $table->index(['lat', 'lng']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('drivers');
    }
};