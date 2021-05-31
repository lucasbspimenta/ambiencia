<?php

namespace App\Scopes;

use App\Models\Agendamento;
use App\Models\Checklist;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsuarioUnidadeScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        Auth::check();
        if (Auth::hasUser()) {
            switch ($model) {

                case $model instanceof Agendamento:
                    $this->filtroDinamicoPorModel($builder, 'agendamentos.unidade_id','usuario_unidades.unidade_id');
                    break;

                case $model instanceof Checklist:
                    /*
                    $builder->join('agendamentos as a', 'agendamento_id','=', 'agendamentos.id');
                    $this->filtroDinamicoPorModel($builder, 'agendamentos.unidade_id','usuario_unidades.unidade_id');
                    $builder->select( DB::raw( 'checklists.*' ) );
                    */
                    $builder->whereIn('agendamento_id', function ($query)  {

                        $query = $query->select('id')
                            ->from('agendamentos');

                        $this->filtroDinamicoPorModel($query, 'agendamentos.unidade_id','usuario_unidades.unidade_id');
                    });

                    break;

                default:
                    $this->filtroDinamicoPorModel($builder, 'unidades.codigo','usuario_unidades.unidade_codigo');
                    break;
            }
        }
    }

    private function filtroDinamicoPorModel(&$builder, String $campo_model, String $campo_tabela){
        $builder
            ->join('usuario_unidades', function ($join) use ($campo_model, $campo_tabela) {
                $join->on($campo_model, '=', $campo_tabela)
                    ->where('usuario_unidades.matricula', '=', Auth::user()->matricula);
            });
    }

}
