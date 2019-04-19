<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wishes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id',false,true);
            $table->integer('user_id',false,true);
            $table->decimal('price', 13, 2);
            $table->boolean('isAvailable');
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_wishes');
    }
}
