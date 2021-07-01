<div>
    <div class="modal-header">
        <h4 class="modal-title w-100 text-caixaAzul text-futurabold" id="modal_demanda">
            @if ($demanda_id) Alterar @else Nova @endif Demanda
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
        <form>
            <input type="hidden" wire:model.defer="demanda_id" name="demanda_id" />
            <div class="mb-2 text-sm">
                @if ($resposta && $resposta->id)
                    <div class="form-row">
                        <div class="mb-3 col-md-12 ">
                            <label class="active">Item</label>
                            <span class="text-caixaAzul d-block">{{ $resposta->item->nome ?? '' }}</span>
                        </div>
                    </div>
                @else
                    <div class="form-row">
                        <div class="mb-3 col-md-12 ">
                            <label class="active">Unidade</label>
                            <select class="browser-default custom-select @error('unidade_id') is-invalid @enderror"
                                wire:model="unidade_id" wire:loading.attr="disabled" wire:ignore.self required>
                                <option value="" selected>Selecione a unidade</option>
                                @forelse(App\Models\Unidade::select('id', 'codigo', 'tipoPv', 'unidades.nome')->orderBy('unidades.nome', 'ASC')->get() as $unidade)
                                    <option value="{{ $unidade->id }}">{{ $unidade->nome_completo }}</option>
                                @empty
                                    <option value="">Nenhuma unidade localizada</option>
                                @endforelse
                            </select>

                            @error('unidade_id') <div class="invalid-feedback is-invalid">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif
                @if (sizeof($demandaExistentes) > 0)
                    <hr class="mt-0">
                    <div class="form-row">
                        <div class="mb-3 col-md-12 ">
                            <label class="active">Vincular a uma demanda já cadastrada:</label>
                            <select class="browser-default custom-select @error('demanda_antiga') is-invalid @enderror"
                                wire:model="demanda_antiga" wire:loading.attr="disabled" required>
                                <option value="" selected>Selecione a demanda / Não vincular</option>
                                @forelse($demandaExistentes as $demanda)
                                    <option value="{{ $demanda->id }}">{{ $demanda->dados_completos }}</option>
                                @empty
                                    <option value="">Nenhuma demanda localizada</option>
                                @endforelse
                            </select>

                            @error('demanda_antiga') <div class="invalid-feedback is-invalid">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <hr>
                @endif
                <div class="form-row">
                    <div class="mb-3 col-md-12 ">
                        <label class="active">Destino</label>
                        <select class="browser-default custom-select @error('sistema_id') is-invalid @enderror"
                            wire:model="sistema_id" wire:loading.attr="disabled" @if ($demanda_antiga) disabled @endif required>
                            <option value="" selected>Selecione o destino da demanda</option>
                            @forelse($sistemas as $sistema)
                                <option value="{{ $sistema->id }}">{{ $sistema->nome }}</option>
                            @empty
                                <option value="" disabled>Nenhum destino encontrado.</option>
                            @endforelse
                        </select>

                        @error('sistema_id') <div class="invalid-feedback is-invalid">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                @if ($this->sistema_id && $sistema->categorias_table && $sistema->categorias && $sistema->categorias->count() > 0)
                    <div class="form-row">
                        <div class="mb-3 col-md-12 ">
                            <label class="active">Categoria</label>
                            <select
                                class="browser-default custom-select @error('categoriaSelecionado') is-invalid @enderror"
                                wire:model="categoriaSelecionado" wire:loading.attr="disabled" @if ($demanda_antiga) disabled @endif required>
                                <option value="" selected>Selecione o categoria</option>
                                @forelse ($sistema->categorias as $categoria)
                                    <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                @empty
                                    <option value="">Nenhuma categoria cadastrado</option>
                                @endforelse
                            </select>

                            @error('categoriaSelecionado') <div class="invalid-feedback is-invalid">{{ $message }}
                            </div>@enderror
                        </div>
                    </div>
                @endif
                @if ($this->sistema_id && $sistema->subcategorias_table && $sistema->subcategorias && $sistema->subcategorias->count() > 0)
                    <div class="form-row">
                        <div class="mb-3 col-md-12 ">
                            <label class="active">Subcategoria</label>
                            <select
                                class="browser-default custom-select @error('subcategoriaSelecionado') is-invalid @enderror"
                                wire:model="subcategoriaSelecionado" wire:loading.attr="disabled" @if ($demanda_antiga) disabled @endif required>
                                <option value="" selected>Selecione o subcategoria</option>
                                @forelse ($sistema->subcategorias as $subcategoria)
                                    <option value="{{ $subcategoria->id }}">{{ $subcategoria->nome }}</option>
                                @empty
                                    <option value="">Nenhuma subcategoria cadastrado</option>
                                @endforelse
                            </select>

                            @error('subcategoriaSelecionado') <div class="invalid-feedback is-invalid">
                                {{ $message }}</div>@enderror
                        </div>
                    </div>
                @endif
                @if ($this->sistema_id && $sistema->itens_table && $sistema->itens && $sistema->itens->count() > 0)
                    <div class="form-row">
                        <div class="mb-3 col-md-12 ">
                            <label class="active">Item</label>
                            <select class="browser-default custom-select @error('sistema_item_id') is-invalid @enderror"
                                wire:model.debounce.defer="sistema_item_id" wire:loading.attr="disabled"
                                wire:target="subcategoriaSelecionado" wire:target="categoriaSelecionado" @if ($demanda_antiga) disabled @endif
                                required>
                                <option value="">Selecione o item</option>
                                @forelse ($sistema->itens as $item)
                                    @if ($categoriaSelecionado)
                                        @if ($item->categoria && $item->categoria == $categoriaSelecionado)
                                            <option categoria="{{ $item->categoria }}" value="{{ $item->id }}">
                                                {{ $item->nome }}</option>
                                        @endif
                                    @else
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                    @endif
                                @empty
                                    <option value="">Nenhum item cadastrado</option>
                                @endforelse
                            </select>

                            @error('sistema_item_id') <div class="invalid-feedback is-invalid">{{ $message }}
                            </div>@enderror
                        </div>
                    </div>
                @endif
                <div class="form-row">
                    <div class="mb-3 col-md-12 ">
                        <label class="active">Descrição</label>
                        <textarea wire:model.debounce.defer="descricao" wire:loading.attr="disabled"
                            class="form-control @error('descricao') is-invalid @enderror"
                            id="exampleFormControlTextarea5" rows="3" @if ($demanda_antiga) disabled @endif></textarea>
                        @error('descricao') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer justify-content-between">
        <div class="d-inline-block">
            @if ($demanda_id)
                <button onclick="excluirDemanda({{ $demanda_id }})" type="button"
                    class="float-left btn btn-danger btn-sm">Excluir demanda</button>
            @endif
        </div>
        <div class="d-inline-block">
            <button wire:loading.attr="disabled" type="button" class="btn btn-secondary btn-sm"
                data-dismiss="modal">Fechar</button>
            <button wire:click.prevent="salvar" wire:loading.attr="disabled" type="button"
                class="btn btn-primary btn-sm">Gravar</button>
        </div>
    </div>
</div>
