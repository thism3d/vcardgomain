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
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('type')->nullable()->after('name');
            $table->integer('minimum_spend')->nullable()->after('type');   
            $table->integer('maximum_spend')->nullable()->after('minimum_spend');   
            $table->integer('per_user_limit')->nullable()->after('limit');   
            $table->date('expiry_date')->nullable()->after('per_user_limit');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
