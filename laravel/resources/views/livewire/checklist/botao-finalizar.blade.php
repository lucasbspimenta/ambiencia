<div style="width: 140px" class="ml-3 align-middle">
    @if($checklist->percentualPreenchimento < 100)
    <div class="progress md-progress mb-0" style="height: 31px; margin-top: 5px;" data-tippy-content="Você deve responder todos os itens para finalizar">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{$checklist->percentualPreenchimento}}%; height: 31px" aria-valuenow="{{$checklist->percentualPreenchimento}}" aria-valuemin="0" aria-valuemax="100"></div>
        <span class="text-caixaAzul" style="
              position: absolute;
              float: right;
              top: 15px;
              right: 0
            "
        >{{$checklist->percentualPreenchimento}}%</span>
    </div>
    @else
        <button wire:click.prevent="salvar" wire:loading.attr="disabled" type="button" class="btn btn-primary btn-sm" data-tippy-content="Ao finalizar você não poderá alterar as informações preenchidas" >
            Finalizar
        </button>
    @endif
</div>
