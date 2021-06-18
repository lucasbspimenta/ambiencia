<?php

namespace App\Http\Livewire\Painel;

use App\Http\Helpers\DateHelper;
use App\Services\RelatoriosVisitasService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VisitasPorPeriodo extends Component
{
    public $visitas = [];
    public $data_inicio;
    public $data_final;
    public $total_unidades = 0;
    public $total_visitado = 0;
    public $total_percentual_visitado = 0;
    public $subnivel = 0;
    public $nivel_exibido = 0;
    public $contador_unidades_nivel = [];
    public $contador_visitas_nivel = [];

    protected $listeners = ['atualizarData' => 'atualizarData'];

    public function render()
    {
        return view('livewire.painel.visitas-por-periodo');
    }

    public function mount()
    {
        $this->subnivel = 0;
        $this->total_registros = 0;
        $this->visitas = [];
        $this->data_inicio = DateHelper::getInicioTrimestre(Carbon::parse('now'));
        $this->data_final = DateHelper::getFinalTrimestre(Carbon::parse('now'));

        $this->carregaDados();
    }

    private function carregaDados()
    {
        $this->visitas = RelatoriosVisitasService::Realizadas($this->data_inicio, $this->data_final);

        $this->total_unidades = $this->visitas->sum('total_unidades');
        $this->total_visitado = $this->visitas->sum('total_visitado');
        $this->total_percentual_visitado = ($this->visitas->sum('total_visitado') * 100) / ($this->total_unidades > 0 ? $this->total_unidades : 1);

        $conta_unicos_responsavel   = $this->visitas->unique('responsavel')->count();
        $conta_unicos_supervisor    = $this->visitas->unique('supervisor')->count();
        $conta_unicos_equipe        = $this->visitas->unique('equipe_id')->count();
        $conta_unicos_coordenador   = $this->visitas->unique('coordenador')->count();

        if($conta_unicos_coordenador > 1)
        {
            $this->visitas = $this->visitas->groupBy('coordenador_nome');
            $this->visitas->each(function ($item, $key) {
                $this->contador_unidades_nivel['1|'.$key] = $item->sum('total_unidades');
                $this->contador_visitas_nivel['1|'.$key] = $item->sum('total_visitado');
                $this->visitas[$key] = $item->groupBy('equipe_nome');
                $this->visitas[$key]->each(function ($item2, $key2) use($key) {
                    $this->contador_unidades_nivel['2|'.$key2] = $item2->sum('total_unidades');
                    $this->contador_visitas_nivel['2|'.$key2] = $item2->sum('total_visitado');
                    $this->visitas[$key][$key2] = $item2->groupBy('supervisor_nome');
                    $this->visitas[$key][$key2]->each(function ($item3, $key3) use($key, $key2) {
                        $this->contador_unidades_nivel['3|'.$key3] = $item3->sum('total_unidades');
                        $this->contador_visitas_nivel['3|'.$key3] = $item3->sum('total_visitado'); 
                        /*
                        $this->visitas[$key][$key2][$key3] = $item3->groupBy('responsavel_nome');
                        $this->visitas[$key][$key2][$key3]->each(function ($item4, $key4) {
                            $this->contador_unidades_nivel['4|'.$key4] = $item4->sum('total_unidades');
                            $this->contador_visitas_nivel['4|'.$key4] = $item4->sum('total_visitado');                    
                        });                
                        */
                        
                    });
                });
            });
        }
        else
        {
            if($conta_unicos_equipe > 1)
            {
                $this->visitas = $this->visitas->groupBy('equipe_nome');
                $this->visitas->each(function ($item2, $key2) {
                    $this->contador_unidades_nivel['1|'.$key2] = $item2->sum('total_unidades');
                    $this->contador_visitas_nivel['1|'.$key2] = $item2->sum('total_visitado');
                    $this->visitas[$key2] = $item2->groupBy('supervisor_nome');
                    $this->visitas[$key2]->each(function ($item3, $key3) use($key2) {
                        $this->contador_unidades_nivel['2|'.$key3] = $item3->sum('total_unidades');
                        $this->contador_visitas_nivel['2|'.$key3] = $item3->sum('total_visitado'); 
                        /*
                        $this->visitas[$key][$key2][$key3] = $item3->groupBy('responsavel_nome');
                        $this->visitas[$key][$key2][$key3]->each(function ($item4, $key4) {
                            $this->contador_unidades_nivel['4|'.$key4] = $item4->sum('total_unidades');
                            $this->contador_visitas_nivel['4|'.$key4] = $item4->sum('total_visitado');                    
                        });                
                        */
                        
                    });
                });
            }
            else
            {
                if($conta_unicos_supervisor > 1)
                {
                    $this->visitas = $this->visitas->groupBy('supervisor_nome');
                    $this->visitas->each(function ($item3, $key3) {
                        $this->contador_unidades_nivel['1|'.$key3] = $item3->sum('total_unidades');
                        $this->contador_visitas_nivel['1|'.$key3] = $item3->sum('total_visitado'); 
                        /*
                        $this->visitas[$key][$key2][$key3] = $item3->groupBy('responsavel_nome');
                        $this->visitas[$key][$key2][$key3]->each(function ($item4, $key4) {
                            $this->contador_unidades_nivel['4|'.$key4] = $item4->sum('total_unidades');
                            $this->contador_visitas_nivel['4|'.$key4] = $item4->sum('total_visitado');                    
                        });                
                        */
                        
                    });
                }
            }
        }

        //dd($this->visitas, $this->contador_unidades_nivel, $this->contador_visitas_nivel);

        $this->dispatchBrowserEvent('atualizarTreeview');
    }

    public function atualizarData($data_inicio, $data_final)
    {
        $this->data_inicio  = Carbon::createFromFormat('Y-m-d', $data_inicio);
        $this->data_final  = Carbon::createFromFormat('Y-m-d', $data_final);
        $this->carregaDados();
    }
}
