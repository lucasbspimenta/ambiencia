@extends('layouts.app')
@section('title', 'Demandas')
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1"
                        style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Demandas
                </h4>
            </div>
            <div class="col d-flex justify-content-end">
                <button id="botao_adicionar_topo" onClick="abrirDemanda();" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i>
                    Nova demanda
                </button>

            </div>
        </div>
        <hr class="mt-2 mb-3" />
        <div class="row">
            <div class="col">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="andamento-tab" data-toggle="tab" href="#andamento" role="tab"
                            aria-controls="andamento" aria-selected="true">Em andamento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tratar-tab" data-toggle="tab" href="#tratar" role="tab"
                            aria-controls="tratar" aria-selected="false">A tratar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="finalizados-tab" data-toggle="tab" href="#finalizados" role="tab"
                            aria-controls="finalizados" aria-selected="false">Finalizadas</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="pt-3 tab-pane fade show active" id="andamento" role="tabpanel"
                        aria-labelledby="andamento-tab">
                        <div class="row">
                            <div class="col-2">
                                <div class="card ">
                                    <div class="card-body ">
                                        <section id="base_filtros_tabela_andamento">
                                        </section>
                                    </div>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="card">
                                    <div class="card-body h-teladisponivel-fullcalendar">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-sm h-100"
                                                id="tabela_andamento">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#Cód</th>
                                                        <th scope="col">Sistema</th>
                                                        <th scope="col">Descrição</th>
                                                        <th scope="col">Prazo</th>
                                                        <th scope="col">Situação</th>
                                                        <th scope="col">Unidade</th>
                                                        <th scope="col">Responsavel</th>
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
                    <div class="pt-3 tab-pane fade" id="tratar" role="tabpanel" aria-labelledby="tratar-tab"></div>
                    <div class="pt-3 tab-pane fade" id="finalizados" role="tabpanel" aria-labelledby="finalizados-tab">
                        <div class="row">
                            <div class="col-2">
                                <div class="card ">
                                    <div class="card-body ">
                                        <section id="base_filtros_tabela_finalizados">
                                        </section>
                                    </div>
                                </div>
                            </div>
                            <div class="col-10">
                                <div class="card">
                                    <div class="card-body h-teladisponivel-fullcalendar">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-sm h-100"
                                                id="tabela_finalizados">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#Cód</th>
                                                        <th scope="col">Sistema</th>
                                                        <th scope="col">Descrição</th>
                                                        <th scope="col">Prazo</th>
                                                        <th scope="col">Situação</th>
                                                        <th scope="col">Unidade</th>
                                                        <th scope="col">Responsavel</th>
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
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_demanda" tabindex="-1" role="dialog" aria-labelledby="modal_demanda"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <livewire:demanda.cadastro :unidades="$unidades_vinculadas" />
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_tratar_demanda" tabindex="-1" role="dialog" aria-labelledby="modal_tratar_demanda"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <livewire:demanda.tratar />
            </div>
        </div>
    </div>
@endsection
@push('styles')

@endpush
@push('scripts')
    <script>
        const renderAcaosDemandas = (data, type, row, meta) => {
            let saida = ``;

            if (data) {
                saida +=
                    `<a class="btn btn-xs btn-primary" href="{{ route('checklist.index') }}/${data}/alterar">Checklist</a>`;
            } else {
                if (row['demanda_migracao'] != 'C') {
                    saida +=
                        `<button onclick="excluirDemanda(${row['demanda_id']})" type="button"
                            class="btn btn-xs btn-danger m-0 flex-shrink-1"><i class="fa fa-trash"
                                aria-hidden="true"></i></button>`;
                }
            }

            return saida;
        }
        const NOME_MODAL = '#modal_demanda';
        const NOME_MODEL_TRATAR_DEMANDA = '#modal_tratar_demanda';
        const CONFIG_COLUNAS = [{
                "data": "demanda_id"
            },
            {
                "data": "sistema_nome",
                //'render': DATATABLES_TIPO_AGENDAMENTO
            },
            {
                "data": "demanda_descricao"
            },
            {
                "data": "demanda_prazo"
            },
            {
                "data": "demanda_situacao",
                'render': DATATABLES_RENDER_SITUACAO_DEMANDA
            },
            {
                "data": "unidade_nome",
                //'render': renderBotoesEditarExluir
            },
            {
                "data": "responsavel_nome",
                //'render': renderBotoesEditarExluir
            },
            {
                "data": "demanda_checklist",
                'render': renderAcaosDemandas
            },
        ];

        const montaFiltrosAoIniciar = function(API) {
            //console.log('Montando filtros', API.table().node().id);
            $('#base_filtros_' + API.table().node().id).html('');
            API.columns().every(function() {
                var destino = $('#base_filtros_' + this.table().node().id);
                var title = $(this.table().column(this.index()).header()).html();
                var column = this;
                var section = $(
                    '<section class="mb-4"><h6 class="mb-2 font-weight-bold">' +
                    title + ':</h6></section>').appendTo(destino);
                var select = $(
                        '<select class="browser-default custom-select"><option value=""></option></select>'
                    )
                    //.appendTo($(column.footer()).empty())
                    .appendTo(section)
                    .on('change', function() {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });
                column.data().unique().sort().each(function(d, j) {
                    if (d) {
                        select.append('<option value="' + d + '">' + d +
                            '</option>');
                    }
                });
            });
        };

        document.addEventListener('DOMContentLoaded', function() {

            var options = {
                backdrop: 'static',
                keyboard: true,
                show: false,
                focus: true
            };

            $(NOME_MODAL).modal(options);
            $(NOME_MODAL).on('hidden.bs.modal', (e) => Livewire.emit('limpar'));

            var options_tratar = {
                backdrop: 'static',
                keyboard: true,
                show: true,
                focus: true
            };
            $(NOME_MODEL_TRATAR_DEMANDA).modal(options_tratar);

            DATATABLE = $('#tabela_andamento').DataTable({
                dom: 'ti',
                scrollY: 'calc(100vh - (370px))',
                paging: false,
                responsive: true,
                scrollCollapse: true,
                ajax: {
                    url: "{{ route('api.demandasapi') }}",
                    dataSrc: ''
                },
                columns: CONFIG_COLUNAS,
                initComplete: function() {
                    var api = this.api();
                    montaFiltrosAoIniciar(api);
                }
            });

            DATATABLE_FINALIZADOS = $('#tabela_finalizados').DataTable({
                dom: 'ti',
                scrollY: 'calc(100vh - (370px))',
                paging: false,
                responsive: true,
                scrollCollapse: true,
                ajax: {
                    url: "{{ route('api.demandasapi', ['finalizados' => true]) }}",
                    dataSrc: ''
                },
                columns: CONFIG_COLUNAS,
                initComplete: function() {
                    var api = this.api();
                    montaFiltrosAoIniciar(api);
                }
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e, ) {
                switch (e.target.id) {
                    case 'andamento-tab':
                        DATATABLE.ajax.reload(montaFiltrosAoIniciar(DATATABLE));
                        DATATABLE.columns.adjust().responsive.recalc();

                        break;

                    case 'finalizados-tab':
                        DATATABLE_FINALIZADOS.ajax.reload(montaFiltrosAoIniciar(DATATABLE_FINALIZADOS));
                        DATATABLE_FINALIZADOS.columns.adjust().responsive.recalc();
                        break;
                }
            });
        });

        window.addEventListener('triggerSucesso', (event) => {
            toastr.success('Demanda para ' + event.detail + ' gravada com sucesso!');
            $(NOME_MODAL).modal('hide');
        });

        window.addEventListener('triggerError', (event) => {
            toastr.error('Erro ao gravar demanda: ' + event.detail);
        });

        function abrirDemanda(demanda_id, resposta_id, componente) {

            if (demanda_id)
                Livewire.emit('defineDemanda', demanda_id);

            $(NOME_MODAL).off('show.bs.modal');
            $(NOME_MODAL).off('shown.bs.modal');
            $(NOME_MODAL).off('hide.bs.modal');
            $(NOME_MODAL).on('hide.bs.modal', (e) => DATATABLE.ajax.reload((json) => montaFiltrosAoIniciar(DATATABLE)));
            $(NOME_MODAL).modal('show');
        }

        function excluirDemanda(demandaId) {

            Swal.fire({
                title: 'Você tem certeza?',
                text: "A demanda será excluída",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, tenho certeza!',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    Livewire.emit('excluirExterno', demandaId)
                } else {
                    console.log("Canceled");
                }
            });
        }

        window.addEventListener('triggerSucessoExclusao', (event) => {
            toastr.success('Demanda excluída com sucesso!');
            DATATABLE.ajax.reload((json) => montaFiltrosAoIniciar(DATATABLE));
        });
    </script>
@endpush
