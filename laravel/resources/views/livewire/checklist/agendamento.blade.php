<div>
    <div class="modal-header">
        <h4 class="modal-title w-100 text-caixaAzul text-futurabold" id="modal_checklist">Novo Checklist - Selecione o
            agendamento</h4>
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
            <div class="mb-2 text-sm">
                <div class="form-row">
                    <div class="col-md-12 mb-3 ">
                        <label class="active">Agendamento</label>
                        <select class="browser-default custom-select @error('agendamento_id') is-invalid @enderror"
                            wire:model.debounce.defer="agendamento_id" wire:loading.attr="disabled" required>
                            <option value="" selected>Selecione o agendamento</option>
                            @forelse($agendamentos as $agendamento)
                                <option value="{{ $agendamento->id }}">
                                    {{ $agendamento->final != $agendamento->inicio ? $agendamento->inicio . ' a ' . $agendamento->final : $agendamento->inicio }}
                                    -
                                    {{ $agendamento->tipoPv ? $agendamento->tipoPv . ' ' . $agendamento->nome : $agendamento->tipo . ' ' . $agendamento->nome }}
                                </option>
                            @empty
                                <option value="" disabled>Nenhum agendamento encontrado.</option>
                            @endforelse
                        </select>

                        @error('agendamento_id') <div class="invalid-feedback is-invalid">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer justify-content-between">
        <div class="d-inline-block">
            <button wire:loading.attr="disabled" type="button" class="btn btn-secondary btn-sm"
                data-dismiss="modal">Fechar</button>
            <button wire:click.prevent="salvar" wire:loading.attr="disabled" type="button"
                class="btn btn-primary btn-sm">Iniciar preenchimento</button>
        </div>
    </div>
</div>
