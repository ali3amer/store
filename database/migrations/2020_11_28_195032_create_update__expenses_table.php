<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('update__expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('expense_id')->unsigned();
            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');
            $table->string('name');
            $table->float('price',8, 2);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('update__expenses');
    }
}
