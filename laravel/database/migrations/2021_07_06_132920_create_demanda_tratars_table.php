<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandaTratarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demanda_tratars', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sistema_id');
            $table->foreign('sistema_id')->references('id')->on('demanda_sistemas');

            $table->unsignedBigInteger('unidade_id')->nullable();
            $table->char('matricula', 7);

            $table->integer('demanda_id');
            $table->longText('solicitacao');
            $table->longText('resposta')->nullable();

            $table->char('migracao', 1)->default('P'); // P = Pendente, C = Concluída

            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('users');

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
        Schema::dropIfExists('demanda_tratars');
    }
}
