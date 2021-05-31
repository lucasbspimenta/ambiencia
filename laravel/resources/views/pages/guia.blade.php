@extends('layouts.app')
@section('title', 'Guias')
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1" style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Guias
                </h4>
            </div>
        </div>
        <hr class="mt-2 mb-3"/>
        <div class="row">
            <div class="container-fluid">
                <div class="row">
                    @foreach($guias as $guia)
                        <div class="col-2 mt-3 mb-5">
                            <a href="#" onClick="exibirGuia({{$guia->id}})">
                                <div class="card card-cascade narrower">
                                    <div class="view view-cascade overlay" style="width: auto; height: 150px;">
                                        @if($guia->imagens->first() && $guia->imagens->first()->imagem)
                                            <img class="rounded card-img-top" style="width: 100%; height: inherit; object-fit: cover;" src="{{ $guia->imagens->first()->imagem }}">
                                        @else
                                            <img class="rounded card-img-top" style="width: 100%; height: inherit; object-fit: cover;" src="{{ asset('images/image_placeholder.png') }}">
                                        @endif
                                    </div>
                                    <div class="card-body card-body-cascade">
                                        <small class="text-black-50 mb-2">{{$guia->checklistitem->macroitem->nome ?? $guia->checklistitem->nome}}</small>
                                        <!--Title-->
                                        <h6 class="card-title text-caixaAzul mt-1 mb-0"><b>{{$guia->checklistitem->nome}}</b></h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col col-auto">
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
        const NOME_MODAL = '#modal_guia';

        document.addEventListener('DOMContentLoaded', function() {

            var options = {
                backdrop: true,
                keyboard: true,
                show: false,
                focus: true
            };

            $(NOME_MODAL).modal(options);

            $(NOME_MODAL).on('hidden.bs.modal', (e) => Livewire.emit('limpar'));
        });

        function exibirGuia(guiaID){
            $(NOME_MODAL).off('show.bs.modal');
            $(NOME_MODAL).off('shown.bs.modal');
            $(NOME_MODAL).on('show.bs.modal', (e) => Livewire.emit('carregaGuia',guiaID));
            //$(NOME_MODAL).on('shown.bs.modal', (e) => ativaJavascriptsModal());
            $(NOME_MODAL).modal('show');
        }
    </script>
@endpush
