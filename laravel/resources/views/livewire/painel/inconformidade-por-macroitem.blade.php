<div class="card w-100">
    <div class="card-body pb-1">
        <div class="d-flex justify-content-between">
            <h5 class="font-weight-bold text-caixaAzul text-futurabold">Inconformidade por macroitem</br>
                <small class="text-black-50">Percentual de itens sinalizados como inconformes em relação ao total
                    preenchido para o item</small>
            </h5>

        </div>
    </div>
    <canvas id="grafico_inconformes_por_macroitem" class="p-2"></canvas>
</div>
@push('scripts')
    <script>
        var grafico_inconformes_por_macroitem;

        const data_inconformes_por_macroitem = {
            labels: {!! json_encode($dados->keys()) !!}, //["Ag. Divinópolis", "Ag. Itaúna", "Ag. Extrema", "Ag. Garapari", "Ag. Cabo Frio"],
            datasets: [{
                label: "%",
                backgroundColor: {!! json_encode($cores->values()) !!},
                data: {!! json_encode($dados->values()) !!}
            }]
        };
        const config_inconformes_por_macroitem = {
            type: 'bar',
            data: data_inconformes_por_macroitem,
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
        var ctx_inconformes_por_macroitem = document.getElementById('grafico_inconformes_por_macroitem');
        grafico_inconformes_por_macroitem = new Chart(ctx_inconformes_por_macroitem, config_inconformes_por_macroitem);

        window.addEventListener('atualizarGraficoMacroItem', (event) => {

            atualizarGrafico(grafico_inconformes_por_macroitem, event.detail.label, event.detail.cores, event.detail
                .dados);
        });

        function atualizarGrafico(grafico, label, cores, dados) {
            let novos_dados = {
                labels: eval(label),
                datasets: [{
                    label: "%",
                    backgroundColor: eval(cores),
                    data: eval(dados)
                }]
            }
            grafico.data = novos_dados;

            grafico.update();
        }
    </script>
@endpush
