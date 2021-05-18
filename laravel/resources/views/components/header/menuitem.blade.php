@if(!empty($slot->__toString()))
    <li class="nav-item dropdown {{ (request()->segment(1) == Str::slug($nome)) ? 'active' : '' }}" id="{{ $nome }}">
        <a class="nav-link dropdown-toggle " href="#" id="{{ $nome }}_navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <!--<i class="fas fa-{{ $icone }} mr-2"></i>-->
            {{ $nome }}
        </a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="{{ $nome }}_navbarDropdown">
            {{$slot}}
        </div>
    </li>
@else
    <li class="nav-item {{ request()->routeIs($nomerota) ? 'active' : '' }}">
        <a class="nav-link "href="{{ route($nomerota) }}">
            <!--<i class="fas fa-{{ $icone }} mr-2"></i>-->
            {{ $nome }}
            @if($badge)
                <span class="badge badge-info">{{$badge}}</span>
            @endif
        </a>
    </li>
@endif
