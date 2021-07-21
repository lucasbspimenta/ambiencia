<div>
    <div class="modal-header">
        <h4 class="modal-title w-100 text-caixaAzul text-futurabold" id="modal_demanda">
           Nova Demanda
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
        <div class="form-row">
            
                @if(!$vinculada_a_resposta)
                <div class="mb-3 col-md-12 @error('unidade_id') is-invalid @enderror">
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
                    @error('unidade_id') 
                        <div class="invalid-feedback is-invalid d-block">{{ $message }}</div>
                    @enderror
                </div>
                @else
                <div class="mb-3 col-md-6 @error('unidade_id') is-invalid @enderror">
                    <label class="active">Unidade</label>
                    <span class="text-caixaAzul d-block">{{ $unidade->nome_completo ?? '' }}</span>
                </div>
                <div class="mb-3 col-md-6 @error('unidade_id') is-invalid @enderror">
                    <label class="active">Item vinculado</label>
                    <span class="text-caixaAzul d-block">{{ $resposta->item->nome ?? '' }}</span>
                </div>
                @endif
            
        </div>
        <div class="form-row">
            <div class="mb-3 col-md-12 ">
                <label class="active">Destino:</label>
                <ul class="nav nav-pills mb-3 nav-justified @error('sistema_id') is-invalid @enderror" id="pills-tab" role="tablist">
                    @foreach($sistemas as $sistema)
                        <li wire:click="$set('sistema_id', '{{ $sistema->id }}')" class="nav-item" style="border: 1px solid #dddddd; margin-right:10px; border-radius: 0.25rem;">
                            <a class="nav-link @if($sistema_id == $sistema->id) show active @endif" id="pills-{{ $sistema->id }}-tab" data-toggle="pill" href="#pills-{{ $sistema->id }}" role="tab" aria-controls="pills-{{ $sistema->id }}" aria-selected="true">{{ $sistema->nome }}</a>
                        </li>
                    @endforeach
                    @error('sistema_id') <div class="invalid-feedback is-invalid d-block">{{ $message }} </div>@enderror
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    @foreach($sistemas as $sistema)
                    <div class="tab-pane fade @if($sistema_id == $sistema->id) show active @endif" id="pills-{{ $sistema->id }}" role="tabpanel" aria-labelledby="pills-{{ $sistema->id }}-tab">
                        <div class="form-row">
                            <div class="col-md-12 ">
                                @if($vinculada_a_resposta)
                                    <label class="active">Tipo</label>
                                @endif
                                <div class="d-block w-100">
                                    @if($vinculada_a_resposta)
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        
                                        <li wire:click="$set('demanda_nova', 'S')" class="nav-item" style="border: 1px solid #dddddd; margin-right:10px; border-radius: 0.25rem;">
                                            <a class="nav-link @if($sistema_id && $sistema_id == $sistema->id && $demanda_nova == 'S') active @endif" id="pills-{{ $sistema->id }}-nova-tab" data-toggle="pill" href="#pills-{{ $sistema->id }}-nova" role="tab" aria-controls="pills-{{ $sistema->id }}-nova" aria-selected="true">Abrir nova demanda</a>
                                        </li>
                                        <li wire:click="$set('demanda_nova', 'N')" class="nav-item" style="border: 1px solid #dddddd; margin-right:10px; border-radius: 0.25rem;">
                                            <a class="nav-link @if($sistema_id && $sistema_id == $sistema->id && $demanda_nova == 'N') active @endif" id="pills-{{ $sistema->id }}-vincular-tab" data-toggle="pill" href="#pills-{{ $sistema->id }}-vincular" role="tab" aria-controls="pills-{{ $sistema->id }}-vincular" aria-selected="true">Vincular a uma demanda existente</a>
                                        </li>
                                    
                                    </ul>
                                    @endif
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade @if($sistema_id && $sistema_id == $sistema->id && $demanda_nova == 'S') show active @endif" id="pills-{{ $sistema->id }}-nova" role="tabpanel" aria-labelledby="pills-{{ $sistema->id }}-nova-tab">
                                            @if ($sistema_id && $sistema_id == $sistema->id && $sistema->categorias_table && $sistema->categorias && $sistema->categorias->count() > 0)
                                                <div class="form-row">
                                                    <div class="mb-3 col-md-12 ">
                                                        <label class="active">Categoria</label>
                                                        <select
                                                            class="browser-default custom-select @error('categoriaSelecionado') is-invalid @enderror"
                                                            wire:model="categoriaSelecionado" wire:loading.attr="disabled" required>
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
                                            @if ($sistema_id && $sistema_id == $sistema->id && $sistema->subcategorias_table && $sistema->subcategorias && $sistema->subcategorias->count() > 0)
                                                <div class="form-row">
                                                    <div class="mb-3 col-md-12 ">
                                                        <label class="active">Subcategoria</label>
                                                        <select
                                                            class="browser-default custom-select @error('subcategoriaSelecionado') is-invalid @enderror"
                                                            wire:model="subcategoriaSelecionado" wire:loading.attr="disabled" required>
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
                                            @if ($sistema_id && $sistema_id == $sistema->id && $sistema->itens_table && $sistema->itens && $sistema->itens->count() > 0)
                                                <div class="form-row">
                                                    <div class="mb-3 col-md-12 ">
                                                        <label class="active">Item</label>
                                                        <select class="browser-default custom-select @error('sistema_item_id') is-invalid @enderror"
                                                            wire:model.debounce.defer="sistema_item_id" wire:loading.attr="disabled"
                                                            wire:target="subcategoriaSelecionado" wire:target="categoriaSelecionado" 
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
                                        </div>
                                        <div class="tab-pane fade @if($sistema_id && $sistema_id == $sistema->id && $demanda_nova == 'N') show active @endif" id="pills-{{ $sistema->id }}-vincular" role="tabpanel" aria-labelledby="pills-{{ $sistema->id }}-vincular-tab">
                                            <div class="form-row">
                                                <div class="mb-3 col-md-12 ">
                                                    @if($vinculada_a_resposta)
                                                    <label class="active">Origem</label>
                                                    <div class="d-block w-100">
                                                        <ul class="nav nav-pills mb-3" id="pills-tab-vinculacao" role="tablist">
                                                            
                                                            <li wire:click="$set('demanda_vinculacao', 'C')" class="nav-item" style="border: 1px solid #dddddd; margin-right:10px; border-radius: 0.25rem;">
                                                                <a class="nav-link @if($sistema_id && $sistema_id == $sistema->id && $demanda_vinculacao == 'C') active @endif" id="pills-{{ $sistema->id }}-vinc-checklist-tab" data-toggle="pill" href="#pills-{{ $sistema->id }}-vinc-checklist" role="tab" aria-controls="pills-{{ $sistema->id }}-vinc-checklist" aria-selected="true">Demandas deste checklist</a>
                                                            </li>
                                                            <li wire:click="$set('demanda_vinculacao', 'D')" class="nav-item" style="border: 1px solid #dddddd; margin-right:10px; border-radius: 0.25rem;">
                                                                <a class="nav-link @if($sistema_id && $sistema_id == $sistema->id && $demanda_vinculacao == 'D') active @endif" id="pills-{{ $sistema->id }}-vinc-existentes-tab" data-toggle="pill" href="#pills-{{ $sistema->id }}-vinc-existentes" role="tab" aria-controls="pills-{{ $sistema->id }}-vinc-existentes" aria-selected="true">Demandas abertas previamente e em andamento</a>
                                                            </li>
                                                        
                                                        </ul>
                                                        <div class="tab-content" id="pills-tab-vinculacao-content">
                                                            <div class="tab-pane fade @if($sistema_id && $sistema_id == $sistema->id && $demanda_vinculacao == 'C') show active @endif" id="pills-{{ $sistema->id }}-vinc-checklist" role="tabpanel" aria-labelledby="pills-{{ $sistema->id }}-vinc-checklist">
                                                                @if($sistema_id && $sistema_id == $sistema->id && $demanda_vinculacao == 'C')
                                                                <div class="form-row">
                                                                    <div class="mb-3 col-md-12 ">
                                                                        <label class="active">Selecione a demanda:</label>
                                                                        <select
                                                                            class="browser-default custom-select @error('demandaExistenteSelecionada') is-invalid @enderror"
                                                                            wire:model="demandaExistenteSelecionada" wire:loading.attr="disabled" required>
                                                                            <option value="" selected>Selecione a demanda</option>
                                                                            @forelse($demandaExistentes as $demanda)
                                                                                <option value="{{ $demanda->id }}">{{ optional($demanda)->dados_com_descricao }}</option>
                                                                            @empty
                                                                                <option value="">Nenhuma demanda localizada</option>
                                                                            @endforelse
                                                                        </select>
                                        
                                                                        @error('demandaExistenteSelecionada') <div class="invalid-feedback is-invalid">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            <div class="tab-pane fade @if($sistema_id && $sistema_id == $sistema->id && $demanda_vinculacao == 'D') show active @endif" id="pills-{{ $sistema->id }}-vinc-existentes" role="tabpanel" aria-labelledby="pills-{{ $sistema->id }}-vinc-existentes">
                                                                @if($sistema_id && $sistema_id == $sistema->id && $demanda_vinculacao == 'D')
                                                                <div class="form-row">
                                                                    <div class="mb-3 col-md-12 ">
                                                                        <label class="active">Selecione a demanda:</label>
                                                                        <select
                                                                            class="browser-default custom-select @error('demandaExistenteSelecionada') is-invalid @enderror"
                                                                            wire:model="demandaExistenteSelecionada" wire:loading.attr="disabled" required>
                                                                            <option value="" selected>Selecione a demanda</option>
                                                                            @forelse($demandaExistentes as $demanda_key => $demanda)
                                                                                <option value="{{ $demanda->id ?? $demanda_key }}">{{ optional($demanda)->dados_com_descricao }}</option>
                                                                            @empty
                                                                                <option value="">Nenhuma demanda localizada</option>
                                                                            @endforelse
                                                                        </select>
                                        
                                                                        @error('demandaExistenteSelecionada') <div class="invalid-feedback is-invalid">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($demanda_nova == 'S')
                <div class="form-row">
                    <div class="mb-3 col-md-12 ">
                        <label class="active">Descrição</label>
                        <textarea wire:model.debounce.defer="descricao" wire:loading.attr="disabled"
                            class="form-control @error('descricao') is-invalid @enderror"
                            id="exampleFormControlTextarea5" rows="3"></textarea>
                        @error('descricao') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <!--
        <div class="d-inline-block">
                <button type="button"
                    class="float-left btn btn-danger btn-sm">Excluir demanda</button>
        </div>
    -->
        <div class="d-inline-block">
            <button wire:loading.attr="disabled" type="button" class="btn btn-secondary btn-sm"
                data-dismiss="modal">Fechar</button>
            <button wire:click.prevent="salvar" wire:loading.attr="disabled" type="button"
                class="btn btn-primary btn-sm">Gravar</button>
        </div>
    </div>
</div>
