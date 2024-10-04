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
        Schema::create('order_requests', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 100)->unique();
            $table->integer('nfc_card_id')->nullable();
            $table->integer('business_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->float('price', 30, 2)->default(0);
            $table->string('status', 100)->default('in progress');
            $table->integer('user_id')->nullable();
            $table->integer('approval')->nullable();
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_request');
    }
};
