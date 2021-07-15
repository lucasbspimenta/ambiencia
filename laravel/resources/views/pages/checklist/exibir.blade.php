@extends('layouts.app')
@section('title', 'Checklist - ' . Str::title($checklist->agendamento->unidade->nome_completo))
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1"
                        style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Checklist - {{ $checklist->agendamento->unidade->nome_completo }}
                    <small style="font-size: 12px;" class="text-black-50 text-small ml-1"> -
                        {{ $checklist->agendamento->unidade->responsavel->nome_responsavel }}</small>
                </h4>
            </div>
            <div class="col d-flex justify-content-end">
                <a href="{{ route('checklist.index') }}" id="botao_voltar" class="btn btn-sm btn-secondary ">
                    <i class="fas fa-chevron-left"></i> Voltar
                </a>
            </div>
        </div>
        <hr class="mt-2 mb-3" />
        <div class="row">
            <div class="col">
                @foreach ($checklist->macroitens as $key => $macroitem)
                    <div class="card mb-3">
                        <div class="card-header bg-transparent pl-3" style="border-left: 5px {{ $macroitem->cor }} solid;"
                            data-toggle="collapse" href="#macroitem-{{ $macroitem->id }}" aria-expanded="true"
                            aria-controls="macroitem-{{ $macroitem->id }}" role="button">
                            <a class="w-100 h-100">
                                <span class="text-caixaAzul font-weight-bold" style="font-size:14px; font-weight: bold;">
                                    {{ $macroitem->nome }}
                                    @if ($macroitem->load('guia')->guia)
                                        <a href="#" onclick="exibirGuia({{ $macroitem->guia->id }})"><i
                                                class="fas fa-question-circle fa-xs ml-1 text-black-50"></i></a>
                                    @endif
                                </span>

                                @if ($macroitem->foto == 'S')
                                    <i class="fas fa-lg fa-camera ml-2 text-black-50"></i>
                                @else
                                    <i class="fas fa-lg fa-camera ml-2 text-black-50"></i>
                                @endif

                            </a>
                        </div>
                        <div class="card-body collapse p-2 show" id="macroitem-{{ $macroitem->id }}">

                            @foreach ($checklist->respostasMacroitem($macroitem->id)->with('item')->get()
        as $chave => $resposta)
                                <div class="col-12 border-bottom" style="background-color: @if ($chave
                                    % 2) #F7F7F7 @endif">
                                    <div class="row" id="resposta-{{ $resposta->id }}">
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
                                        <div class="col-5">
                                            @if (is_null($resposta->resposta))
                                                <label class="btn btn-info btn-sm">Não respondido</label>
                                            @else
                                                @if ($resposta->resposta == 0)
                                                    <label class="btn btn-light-blue btn-sm">N/A</label>
                                                @endif
                                                @if ($resposta->resposta == 1)
                                                    <label class="btn btn-green btn-sm">Conforme</label>
                                                @endif
                                                @if ($resposta->resposta == -1)
                                                    <label class="btn btn-red btn-sm">Inconforme</label>
                                                @endif
                                            @endif
                                        </div>
                                        <div style="width: 130px;">

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                @endforeach
            </div>
            <div class="col-4">
                @if ($checklist->fotosObrigatorias)
                    <div class="card mb-3">
                        <div class="card-header bg-transparent text-caixaAzul font-weight-bold" style="font-size:14px"
                            data-toggle="collapse" href="#fotos" aria-expanded="true" aria-controls="fotos" role="button">
                            Fotos Obrigatórias
                        </div>
                        <div class="card-body collapse p-3 show" id="fotos">
                            <div class="d-flex justify-content-start flex-wrap">
                                @foreach ($checklist->fotosObrigatorias as $key => $resposta)
                                    <div class="col col-auto pl-0">
                                        <figure class="figure">
                                            <div style="width: 100px;"><small
                                                    class="text-truncate text-caixaAzul d-block">{{ $resposta->item->nome }}</small>
                                            </div>
                                            <div style="height: 100px; width: 100px;">
                                                <img class="img-thumbnail rounded"
                                                    style="width: inherit; height: inherit; object-fit: cover;"
                                                    src="{{ $resposta->foto }}">
                                            </div>
                                        </figure>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header bg-transparent text-caixaAzul font-weight-bold" style="font-size:14px"
                        data-toggle="collapse" href="#demandas" aria-expanded="true" aria-controls="demandas" role="button">
                        Demandas vinculadas
                    </div>
                    <div class="card-body collapse p-1 show" id="demandas">
                        <div class="list-group list-group-flush">
                            @forelse($checklist->demandas as $demanda)
                                <a href="javascript: void(abrirVerDemanda({{$demanda->id}}))" class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div class="w-100">
                                            <small
                                                class="d-block mb-1 text-caixaAzul">{{ $demanda->sistema->nome }}</small>
                                            <small
                                                class="d-block text-black-50">{{ $demanda->sistema_item->nome }}</small>
                                        </div>
                                        <div class="flex-shrink-1">
                                            @if (trim($demanda->migracao) == 'P')
                                                <span class="badge badge-info z-depth-0" style="font-size:85%">A
                                                    processar</span>
                                            @endif
                                            @if (trim($demanda->migracao) == 'C')
                                                @if(trim($demanda->demanda_situacao) != '')
                                                    <span class="badge badge-default z-depth-0" style="font-size:85%">{{ trim($demanda->demanda_situacao) }}</span>
                                                @else
                                                    <span class="badge badge-default z-depth-0" style="font-size:85%">Processado</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex w-100 justify-content-between">
                                        <p class="mb-2 mt-2 w-100 text-truncate d-block">{{ $demanda->descricao }}</p>
                                    </div>
                                    <div class="d-flex w-100 justify-content-start">
                                        @foreach ($demanda->load('respostas')->respostas->where('checklist_id', $checklist->id) as $key_resp => $resposta)

                                            <div>
                                                <span class="badge badge-primary z-depth-1 p-2 font-weight-normal mr-2">
                                                    {{ $resposta->item->nome }}
                                                </span>
                                            </div>

                                        @endforeach
                                    </div>
                                </a>
                            @empty
                                Nenhuma demanda vinculada
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_guia" tabindex="-1" role="dialog" aria-labelledby="modal_guia" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fluid" role="document">
            <div class="modal-content">
                <livewire:guia.exibir />
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_ver_demanda" tabindex="-1" role="dialog" aria-labelledby="modal_ver_demanda"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <livewire:demanda.visualizar />
            </div>
        </div>
    </div>
@endsection
@push('styles')
@endpush
@push('scripts')
    <script>
        const NOME_MODAL_GUIA = '#modal_guia';
        const NOME_MODEL_VER_DEMANDA = '#modal_ver_demanda';

        function exibirGuia(guiaID) {
            $(NOME_MODAL_GUIA).off('show.bs.modal');
            $(NOME_MODAL_GUIA).off('shown.bs.modal');
            $(NOME_MODAL_GUIA).on('show.bs.modal', (e) => Livewire.emit('carregaGuia', guiaID));
            $(NOME_MODAL_GUIA).modal('show');
        }

         document.addEventListener('DOMContentLoaded', function() {

            var options = {
                backdrop: 'static',
                keyboard: true,
                show: false,
                focus: true
            };

            $(NOME_MODEL_VER_DEMANDA).modal(options);
         });

        function abrirVerDemanda(demanda_id) {

            if (demanda_id)
                Livewire.emit('definirVerDemanda', demanda_id);

            $(NOME_MODEL_VER_DEMANDA).off('show.bs.modal');
            $(NOME_MODEL_VER_DEMANDA).off('shown.bs.modal');
            $(NOME_MODEL_VER_DEMANDA).off('hide.bs.modal');
            $(NOME_MODEL_VER_DEMANDA).modal('show');
        }

    </script>
@endpush
