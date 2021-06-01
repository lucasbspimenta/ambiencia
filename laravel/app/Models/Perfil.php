<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Perfil extends Model
{
    protected $table = 'usuario_perfil';

    public function usuario()
    {
        return $this->hasOne(User::class,'matricula','matricula');
    }

    public static function getIDsPorPerfilAttribute($perfil) {

        $perfis = array(
            'agente' => explode(',', env('PERFIL_RELOG')),
            'gestor' => explode(',', env('PERFIL_GESTOR')),
            'admin' => explode(',', env('PERFIL_ADMIN'))
        );

        return $perfis[$perfil];
    }

    public function getIsAdminAttribute() {

        if(strtoupper($this->usuario->equipe->nome) == 'SISTEMAS')
            return true;

        if(in_array($this->id, self::getIDsPorPerfilAttribute('admin')))
            return true;

        return false;
    }

    public function getIsGestorAttribute() {

        if(strtoupper($this->usuario->equipe->nome) == 'SISTEMAS')
            return true;

        if(in_array($this->id, self::getIDsPorPerfilAttribute('gestor')))
            return true;

        return false;
    }

    public function getIsRelogAttribute() {

        if(strtoupper($this->usuario->equipe->nome) == 'SISTEMAS')
            return true;

        if(in_array($this->id, self::getIDsPorPerfilAttribute('agente')))
            return true;

        return false;
    }
}
