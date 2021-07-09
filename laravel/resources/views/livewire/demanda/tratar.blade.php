<div>
    <div class="modal-header">
        <h4 class="modal-title w-100 text-caixaAzul text-futurabold" id="modal_tratar_demanda">
            @if($demanda && !$demanda->resposta)Atender @else Visualizar @endif Demanda
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div wire:loading
            style="position: absolute; width: 100%; height: 100%; background-color: #f5f5f5; z-index: 1; opacity:0.5; left: 0; top: 0;">
            <div class="align-middle d-flex justify-content-center align-items-stretch">
                <img class="mt-5" src="{{ asset('images/ajax-loader-mini.gif') }}" />
            </div>
        </div>
        <div class="mb-2 text-sm">
            <form>
                <input type="hidden" wire:model.defer="demanda_id" name="demanda_id" />
                <div class="form-row">
                    <div class="mb-3 col-md-4 ">
                        <label class="active">Demanda:</label>
                        <span class="text-caixaAzul d-block">{{ optional($demanda->sistema)->nome }}</span>
                    </div>
                    <div class="mb-3 col-md-4 ">
                        <label class="active">Unidade:</label>
                        <span class="text-caixaAzul d-block">{{ optional($demanda->unidade)->nome_completo }}</span>
                    </div>
                    <div class="mb-3 col-md-4 ">
                        <label class="active">Responsável:</label>
                        <span class="text-caixaAzul d-block">{{ optional($demanda->responsavel)->name }}</span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="mb-3 col-md-12 ">
                        <label class="active">Questionamento:</label>
                        <b>
                            <span class="text-caixaAzul d-block font-weight-bold">
                                {{ $demanda->solicitacao }}
                            </span>
                        </b>
                    </div>
                </div>
                <div class="form-row">
                    <div class="mb-3 col-md-12 ">
                        <label class="active">Resposta</label>
                        @if($demanda && !$demanda->resposta)
                        <textarea wire:model.debounce.defer="demanda.resposta" wire:loading.attr="disabled"
                            class="form-control @error('demanda.resposta') is-invalid @enderror" rows="3"></textarea>
                        @error('demanda.resposta') <div class="invalid-feedback is-invalid">{{ $message }}</div>
                        @enderror
                        @else
                        <b>
                            <span class="text-caixaAzul d-block font-weight-bold">
                                {{ $demanda->resposta }}
                            </span>
                        </b>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <div class="d-inline-block">
            <button wire:loading.attr="disabled" type="button" class="btn btn-secondary btn-sm"
                data-dismiss="modal">Fechar</button>
                @if(!$demanda->resposta)
            <button wire:click.prevent="salvar" wire:loading.attr="disabled" type="button"
                class="btn btn-primary btn-sm">Gravar</button>
                @endif
        </div>
    </div>
</div>
