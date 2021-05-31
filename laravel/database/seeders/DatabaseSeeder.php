<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsuarioSeeder::class);
        $this->call(AgendamentoTipoSeeder::class);
        $this->call(ChecklistItemSeeder::class);
        $this->call(GuiasSeeder::class);
        $this->call(DemandaSistemasSeeder::class);
        $this->call(AgendamentoSeeder::class);
        $this->call(ChecklistPreenchimentoSeeder::class);
    }
}
