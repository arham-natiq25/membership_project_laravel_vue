<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->text('trip_name');
            $table->dateTime('trip_date');
            $table->dateTime('booking_close_date');
            $table->double('price');
            $table->dateTime('close_trip_booking');
            $table->dateTime('auto_activation_date');
            $table->boolean('status')->default(0);
            $table->boolean('night')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
