<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demandas', function (Blueprint $table) {
            $table->id();

            $table->char('migracao', 1)->default('A'); // a = Aguardando finalizacao , p = Pendente, C = Concluída

            $table->unsignedBigInteger('sistema_id');
            $table->foreign('sistema_id')->references('id')->on('demanda_sistemas');

            $table->string('sistema_item_id')->nullable();

            $table->integer('demanda_id')->nullable();
            $table->string('demanda_url')->nullable();
            $table->string('demanda_situacao')->nullable();
            $table->date('demanda_prazo')->nullable();
            $table->date('demanda_prazo_inicial')->nullable();
            $table->date('demanda_conclusao')->nullable();

            $table->longText('descricao')->nullable();

            $table->unsignedBigInteger('unidade_id');

            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('demandas');
    }
}
