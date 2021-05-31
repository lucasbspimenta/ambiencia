@extends('layouts.app')
@section('title', 'Checklist')
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1" style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Painel
                </h4>
            </div>
        </div>
        <hr class="mt-2 mb-3"/>
        <div class="row">
            <div class="col-4 ">
                <livewire:painel.proximos-agendamentos />
            </div>
            <div class="col-3">
                <livewire:painel.checklists-pendentes />
            </div>
            <div class="col-5">
                <div class="card w-100">
                    <div class="card-body pb-1">
                        <div class="d-flex justify-content-between">
                            <h5 class="font-weight-bold text-caixaAzul text-futurabold">Demandas em andamento</h5>
                        </div>
                    </div>
                    <canvas id="myChart" class="p-2"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const data = {
            labels: ["Ag. Divinópolis", "Ag. Itaúna", "Ag. Extrema", "Ag. Garapari", "Ag. Cabo Frio"],
            datasets: [
                {
                    label: "Population (millions)",
                    backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                    data: [2478,5267,734,784,433]
                }
            ]
        };
        const config = {
            type: 'bar',
            data: data,
            options: {
                indexAxis: 'y',
                // Elements options apply to all of the options unless overridden in a dataset
                // In this case, we are setting the border of each horizontal bar to be 2px wide
                elements: {
                    bar: {
                        borderWidth: 2,
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        display: false,
                    },
                    title: {
                        display: false,
                        text: 'Chart.js Horizontal Bar Chart'
                    }
                }
            },
        };
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, config);
    </script>
@endpush
