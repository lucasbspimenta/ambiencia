<div class="mb-4 card w-100">
    <div class="pb-1 card-body">
        <div class="d-flex justify-content-between">
            <h5 class="font-weight-bold text-caixaAzul text-futurabold">Visitas realizadas</br>
                <small class="text-black-50">Percentual de visitas realizadas no período em relação ao total de unidades
                    vinculadas</small>
            </h5>

        </div>
    </div>
    <div class="mb-1 col-12 d-flex justify-content-between">
        <div class="text-caixaAzul font-weight-bold">{{ $total_visitado ?? 0 }} de {{ $total_unidades ?? 0 }}
            unidades</div>
        <div class="text-caixaAzul font-weight-bold">
            {{ number_format($total_percentual_visitado ?? 0.0, 2, ',', '.') }}%</div>
    </div>
    <div class="col-12">
        <div class="progress md-progress" style="height: 20px">
            <div class="progress-bar bg-success" role="progressbar"
                style="width: {{ $total_percentual_visitado ?? 0.0 }}%; height: 20px"
                aria-valuenow="{{ $total_percentual_visitado ?? 0.0 }}" aria-valuemin="0" aria-valuemax="100">
                {{ number_format($total_percentual_visitado ?? 0.0, 2, ',', '.') }}%</div>
        </div>
    </div>
    @if (sizeof($visitas) > 1)
        <div class="treeview-animated w-100">
            <ul class="pl-0 mb-3 treeview-animated-list">
                @include('livewire/painel/visitas-por-periodo-item', ['visitas' => $visitas, 'contador_unidades_nivel'
                =>
                $contador_unidades_nivel, 'contador_visitas_nivel' => $contador_visitas_nivel, 'nivel' => 1])
            </ul>
        </div>
    @endif
</div>
