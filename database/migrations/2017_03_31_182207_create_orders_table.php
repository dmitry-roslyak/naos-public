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
            $table->integer('user_id',false,true);
            $table->uuid('cart_uuid');
            $table->decimal('price', 13, 2);
            $table->integer('visa_an');
            $table->string('visa_rrn');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('deliver_type');
            $table->string('deliver_adr');
            $table->string('payment_type');
            $table->string('payment_state');
            $table->string('order_state');
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
