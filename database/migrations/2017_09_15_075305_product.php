<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_productinfo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('brand_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('product_item')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('product_category')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('product_brand')->onDelete('cascade');
            $table->string('product_name');
            $table->string('measurement_type');
            $table->string('purchase_price')->integer();
            $table->string('sale_price')->integer();
            $table->string('image'); 
            $table->string('barcode'); 
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
        Schema::dropIfExists('product_productinfo');
    }
}
