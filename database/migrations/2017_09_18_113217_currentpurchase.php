<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Currentpurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_currentpurchase', function (Blueprint $table) {
            $table->increments('invoice_no');
            $table->string('invoice_date');
            $table->string('voucher_no');
            $table->string('voucher_date');
            $table->integer('suplier_id')->unsigned();
            $table->foreign('suplier_id')->references('id')->on('suplier_info')->onDelete('cascade');
            $table->string('suplier_phone');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('product_productinfo')->onDelete('cascade');
            $table->string('product_quantity');
            $table->string('product_purchaseprice');
            $table->string('product_totalpurchaseprice');
            $table->string('product_unitsaleprice');
            $table->string('product_adminid');
            $table->string('session_id');
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
        Schema::dropIfExists('purchase_currentpurchase');
    }
}
