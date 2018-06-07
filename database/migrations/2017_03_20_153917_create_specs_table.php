<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_id',false,true);
            $table->string('name');
            $table->string('value');
            $table->string('val_type');
            $table->string('category');
            $table->boolean('isComparable');
            $table->boolean('isFilterable');
            // $table->foreign('id')->references('id')->on('product_specs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specs');
    }
}
