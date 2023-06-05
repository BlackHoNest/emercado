<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farms', function (Blueprint $table) {
            $table->id();
            $table->integer('seller_id');
            $table->string('farm_province');
            $table->string('farm_municipality');
            $table->string('farm_barangay');
            $table->string('farm_coordinates');
            $table->string('farm_size');
            $table->string('farm_type');
            $table->string('farm_crops')->nullable();
            $table->string('farm_livestocks')->nullable();
            $table->string('farm_vegetables')->nullable();
            $table->string('farm_products')->nullable();
            $table->string('gross_harvest');
            $table->string('net_harvest');
            $table->string('beneficiary')->nullable();
            $table->string('beneficiary_specify')->nullable();
            $table->string('aid_received')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farms');
    }
}
