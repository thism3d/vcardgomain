<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('qrsettings')) {
            Schema::create('qrsettings', function (Blueprint $table) {
                $table->id();
                $table->string('type')->nullable();
                $table->text('json')->nullable();
                $table->integer('total_scan')->nullable();
                $table->integer('template_id')->nullable();
                $table->integer('created_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qrsettings');
    }
};
