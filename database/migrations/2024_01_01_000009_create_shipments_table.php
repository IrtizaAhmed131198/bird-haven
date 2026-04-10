<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('tracking_number')->unique();
            $table->string('stage')->default('in_flight');
            $table->string('temperature')->nullable();
            $table->string('oxygen')->nullable();
            $table->date('estimated_delivery')->nullable();
            $table->date('hatchery_date')->nullable();
            $table->date('health_date')->nullable();
            $table->date('flight_date')->nullable();
            $table->date('local_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
