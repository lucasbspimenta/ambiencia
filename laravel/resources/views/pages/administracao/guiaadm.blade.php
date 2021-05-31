@extends('layouts.app')
@section('title', 'Guia - Administração')
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1" style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Guia
                </h4>
            </div>
            <div class="col d-flex justify-content-end">
                <button id="botao_adicionar_topo" onClick="abrirModalGuia();" class="btn btn-sm btn-primary" >
                    <i class="fas fa-plus"></i>
                    Novo Guia
                </button>

            </div>
        </div>
        <hr class="mt-2 mb-3"/>
        <div class="col-12">
            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive">
                        <table id="tabela_guias" class="table table-striped table-hover table-sm ">
                            <thead>
                            <tr>
                                <th scope="col">#Cód</th>
                                <th scope="col">Item</th>
                                <th scope="col" class="max-w-150px">Descrição</th>
                                <th scope="col">Total Itens</th>
                                <th scope="col">Total Imagens</th>
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
    <div class="modal fade" id="modal_guia" tabindex="-1" role="dialog" aria-labelledby="modal_guia" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <livewire:guia.cadastro :checklistItens="$checklistItens" />
            </div>
        </div>
    </div>
@endsection
@push('styles')
@endpush
@push('scripts')
    <script>
        var DATATABLE;
        const NOME_MODAL = '#modal_guia';

        document.addEventListener('DOMContentLoaded', function() {

            var options = {
                backdrop: 'static',
                keyboard: true,
                show: false,
                focus: true
            };

            $(NOME_MODAL).modal(options);

            $(NOME_MODAL).on('hide.bs.modal', (e) => {$(NOME_MODAL + ' form').trigger("reset"); });
            $(NOME_MODAL).on('hidden.bs.modal', (e) => Livewire.emit('limpar'));

            let renderBotoesEditarExluir = (data, type, row, meta) => {

                let saida = `<div class="d-flex justify-content-around">
                    <button onclick="editarGuia(${row.id})" type="button" class="btn btn-xs btn-primary m-0"><i class="fa fa-edit" aria-hidden="true"></i></button>
                    <button onclick="excluirGuia(${row.id})" type="button" class="btn btn-xs btn-danger m-0"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </div>`;
                return  saida;
            }


            DATATABLE = $('#tabela_guias').DataTable( {
                dom: 'ti',
                paging: false,
                "ajax": "{{ route("api.guias.index") }}",
                "columns": [
                    { "data": "id" },
                    { "data": "checklist_item.nome" },
                    { "data": "descricao" },
                    { "data": "itens.length" },
                    { "data": "imagens.length" },
                    { "data": "checklist_item.ordem", "render": renderBotoesEditarExluir },
                ]
            } );
        });

        window.addEventListener('triggerSucesso', (event) => {
            toastr.success('Guia do Item: '+ event.detail +' gravado com sucesso!');
            $(NOME_MODAL).modal('hide');
            DATATABLE.ajax.reload();
        });

        window.addEventListener('triggerSucessoExclusao', (event) => {
            toastr.success('Guia excluído com sucesso!');
            $(NOME_MODAL).modal('hide');
            DATATABLE.ajax.reload();
        });

        window.addEventListener('triggerError', (event) => {
            toastr.error('Erro ao gravar guia: '+ event.detail);
        });

        function excluirGuia(guiaID) {

            Swal.fire({
                title: 'Você tem certeza?',
                text: "O guia será excluído junto com seus registros vinculados",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, tenho certeza!',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    Livewire.emit('excluirGuia', guiaID);
                } else {
                    console.log("Canceled");
                }
            });
        }

        function abrirModalGuia() {

            $(NOME_MODAL).off('show.bs.modal');
            $(NOME_MODAL).off('shown.bs.modal');
            //$(NOME_MODAL).on('shown.bs.modal', (e) => ativaJavascriptsModal());
            $(NOME_MODAL).modal('show');
        }

        function editarGuia(guiaID){
            $(NOME_MODAL).off('show.bs.modal');
            $(NOME_MODAL).off('shown.bs.modal');
            $(NOME_MODAL).on('show.bs.modal', (e) => Livewire.emit('carregaGuia',guiaID));
            //$(NOME_MODAL).on('shown.bs.modal', (e) => ativaJavascriptsModal());
            $(NOME_MODAL).modal('show');
        }
    </script>
@endpush
