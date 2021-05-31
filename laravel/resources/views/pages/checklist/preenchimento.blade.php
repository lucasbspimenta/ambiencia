@extends('layouts.app')
@section('title', 'Checklist - ' . Str::title($checklist->agendamento->unidade->nome_completo))
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1" style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Checklist - {{ $checklist->agendamento->unidade->nome_completo }}
                </h4>
            </div>
            <div class="col d-flex justify-content-end">
                <a href="{{ session('redirect_to') ?? url()->previous() }}" id="botao_voltar" class="btn btn-sm btn-secondary " >
                    <i class="fas fa-chevron-left"></i> Voltar
                </a>
                <livewire:checklist.botao-finalizar :checklist=$checklist />
            </div>
        </div>
        <hr class="mt-2 mb-3"/>
        <div class="row">
            <div class="col">
                @foreach($checklist->macroitens as $key => $macroitem)
                    <div>
                        <livewire:checklist.macroitem :checklist=$checklist :macroitem="$macroitem" :wire:key="'macroitem-'.$macroitem->id" />
                    </div>
                @endforeach
            </div>
            <div class="col-4">
                @if($checklist->fotosObrigatorias)
                <div class="card mb-3">
                    <div class="card-header bg-transparent text-caixaAzul font-weight-bold" style="font-size:14px" data-toggle="collapse" href="#fotos" aria-expanded="true" aria-controls="fotos" role="button">
                        Fotos Obrigatórias
                    </div>
                    <div class="card-body collapse p-3 show" id="fotos">
                        <div class="d-flex justify-content-start flex-wrap">
                            @foreach($checklist->fotosObrigatorias as $key => $resposta)
                                <livewire:checklist.foto :resposta=$resposta :wire:key="'foto-'.$resposta->id" />
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                <div class="card">
                    <div class="card-header bg-transparent text-caixaAzul font-weight-bold" style="font-size:14px" data-toggle="collapse" href="#demandas" aria-expanded="true" aria-controls="demandas" role="button">
                        Demandas vinculadas
                    </div>
                    <div class="card-body collapse p-1 show" id="demandas">
                        <livewire:demanda.vinculadas :checklist=$checklist />
                    </div>
                </div>
                <div class="modal fade" id="modal_demanda" tabindex="-1" role="dialog" aria-labelledby="modal_demanda" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <livewire:demanda.cadastro />
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

@endsection
@push('styles')

@endpush
@push('scripts')
    <script>
        const NOME_MODAL = '#modal_demanda';
        const NOME_MODAL_GUIA = '#modal_guia';

        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                backdrop: 'static',
                keyboard: true,
                show: false,
                focus: true
            };

            $(NOME_MODAL).modal(options);
            $(NOME_MODAL).on('hidden.bs.modal', (e) => Livewire.emit('limpar'));

            tippy('[data-tippy-content]');

            $('.image-popup-link').magnificPopup({
                type: 'image',
                alignTop: true
            });
        });

        window.addEventListener('triggerSucesso', (event) => {
            toastr.success('Demanda para '+ event.detail +' gravada com sucesso!');
            $(NOME_MODAL).modal('hide');
        });

        window.addEventListener('triggerSucessoExclusao', (event) => {
            toastr.success('Demanda excluída com sucesso!');
            $(NOME_MODAL).modal('hide');
        });

        window.addEventListener('triggerError', (event) => {
            toastr.error('Erro ao gravar demanda: '+ event.detail);
        });

        window.addEventListener('atualizarResposta', (event) => {
            atualizaRespostaPorId(event.detail.resposta_id);
        });

        function abrirDemanda(demanda_id, resposta_id, componente) {

            if(resposta_id)
                Livewire.emit('defineResposta', resposta_id);

            if(demanda_id)
                Livewire.emit('defineDemanda', demanda_id);

            $(NOME_MODAL).off('show.bs.modal');
            $(NOME_MODAL).off('shown.bs.modal');
            $(NOME_MODAL).off('hide.bs.modal');
            $(NOME_MODAL).on('hide.bs.modal', (e) => eval(componente).atualizar());
            $(NOME_MODAL).modal('show');
        }

        function exibirGuia(guiaID){
            $(NOME_MODAL_GUIA).off('show.bs.modal');
            $(NOME_MODAL_GUIA).off('shown.bs.modal');
            $(NOME_MODAL_GUIA).on('show.bs.modal', (e) => Livewire.emit('carregaGuia',guiaID));
            $(NOME_MODAL_GUIA).modal('show');
        }

        function excluirDemanda(demandaId, componente, resposta_id) {

            Swal.fire({
                title: 'Você tem certeza?',
                text: "A demanda será excluída e perderá a referência ao checklist",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, tenho certeza!',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    eval(componente).excluir(demandaId)
                } else {
                    console.log("Canceled");
                }
            });
        }

        function excluirVinculacao(demandaId, componente, resposta_id) {

            Swal.fire({
                title: 'Você tem certeza?',
                text: "O item será desvinculado da demanda",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, tenho certeza!',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    eval(componente).desvincular(demandaId, resposta_id)
                } else {
                    console.log("Canceled");
                }
            });
        }

        function atualizaRespostaPorId(id)
        {
            if(id) {
                if (typeof id === "object") {

                    id.forEach((id_unico) => {
                        let componente = eval($('#resposta-' + id_unico).data('lw'));
                        if (componente)
                            componente.atualizar();
                    })
                } else {
                    let componente = eval($('#resposta-' + id).data('lw'));
                    if (componente)
                        componente.atualizar();
                }
            }
        }

        /*
        document.addEventListener('renderizeiFoto', function (evento) {
            console.log('renderizei', evento.id);
            //console.log( $('[wire:id="'+ evento.id + '"]').find('[data-tippy-content]').length);
            //console.log( $('[wire:id="'+ evento.id + '"]').find('.image-popup-link').length);
        });

        Livewire.hook('element.updated', (el, component) => {
            //console.log( $(el).find('[data-tippy-content]').length);
            //console.log( $(el).find('.image-popup-link').length);
            //tippy('[data-tippy-content]').destroy();
            //tippy('[data-tippy-content]');

            if($(el).find('[data-tippy-content]').length > 0)
            {
                if($(el)._tippy)
                    $(el)._tippy.destroy();

                tippy($(el));
            }

            if($(el).find('.image-popup-link').length > 0)
            {
                $(el).find('.image-popup-link');
                $(el).find('a.image-popup-link').magnificPopup({
                    type: 'image',
                    alignTop: true
                });
            }


        })
        */


    </script>
@endpush
