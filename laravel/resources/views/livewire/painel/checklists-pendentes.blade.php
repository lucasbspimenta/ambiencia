<div class="card w-100">
    <div class="card-body pb-1">
        <div class="d-flex justify-content-between">
            <h5 class="font-weight-bold text-caixaAzul text-futurabold">Checklist pendentes</h5>
            <a href="{{route('checklist.index')}}">ver checklists</a>
        </div>
    </div>
    <ul class="list-group list-group-flush scrollbar scrollbar-indigo thin overflow-auto" style="max-height: 275px; height: 275px;">
        @if($funcao_gerencial)
            <li class="list-group-item">
                <div class="w-100 d-flex justify-content-between">
                    <p class="text-caixaAzul font-weight-bold mb-0">Total pendentes</p>
                    <span class="text-caixaAzul font-weight-bold">{{ collect($dados_subordinados)->sum('total_pendentes') }} pendentes</span>
                </div>
            </li>
            @foreach($dados_subordinados as $key => $subordinado)
                <li class="list-group-item" @if(!$key%2) style="background-color:#f9f9f9" @endif>
                    <div class="w-100 d-flex justify-content-between">
                        <p class="text-black-50 font-weight-bold mb-0">{{$subordinado->equipe_nome}}</p>
                        <span class="text-caixaAzul ">{{$subordinado->total_pendentes}} pendentes</span>
                    </div>
                </li>
            @endforeach
        @else
            @forelse($checklists as $key => $checklist)

                <li class="list-group-item" @if($key%2) style="background-color:#f9f9f9" @endif>
                    <div class="w-100 d-flex justify-content-between">
                        <a href="{{ route('checklist.edit',['checklist' => $checklist->checklist_id]) }}" class="text-caixaAzul text-nowrap d-block text-left">{{$checklist->nome_completo}}</a>
                        <span>{{$checklist->percentual_respondido}}%</span>
                    </div>
                    <div class="w-100" style="height: 11px;">
                        <div class="text-nowrap w-100">
                            <div class="progress md-progress mb-0 mt-2">
                                <div class="progress-bar" role="progressbar" style="width: {{$checklist->percentual_respondido}}%" aria-valuenow="{{$checklist->percentual_respondido}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="text-center text-black-50">Nenhum checklist pendente</li>
            @endforelse
        @endif
    </ul>
</div>
