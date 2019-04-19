<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id',false,true);
            $table->integer('discount_id',false,true);
            $table->string('name');
            $table->text('description');
            $table->string('img_src');
            $table->decimal('price', 13, 2);
            // $table->float('price_provider');
            $table->float('rating');
            $table->integer('vote_count');
            $table->boolean('is_visible');
            $table->boolean('is_bestseller');
            $table->datetime('arrive_date');
            $table->integer('available');
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
        Schema::dropIfExists('products');
    }
}
