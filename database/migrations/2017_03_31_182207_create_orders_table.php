<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {	
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id',false,true)->nullable()->default(0);
            $table->uuid('cart_uuid');
            $table->decimal('price', 13, 2);
            $table->string('currency_type')->default('UAH');
            $table->integer('visa_an')->default(0);
            $table->string('visa_rrn')->default('0');
            $table->string('name');
            $table->string('email')->default('');
            $table->string('phone');
            $table->string('deliver_type');
            $table->string('deliver_adr');
            $table->string('payment_type');
            $table->string('payment_state')->default('pending');
            $table->string('order_state')->default('pending');
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
        Schema::dropIfExists('orders');
    }
}
