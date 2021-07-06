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
            $table->longText('demanda_retorno')->nullable();
            $table->date('demanda_conclusao')->nullable();

            $table->longText('descricao')->nullable();

            $table->unsignedBigInteger('unidade_id');

            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('users');

            $table->timestamps();
        });

        DB::unprepared("
            ALTER TABLE [dbo].[demandas] ADD
            [TimeStart] DATETIME2(0)  GENERATED ALWAYS AS ROW START NOT NULL CONSTRAINT DFT_demandas_TimeStart DEFAULT ('19000101'),
            [TimeEnd] DATETIME2(0) GENERATED ALWAYS AS ROW END NOT NULL CONSTRAINT DFT_demandas_TimeEnd DEFAULT ('99991231 23:59:59'),
            PERIOD FOR SYSTEM_TIME ([TimeStart], [TimeEnd]);

            ALTER TABLE [dbo].[demandas] DROP CONSTRAINT DFT_demandas_TimeStart, DFT_demandas_TimeEnd;
            ALTER TABLE [dbo].[demandas]  SET ( SYSTEM_VERSIONING = ON ( HISTORY_TABLE = [dbo].[demandas_history] ) );
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("ALTER TABLE [dbo].[demandas] SET ( SYSTEM_VERSIONING = OFF );");
        Schema::dropIfExists('demandas');
    }
}
