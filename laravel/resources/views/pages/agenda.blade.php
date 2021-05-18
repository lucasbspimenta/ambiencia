@extends('layouts.app')
@section('title', 'Agenda')
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1" style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Agenda
                </h4>
            </div>
            <div class="col d-flex justify-content-end">
                <button id="botao_adicionar_topo" onClick="abrirModalAgenda('{{ (new \DateTime())->format("d/m/Y") }}','{{ (new \DateTime())->format("d/m/Y") }}');" class="btn btn-sm btn-primary" >
                    <i class="fas fa-plus"></i>
                    Novo agendamento
                </button>

            </div>
        </div>
        <hr class="mt-2 mb-3"/>
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
                                        <div style="background-color: {{ $tipo->cor }}; width: 12px; height: 12px; display: inline-block"></div>
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
                                        <a class="nav-link active show" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Calendário</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Tabela</a>
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

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active h-teladisponivel-fullcalendar" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div id="calendar" ></div>
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt consequuntur possimus dignissimos enim atque dicta excepturi laudantium explicabo sit.</div>
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
                <div class="modal-header">
                    <h4 class="modal-title w-100 text-caixaAzul text-futurabold" id="modal_agenda">Novo Agendamento</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <livewire:agendamento.cadastro :tiposagendamentos="$lista_tipos_de_agendamento" />
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
                backdrop: true,
                keyboard: true,
                show: false,
                focus: true
            };

            $('#modal_agenda').modal(options);
            $('#modal_agenda').on('hide.bs.modal', (e) => {$('#modal_agenda form').trigger("reset"); $('.datepicker').data('daterangepicker').remove() });
            $('#modal_agenda').on('hidden.bs.modal', (e) => Livewire.emit('limpar'));

            var calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [ interactionPlugin, dayGridPlugin, listPlugin, timeGridPlugin ],
                headerToolbar: {
                    left  : 'prev,next today',
                    center: 'title',
                    right : 'dayGridMonth,listWeek'
                },
                buttonText:
                    {
                        today:    'Hoje',
                        month:    'Mês',
                        week:     'Semana',
                        day:      'Dia',
                        list:     'Lista da Semana'
                    },
                locale: 'pt-BR',
                initialView: 'dayGridMonth',
                selectable:true,
                height: '100%',
                themeSystem: 'bootstrap',
                select: aoSelecionarData,
                //eventResize: alterarAgendamento,
                //eventDrop: alterarAgendamento,
                lazyFetching: true,
                editable:true,
                eventSources: [
                        @foreach ($lista_tipos_de_agendamento as $tipo)
                        {
                            url: '{{ route("api.agendamentostipo",[$tipo->id]) }}', // use the `url` property
                            color: '{{ $tipo->cor }}',    // an option!
                            textColor: 'white',
                            startParam: 'inicio',
                            endParam: 'final'
                        },
                        @endforeach
                ],
                eventClick: function(info) {
                    console.log(info.event.id);
                    Livewire.emit('abrirModalVerAgenda', info.event.id);
                },
                eventDidMount: function(info) {

                    if(info.event.extendedProps.descricao) {
                        /*
                        var tooltip = tippy(info.el, {
                            content: '<ul><li>'+ info.event.extendedProps.descricao +'</li></ul>',
                            allowHTML: true,
                        });
                         */
                    }
                },
            });

            calendar.render();
        });

        window.addEventListener('triggerAgendaGravadaSucesso', (event) => {
            toastr.success('Agendamento em '+ event.detail +' gravado com sucesso!');
            calendar.refetchEvents();
            $('#modal_agenda').modal('hide');
        })

        window.addEventListener('triggerError', (event) => {
            toastr.error('Erro ao gravar agendamento: '+ event.detail);
        })

        function abrirModalAgenda(data_inicio, data_final) {

            $('#modal_agenda').on('show.bs.modal', (e) => Livewire.emit('definirDatas',data_inicio, data_final));
            $('#modal_agenda').on('shown.bs.modal', (e) => ativaJavascriptsModal());
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

        function aoSelecionarData({startStr, endStr}) {

            let startStr_f = moment(startStr, "YYYY-MM-DD").format('DD/MM/YYYY');
            let endStr_f = moment(endStr, "YYYY-MM-DD").subtract(1, 'days').format('DD/MM/YYYY');
            abrirModalAgenda(startStr_f, endStr_f);
        }
    </script>
@endpush
