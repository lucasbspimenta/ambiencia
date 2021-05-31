<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendamentoTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamento_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->boolean('situacao')->default(true);
            $table->string('cor', 7)->nullable();
            $table->integer('ordem')->default(0);
            $table->integer('com_checklist')->default(0);

            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->bigInteger('deleted_by')->nullable()->unsigned();
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->softDeletes();
            //$table->unique(['nome', 'deleted_at']);

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
        Schema::dropIfExists('agendamento_tipos');
    }
}
