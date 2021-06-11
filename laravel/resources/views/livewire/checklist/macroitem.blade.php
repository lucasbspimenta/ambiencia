<div class="card mb-3">
    <div class="card-header bg-transparent pl-3 d-flex justify-content-between" style="border-left: 5px {{$macroitem->cor}} solid;" data-toggle="collapse" href="#macroitem-{{$macroitem->id}}" aria-expanded="true" aria-controls="macroitem-{{$macroitem->id}}" role="button" >
        <div>
            <a class="w-100 h-100" >
                <span class="text-caixaAzul font-weight-bold" style="font-size:14px; font-weight: bold;">
                    {{$macroitem->nome}}
                    @if($macroitem->load('guia')->guia)
                        <a href="#" onclick="exibirGuia({{$macroitem->guia->id}})"><i class="fas fa-question-circle fa-xs ml-1 text-black-50"></i></a>
                    @endif
                </span>
                @if($macroitem->foto && $macroitem_reposta)
                    @if($macroitem->foto == 'S')
                        <i class="fas fa-lg fa-camera ml-2 text-black-50"></i>
                    @else
                        <i class="fas fa-lg fa-camera ml-2 text-black-50"></i>
                    @endif
                @endif
            </a>
        </div>
       <!-- <livewire:checklist.macroitem-progresso :checklist=$checklist :macroitem="$macroitem" :wire:key="'checklist.macroitem-progresso-'.$macroitem->id"  /> -->
    </div>
    <div class="card-body collapse p-2 show" id="macroitem-{{$macroitem->id}}" data-lw="@this">

        @foreach($itens as $chave => $resposta)
            <div class="col-12 border-bottom" style="background-color: @if($chave % 2) #F7F7F7 @endif">
                <livewire:checklist.item :resposta="$resposta" :macroitem="$macroitem" :wire:key="'item-reposta-'.$resposta->id"  />
            </div>
        @endforeach

    </div>
</div>
