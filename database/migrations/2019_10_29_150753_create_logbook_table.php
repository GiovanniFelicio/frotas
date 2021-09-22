<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogbookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logbook', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('dateTimeSai');
            $table->string('dateTimeCheg')->nullable();
            $table->integer('veiculo');
            $table->integer('func_id');
            $table->integer('setor_id');
            $table->integer('sec_id');
            $table->string('origem')->nullable();
            $table->string('kmInicial')->nullable();
            $table->string('destino')->nullable();
            $table->string('kmFinal')->nullable();
            $table->text('irregu')->nullable();
            $table->text('actions');
            $table->smallInteger('status')->default(1);
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
        Schema::dropIfExists('logbook');
    }
}
