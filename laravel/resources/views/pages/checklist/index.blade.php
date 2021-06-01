@extends('layouts.app')
@section('title', 'Checklist')
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1" style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Checklists
                </h4>
            </div>
            <div class="col d-flex justify-content-end">
                <livewire:checklist.botao-novo :agendamentos_sem_checklist="$agendamentos_sem_checklist" />
            </div>
        </div>
        <hr class="mt-2 mb-3"/>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table id="tabela_checklists" class="table table-striped table-hover table-sm ">
                                <thead>
                                <tr>
                                    <th scope="col">#Cód</th>
                                    <th scope="col">Unidade</th>
                                    <th scope="col">Agendamento</th>
                                    <th scope="col">% Preenchimento</th>
                                    <th scope="col">% Demandas</th>
                                    <th scope="col">Opções</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_checklist" tabindex="-1" role="dialog" aria-labelledby="modal_checklist" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <livewire:checklist.agendamento :agendamentos="$agendamentos_sem_checklist" />
            </div>
        </div>
    </div>
@endsection
@push('styles')

@endpush
@push('scripts')
    <script>
        var DATATABLE;
        const NOME_DATATABLE = '#tabela_checklists';
        const NOME_MODAL = '#modal_checklist';

        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                backdrop: 'static',
                keyboard: true,
                show: false,
                focus: true
            };

            $(NOME_MODAL).modal(options);
            $(NOME_MODAL).on('hidden.bs.modal', (e) => Livewire.emit('limpar'));

            let renderBotoesEditarExluir = (data, type, row, meta) => {
                let saida = '';
                if (row.concluido == 1)
                {
                    saida = `<div class="d-flex justify-content-around">
                        <a href="{{route('checklist.index')}}/${row.id}" role="button" class="btn btn-xs btn-primary m-0"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    </div>`;
                }
                else
                {
                    saida = `<div class="d-flex justify-content-around">
                        <button onclick="redirecionaChecklist(${row.id})" type="button" class="btn btn-xs btn-primary m-0"><i class="fa fa-edit" aria-hidden="true"></i></button>
                        <button onclick="excluirChecklist(${row.id})" type="button" class="btn btn-xs btn-danger m-0"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    </div>`;
                }

                return  saida;
            }

            DATATABLE = $(NOME_DATATABLE).DataTable( {
                dom: 'ti',
                paging: false,
                "ajax": "{{ route("api.checklists.index") }}",
                "columns": [
                    { "data": "id" },
                    { "data": "agendamento.unidade.nome" },
                    { "data": "agendamento.inicio" },
                    { "data": "preenchimento", "render": DATATABLES_PROGRESSO_AZUL },
                    { "data": "agendamento_id", "render": DATATABLES_PROGRESSO_AZUL },
                    { "data": "agendamento_id", "render": renderBotoesEditarExluir },
                ]
            } );

        });



        function abrirModalChecklist(guiaID){
            $(NOME_MODAL).off('show.bs.modal');
            $(NOME_MODAL).off('shown.bs.modal');
            $(NOME_MODAL).modal('show');
        }

        window.addEventListener('triggerSucesso', (event) => {
            toastr.success('Checklist criado com sucesso!');
            $(NOME_MODAL).modal('hide');
            DATATABLE.ajax.reload();
            Livewire.emit('atualizarBotaoIncluir');
            redirecionaChecklist(event.detail.id);
        });

        window.addEventListener('triggerSucessoExclusao', (event) => {
            toastr.success('Checklist excluído com sucesso!');
            $(NOME_MODAL).modal('hide');
            DATATABLE.ajax.reload();
            Livewire.emit('atualizarBotaoIncluir');
        });

        window.addEventListener('triggerError', (event) => {
            toastr.error('Erro ao gravar checklist: '+ event.detail);
            DATATABLE.ajax.reload();
            Livewire.emit('atualizarBotaoIncluir');
        });

        function redirecionaChecklist(id)
        {
            let timerInterval
            Swal.fire({
                title: 'Abrindo o checklist...',
                html: 'Aguarde enquanto redirecionamos ao checklist',
                //timer: 3000,
                //timerProgressBar: true,
                didOpen: () => {
                    //Swal.showLoading();
                    let url = '{{ route('checklist.edit', ['null'])  }}';
                    var re = /null/gi;
                    window.location = url.replace(re, id); ;
                }
            }).then((result) => {

            })
        }

        function excluirChecklist(checklistID) {

            Swal.fire({
                title: 'Você tem certeza?',
                text: "O checklist será excluído junto com suas respostas",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, tenho certeza!',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    Livewire.emit('excluirChecklist', checklistID);
                } else {
                    console.log("Canceled");
                }
            });
        }
    </script>
@endpush