<div>
    <div class="modal-header">
        <h4 class="modal-title w-100 text-caixaAzul text-futurabold" id="modal_agenda">
            @if ($agendamento_id) Alterar @else Novo @endif
            Agendamento
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div wire:loading
            style="position: absolute; width: 100%; height: 100%; background-color: #f5f5f5; z-index: 1; opacity:0.5; left: 0; top: 0;">
            <div class="d-flex justify-content-center align-middle align-items-stretch">
                <img class="mt-5" src="{{ asset('images/ajax-loader-mini.gif') }}" />
            </div>
        </div>
        <form>
            <input type="hidden" wire:model.defer="agendamento_id" name="agendamento_id" />
            <div class="mb-2 text-sm">
                <div class="form-row">
                    <div class="col-md-4 mb-3 ">
                        <label for="inicio">Início: </label>
                        <input type="text" wire:model.defer="inicio" wire:loading.attr="disabled"
                            onchange="this.dispatchEvent(new InputEvent('input'))"
                            class="form-control input-150 datepicker @error('inicio') is-invalid @enderror"
                            name="inicio" id="inicio" placeholder="dd/mm/aaaa" value="" required>
                        @error('inicio') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3 ">
                        <label for="final">Final: </label>
                        <input type="text" wire:model.defer="final" wire:loading.attr="disabled"
                            onchange="this.dispatchEvent(new InputEvent('input'))"
                            class="form-control input-150 datepicker @error('final') is-invalid @enderror" name="final"
                            id="final" value="" placeholder="dd/mm/aaaa">
                        @error('final') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3 ">
                        <label class="active">Tipo de Agendamento</label>
                        <select
                            class="browser-default custom-select @error('agendamento_tipos_id') is-invalid @enderror"
                            wire:model.debounce.defer="agendamento_tipos_id" wire:loading.attr="disabled" required>
                            <option value="" selected>Selecione o tipo de agendamento</option>
                            @forelse($tiposagendamentos as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>
                            @empty
                                <option value="" disabled>Nenhum tipo de agendamento cadastrado</option>
                            @endforelse
                        </select>

                        @error('agendamento_tipos_id') <div class="invalid-feedback is-invalid">{{ $message }}
                        </div>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-3 ">
                        <label class="active">Unidade</label>
                        <select class="browser-default custom-select @error('unidade_id') is-invalid @enderror"
                            wire:model.debounce.defer="unidade_id" wire:loading.attr="disabled" required>
                            <option value="" selected>Selecione a unidade</option>
                            @forelse(App\Models\Unidade::select('id', 'codigo', 'tipoPv', 'unidades.nome')->orderBy('unidades.nome', 'ASC')->get() as $unidade)
                                <option value="{{ $unidade->id }}">
                                    {{ Str::padLeft($unidade->codigo, 4, '0') ?? '' }}
                                    - {{ $unidade->tipoPv ?? '' }}&nbsp;{{ $unidade->nome }}</option>
                            @empty
                                <option value="" disabled>Nenhuma unidade encontrada.</option>
                            @endforelse
                        </select>

                        @error('unidade_id') <div class="invalid-feedback is-invalid">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-3 ">
                        <label class="active">Descrição</label>
                        <textarea wire:model.debounce.defer="descricao" wire:loading.attr="disabled"
                            class="form-control @error('descricao') is-invalid @enderror"
                            id="exampleFormControlTextarea5" rows="3"></textarea>
                        @error('descricao') <div class="invalid-feedback is-invalid">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer justify-content-between">
        <div class="d-inline-block">
            @if ($agendamento_id && !$agendamento_tem_checklist)
                <button onclick="excluirAgendamento({{ $agendamento_id }})" type="button"
                    class="btn btn-danger btn-sm float-left">Excluir agendamento</button>
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
