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
        Schema::create('catalog_price_temps', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 100);
            $table->string('name');
            $table->float('rrp')->comment('Price before discount');
            $table->float('cbp')->comment('Price after discount');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('marketplace_id')->constrained();
            $table->boolean('is_whitelist')->default(false);
            $table->boolean('is_negative')->default(false)->comment('CBP inputted is wrong');
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
        Schema::dropIfExists('catalog_price_temps');
    }
};
