<?php

namespace App\Scopes;

use App\Models\Agendamento;
use App\Models\Checklist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class UsuarioUnidadeScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check() && Auth::hasUser()) {
            switch ($model) {

                case $model instanceof Agendamento:
                    $this->filtroDinamicoPorModel($builder, 'agendamentos.unidade_id', 'unidades_responsavel.unidade_id');
                    break;

                case $model instanceof Checklist:
                    /*
                    $builder->join('agendamentos as a', 'agendamento_id','=', 'agendamentos.id');
                    $this->filtroDinamicoPorModel($builder, 'agendamentos.unidade_id','usuario_unidades.unidade_id');
                    $builder->select( DB::raw( 'checklists.*' ) );
                     */
                    $builder->whereIn('agendamento_id', function ($query) {

                        $query = $query->select('id')
                            ->from('agendamentos');

                        $this->filtroDinamicoPorModel($query, 'agendamentos.unidade_id', 'unidades_responsavel.unidade_id');
                    });

                    break;

                default:
                    $this->filtroDinamicoPorModel($builder, 'unidades.codigo', 'unidades_responsavel.unidade_codigo');
                    break;
            }
        }
    }

    private function filtroDinamicoPorModel(&$builder, String $campo_model, String $campo_tabela)
    {
        $builder
            ->join('unidades_responsavel', function ($join) use ($campo_model, $campo_tabela) {
                $usuario = Auth::user();
                if ($usuario->is_admin || $usuario->is_matriz) {
                    $join->on($campo_model, '=', $campo_tabela);
                } else {
                    $join->on($campo_model, '=', $campo_tabela)
                        ->where('unidades_responsavel.matricula', '=', Auth::user()->matricula)
                        ->orWhere('unidades_responsavel.coordenador', '=', Auth::user()->matricula)
                        ->orWhere('unidades_responsavel.supervisor', '=', Auth::user()->matricula);
                }
            });
    }

}
