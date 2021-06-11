<div class="card w-100">
    <div class="card-body pb-1">
        <div class="d-flex justify-content-between">
            <h5 class="font-weight-bold text-caixaAzul text-futurabold">Os 10 itens com mais inconformidade</br>
                <small class="text-black-50">% de itens sinalizados como inconformes em relação ao total preenchido</small>
            </h5>

        </div>
    </div>
    <canvas id="grafico_inconformes_por_item" class="p-2"></canvas>
</div>
@push('scripts')
    <script>
        const data_inconformes_por_item = {
            labels:  {!! json_encode($dados->keys()) !!}, //["Ag. Divinópolis", "Ag. Itaúna", "Ag. Extrema", "Ag. Garapari", "Ag. Cabo Frio"],
            datasets: [
                {
                    label: "%",
                    backgroundColor: {!! json_encode(array_values($cores)) !!},
                    data: {!! json_encode($dados->values()) !!}
                }
            ]
        };
        const config_inconformes_por_item = {
            type: 'bar',
            data: data_inconformes_por_item,
            options: {
                indexAxis: 'y',
                // Elements options apply to all of the options unless overridden in a dataset
                // In this case, we are setting the border of each horizontal bar to be 2px wide
                elements: {
                    bar: {
                        borderWidth: 1,
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
        var ctx_inconformes_por_item = document.getElementById('grafico_inconformes_por_item');
        var grafico_inconformes_por_item = new Chart(ctx_inconformes_por_item, config_inconformes_por_item);
    </script>
@endpush