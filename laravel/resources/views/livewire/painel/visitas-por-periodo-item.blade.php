@foreach($visitas as $key => $visita)
        @if(is_countable($visita) && $visita->count() > 0)
        <li class="p-0 treeview-animated-items list-group-item">
            <a  class="px-2 py-2 closed d-flex rounded-0" >
                 <i class="pt-1 mr-2 fas fa-angle-right text-black-50 d-inline-block"></i> 
                 <div class="mb-1 col-12 d-flex justify-content-between">
                    <div class="text-black-50 font-weight-bold">{{ $key }}</div>
                    <div class="d-flex justify-content-between">
                        <div class="mr-3 text-caixaAzul">{{ $contador_visitas_nivel[$nivel.'|'.$key] ?? 0.00 }} de {{ $contador_unidades_nivel[$nivel.'|'.$key] ?? 0.00 }}  unidades</div>
                        <div class="text-caixaAzul ">
                            {{ number_format(((($contador_visitas_nivel[$nivel.'|'.$key] ?? 0.00) * 100)/($contador_unidades_nivel[$nivel.'|'.$key] ?? 0.00)), 2, ',', '.') }} %
                        </div>
                    </div>
                </div>
            </a>
            <ul class="nested">
                @include('livewire/painel/visitas-por-periodo-item', ['visitas' => $visita, 'contador_unidades_nivel' => $contador_unidades_nivel, 'contador_visitas_nivel' => $contador_visitas_nivel, 'nivel' => $nivel + 1])
            </ul>
        </li>
        @else
        <li class="p-0 treeview-animated-items list-group-item">
            <div class="px-2 py-2 treeview-animated-element d-flex rounded-0">
                <div class="w-100">
                    <div class="mb-1 col-12 d-flex justify-content-between">
                        <div class="text-black-50 font-weight-bold">{{ $visita->responsavel_nome ?? $visita->responsavel ?? $visita->equipe_nome }}</div>
                        <div class="d-flex justify-content-between">
                            <div class="mr-3 text-caixaAzul">{{ $visita->total_visitado ?? 0 }} de {{  $visita->total_unidades ?? 0 }} unidades</div>
                            <div class="text-caixaAzul ">{{ number_format($visita->percentual_visitado ?? 0.00, 2, ',', '.') }} %</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-1 progress md-progress" style="height: 10px">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $visita->percentual_visitado ?? 0.00 }}%; height: 10px" aria-valuenow="{{ $visita->percentual_visitado ?? 0.00 }}" aria-valuemin="0" aria-valuemax="100">{{ number_format($visita->percentual_visitado ?? 0.00, 2, ',', '.')}}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        @endif
@endforeach
                    