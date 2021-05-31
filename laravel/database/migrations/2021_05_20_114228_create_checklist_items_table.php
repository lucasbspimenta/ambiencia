<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->boolean('situacao',1)->default(true);
            $table->string('cor', 7)->nullable();
            $table->char('foto',1)->default('N');
            $table->unsignedBigInteger('item_pai_id')->nullable();
            $table->foreign('item_pai_id')->references('id')->on('checklist_items');

            $table->integer('ordem')->nullable();

            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->bigInteger('deleted_by')->nullable()->unsigned();
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->timestamps();

            $table->softDeletes();
            //$table->unique(['nome', 'item_pai_id', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklist_items');
    }
}
