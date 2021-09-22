<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGastosVeiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gastos_veiculos', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('vehicle_id');
            $table->integer('sec_id');
            $table->string('item');
            $table->string('valor');
            $table->string('data');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('gastos_veiculos');
    }
}
