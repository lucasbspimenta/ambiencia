<div class="card w-100">
    <div class="pb-1 card-body">
        <div class="d-flex justify-content-between">
            <h5 class="font-weight-bold text-caixaAzul text-futurabold">Checklist pendentes ({{ $total_registros }})
            </h5>
            <a href="{{ route('checklist.index') }}">ver checklists</a>
        </div>
    </div>
    <div class="treeview-animated w-100 scrollbar scrollbar-indigo thin overflow-auto" style="max-height: 275px; height: 275px;">
        <ul class="pl-0 mb-3 treeview-animated-list">
            @include('livewire/painel/checklists-pendentes-item', ['checklists' => $checklists, 'contador_nivel' =>
            $contador_nivel, 'nivel' => 1])
        </ul>
    </div>
</div>
