<?php

namespace App\Http\Livewire\Painel;

use App\Http\Helpers\DateHelper;
use App\Models\User;
use App\Services\RelatoriosChecklistsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChecklistsPendentes extends Component
{
    public $checklists = [];
    public $data_inicio;
    public $data_final;
    public $total_registros = 0;
    public $subnivel = 0;
    public $nivel_exibido = 0;
    public $contador_nivel = [];

    protected $listeners = ['atualizarData' => 'atualizarData'];

    public function render()
    {
        return view('livewire.painel.checklists-pendentes');
    }

    public function mount()
    {
        $this->subnivel = 0;
        $this->total_registros = 0;
        $this->checklists = [];
        $this->data_inicio = DateHelper::getInicioTrimestre(Carbon::parse('now'));
        $this->data_final = DateHelper::getFinalTrimestre(Carbon::parse('now'));

        $this->carregaDados();
    }

    private function carregaDados()
    {

        $this->checklists = RelatoriosChecklistsService::Pendentes($this->data_inicio, $this->data_final);

        $this->total_registros = sizeof($this->checklists);

        $conta_unicos_responsavel = $this->checklists->unique('responsavel')->count();
        $conta_unicos_supervisor = $this->checklists->unique('supervisor')->count();
        $conta_unicos_equipe = $this->checklists->unique('equipe_id')->count();
        $conta_unicos_coordenador = $this->checklists->unique('coordenador')->count();

        if ($conta_unicos_coordenador > 1) {
            $this->subnivel = 4; // PERFIL MATRIZ
            $this->checklists = $this->checklists->groupBy('coordenador_nome');
            $this->checklists->each(function ($item, $key) {
                $this->contador_nivel['1|' . $key] = $item->count();
                $this->checklists[$key] = $item->groupBy('equipe_nome');
                $this->checklists[$key]->each(function ($item2, $key2) use ($key) {
                    $this->contador_nivel['2|' . $key2] = $item2->count();
                    $this->checklists[$key][$key2] = $item2->groupBy('supervisor_nome');
                    $this->checklists[$key][$key2]->each(function ($item3, $key3) use ($key, $key2) {
                        $this->contador_nivel['3|' . $key3] = $item3->count();
                        $this->checklists[$key][$key2][$key3] = $item3->groupBy('responsavel_nome');
                        $this->checklists[$key][$key2][$key3]->each(function ($item3, $key3) {
                            $this->contador_nivel['4|' . $key3] = $item3->count();
                        });
                    });
                });
            });
        } else {
            if ($conta_unicos_equipe > 1 || Auth::user()->is_gestorequipe) {
                $this->subnivel = 3; // PERFIL COORDENADOR
                $this->checklists = $this->checklists->groupBy('equipe_nome');
                $this->checklists->each(function ($item, $key) {
                    $this->contador_nivel['1|' . $key] = $item->count();
                    $this->checklists[$key] = $item->groupBy('supervisor_nome');
                    $this->checklists[$key]->each(function ($item2, $key2) use ($key) {
                        $this->contador_nivel['2|' . $key2] = $item2->count();
                        $this->checklists[$key][$key2] = $item2->groupBy('responsavel_nome');
                        $this->checklists[$key][$key2]->each(function ($item3, $key3) {
                            $this->contador_nivel['3|' . $key3] = $item3->count();
                        });
                    });
                });
            } else {
                if ($conta_unicos_supervisor > 1 || Auth::user()->is_gestorequipe) {

                    $this->subnivel = 2; // PERFIL SUPERVISOR
                    $this->checklists = $this->checklists->groupBy('supervisor_nome');
                    $this->checklists->each(function ($item, $key) {
                        $this->contador_nivel['1|' . $key] = $item->count();
                        $this->checklists[$key] = $item->groupBy('responsavel_nome');
                    });
                } else {
                    if ($conta_unicos_responsavel > 1) {
                        $this->subnivel = 1; // PERFIL RESPONSAVEL
                        $this->checklists = $this->checklists->groupBy('responsavel_nome');
                        $this->checklists->each(function ($item, $key) {
                            $this->contador_nivel['1|' . $key] = $item->count();
                        });
                    }
                }
            }
        }

        $this->nivel_exibido = $this->subnivel;
        //dd($this->checklists, $this->contador_nivel, Auth::user()->is_gestorequipe);

        $this->dispatchBrowserEvent('atualizarTreeview');
    }

    public function atualizarData($data_inicio, $data_final)
    {
        $this->data_inicio = Carbon::createFromFormat('Y-m-d', $data_inicio);
        $this->data_final = Carbon::createFromFormat('Y-m-d', $data_final);
        $this->carregaDados();
    }
}
