<?php

namespace App\Models;

use App\Scopes\UsuarioUnidadeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    use HasFactory;

    public function getNomeCompletoAttribute()
    {
        return ($this->tipoPv) ? $this->tipoPv . ' ' . $this->nome : $this->tipo . ' ' . $this->nome;
    }

    public function demandas()
    {
        return $this->hasMany(Demanda::class,'unidade_id','id');
    }

    public function demandasEmAndamento()
    {
        return $this->hasMany(Demanda::class,'unidade_id','id')->whereNull('demanda_situacao');
    }

    public function responsavel()
    {
        return $this->hasOne(UnidadeResponsavel::class,'unidade_id','id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new UsuarioUnidadeScope);
    }
}
