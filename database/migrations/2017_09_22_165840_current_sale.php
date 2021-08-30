<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CurrentSale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_currentsale', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_id');
            $table->string('product_id');
            $table->string('product_quantity');
            $table->string('product_saleprice');
            $table->string('product_purchaseprice');
            $table->string('product_discount');
            $table->string('product_totalsaleprice');
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
        Schema::dropIfExists('sale_currentsale');
    }
}
