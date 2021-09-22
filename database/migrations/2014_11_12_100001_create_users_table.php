<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->integer('setor_id');
            $table->integer('sec_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->mediumInteger('level');
            $table->string('token_access')->nullable();
            $table->string('matricula');
            $table->string('barcode')->nullable();
            $table->smallInteger('authSpecial')->default(0);
            $table->tinyInteger('status')->default(1);
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
