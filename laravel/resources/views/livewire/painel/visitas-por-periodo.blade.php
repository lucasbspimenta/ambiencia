<div class="card w-100 mb-4">
    <div class="card-body pb-1">
        <div class="d-flex justify-content-between">
            <h5 class="font-weight-bold text-caixaAzul text-futurabold">Visitas realizadas</br>
                <small class="text-black-50">Percentual de visitas realizadas no período em relação ao total de unidades vinculadas</small>
            </h5>

        </div>
    </div>
    <div class="col-12 d-flex justify-content-between mb-1">
        <div class="text-caixaAzul font-weight-bold">{{ $dados['total_visitado'] ?? 0 }} de {{  $dados['total_unidades'] ?? 0 }} unidades</div>
        <div class="text-caixaAzul font-weight-bold">{{ $dados['percentual_visitado'] ?? 0.00 }}%</div>
    </div>
    <div class="col-12">
        <div class="progress md-progress" style="height: 30px">
            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $dados['percentual_visitado'] ?? 0.00 }}%; height: 30px" aria-valuenow="{{ $dados['percentual_visitado'] ?? 0.00 }}" aria-valuemin="0" aria-valuemax="100">{{ $dados['percentual_visitado'] ?? 0.00}}%</div>
        </div>
    </div>
    @if(sizeof($dados_subordinados) >0)
    <div>
        @foreach($dados_subordinados as $subordinado)
            <div class="col-12 d-flex justify-content-between mb-1">
                <div class="text-black-50 font-weight-bold">{{ $subordinado->responsavel_nome ?? $subordinado->responsavel ?? $subordinado->equipe_nome }}</div>
                <div class="text-caixaAzul ">{{ $subordinado->total_visitado ?? 0 }} de {{  $subordinado->total_unidades ?? 0 }} unidades</div>
                <div class="text-caixaAzul ">{{ $subordinado->percentual_visitado ?? 0.00 }}%</div>
            </div>
            <div class="col-12">
                <div class="progress md-progress" style="height: 10px">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $subordinado->percentual_visitado ?? 0.00 }}%; height: 10px" aria-valuenow="{{ $subordinado->percentual_visitado ?? 0.00 }}" aria-valuemin="0" aria-valuemax="100">{{ $subordinado->percentual_visitado ?? 0.00}}%</div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@push('scripts')

@endpush
