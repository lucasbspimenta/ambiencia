@if($progresso >= 100)
    <span class="text-success d-inline-block">
        <i class="fas fa-check-double"></i>
    </span>
@else
    <div class="progress mt-1" style="width: 30%; height: 10px;">
        <div class="progress-bar @if($progresso >= 100) bg-success @endif" role="progressbar" style="width: {{ $progresso ?? 0.00 }}%; " aria-valuenow="{{ $progresso ?? 0.00 }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
@endif
