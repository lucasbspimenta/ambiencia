<div>
    <div class="modal-header">
        <h4 class="modal-title w-100 text-caixaAzul text-futurabold" id="modal_ver_demanda">
           Visualizar Demanda
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
            <div class="form-row">
                <div class="mb-3 col-md-3 ">
                    <label class="active">Destino:</label>
                    <span class="text-caixaAzul d-block">{{ optional($demanda->sistema)->nome }}</span>
                </div>
                <div class="mb-3 col-md-4 ">
                    <label class="active">Situação:</label>
                    <span class="text-caixaAzul d-block">{{ $demanda->demanda_situacao }}</span>
                </div>
                <div class="mb-3 col-md-2 ">
                    <label class="active">Integração:</label>
                    <span class="text-caixaAzul d-block">{{ $demanda->migracao_texto }}</span>
                </div>
                <div class="mb-3 col-md-2 ">
                    <label class="active">Link externo:</label>
                    <span class="text-caixaAzul d-block">
                    @if($demanda->demanda_url)
                        <a href="{{ $demanda->demanda_url_completa }}" target="_blank">Abrir</a>
                    @else
                        Indisponível
                    @endif
                    </span>
                </div>
            </div>
            <div class="form-row" style="border-top: 1px solid #f5f5f5; padding-top:10px;">
                <div class="mb-3 col-md-7 ">
                    <label class="active">Unidade:</label>
                    <span class="text-caixaAzul d-block">{{ optional($demanda->unidade)->nome_completo }}</span>
                </div>
                <div class="mb-3 col-md-2 ">
                    <label class="active">Prazo:</label>
                    <span class="text-caixaAzul d-block">{{ $demanda->prazo_formatado }}</span>
                </div>
                <div class="mb-3 col-md-2 ">
                    <label class="active">Ult. Atualização:</label>
                    <span class="text-caixaAzul d-block">{{ $demanda->atualizacao_formatado }}</span>
                </div>
            </div>
            <div class="form-row" style="border-top: 1px solid #f5f5f5; padding-top:10px;">
                <div class="mb-3 col-md-6 ">
                    <label class="active">Item:</label>
                    <span class="text-caixaAzul d-block">{{ optional($demanda->sistema_item)->nome }}</span>
                </div>
                <div class="mb-3 col-md-6 ">
                    <label class="active">Responsável:</label>
                    <span class="text-caixaAzul d-block">{{ optional($demanda->responsavel)->name }}</span>
                </div>
                
            </div>
            <div class="form-row" style="border-top: 1px solid #f5f5f5; padding-top:10px;">
                <div class="mb-3 col-md-12 ">
                    <label class="active">Descrição:</label>
                    <b>
                        <span class="text-caixaAzul d-block font-weight-bold">
                            {{ $demanda->descricao }}
                        </span>
                    </b>
                </div>
            </div>
            @if($demanda->demanda_retorno)
            <div class="form-row" style="border-top: 1px solid #f5f5f5; padding-top:10px;">
                <div class="mb-3 col-md-12 ">
                    <label class="active">Retorno:</label>
                    <b>
                        <span class="text-caixaAzul d-block font-weight-bold">
                            {{ $demanda->demanda_retorno }}
                        </span>
                    </b>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <div class="d-inline-block">
            <button wire:loading.attr="disabled" type="button" class="btn btn-secondary btn-sm"
                data-dismiss="modal">Fechar</button>
        </div>
    </div>
</div>
