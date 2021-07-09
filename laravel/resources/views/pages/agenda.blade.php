@extends('layouts.app')
@section('title', 'Agenda')
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1"
                        style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Agenda
                </h4>
            </div>
            <div class="col d-flex justify-content-end">
                <button id="botao_adicionar_topo"
                    onClick="abrirModalAgenda('{{ (new \DateTime())->format('d/m/Y') }}','{{ (new \DateTime())->format('d/m/Y') }}');"
                    class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i>
                    Novo agendamento
                </button>

            </div>
        </div>
        <hr class="mt-2 mb-3" />
        <div class="row">
            <div class="col-2">
                <div class="card">
                    <div class="card-body ">
                        <section>
                            <!--<h5>Filters</h5>-->
                            <section class="mb-4">

                                <h6 class="font-weight-bold mb-3">Tipos de Agendamento:</h6>
                                @forelse($lista_tipos_de_agendamento as $tipo)
                                    <div class="text-nowrap ">
                                        <div
                                            style="background-color: {{ $tipo->cor }}; width: 12px; height: 12px; display: inline-block">
                                        </div>
                                        {{ $tipo->nome }}
                                    </div>
                                @empty
                                    Nenhum tipo localizado
                                @endforelse
                            </section>
                            <section class="mb-4">
                                <h6 class="font-weight-bold mb-3">Exibir como:</h6>
                                <ul class="nav tabs-primary nav-justified tabs-filter" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active show" id="calendario-tab" data-toggle="tab"
                                            href="#calendario" role="tab" aria-controls="calendario"
                                            aria-selected="true">Calendário</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tabela-tab" data-toggle="tab" href="#tabela" role="tab"
                                            aria-controls="tabela" aria-selected="false">Tabela</a>
                                    </li>
                                </ul>
                            </section>
                        </section>
                    </div>
                </div>
            </div>
            <div class="col-10">
                <div class="card">
                    <div class="card-body ">
                        <div class="classic-tabs">

                            <div class="tab-content">
                                <div class="tab-pane fade show active h-teladisponivel-fullcalendar" id="calendario"
                                    role="tabpanel" aria-labelledby="calendario-tab">
                                    <div id="calendar"></div>
                                </div>
                                <div class="tab-pane fade h-teladisponivel-fullcalendar" id="tabela" role="tabpanel"
                                    aria-labelledby="tabela-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-sm h-100"
                                            id="tabela_agendamentos">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#Cód</th>
                                                    <th scope="col">Tipo</th>
                                                    <th scope="col">Unidade</th>
                                                    <th scope="col">Responsavel</th>
                                                    <th scope="col">Descrição</th>
                                                    <th scope="col">Período</th>
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
    <div class="modal fade" id="modal_agenda" tabindex="-1" role="dialog" aria-labelledby="modal_agenda" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <livewire:agendamento.cadastro :tiposagendamentos="$lista_tipos_de_agendamento"
                    />
            </div>
        </div>
    </div>
@endsection
@push('styles')

@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var options = {
                backdrop: 'static',
                keyboard: true,
                show: false,
                focus: true
            };

            $('#modal_agenda').modal(options);
            $('#modal_agenda').on('hide.bs.modal', (e) => {
                $('#modal_agenda form').trigger("reset");
                $('.datepicker').data('daterangepicker').remove()
            });
            $('#modal_agenda').on('hidden.bs.modal', (e) => Livewire.emit('limpar'));

            var calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                locales: allLocales,
                locale: 'pt-br',
                plugins: [interactionPlugin, dayGridPlugin, listPlugin, timeGridPlugin, bootstrapPlugin],
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listWeek'
                },
                buttonText: {
                    today: 'Hoje',
                    month: 'Mês',
                    week: 'Semana',
                    day: 'Dia',
                    list: 'Lista da Semana'
                },
                locale: 'pt-BR',
                initialView: 'dayGridMonth',
                selectable: true,
                height: '100%',
                themeSystem: 'bootstrap',
                select: aoSelecionarData,
                eventResize: alterarAgendamento,
                eventDrop: alterarAgendamento,
                lazyFetching: true,
                editable: true,
                eventSources: [
                    @foreach ($lista_tipos_de_agendamento as $tipo)
                        {
                        url: '{{ route('api.agendamentostipo', [$tipo->id]) }}', // use the `url` property
                        color: '{{ $tipo->cor }}', // an option!
                        textColor: 'white',
                        startParam: 'inicio',
                        endParam: 'final'
                        },
                    @endforeach
                ],
                eventClick: function(info) {
                    editarAgendamento(info.event.id);
                },
                eventDidMount: function(info) {

                    if (info.event.extendedProps.descricao) {
                        //$(info.el).tooltip({title:info.event.extendedProps.descricao})
                        $(info.el).popover({
                            title: 'Descrição',
                            html: true,
                            content: info.event.extendedProps.descricao + '</br></br>' +
                                'Responsável: <b>' + info.event.extendedProps
                                .unidade_responsavel + '</b>',
                            trigger: 'hover',
                            placement: 'auto'
                        });
                    }
                },
            });

            calendar.render();

            let formataPeriodo = (data, type, row, meta) => {
                if (row.final != data)
                    return data + ' a ' + row.final;
                return data;
            }

            let renderBotoesEditarExluir = (data, type, row, meta) => {

                let saida = `<div class="d-flex justify-content-around">
                    <button onclick="editarAgendamento(${row.id})" type="button" class="btn btn-xs btn-primary m-0"><i class="fa fa-edit" aria-hidden="true"></i></button>
                    <button onclick="excluirAgendamento(${row.id})" type="button" class="btn btn-xs btn-danger m-0"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </div>`;
                return saida;
            }

            DATATABLE = $('#tabela_agendamentos').DataTable({
                dom: 'ti',
                scrollY: 'calc(100vh - (350px))',
                paging: false,
                responsive: true,
                scrollCollapse: true,
                ajax: {
                    url: "{{ route('api.agendamentos.index') }}",
                    dataSrc: ''
                },
                columns: [{
                        "data": "id"
                    },
                    {
                        "data": "tipo",
                        'render': DATATABLES_TIPO_AGENDAMENTO
                    },
                    {
                        "data": "title"
                    },
                    {
                        "data": "unidade_responsavel"
                    },
                    {
                        "data": "descricao"
                    },
                    {
                        "data": "inicio",
                        'render': formataPeriodo
                    },
                    {
                        "data": "created_by",
                        'render': renderBotoesEditarExluir
                    },
                ]
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e, ) {

                if (e.target.id == 'tabela-tab') {
                    $('#tabela_agendamentos').css("width", '100%');
                    DATATABLE.ajax.reload();
                    DATATABLE.columns.adjust().responsive.recalc();
                } else {
                    calendar.render();
                }
            });
        });

        window.addEventListener("livewire:load", function(event) {
            window.livewire.hook('element.updated', () => {
                ativaJavascriptsModal();
            });
        });


        window.addEventListener('triggerAgendaGravadaSucesso', (event) => {
            toastr.success('Agendamento em ' + event.detail + ' gravado com sucesso!');
            calendar.refetchEvents();
            DATATABLE.ajax.reload();
            $('#modal_agenda').modal('hide');
        });

        window.addEventListener('triggerError', (event) => {
            toastr.error('Erro ao gravar agendamento: ' + event.detail);
        });

        window.addEventListener('triggerAgendaExcluidaSucesso', (event) => {
            toastr.success('Agendamento excluído com sucesso!');
            calendar.refetchEvents();
            DATATABLE.ajax.reload();
            $('#modal_agenda').modal('hide');
        });

        function abrirModalAgenda(data_inicio, data_final) {

            $('#modal_agenda').off('show.bs.modal');
            $('#modal_agenda').off('shown.bs.modal');
            $('#modal_agenda').on('show.bs.modal', (e) => Livewire.emit('definirDatas', data_inicio, data_final));
            $('#modal_agenda').on('shown.bs.modal', (e) => {
                ativaJavascriptsModal();
                defineDataNoDatePicker('inicio', data_inicio);
                defineDataNoDatePicker('final', data_final);
            });
            $('#modal_agenda').modal('show');
        }

        function ativaJavascriptsModal() {


            $('.datepicker').daterangepicker({
                "singleDatePicker": true,
                "autoUpdateInput": true,
                "autoApply": true,
                "locale": dateRangePickerSettings,
            });

        }

        function defineDataNoDatePicker(id, valor) {
            $('#' + id).data('daterangepicker').setStartDate(valor);
        }

        function aoSelecionarData({
            startStr,
            endStr
        }) {

            let startStr_f = moment(startStr, "YYYY-MM-DD").format('DD/MM/YYYY');
            let endStr_f = moment(endStr, "YYYY-MM-DD").subtract(1, 'days').format('DD/MM/YYYY');
            abrirModalAgenda(startStr_f, endStr_f);
        }

        function alterarAgendamento(info, delta) {
            const event = info.event;
            console.log(info.event.id);
            axios.post('{{ route('api.agendamento_update') }}', {
                    id: event.id,
                    inicio: event.startStr,
                    final: (event.endStr ? event.endStr : event.startStr)
                })
                .then(function(response) {
                    toastr.success('Reagendado com sucesso!');
                    calendar.refetchEvents();
                })
                .catch(function(error) {
                    toastr.error('Erro ao reagendar ' + error);
                    calendar.refetchEvents();
                });
        }

        function editarAgendamento(id) {

            $('#modal_agenda').off('show.bs.modal');
            $('#modal_agenda').off('shown.bs.modal');
            $('#modal_agenda').on('show.bs.modal', (e) => Livewire.emit('carregaAgendamento', id));
            $('#modal_agenda').on('shown.bs.modal', (e) => ativaJavascriptsModal());
            $('#modal_agenda').modal('show');
        }

        function excluirAgendamento(agendaId, temChecklist) {

            Swal.fire({
                title: 'Você tem certeza?',
                text: (temChecklist) ? "O agendamento será excluído juntamento com o checklist vinculado a ele" : "O agendamento será excluído",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, tenho certeza!',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    Livewire.emit('excluirAgendamento', agendaId);
                } else {
                    console.log("Canceled");
                }
            });
        }
    </script>
@endpush
