<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Saleentry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_entry', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_id');
            $table->string('product_id');
            $table->string('product_quantity');
            $table->double('product_saleprice');
            $table->string('product_discount');
            $table->double('product_totalsaleprice');
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
        Schema::dropIfExists('sale_entry');
    }
}
