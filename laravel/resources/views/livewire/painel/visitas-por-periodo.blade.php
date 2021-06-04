<div class="card w-100 mb-4">
    <div class="card-body pb-1">
        <div class="d-flex justify-content-between">
            <h5 class="font-weight-bold text-caixaAzul text-futurabold">Visitas realizadas</br>
                <small class="text-black-50">Percentual de visitas realizadas no período em relação ao total de unidades vinculadas</small>
            </h5>

        </div>
    </div>
    <div class="col-12 d-flex justify-content-between mb-1">
        <div class="text-caixaAzul font-weight-bold">{{ $dados['total_visitado'] }} de {{  $dados['total_unidades'] }} unidades</div>
        <div class="text-caixaAzul font-weight-bold">{{ $dados['percentual_visitado'] }}%</div>
    </div>
    <div class="col-12">
        <div class="progress md-progress" style="height: 30px">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $dados['percentual_visitado'] }}%; height: 30px" aria-valuenow="{{ $dados['percentual_visitado'] }}" aria-valuemin="0" aria-valuemax="100">{{ $dados['percentual_visitado'] }}%</div>
        </div>
    </div>
</div>
@push('scripts')

@endpush
