<?php

namespace Tests\Feature;

use App\Models\EquipeUnidade;
use App\Models\Perfil;
use App\Models\Unidade;
use App\Models\User;
use Tests\TestCase;

class UsuariosTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_unidades_do_agente_de_ambiencia()
    {
        $total_unidades = Unidade::count();

        $user = User::whereHas('perfil', function($q){
            $q->whereIn('id', Perfil::getIDsPorPerfilAttribute('agente'));
        })->first();

        $this->assertTrue($user->perfil->is_relog);

        $this->assertGreaterThan(0, $user->unidades->count());
        $this->assertLessThan($total_unidades, $user->unidades->count());

    }

    public function test_unidades_do_agente_do_gestor()
    {
        $total_unidades = Unidade::count();

        $user = User::whereHas('perfil', function($q){
            $q->whereIn('id', Perfil::getIDsPorPerfilAttribute('gestor'));
        })->first();

        $this->assertTrue($user->perfil->is_gestor);

        $unidades_equipe = EquipeUnidade::where('nome',$user->equipe->nome)->count();

        $this->assertGreaterThan(0, $user->unidades->count());
        $this->assertEquals($unidades_equipe, $user->unidades->count());
        $this->assertLessThan($total_unidades, $user->unidades->count());
    }
}
