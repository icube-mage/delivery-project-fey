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
            $table->uuid('upload_hash');
            $table->string('sku', 100);
            $table->string('product_name');
            $table->float('discount_price', 10, 2);
            $table->foreignId('user_id')->constrained();
            $table->string('brand');
            $table->string('marketplace');
            $table->boolean('is_whitelist')->default(false)->nullable();
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
