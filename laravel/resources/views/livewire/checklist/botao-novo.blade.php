@if($agendamentos_sem_checklist && $agendamentos_sem_checklist->count() > 0)
    <button id="botao_adicionar_topo" onClick="abrirModalChecklist();" class="btn btn-sm btn-primary" >
        <i class="fas fa-plus"></i>
        Novo checklist
    </button>
@else
    <button id="botao_adicionar_topo" onClick="abrirModalChecklist();" class="btn btn-sm btn-light disabled" >
        Não existem agendamentos sem checklist
    </button>
@endif
