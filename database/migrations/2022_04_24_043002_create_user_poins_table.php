<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_poins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id', 100)->nullable();
            $table->string('persen', 5)->nullable();
            $table->double('shop')->nullable();
            $table->double('poin')->nullable();
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
        Schema::dropIfExists('user_poins');
    }
}
