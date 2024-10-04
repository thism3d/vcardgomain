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
        Schema::table('plans', function (Blueprint $table) {
            $table->string('is_trial')->nullable()->after('storage_limit');
            $table->integer('trial_day')->default(0)->after('is_trial');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('is_trial_plan')->default(0)->after('is_enable_login');         
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
