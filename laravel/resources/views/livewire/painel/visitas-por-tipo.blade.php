<div class="card w-100">
    <div class="card-body pb-1">
        <div class="d-flex justify-content-between">
            <h5 class="font-weight-bold text-caixaAzul text-futurabold">Visitas realizadas por tipo</br>
                <small class="text-black-50">Percentual por tipo de visita realizada no período em relação ao total de visitas</small>
            </h5>

        </div>
    </div>
    <div class="col-12 d-flex justify-content-between mb-1">
        @foreach($tipos as $tipo)
            <div>
                <span class="text-black-50 text-nowrap  d-block text-left">
                    <span style="width: 13px; height: 11px; margin-right:5px; background-color: {{$tipo->cor}}" class="d-inline-block align-text-middle"></span>
                    {{$tipo->nome}}
                </span>
            </div>
        @endforeach
    </div>
    <div class="col-12 pb-3">
        <div class="progress" style="height: 30px">
            @foreach($tipos as $tipo)
                <div class="progress-bar" role="progressbar" style="width: {{ $dados[$tipo->id] ?? 0.00 }}%; height: 30px; background-color: {{$tipo->cor}}" aria-valuenow="{{ $dados[$tipo->id] ?? 0.00 }}" aria-valuemin="0" aria-valuemax="100">{{ $dados[$tipo->id] ?? 0.00 }}%</div>
            @endforeach
        </div>
    </div>
</div>
