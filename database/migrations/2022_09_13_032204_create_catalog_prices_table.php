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
        Schema::create('catalog_prices', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 100);
            $table->float('rrp')->comment('Price before discount');
            $table->float('cbp')->comment('Price after discount');
            $table->foreignId('user_id');
            $table->foreignId('brand_id');
            $table->foreignId('marketplace_id');
            $table->date('start_date');
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
        Schema::dropIfExists('catalog_prices');
    }
};