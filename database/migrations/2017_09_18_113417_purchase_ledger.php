<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PurchaseLedger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_ledger', function (Blueprint $table) {
            $table->increments('invoice_no');
            $table->string('invoice_date');
            $table->string('voucher_no');
            $table->string('voucher_date');
            $table->string('total_item');
            $table->string('net_total_ammount');
            $table->string('paid');
            $table->string('due');
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
        Schema::dropIfExists('purchase_ledger');
    }
}
