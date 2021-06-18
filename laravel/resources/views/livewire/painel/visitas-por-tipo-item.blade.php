@foreach($visitas as $key => $visita)
        @if(is_countable($visita) && $visita->count() > 0)
        <li class="p-0 treeview-animated-items list-group-item">
            <a  class="px-2 py-2 closed d-flex rounded-0" >
                 <i class="pt-1 mr-2 fas fa-angle-right text-black-50 d-inline-block h-100"></i> 
                 <div class="w-100">
                    <div class="mb-1 col-12 d-flex justify-content-between">
                        <div class="text-black-50 font-weight-bold">{{ $key }}</div>
                    </div>
                    <div class="progress w-100" style="height: 15px">
                        @foreach($tipos as $tipo)
                            @if(isset($contador_visitas_nivel[$nivel.'|'.$key][$tipo->id]))
                                <div class="progress-bar" role="progressbar" style="width: {{ ($contador_visitas_nivel[$nivel.'|'.$key][$tipo->id] * 100)/$contador_visitas_nivel[$nivel.'|'.$key]->sum() ?? 0.00 }}%; height: 15px; background-color: {{$tipo->cor}}" aria-valuenow="{{ ($contador_visitas_nivel[$nivel.'|'.$key][$tipo->id] * 100)/$contador_visitas_nivel[$nivel.'|'.$key]->sum() ?? 0.00 }}" aria-valuemin="0" aria-valuemax="100">{{ number_format(($contador_visitas_nivel[$nivel.'|'.$key][$tipo->id] * 100)/$contador_visitas_nivel[$nivel.'|'.$key]->sum() ?? 0.00, 2, ',', '.') }}%</div>
                            @endif
                        @endforeach
                    </div>
                 </div>
            </a>
            <ul class="nested">
                @include('livewire/painel/visitas-por-tipo-item', ['visitas' => $visita, 'tipos' => $tipos, 'contador_visitas_nivel' => $contador_visitas_nivel, 'nivel' => $nivel + 1])
            </ul>
        </li>
        @else
            @php
                
                $visitas = $visitas->groupBy('responsavel_nome');
                $total_por_responsavel = [];
                $visitas->each(function ($item4, $key4) use(&$total_por_responsavel) {
                    //dump($item4, $key4, $total_por_responsavel);
                    $total_por_responsavel[$key4] = $item4->groupBy('tipo_id')->map(function ($row) {
                        //dd($row);
                        return $row->sum('total_tipo');
                    });
                });
                //dd($visitas, $total_por_responsavel);
            @endphp
            <li class="p-0 treeview-animated-items list-group-item">
                <div class="px-2 py-2 treeview-animated-element d-flex rounded-0">
                    <div class="w-100">
                        <div class="mb-1 col-12 d-flex justify-content-between">
                            <div class="text-black-50 font-weight-bold">{{ $visita->responsavel_nome ?? $visita->responsavel ?? $visita->equipe_nome }}</div>
                        </div>
                        <div class="col-12">
                            <div class="progress" style="height: 15px">
                                @foreach($tipos as $tipo)
                                    @if(isset($total_por_responsavel[$tipo->id]))
                                        <div class="progress-bar" role="progressbar" style="width: {{ ($total_por_responsavel[$tipo->id] * 100)/$total_por_responsavel->sum() ?? 0.00 }}%; height: 15px; background-color: {{$tipo->cor}}" aria-valuenow="{{ ($total_por_responsavel[$tipo->id] * 100)/$total_por_responsavel->sum() ?? 0.00 }}" aria-valuemin="0" aria-valuemax="100">{{ number_format(($total_por_responsavel[$tipo->id] * 100)/$total_por_responsavel->sum() ?? 0.00, 2, ',', '.') }}%</div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @endif
@endforeach
                    