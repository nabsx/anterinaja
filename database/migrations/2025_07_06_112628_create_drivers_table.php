<?php
// database/migrations/2024_01_01_000001_create_drivers_table.php
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
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->boolean('is_online')->default(false);
            $table->enum('status', ['available', 'busy'])->default('available');
            $table->string('vehicle_type')->nullable(); // motor, mobil
            $table->string('vehicle_plate')->nullable();
            $table->string('license_number')->nullable();
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('total_orders')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drivers');
    }
};