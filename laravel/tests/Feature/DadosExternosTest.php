<?php

namespace Tests\Feature;

use App\Models\Equipe;
use App\Models\Unidade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DadosExternosTest extends TestCase
{
    use RefreshDatabase;

    public function test_conexao_com_a_tabela_rh()
    {
        $equipes = Equipe::all()->count();
        $this->assertGreaterThan(0,$equipes);
    }

    public function test_conexao_com_a_tabela_unidades()
    {
        $unidades = Unidade::all()->count();
        $this->assertGreaterThan(0,$unidades);
    }
}
