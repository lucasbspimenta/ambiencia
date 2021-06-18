<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Perfil extends Model
{
    protected $table = 'usuario_perfil';

//    public function usuario()
//    {
//        return $this->hasOne(User::class,'matricula','matricula');
//    }

    public static function getIDsPorPerfilAttribute($perfil) {

        $perfis = array(
            'agente' => explode(',', config('app.PERFIL_RELOG')),
            'gestor' => explode(',', config('app.PERFIL_GESTOR')),
            'admin' => explode(',', config('app.PERFIL_ADMIN')),
            'matriz' => explode(',', config('app.PERFIL_MATRIZ'))
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

    public function getIsMatrizAttribute() {

        if(in_array($this->id, self::getIDsPorPerfilAttribute('matriz')))
            return true;

        return false;
    }
}
