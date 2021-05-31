<div class="card w-100 ">
    <div class="card-body pb-1">
        <div class="d-flex justify-content-between">
            <h5 class="font-weight-bold text-caixaAzul text-futurabold">Próximos Agendamentos ({{$agendamentos->count()}})</h5>
            <a href="{{route('agenda')}}">ver agenda</a>
        </div>
    </div>
    <ul class="list-group list-group-flush scrollbar scrollbar-indigo thin overflow-auto" style="max-height: 275px; height: 275px;">
        @foreach($agendamentos as $key => $agendamento)
            <li class="list-group-item d-flex justify-content-between"  @if($key%2) style="background-color:#f9f9f9" @endif>
                <div class="w-100 justify-content-start">
                    <span class="text-caixaAzul text-nowrap d-block text-left">
                        {{ $agendamento->unidade->nome_completo }}
                    </span>
                    <span class="text-black-50 text-nowrap  d-block text-left">{!! $agendamento->tipo->nome_formatado !!}</span>
                </div>
                <div class="d-flex flex-column flex-shrink-1">
                    <span class="text-nowrap text-right ">
                        {{ $agendamento->inicio }}
                        ({{ Illuminate\Support\Carbon::parse($agendamento->inicio_americano)->locale('pt-BR')->shortDayName }})
                    </span>
                    <span class="text-black-50 text-nowrap text-right">
                        {{ Illuminate\Support\Carbon::parse($agendamento->inicio_americano)->locale('pt-BR')->diffForHumans(Illuminate\Support\Carbon::today(),[
                            'syntax' => Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                            'options' => Illuminate\Support\Carbon::JUST_NOW | Illuminate\Support\Carbon::ONE_DAY_WORDS | Illuminate\Support\Carbon::TWO_DAY_WORDS,
                        ]) }}
                    </span>
                </div>

            </li>
        @endforeach
    </ul>
</div>
