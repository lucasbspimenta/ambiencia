<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $with = ['perfil','equipe'];
    protected $appends = ['is_admin','is_gestor','is_relog'];

    protected $fillable = [
        'name',
        'email',
        'matricula',
        'fisica',
        'unidade',
        'funcao',
        'cargo'
    ];

    public function perfil()
    {
        return $this->hasOne(Perfil::class,'matricula','matricula');
    }

    public function equipe()
    {
        return $this->hasOne(Equipe::class,'matricula','matricula');
    }

    public function unidades()
    {
        if($this->perfil() && $this->perfil->is_admin) {

            $todas_unidades = DB::table('unidades')
                ->select([DB::raw('unidades.*'),DB::raw('usuario_unidades.matricula as laravel_through_key')])
                ->join('usuario_unidades','unidade_codigo','=','codigo')
                ->where('id','!=',null);

            return $this->hasManyThrough(
                Unidade::class,
                UserUnidade::class,
                'matricula', // Foreign key on the environments table...
                'codigo', // Foreign key on the deployments table...
                'matricula', // Local key on the projects table...
                'unidade_codigo' // Local key on the environments table...
            )->withoutGlobalScopes()->union($todas_unidades);
        }

        if($this->perfil() && $this->perfil->is_gestor) {
            if (isset($this->equipe)) {
                return $this->equipe->unidades();
            }
        }



        return $this->hasManyThrough(
            Unidade::class,
            UserUnidade::class,
            'matricula', // Foreign key on the environments table...
            'codigo', // Foreign key on the deployments table...
            'matricula', // Local key on the projects table...
            'unidade_codigo' // Local key on the environments table...
        )->withoutGlobalScopes();
    }

    public function getIsAdminAttribute() {

        return optional($this->perfil)->is_admin;
    }

    public function getIsGestorAttribute() {

        return optional($this->perfil)->is_gestor;
    }

    public function getIsRelogAttribute() {

        return optional($this->perfil)->is_relog;
    }
}
