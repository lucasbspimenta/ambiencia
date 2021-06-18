<div class="card w-100">
    <div class="card-body pb-1">
        <div class="d-flex justify-content-between">
            <h5 class="font-weight-bold text-caixaAzul text-futurabold">Visitas realizadas por tipo ({{  $total->sum() }})</br>
                <small class="text-black-50">Percentual por tipo de visita realizada no período em relação ao total de visitas</small>
            </h5>

        </div>
    </div>
    <div class="col-12 d-flex justify-content-between mb-1">
        @foreach($tipos as $tipo)
            <div style="font-size:11px;">
                <span class="text-nowrap d-block text-left ">
                    <span style="width: 13px; height: 11px; margin-right: 2px; background-color: {{$tipo->cor}}" class="d-inline-block align-text-middle"></span>
                    {{$tipo->nome}} ({{ $total[$tipo->id] ?? 0 }})
                </span>
            </div>
        @endforeach
    </div>
    <div class="col-12 pb-3">
        <div class="progress" style="height: 20px">
            @foreach($tipos as $tipo)
                @if(isset($total[$tipo->id]))
                    <div class="progress-bar" role="progressbar" style="width: {{ ($total[$tipo->id] * 100)/$total->sum() ?? 0.00 }}%; height: 20px; background-color: {{$tipo->cor}}" aria-valuenow="{{ ($total[$tipo->id] * 100)/$total->sum() ?? 0.00 }}" aria-valuemin="0" aria-valuemax="100">{{ number_format(($total[$tipo->id] * 100)/$total->sum() ?? 0.00, 2, ',', '.') }}%</div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="treeview-animated w-100">
        <ul class="pl-0 mb-3 treeview-animated-list">
            @include('livewire/painel/visitas-por-tipo-item', ['visitas' => $visitas, 'tipos' => $tipos, 'contador_visitas_nivel' => $contador_visitas_nivel, 'nivel' => 1])
        </ul>
    </div>
</div>
