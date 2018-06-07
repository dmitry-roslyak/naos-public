<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id',false,true);
            $table->float('price');
            $table->string('currency_type');
            $table->integer('sales');
            $table->date('date');
            //$table->timestamps();
            // $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices_histories');
    }
}
