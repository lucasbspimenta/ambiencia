<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandaChecklistRespostaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demanda_checklist_resposta', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('demanda_id');
            $table->unsignedBigInteger('checklist_resposta_id');

            $table->foreign('checklist_resposta_id')->references('id')->on('checklist_respostas');
            $table->foreign('demanda_id')->references('id')->on('demandas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demanda_checklist_resposta');
    }
}
