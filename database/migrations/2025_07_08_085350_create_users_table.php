<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // Primary Key
            $table->id();

            // Authentication
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            
            // User Profile
            $table->string('name');
            $table->string('phone')->unique()->nullable();
            $table->string('avatar')->nullable();
            
            // Role Management
            $table->enum('role', ['user', 'driver', 'admin'])->default('user');
            
            // Location Data (digunakan untuk semua role)
            $table->decimal('current_lat', 10, 8)->nullable();
            $table->decimal('current_lng', 11, 8)->nullable();
            $table->string('current_address')->nullable();
            
            // Driver-specific Fields (nullable, akan diisi jika register sebagai driver)
            $table->string('vehicle_type')->nullable(); // motor, mobil, dll
            $table->string('vehicle_number')->nullable();
            $table->boolean('is_online')->default(false);
            $table->decimal('rating', 3, 2)->nullable()->default(0); // 0.00 - 5.00
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // Untuk soft delete
            
            // Indexes
            $table->index(['role', 'is_online']); // Untuk query driver available
            $table->index(['current_lat', 'current_lng']); // Untuk spatial query
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};