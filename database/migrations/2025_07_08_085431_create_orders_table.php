<?php

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
            $table->string('type'); // ride or delivery
            $table->decimal('jemput_lat', 10, 8);
            $table->decimal('jemput_lng', 11, 8);
            $table->decimal('tujuan_lat', 10, 8);
            $table->decimal('tujuan_lng', 11, 8);
            $table->text('alamat_jemput');
            $table->text('alamat_tujuan');
            $table->text('deskripsi')->nullable();
            $table->string('status')->default('waiting');
            $table->decimal('ongkir', 12, 2);
            $table->decimal('jarak_km', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};