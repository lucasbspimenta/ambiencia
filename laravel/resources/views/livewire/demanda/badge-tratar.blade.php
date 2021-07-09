<span class="badge @if($pendentes > 0) badge-danger @else badge-success @endif">{{ $pendentes }}</span>
<!--wire:poll.10000ms="atualizarPendentes"-->