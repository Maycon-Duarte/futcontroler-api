<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ytbpartidas', function (Blueprint $table) {
            $table->id();
            $table->string('usuario');
            $table->string('ytb_id');
            $table->string('partida_id');
            $table->string('tags');
            $table->string('radio');
            $table->string('obs');
            $table->string('data');
            $table->string('hora_inicio');
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
        Schema::dropIfExists('ytbpartidas');
    }
};
