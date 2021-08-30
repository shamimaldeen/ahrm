<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PurchaseEntry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_entry', function (Blueprint $table) {
            $table->increments('invoice_no');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('product_productinfo')->onDelete('cascade');
            $table->string('product_quantity');
            $table->string('product_purchaseprice');
            $table->string('product_totalpurchaseprice');
            $table->string('product_unitsaleprice');
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
        Schema::dropIfExists('purchase_entry');
    }
}
