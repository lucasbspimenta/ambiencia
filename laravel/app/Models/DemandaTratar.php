<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DemandaTratar extends Model
{
    protected $fillable = [
        'resposta',
    ];

    public function sistema()
    {
        return $this->belongsTo(DemandaSistema::class, 'sistema_id', 'id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_id', 'id');
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'matricula', 'matricula');
    }

    protected static function boot()
    {
        parent::boot();
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }
}
