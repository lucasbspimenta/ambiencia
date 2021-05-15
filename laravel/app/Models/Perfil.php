<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'usuario_perfil';

    public static function getIDsPorPerfilAttribute($perfil) {

        $perfis = array(
            'agente' => explode(',', env('PERFIL_RELOG')),
            'gestor' => explode(',', env('PERFIL_GESTOR')),
            'admin' => explode(',', env('PERFIL_ADMIN'))
        );

        return $perfis[$perfil];
    }

    public function getIsAdminAttribute() {

        if(in_array($this->id, self::getIDsPorPerfilAttribute('admin')))
            return true;

        return false;
    }

    public function getIsGestorAttribute() {

        if(in_array($this->id, self::getIDsPorPerfilAttribute('gestor')))
            return true;

        return false;
    }

    public function getIsRelogAttribute() {

        if(in_array($this->id, self::getIDsPorPerfilAttribute('agente')))
            return true;

        return false;
    }
}
