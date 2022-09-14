<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_price_whitelists', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 100);
            $table->float('min_price');
            $table->float('max_price');
            $table->foreignId('user_id');
            $table->foreignId('brand_id');
            $table->foreignId('marketplace_id');
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
        Schema::dropIfExists('catalog_price_whitelists');
    }
};