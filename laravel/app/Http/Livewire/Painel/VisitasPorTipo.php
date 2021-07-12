<?php

namespace App\Http\Livewire\Painel;

use App\Http\Helpers\DateHelper;
use App\Models\AgendamentoTipo;
use App\Services\RelatoriosVisitasService;
use Carbon\Carbon;
use Livewire\Component;

class VisitasPorTipo extends Component
{
    public $tipos = [];
    public $total = [];
    public $visitas = [];
    public $data_inicio;
    public $data_final;
    public $contador_visitas_nivel = [];

    protected $listeners = ['atualizarData' => 'atualizarData'];

    public function render()
    {
        return view('livewire.painel.visitas-por-tipo');
    }

    public function mount()
    {
        $this->visitas = [];

        $this->tipos = AgendamentoTipo::where('situacao', 1)->get();
        $this->data_inicio = DateHelper::getInicioTrimestre(Carbon::parse('now'));
        $this->data_final = DateHelper::getFinalTrimestre(Carbon::parse('now'));

        $this->carregaDados();
    }

    public function atualizarData($data_inicio, $data_final)
    {
        $this->data_inicio = Carbon::createFromFormat('Y-m-d', $data_inicio);
        $this->data_final = Carbon::createFromFormat('Y-m-d', $data_final);
        $this->carregaDados();
    }

    private function carregaDados()
    {
        $this->visitas = RelatoriosVisitasService::RealizadasPorTipo($this->data_inicio, $this->data_final);

        $this->total = $this->visitas->groupBy('tipo_id')->map(function ($row) {
            return $row->sum('total_tipo');
        });

        $conta_unicos_responsavel = $this->visitas->unique('responsavel')->count();
        $conta_unicos_supervisor = $this->visitas->unique('supervisor')->count();
        $conta_unicos_equipe = $this->visitas->unique('equipe_id')->count();
        $conta_unicos_coordenador = $this->visitas->unique('coordenador')->count();

        if ($conta_unicos_coordenador > 1) {
            $this->visitas = $this->visitas->groupBy('coordenador_nome');
            $this->visitas->each(function ($item, $key) {
                $this->contador_visitas_nivel['1|' . $key] = $item->groupBy('tipo_id')->map(function ($row) {
                    return $row->sum('total_tipo');
                });
                $this->visitas[$key] = $item->groupBy('equipe_nome');
                $this->visitas[$key]->each(function ($item2, $key2) use ($key) {
                    $this->contador_visitas_nivel['2|' . $key2] = $item2->groupBy('tipo_id')->map(function ($row) {
                        return $row->sum('total_tipo');
                    });
                    $this->visitas[$key][$key2] = $item2->groupBy('supervisor_nome');
                    $this->visitas[$key][$key2]->each(function ($item3, $key3) use ($key, $key2) {
                        $this->contador_visitas_nivel['3|' . $key3] = $item3->groupBy('tipo_id')->map(function ($row) {
                            return $row->sum('total_tipo');
                        });
                        $this->visitas[$key][$key2][$key3] = $item3->groupBy('responsavel_nome');
                        $this->visitas[$key][$key2][$key3]->each(function ($item4, $key4) {
                            $this->contador_visitas_nivel['4|' . $key4] = $item4->groupBy('tipo_id')->map(function ($row) {
                                return $row->sum('total_tipo');
                            });
                        });
                    });
                });
            });
        } else {
            if ($conta_unicos_equipe > 1) {
                $this->visitas = $this->visitas->groupBy('equipe_nome');
                $this->visitas->each(function ($item2, $key2) {
                    $this->contador_visitas_nivel['2|' . $key2] = $item2->groupBy('tipo_id')->map(function ($row) {
                        return $row->sum('total_tipo');
                    });
                    $this->visitas[$key2] = $item2->groupBy('supervisor_nome');
                    $this->visitas[$key2]->each(function ($item3, $key3) use ($key2) {
                        $this->contador_visitas_nivel['3|' . $key3] = $item3->groupBy('tipo_id')->map(function ($row) {
                            return $row->sum('total_tipo');
                        });
                        $this->visitas[$key2][$key3] = $item3->groupBy('responsavel_nome');
                        $this->visitas[$key2][$key3]->each(function ($item4, $key4) {
                            $this->contador_visitas_nivel['4|' . $key4] = $item4->groupBy('tipo_id')->map(function ($row) {
                                return $row->sum('total_tipo');
                            });
                        });
                    });
                });
            } else {
                if ($conta_unicos_supervisor > 1) {
                    $this->visitas = $this->visitas->groupBy('supervisor_nome');
                    $this->visitas->each(function ($item3, $key3) {
                        $this->contador_visitas_nivel['3|' . $key3] = $item3->groupBy('tipo_id')->map(function ($row) {
                            return $row->sum('total_tipo');
                        });
                        $this->visitas[$key3] = $item3->groupBy('responsavel_nome');
                        $this->visitas[$key3]->each(function ($item4, $key4) {
                            $this->contador_visitas_nivel['4|' . $key4] = $item4->groupBy('tipo_id')->map(function ($row) {
                                return $row->sum('total_tipo');
                            });
                        });
                    });
                } else {
                    $this->visitas = $this->visitas->groupBy('responsavel_nome');
                    $this->visitas->each(function ($item4, $key4) {
                        $this->contador_visitas_nivel['4|' . $key4] = $item4->groupBy('tipo_id')->map(function ($row) {
                            return $row->sum('total_tipo');
                        });
                    });
                }
            }
        }
        //dd($this->contador_visitas_nivel);

        //dd($this->visitas);
    }
}
