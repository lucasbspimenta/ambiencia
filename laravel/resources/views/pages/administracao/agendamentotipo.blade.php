@extends('layouts.app')
@section('title', 'Tipo de Agendamento - Administração')
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1" style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Tipo de Agendamento
                </h4>
            </div>
            <div class="col d-flex justify-content-end">
                <button id="botao_adicionar_topo" onClick="abrirModalTipoAgendamento();" class="btn btn-sm btn-primary" >
                    <i class="fas fa-plus"></i>
                    Novo Tipo de Agendamento
                </button>

            </div>
        </div>
        <hr class="mt-2 mb-3"/>
        <div class="col-12">
            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive">
                        <table id="tabela_agendamentotipos" class="table table-striped table-hover table-sm ">
                            <thead>
                            <tr>
                                <th scope="col">#Cód</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Situação</th>
                                <th scope="col">Total agendamentos</th>
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
    <div class="modal fade" id="modal_tipoagendamento" tabindex="-1" role="dialog" aria-labelledby="modal_tipoagendamento" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <livewire:agendamento-tipo.cadastro />
            </div>
        </div>
    </div>
@endsection
@push('styles')
@endpush
@push('scripts')
    <script>
        var DATATABLE;
        document.addEventListener('DOMContentLoaded', function() {

            var options = {
                backdrop: 'static',
                keyboard: true,
                show: false,
                focus: true
            };

            $('#modal_tipoagendamento').modal(options);

            $('#modal_tipoagendamento').on('hide.bs.modal', (e) => {$('#modal_tipoagendamento form').trigger("reset"); });
            $('#modal_tipoagendamento').on('hidden.bs.modal', (e) => Livewire.emit('limpar'));


            let renderBotoesEditarExluir = (data, type, row, meta) => {

                let saida = `<div class="d-flex justify-content-around">
                    <button onclick="editarTipoDeAgendamento(${row.id})" type="button" class="btn btn-xs btn-primary m-0"><i class="fa fa-edit" aria-hidden="true"></i></button>
                    <button onclick="excluirTipoDeAgendamento(${row.id})" type="button" class="btn btn-xs btn-danger m-0"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </div>`;
                return  saida;
            }


            DATATABLE = $('#tabela_agendamentotipos').DataTable( {
                dom: 'ti',
                paging: false,
                "ajax": "{{ route("api.tiposagendamentos.index") }}",
                "columns": [
                    { "data": "id" },
                    { "data": "nome", "render": DATATABLES_TIPO_AGENDAMENTO },
                    { "data": "descricao" },
                    { "data": "situacao", "render": DATATABLES_RENDER_SITUACAO },
                    { "data": "agendamentos_count" },
                    { "data": "ordem", "render": renderBotoesEditarExluir },
                ]
            } );

        });



        function abrirModalTipoAgendamento() {

            $('#modal_tipoagendamento').off('show.bs.modal');
            $('#modal_tipoagendamento').off('shown.bs.modal');
            $('#modal_tipoagendamento').on('shown.bs.modal', (e) => ativaJavascriptsModal());
            $('#modal_tipoagendamento').modal('show');
        }

        function ativaJavascriptsModal() {
            // #PENDENTE - Fazer o Picker para Cor
        }

        function excluirTipoDeAgendamento(agendamentoTipoID) {

            Swal.fire({
                title: 'Você tem certeza?',
                text: "O tipo de agendamento será excluído",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, tenho certeza!',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    Livewire.emit('excluirTipoDeAgendamento', agendamentoTipoID);
                } else {
                    console.log("Canceled");
                }
            });
        }

        function editarTipoDeAgendamento(agendamentoTipoID){
            $('#modal_tipoagendamento').off('show.bs.modal');
            $('#modal_tipoagendamento').off('shown.bs.modal');
            $('#modal_tipoagendamento').on('show.bs.modal', (e) => Livewire.emit('carregaTipoDeAgendamento',agendamentoTipoID));
            $('#modal_tipoagendamento').on('shown.bs.modal', (e) => ativaJavascriptsModal());
            $('#modal_tipoagendamento').modal('show');
        }

        window.addEventListener('triggerTipoAgendamentoGravadoSucesso', (event) => {
            toastr.success('Agendamento em '+ event.detail +' gravado com sucesso!');
            $('#modal_tipoagendamento').modal('hide');
            DATATABLE.ajax.reload();
        });

        window.addEventListener('triggerError', (event) => {
            toastr.error('Erro ao gravar tipo de agendamento: '+ event.detail);
        });

        window.addEventListener('triggerTipoAgendamentoExcluidoSucesso', (event) => {
            toastr.success('Tipo de Agendamento excluído com sucesso!');
            $('#modal_tipoagendamento').modal('hide');
            DATATABLE.ajax.reload();
        });

    </script>
@endpush
