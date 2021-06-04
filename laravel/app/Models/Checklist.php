<?php

namespace App\Models;

use App\Scopes\UsuarioUnidadeScope;
use App\Services\ChecklistService;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Checklist extends Model
{
    use SoftDeletes;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $with = ['agendamento'];

    public static function VALIDATION_RULES($ignore_id=null)
    {
        return [
            'agendamento_id' =>
                [
                    'required',
                    'integer',
                    'exists:agendamentos,id',
                    (!is_null($ignore_id) ?
                        Rule::unique('checklists')->where(function ($query) {
                            return $query->whereNull('deleted_at');
                        })->ignore($ignore_id) :
                        Rule::unique('checklists')->where(function ($query) {
                            return $query->whereNull('deleted_at');
                        })
                    )
                ]
                    //(!is_null($ignore_id) ? 'unique:checklists,agendamento_id,'.$ignore_id : 'unique:checklists,agendamento_id')],
        ];
    }

    public const VALIDATION_MESSAGES = [
        'agendamento_id.required' => 'Obrigatório vincular um agendamento',
        'agendamento_id.integer' => 'Agendamento informado é inválido',
        'agendamento_id.exists' => 'Agendamento infomado não existe',
        'agendamento_id.unique' => 'Já existe checklist para este agendamento',
    ];

    protected $fillable = [
        'agendamento_id'
    ];

    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class, 'agendamento_id');
    }

    public function respostas()
    {
        return $this->hasMany(ChecklistResposta::class, 'checklist_id', 'id');
    }

    public function fotosObrigatorias()
    {
        return $this->hasMany(ChecklistResposta::class, 'checklist_id', 'id')
            ->join(app(ChecklistItem::class)->getTable(), app(ChecklistResposta::class)->getTable().'.checklist_item_id', '=', app(ChecklistItem::class)->getTable() . '.id')
            ->where(app(ChecklistItem::class)->getTable().'.foto','S')
            ->select(app(ChecklistResposta::class)->getTable().'.*');;
    }

    public function respostasMacroitem($macroitem_id)
    {
        return $this->hasMany(ChecklistResposta::class, 'checklist_id', 'id')
            ->join(app(ChecklistItem::class)->getTable(), app(ChecklistResposta::class)->getTable().'.checklist_item_id', '=', app(ChecklistItem::class)->getTable() . '.id')
            ->where(app(ChecklistItem::class)->getTable() . '.item_pai_id', $macroitem_id)
            ->select(app(ChecklistResposta::class)->getTable().'.*');
    }

    private function ids_itens_pais() {
        return ChecklistResposta::join(app(ChecklistItem::class)->getTable(), app(ChecklistResposta::class)->getTable().'.checklist_item_id', '=', app(ChecklistItem::class)->getTable() . '.id')
            ->select('item_pai_id')
            ->distinct()
            ->get()
            ->toArray();
    }

    public function getMacroitensAttribute()
    {
        return ChecklistItem::whereIn('id', $this->ids_itens_pais())->get();
    }

    /*
    public function demandas2()
    {
        return $this->hasManyThrough(
            Demanda::class,
            ChecklistResposta::class,
            'checklist_id', // Foreign key on the environments table...
            'checklist_resposta_id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }
    */

    public function demandas()
    {
        //return $this->hasManyDeep('App\Models\Demanda', ['demanda_checklist_resposta', 'App\Models\ChecklistResposta', 'permission_role']);
        return $this->hasManyDeep('App\Models\Demanda', ['App\Models\ChecklistResposta', 'demanda_checklist_resposta'])->select('demandas.*')->distinct();
    }


    public function getPercentualPreenchimentoAttribute()
    {
        $sql = "SELECT
                    DISTINCT
                       rbr.[checklist_id]
                       ,COUNT([id]) OVER (PARTITION BY checklist_id) as total
                       ,SUM([respondido]) OVER (PARTITION BY checklist_id) as total_respondido
                       , percentual_preenchimento = CAST((SUM([respondido]) OVER (PARTITION BY checklist_id) * 100.00) / COUNT([id]) OVER (PARTITION BY checklist_id) as decimal(16,2))
                  FROM [relatorio_base_respostas] rbr
                  WHERE rbr.[checklist_id] = '". $this->id ."'";

        $dados = collect(DB::select($sql))->first();
        return (float)$dados->percentual_preenchimento;
        /*
        $respostas = $this->respostas;
        $total_itens = $respostas->count();
        $concluidos  = $respostas->where('concluido', 1)->count();

        $percentual = ($total_itens > 0) ? ($concluidos * 100) / $total_itens : 0;

        return round($percentual,2,PHP_ROUND_HALF_ODD);
        */
    }

    public static function boot() {

        parent::boot();

        static::addGlobalScope(new UsuarioUnidadeScope);

        static::deleting(function($model) {
            $model->respostas()->delete();

            $model->deleted_by = Auth::id();
        });

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    protected static function booted()
    {
        static::created(function ($checklist)
        {
            try{
                $cheklistService = new ChecklistService();
                $cheklistService->vincularItensAoChecklist($checklist);
                $checklist->refresh();
            }catch (Exception $e){
                $checklist->forceDelete();
                throw new Exception('Não foi possivel vincular itens ao checklist. Tente novamente. Checklist será excluído' . $e->getMessage());
            }
        });
    }
}
