<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandaSistemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demanda_sistemas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('conexao'); // NOME REGISTRADO NO config/database.php
            $table->string('categorias_table')->nullable()->default(null);
            $table->string('categorias_campo_id')->nullable()->default(null);
            $table->string('categorias_campo_texto')->nullable()->default(null);
            $table->string('categorias_filtros')->nullable()->default(null);
            $table->string('subcategorias_table')->nullable()->default(null);
            $table->string('subcategorias_campo_id')->nullable()->default(null);
            $table->string('subcategorias_campo_texto')->nullable()->default(null);
            $table->string('subcategorias_filtros')->nullable()->default(null);
            $table->string('subcategorias_campo_id_categoria')->nullable()->default(null);
            $table->string('itens_table')->nullable()->default(null);
            $table->string('itens_campo_id')->nullable()->default(null);
            $table->string('itens_campo_texto')->nullable()->default(null);
            $table->string('itens_filtros')->nullable()->default(null);
            $table->string('itens_campo_id_categoria')->nullable()->default(null);
            $table->string('itens_campo_id_subcategoria')->nullable()->default(null);
            $table->string('service_class_name')->default(null);
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
        Schema::dropIfExists('demanda_sistemas');
    }
}
