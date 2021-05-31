<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ChecklistResposta extends Model
{
    use SoftDeletes;
    //protected $with = ['item'];
    //protected $withCount = ['demandas'];

    protected $fillable = [
        'checklist_item_id'
        , 'checklist_id'
        , 'resposta'
    ];

    public function checklist()
    {
        return $this->belongsTo(Checklist::class,'checklist_id','id');
    }

    public function item()
    {
        return $this->hasOne(ChecklistItem::class,'id','checklist_item_id');
    }

    public function demandas()
    {
        return $this->belongsToMany(Demanda::class,'demanda_checklist_resposta','checklist_resposta_id','demanda_id');
    }

    public function getConcluidoAttribute()
    {
        $resposta = !(is_null($this->resposta) && !$this->item->isMacroitem()) ?? true;
        $foto     = !($this->item->foto == 'S' && !isset($this->foto)) ?? true;
        $demandas = !(!is_null($this->resposta) && $this->resposta == -1 && $this->demandas->count() <= 0) ?? true;

        return $resposta && $foto && $demandas;
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::deleting(function($model) {
            $model->deleted_by = Auth::id();
            $model->demandas()->detach();
        });

    }
}
