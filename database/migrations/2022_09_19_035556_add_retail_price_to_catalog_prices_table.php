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
        Schema::table('catalog_prices', function (Blueprint $table) {
            $table->float('retail_price', 10, 2)->after('product_name')->nullable();
        });
        Schema::table('catalog_price_temps', function (Blueprint $table) {
            $table->float('retail_price', 10, 2)->after('product_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalog_prices', function (Blueprint $table) {
            $table->dropColumn('retail_price');
        });
        Schema::table('catalog_price_temps', function (Blueprint $table) {
            $table->dropColumn('retail_price');
        });
    }
};
