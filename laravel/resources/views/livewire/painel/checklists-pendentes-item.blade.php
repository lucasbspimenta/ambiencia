@foreach($checklists as $key => $checklist0)
        @if(is_countable($checklist0) && $checklist0->count() > 0)
        <li class="p-0 treeview-animated-items list-group-item">
            <a  class="px-2 py-2 closed d-flex rounded-0" >
                 <i class="pt-1 mr-2 fas fa-angle-right text-black-50 d-inline-block"></i> 
                <div class="w-100 d-flex justify-content-between">
                    <p class="mb-0 text-black-50 font-weight-bold">{{ $key }}</p>
                    <span class="text-caixaAzul ">{{ $contador_nivel[$nivel.'|'.$key] ?? 0.00 }} pendentes</span>
                </div>
            </a>
            <ul class="nested">
                @include('livewire/painel/checklists-pendentes-item', ['checklists' => $checklist0, 'contador_nivel' => $contador_nivel, 'nivel' => $nivel + 1])
            </ul>
        </li>
        @else
        <li class="p-0 treeview-animated-items list-group-item">
            <div class="px-2 py-2 treeview-animated-element d-flex rounded-0">
                <div class="w-100 d-flex justify-content-between">
                    <a href="{{ route('checklist.edit',['checklist' => $checklist0->id]) }}" class="text-left text-caixaAzul text-nowrap d-block">{{$checklist0->unidade_nome_completo}}</a>
                    <span>{{number_format($checklist0->percentual_respondido, 2, ',', '.')}}%</span>
                </div>
            </div>
        </li>
        @endif
@endforeach
                    