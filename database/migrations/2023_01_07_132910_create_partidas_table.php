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
        Schema::create('partidas', function (Blueprint $table) {
            $table->id();
            $table->text('campeonato');
            $table->text('campeonato_id');
            $table->text('casa');
            $table->text('fora');
            $table->integer('gols_casa')->default(0);
            $table->integer('gols_fora')->default(0);
            $table->integer('penaltis_casa')->default(0);;
            $table->integer('penaltis_fora')->default(0);;
            $table->text('status_jogo');
            $table->text('link');
            $table->boolean('intervalo')->default(false);
            $table->boolean('penalti')->default(false);
            $table->boolean('prorrogacao')->default(false);
            $table->boolean('encerrado')->default(false);
            $table->text('data');
            $table->text('hora_inicio');
            $table->text('token');
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
        Schema::dropIfExists('partidas');
    }
};
