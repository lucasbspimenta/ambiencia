<div>
    <div class="modal-header">
        <h4 class="modal-title w-100 text-caixaAzul text-futurabold" id="modal_agenda">@if($guia_id) Alterar @else Novo @endif Guia</h4>
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
            <input type="hidden" wire:model.defer="guia_id" name="guia_id" />
            <div class="mb-2 text-sm">
                <div class="form-row">
                    <div class="col-md-6 mb-3 ">
                        <div class="form-row">
                            <div class="col-md-12 mb-3 ">
                                <label class="active">Item do Checklist</label>
                                <select class="browser-default custom-select @error('checklist_item_id') is-invalid @enderror" wire:model.debounce.defer="checklist_item_id" wire:loading.attr="disabled" required>
                                    <option value="" selected>Selecione a item</option>
                                    @forelse($checklistItens as $checklistItem)
                                        <option @if(!is_null($checklistItem->guia) && $checklistItem->id != $checklist_item_id) disabled @endif value="{{ $checklistItem->id }}">{{ $checklistItem->nome }}@if(!is_null($checklistItem->guia) && $checklistItem->id != $checklist_item_id) - Já tem guia cadastrado @endif</option>
                                    @empty
                                        <option value="" disabled>Nenhum item encontrado.</option>
                                    @endforelse
                                </select>

                                @error('checklist_item_id') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3 ">
                                <label class="active">Descrição</label>
                                <textarea wire:model.debounce.defer="descricao" wire:loading.attr="disabled" class="form-control @error('descricao') is-invalid @enderror" id="exampleFormControlTextarea5" rows="3"></textarea>
                                @error('descricao') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3 ">
                                <label class="active">Fotos</label>
                                <div class="d-flex justify-content-start">
                                    @if ($fotosexistentes)
                                        @foreach($fotosexistentes as $key_foto => $foto)
                                        <figure class="figure mr-3">
                                            <div style="height: 100px; width: 100px;">
                                                @if($foto instanceof Livewire\TemporaryUploadedFile)
                                                    <img class="img-thumbnail rounded" style="width: inherit; height: inherit; object-fit: cover;" src="{{ $foto->temporaryUrl() }}">
                                                @else
                                                    <img class="img-thumbnail rounded" style="width: inherit; height: inherit; object-fit: cover;" src="{{ $foto->imagem ?? $foto['imagem'] }}">
                                                @endif
                                            </div>
                                            <figcaption class="figure-caption text-right"><a class="text-danger" href="#" wire:click="removerFoto({{$key_foto}})">Remover</a></figcaption>
                                        </figure>
                                        @endforeach
                                    @endif
                                    <figure class="figure mr-3">
                                        <div style="height: 100px; width: 100px;">
                                            <div class="upload-btn-wrapper h-100">
                                              <button class="btn-upload h-100">Enviar</button>
                                              <input type="file" wire:model="fotosenviadas" multiple />
                                            </div>
                                        </div>
                                    </figure>
                                </div>
                                @error('photos.*') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3 ">
                        <div class="pl-2 mb-2" style="background-color: #fdfdff; border-bottom: 1px solid #ced4da;">
                            <label class="active text-caixaAzul">Vincular orientação</label>
                            <div class="form-row d-flex">
                                <div class="col mb-3 ">
                                    <label for="nome">
                                        @if($item_is_pergunta)
                                            Pergunta:
                                        @else
                                            Orientação:
                                        @endif
                                    </label>
                                    <input type="text" wire:model.defer="pergunta" wire:loading.attr="disabled" class="form-control input-150 @error('pergunta') is-invalid @enderror" name="pergunta" id="pergunta"  value="" required>
                                    @error('pergunta') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 col-auto">
                                    <label class="active">Tipo</label>
                                    <div class="custom-control custom-switch mt-1">
                                        <input wire:model="item_is_pergunta" wire:loading.attr="disabled" type="hidden" name="item_is_pergunta" value="0" />
                                        <input wire:model="item_is_pergunta" wire:loading.attr="disabled" type="checkbox" class="custom-control-input " id="customSwitch1" value="1" >
                                        <label class="custom-control-label @error('item_is_pergunta') is-invalid @enderror" for="customSwitch1">Pergunta</label>
                                    </div>
                                    @error('item_is_pergunta') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 col-auto">
                                    <label class="active w-100">&nbsp;</label>
                                    <button wire:click.prevent="incluirItem" wire:loading.attr="disabled" type="button" class="btn btn-info btn-sm m-0"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>

                            <div class="form-row d-flex justify-content-end">
                                @if($item_is_pergunta)
                                <div class="col mb-3 ">
                                    <label for="nome">Resposta: </label>
                                    <input type="text" wire:model.defer="resposta" wire:loading.attr="disabled" class="form-control input-150 @error('resposta') is-invalid @enderror" name="resposta" id="resposta"  value="" required>
                                    @error('resposta') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                                </div>
                                @endif

                            </div>

                        </div>
                        <div class="pl-2 mb-3">
                            <label class="active text-caixaAzul">Itens vinculados</label>
                            @if ($itens)
                                @foreach($itens as $key_itemadd => $item_adicionado)
                                <div class="form-row">
                                    <div class="col-11 mb-1 border-bottom ">
                                    @if(!empty($item_adicionado['resposta']))
                                            <label class="active text-caixaAzul">Pergunta: </label>
                                        @else
                                            <label class="active text-caixaAzul">Orientação: </label>
                                    @endif
                                        <span>{{$item_adicionado['pergunta']}}</span>
                                    @if(!empty($item_adicionado['resposta']))
                                        <br>
                                        <label class="active text-caixaAzul">Resposta: </label>
                                        <span>{{$item_adicionado['resposta']}}</span>

                                    @endif
                                    </div>
                                    <div class="col-1 mb-1 border-bottom justify-content-center">
                                        <button wire:click.prevent="removerItem({{ $key_itemadd  }})" type="button" class="close text-danger" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="form-row">
                                    <div class="col-md-12 mb-3 ">
                                    Nenhum item vinculado
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer justify-content-between">
        <div class="d-inline-block">
            @if($guia_id)
                <button onclick="excluirGuia({{ $guia_id }})" type="button" class="btn btn-danger btn-sm float-left">Excluir guia</button>
            @endif
        </div>
        <div class="d-inline-block">
            <button wire:loading.attr="disabled" type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Fechar</button>
            <button wire:click.prevent="salvar" wire:loading.attr="disabled" type="button" class="btn btn-primary btn-sm">Gravar</button>
        </div>
    </div>
</div>
