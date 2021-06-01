<div class="card w-100">
    <div class="card-body pb-1">
        <div class="d-flex justify-content-between">
            <h5 class="font-weight-bold text-caixaAzul text-futurabold">Checklist pendentes</h5>
            <a href="{{route('checklist.index')}}">ver checklists</a>
        </div>
    </div>
    <ul class="list-group list-group-flush scrollbar scrollbar-indigo thin overflow-auto" style="max-height: 275px; height: 275px;">
        @foreach($checklists as $key => $checklist)

            <li class="list-group-item" @if($key%2) style="background-color:#f9f9f9" @endif>
                <div class="w-100 d-flex justify-content-between">
                    <a href="{{ route('checklist.edit',['checklist' => $checklist->id]) }}" class="text-caixaAzul text-nowrap d-block text-left">{{$checklist->agendamento->unidade->nome_completo}}</a>
                    <span>{{$checklist->percentual_preenchimento}}%</span>
                </div>
                <div class="w-100" style="height: 11px;">
                    <div class="text-nowrap w-100">
                        <div class="progress md-progress mb-0 mt-2">
                            <div class="progress-bar" role="progressbar" style="width: {{$checklist->percentual_preenchimento}}%" aria-valuenow="{{$checklist->percentual_preenchimento}}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </li>

        @endforeach
    </ul>
</div>
