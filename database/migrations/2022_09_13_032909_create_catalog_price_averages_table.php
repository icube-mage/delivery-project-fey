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
        Schema::create('catalog_price_averages', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 100);
            $table->float('average_price', 10, 2);
            $table->integer('total_record');
            $table->foreignId('user_id')->constrained();
            $table->string('brand');
            $table->string('marketplace');
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
        Schema::dropIfExists('catalog_price_averages');
    }
};
