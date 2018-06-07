<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('wishes_id',false,true);
            // $table->integer('mails_id',false,true);
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('currency');
            $table->string('language');
            $table->string('tel')->nullable();
            $table->string('adr')->nullable();
            $table->string('pan')->nullable();
            $table->unsignedTinyInteger('bstate')->default(1);
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
