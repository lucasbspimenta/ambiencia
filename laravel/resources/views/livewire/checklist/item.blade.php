<div class="row" id="resposta-{{ $resposta->id }}" data-lw="@this">
    <div class="col" style="line-height: 43px;">
        <span class="text-caixaAzul">{{ $resposta->item->nome }}</span>
        @if ($resposta->item->load('guia')->guia)
            <a href="#" onclick="exibirGuia({{ $resposta->item->guia->id }})"><i
                    class="fas fa-question-circle fa-xs ml-1 text-black-50"></i></a>
        @endif
        @if ($resposta->item->foto == 'S')
            <i class="fas fa-lg fa-camera ml-2 text-black-50"></i>
        @endif
    </div>
    <div class="col col-auto">
        <span class="align-middle" style="line-height: 43px;">
            @if ($resposta->concluido)
                <i class="fas fa-lg fa-check-circle text-success"></i>
            @else
                <i class="fas fa-lg fa-hourglass-half text-info"></i>
            @endif
        </span>
    </div>
    <div class="col-5">
        <div class="btn-group btn-group-sm" data-toggle="buttons">

            <span @if ($resposta->demandas->count() > 0) data-tippy-content="Existem demandas vinculadas" @endif>
                <label
                    class="btn btn-light-blue btn-sm form-check-label {{ !is_null($resposta->resposta) && $resposta->resposta == 0 ? 'active' : '' }} @if ($resposta->demandas->count() > 0) disabled
                    btn-group-checklist-disabled @endif" >
                    <input wire:model="resposta.resposta" value="0" class="form-check-input" type="radio"
                        name="resposta_{{ $resposta->id }}" id="resposta_{{ $resposta->id }}_opt_1"
                        {{ !is_null($resposta->resposta) && $resposta->resposta == 0 ? 'checked' : '' }} @if ($resposta->demandas->count() > 0) disabled @endif>
                    N/A
                </label>
            </span>
            <label
                class="btn btn-light-blue btn-sm form-check-label {{ !is_null($resposta->resposta) && $resposta->resposta == 1 ? 'active' : '' }} @if ($resposta->demandas->count() > 0) disabled btn-group-checklist-disabled @endif" >
                <input wire:model="resposta.resposta" value="1" class="form-check-input" type="radio"
                    name="resposta_{{ $resposta->id }}" id="resposta_{{ $resposta->id }}_opt_2"
                    {{ !is_null($resposta->resposta) && $resposta->resposta == 1 ? 'checked' : '' }} @if ($resposta->demandas->count() > 0) disabled @endif> Conforme
            </label>
            <label
                class="btn btn-light-blue btn-sm form-check-label {{ !is_null($resposta->resposta) && $resposta->resposta == -1 ? 'active' : '' }}">
                <input wire:model="resposta.resposta" value="-1" class="form-check-input" type="radio"
                    name="resposta_{{ $resposta->id }}" id="resposta_{{ $resposta->id }}_opt_3"
                    {{ !is_null($resposta->resposta) && $resposta->resposta == -1 ? 'checked' : '' }}> Inconforme
            </label>
        </div>
    </div>
    <div style="width: 130px;">
        @if (!is_null($resposta->resposta) && $resposta->resposta == -1)
            <button id="botao_vincular_demanda" onClick="abrirDemanda(null, {{ $resposta->id }}, @this)"
                class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i>
                <span class="d-none d-xl-inline-block">Demanda</span>
            </button>
        @endif
    </div>
</div>
