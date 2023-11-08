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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('first_date');
            $table->integer('first_price');
            $table->string('second_date');
            $table->integer('second_price');
            $table->string('third_date');
            $table->integer('third_price');
            $table->boolean('limit')->default(false);
            $table->boolean('status')->default(false);
            $table->integer('display_order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
