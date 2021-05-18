@auth
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#"
           id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" aria-expanded="false">
            <img src="http://tedx.caixa/lib/asp/foto.asp?matricula={{ Auth::user()->matricula }}" alt="{{ Str::title(Auth::user()->name) }}" onerror="this.onerror=null; this.src='{{ asset('images/semfoto.png') }}'"
                 class="rounded-circle"
                 height="20"
                 loading="lazy"
                 alt="avatar image">
        </a>
        <div class="dropdown-menu dropdown-menu-lg-right dropdown-secondary" aria-labelledby="navbarDropdownMenuLink">
            <div class="text-center">
                <div class="mt-2">
                    <label class="font-weight-bold">{{ Str::title(Auth::user()->name) }}</label>
                    <small>
                        <p>{{ Str::title(Auth::user()->perfil->nome) }}</p>
                    </small>
                    <table class="table table-sm text-center mb-0">
                        <tbody>
                        <tr>
                            <td><small class="font-weight-bold">Equipe</small></td>
                            <td class=""><small>{{ Str::title(Auth::user()->equipe->nome) }}</small></td>
                        </tr>
                        <tr>
                            <td><small class="font-weight-bold">Gestor</small></td>
                            <td class="">
                                <small>
                                    <a style="padding:0px; font-size:90%; font-weight: 400;padding-top:4px;" class="text-nowrap" target="_blank" href="https://teams.microsoft.com/l/chat/0/0?users={{ trim(Auth::user()->equipe->gestor) }}@corp.caixa.gov.br">
                                        {{ Str::title(Auth::user()->equipe->gestor) }}
                                        <img class="inline-block" src="{{ asset('images/teams_16.png') }}" />
                                    </a>
                                </small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </li>
    <!--
<ul class="navbar-nav ml-auto nav-flex-icons">
    <li class="nav-item avatar dropdown">
      <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <img src="http://tedx.caixa/lib/asp/foto.asp?matricula={{ Auth::user()->matricula }}" alt="{{ Str::title(Auth::user()->name) }}" onerror="this.onerror=null; this.src='{{ asset('images/semfoto.png') }}'" class="rounded-circle z-depth-0"
          alt="avatar image">
      </a>
      <div class="dropdown-menu dropdown-menu-lg-right dropdown-secondary"
        aria-labelledby="navbarDropdownMenuLink-55">

      </div>
    </li>
</ul>
->
@endauth
