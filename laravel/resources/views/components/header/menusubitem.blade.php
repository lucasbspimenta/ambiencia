<a class="dropdown-item {{ request()->routeIs($nomerota) ? 'active' : '' }}" href="{{ route($nomerota) }}">
    <i class="fas fa-{{$icone}} mr-2"></i>{{$nome}}</br>
    <small class="text-xs">{{$descricao}}</small>
</a>