<div>
    <div class="list-group list-group-flush">
        @forelse($checklist->demandas as $demanda)
            @php $demanda->load('respostas') @endphp
        <div class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
                <div class="w-100">
                    <small class="d-block mb-1 text-caixaAzul">{{$demanda->sistema->nome}}</small>
                    <small class="d-block text-black-50">{{$demanda->sistema_item->nome}}</small>
                </div>
                <div class="flex-shrink-1">
                    @if(trim($demanda->migracao) == 'P')
                        <span class="badge badge-info z-depth-0" style="font-size:85%">A processar</span>
                    @endif
                    @if(trim($demanda->migracao) == 'C')
                            <span class="badge badge-default z-depth-0" style="font-size:85%">Processado</span>
                    @endif
                </div>
            </div>
            <div class="d-flex w-100 justify-content-between">
                <p class="mb-2 mt-2 w-100 text-truncate d-block">{{$demanda->descricao}}</p>
                <button onclick="excluirDemanda({{ $demanda->id }},@this)" type="button" class="btn btn-xs btn-danger m-0 flex-shrink-1"><i class="fa fa-trash" aria-hidden="true"></i></button>
            </div>
            <div class="d-flex w-100 justify-content-start">

                    @foreach($demanda->respostas->where('checklist_id', $checklist->id) as $key_resp => $resposta)

                        <div>
                            <span class="badge badge-primary z-depth-1 p-2 font-weight-normal mr-2">

                                    {{$resposta->item->nome}}
                                    @if($demanda->respostas->where('checklist_id', $checklist->id)->count() > 1)
                                        <a class="align-self-stretch" onclick="excluirVinculacao({{ $demanda->id }},@this,{{$resposta->id}})"><i class="fa fa-times text-white font-weight-bold ml-1" aria-hidden="true"></i></a>
                                    @endif
                            </span>
                        </div>
                    @endforeach

            </div>
        </div>
        @empty
            Nenhuma demanda vinculada
        @endforelse
    </div>
</div>
