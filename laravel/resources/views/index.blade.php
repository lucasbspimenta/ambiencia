@extends('layouts.app')
@section('title', 'Checklist')
@section('content')
    <div class="mb-5 container-fluid">

        <div class="row d-flex justify-content-between">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1" style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Painel
                </h4>
            </div>
            <div class="col-auto d-flex">
                <div id="reportrange" class="mr-3 d-inline-block" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>
            </div>
        </div>
        <hr class="mt-2 mb-3"/>
        <div class="mb-4 row">
            <div class="col-4 ">
                <livewire:painel.proximos-agendamentos />
            </div>
            <div class="col-4">
                <livewire:painel.checklists-pendentes />
            </div>
            <div class="col-4">
                <livewire:painel.visitas-por-periodo />
                <livewire:painel.visitas-por-tipo />
            </div>
        </div>
        <div class="mb-3 row">
            <div class="col-6">
                <livewire:painel.inconformidade-por-item />
            </div>
            <div class="col-6">
                <livewire:painel.inconformidade-por-macroitem />
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {

            var start = moment().locale('pt-br').subtract(29, 'days');
            var end = moment().locale('pt-br');

            switch(moment().locale('pt-br').format('MM'))
            {
                case '01':
                case '02':
                case '03':
                    start = moment("01/01/{{ Date('Y') }}", "DD-MM-YYYY");
                    end = moment("{{ Carbon\Carbon::parse('last day of march')->format('d/m/Y') }}", "DD-MM-YYYY");
                    break;

                case '04':
                case '05':
                case '06':
                    start = moment("{{ Carbon\Carbon::parse('first day of april ' . Date('Y'))->format('d/m/Y') }}", "DD-MM-YYYY");
                    end = moment("{{ Carbon\Carbon::parse('last day of june')->format('d/m/Y') }}", "DD-MM-YYYY");
                    break;

                case '07':
                case '08':
                case '09':
                    start = moment("{{ Carbon\Carbon::parse('first day of july ' . Date('Y'))->format('d/m/Y') }}", "DD-MM-YYYY");
                    end = moment("{{ Carbon\Carbon::parse('last day of september')->format('d/m/Y') }}", "DD-MM-YYYY");
                    break;

                case '10':
                case '11':
                case '12':
                    start = moment("{{ Carbon\Carbon::parse('first day of october ' . Date('Y'))->format('d/m/Y') }}", "DD-MM-YYYY");
                    end = moment("{{ Carbon\Carbon::parse('last day of december')->format('d/m/Y') }}", "DD-MM-YYYY");
                    break;

            }

            function cb(start, end) {
                $('#reportrange span').html(start.locale('pt-br').format('D MMMM YYYY') + ' - ' + end.locale('pt-br').format('D MMMM YYYY'));
            }

            $('#reportrange').daterangepicker({
                locale: dateRangePickerSettings,
                startDate: start,
                endDate: end,
                ranges: {
                    //'Today': [moment(), moment()],
                    //'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    //'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    //'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    //'This Month': [moment().startOf('month'), moment().endOf('month')],
                    //'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    '1 trimestre': [ moment("01/01/{{ Date('Y') }}", "DD-MM-YYYY"), moment("{{ Carbon\Carbon::parse('last day of march')->format('d/m/Y') }}", "DD-MM-YYYY")],
                    '2 trimestre': [ moment("{{ Carbon\Carbon::parse('first day of april ' . Date('Y'))->format('d/m/Y') }}", "DD-MM-YYYY"), moment("{{ Carbon\Carbon::parse('last day of june')->format('d/m/Y') }}", "DD-MM-YYYY")],
                    '3 trimestre': [ moment("{{ Carbon\Carbon::parse('first day of july ' . Date('Y'))->format('d/m/Y') }}", "DD-MM-YYYY"), moment("{{ Carbon\Carbon::parse('last day of september')->format('d/m/Y') }}", "DD-MM-YYYY")],
                    '4 trimestre': [ moment("{{ Carbon\Carbon::parse('first day of october ' . Date('Y'))->format('d/m/Y') }}", "DD-MM-YYYY"), moment("{{ Carbon\Carbon::parse('last day of december')->format('d/m/Y') }}", "DD-MM-YYYY")]
                }
            }, cb);

            cb(start, end);

            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                atualizarData(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
            });

            $('.treeview-animated').mdbTreeview();
        });

        function atualizarData(data_inicio, data_final)
        {
            Livewire.emit('atualizarData', data_inicio, data_final);
        }

        document.addEventListener('atualizarTreeview', function() {
            $('.treeview-animated').mdbTreeview();
        });
    </script>
@endpush
