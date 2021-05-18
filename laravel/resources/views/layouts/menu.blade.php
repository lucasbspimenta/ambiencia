<div class="collapse navbar-collapse" id="basicExampleNav">
    <ul class="navbar-nav mr-auto" >
        <x-header.menuitem nome='Painel' nomerota='index' icone='chalkboard' badge=''/>
        <x-header.menuitem nome='Agenda' nomerota='agenda' icone='calendar-alt' badge=''/>
        <x-header.menuitem nome='Checklist' nomerota='checklist.index' icone='clipboard-check' badge=''/>
        <x-header.menuitem nome='Guia' nomerota='guia.index' icone='book' badge=''/>
        @if (Auth::check() && !Auth::user()->isAdmin)
            <x-header.menuitem nome='Administração' nomerota='index' icone='cogs' badge=''>
                <x-header.menusubitem nome='Tipos de Agendamento' descricao='Inclusão e alteração dos tipos de atendimentos' nomerota="adm.tipodeagendamento"  icone='calendar-day' badge=''/>
                <x-header.menusubitem nome='Itens do Checklist' descricao='Inclusão e alteração dos itens do checklist' nomerota='adm.checklist' icone='list-alt' badge=''/>
                <x-header.menusubitem nome='Guia' descricao='Inclusão e alteração dos itens do guia' nomerota='adm.guia.index' icone='book' badge=''/>
            </x-header.menuitem>
        @endif
    </ul>
</div>
<form class="form-inline d-none d-md-flex input-group w-auto my-auto mr-3">
    <div class="md-form my-0">
        <input class="form-control mr-sm-2" type="text" placeholder="Buscar" aria-label="Buscar">
    </div>
</form>
<ul class="navbar-nav ms-auto d-flex flex-row">

    <x-header.perfil/>
</ul>
