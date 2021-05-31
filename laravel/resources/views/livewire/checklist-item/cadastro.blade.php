<div>
    <div class="modal-header">
        <h4 class="modal-title w-100 text-caixaAzul text-futurabold" id="modal_tipoagendamento">@if($checklistitem_id) Alterar @else Novo @endif Item do Checklist</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div wire:loading style="position: absolute; width: 100%; height: 100%; background-color: #f5f5f5; z-index: 1; opacity:0.5; left: 0; top: 0;">
            <div class="d-flex justify-content-center align-middle align-items-stretch">
                <img class="mt-5" src="{{ asset('images/ajax-loader-mini.gif') }}" />
            </div>
        </div>
        <form >
            <input type="hidden" wire:model.defer="checklistitem_id" name="checklistitem_id" />
            <div class="mb-2 text-sm">
            @if($item_pai_id)
                <div class="form-row">
                    <div class="col-md-12 mb-3 ">
                        <label class="active">Macroitem</label>
                        <select class="browser-default custom-select @error('item_pai_id') is-invalid @enderror" wire:model.debounce.defer="item_pai_id" wire:loading.attr="disabled" required>
                            <option value="" selected>Selecione a macroitem</option>
                            @forelse($macroitens as $macroitem)
                                <option value="{{ $macroitem->id }}">{{ $macroitem->nome }}</option>
                            @empty
                                <option value="" disabled>Nenhum macroitem encontrado.</option>
                            @endforelse
                        </select>

                        @error('item_pai_id') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                    </div>
                </div>
            @endif
                <div class="form-row">
                    <div class="col mb-3 ">
                        <label for="nome">Nome: </label>
                        <input type="text" wire:model.defer="nome" wire:loading.attr="disabled" class="form-control input-150 @error('nome') is-invalid @enderror" name="nome" id="nome"  value="" required>
                        @error('nome') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                    </div>
                    @if(!$item_pai_id)
                    <div class="col-2 mb-3 ">
                        <label for="final">Cor: </label>
                        <input type="color" wire:model.defer="cor" class="form-control @error('cor') is-invalid @enderror" wire:loading.attr="disabled" name="cor" id="cor"  required>
                        @error('cor') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                    </div>
                    @endif
                    <div class="col-2 mb-3 ">
                        <label class="active">Situação</label>
                        <div class="custom-control custom-switch">
                            <input wire:model.defer="situacao" wire:loading.attr="disabled" type="hidden" name="situacao" value="0" />
                            <input wire:model.defer="situacao" wire:loading.attr="disabled" type="checkbox" class="custom-control-input " id="situacao" value="1" >
                            <label class="custom-control-label @error('situacao') is-invalid @enderror" for="situacao">Ativo</label>
                        </div>
                        @error('situacao') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-2 mb-3 ">
                        <label class="active">Foto</label>
                        <div class="custom-control custom-switch">
                            <input wire:model.defer="foto_boolean" wire:loading.attr="disabled" type="hidden" name="foto_boolean" value="0" />
                            <input wire:model.defer="foto_boolean" wire:loading.attr="disabled" type="checkbox" class="custom-control-input " id="foto_boolean" value="1" >
                            <label class="custom-control-label @error('foto_boolean') is-invalid @enderror" for="foto_boolean">Obrigatória</label>
                        </div>
                        @error('situacao') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-3 ">
                        <label class="active">Descrição</label>
                        <textarea wire:model.debounce.defer="descricao" wire:loading.attr="disabled" class="form-control @error('descricao') is-invalid @enderror" id="exampleFormControlTextarea5" rows="3"></textarea>
                        @error('descricao') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer justify-content-between">
        <div class="d-inline-block">
            @if($checklistitem_id)
                <button onclick="excluirTipoDeAgendamento({{ $checklistitem_id }})" type="button" class="btn btn-danger btn-sm float-left">Excluir item do checklist</button>
            @endif
        </div>
        <div class="d-inline-block">
            <button wire:loading.attr="disabled" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Fechar</button>
            <button wire:click.prevent="salvar" wire:loading.attr="disabled" type="button" class="btn btn-primary btn-sm">Gravar</button>
        </div>
    </div>
</div>
