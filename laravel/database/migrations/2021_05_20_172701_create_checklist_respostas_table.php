<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistRespostasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist_respostas', function (Blueprint $table) {
            $table->id();

            $table->integer('resposta')->nullable();

            $table->unsignedBigInteger('checklist_id');
            $table->foreign('checklist_id')->references('id')->on('checklists');

            $table->unsignedBigInteger('checklist_item_id');
            $table->foreign('checklist_item_id')->references('id')->on('checklist_items');

            $table->longText('foto')->nullable();

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
        Schema::dropIfExists('checklist_respostas');
    }
}
