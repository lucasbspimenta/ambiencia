<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuiaItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guia_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('guia_id');
            $table->foreign('guia_id')->references('id')->on('guias');

            $table->string('pergunta');
            $table->string('resposta')->nullable();

            $table->char('situacao',1)->default('A');

            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->bigInteger('deleted_by')->nullable()->unsigned();
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->softDeletes();

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
        Schema::dropIfExists('guia_items');
    }
}
