<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SaleLedger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_ledger', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_id');
            $table->datetime('invoice_date');
            $table->string('customer_id');
            $table->string('total_item');
            $table->double('totalsaleprice');
            $table->string('totaldiscount');
            $table->double('paid');
            $table->double('due');
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
        Schema::dropIfExists('sale_ledger');
    }
}
