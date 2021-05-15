<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    use HasFactory;

    protected $table = 'usuario_equipe';

    public function usuarios()
    {
        return $this->hasMany(User::class,'matricula','matricula');
    }

    public function unidades()
    {
        return $this->hasManyThrough(
            Unidade::class,
            EquipeUnidade::class,
            'nome', // Foreign key on the environments table...
            'codigo', // Foreign key on the deployments table...
            'nome', // Local key on the projects table...
            'unidade' // Local key on the environments table...
        );
    }
}
