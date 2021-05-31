@extends('layouts.app')
@section('title', 'Integração - Administração')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1" style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Integração
                </h4>
            </div>
            <div class="col d-flex justify-content-end">
                <form action="{{ route('adm.integracao.create') }}">
                    <button id="botao_adicionar_topo" class="btn btn-sm btn-primary" >
                        <i class="fas fa-plus"></i>
                        Executar integração
                    </button>
                </form>
            </div>
        </div>
        <hr class="mt-2 mb-3"/>
        <div class="col-12">
            <div class="jumbotron">
                <h2 class="h1-responsive">Migração</h2>
                <p>Migradas: {{$demandas_migradas}}</p>
                <p>Pendentes: {{ $demandas_pendentes }}</p>
                @if (session('status'))

                    <div class="alert alert-success">
                        <p>Demandas migradas: {{ session('status')['migradas'] }}</p>
                        <p>Demandas atualizadas: {{ session('status')['atualizadas'] }}</p>
                    </div>
                    @if(session('status')['errors'])
                        <div class="alert alert-danger">
                        @foreach(session('status')['errors'] as $demanda_id => $erro)
                                <p>Demanda <b>{{$demanda_id}}</b> apresentou o erro: {{ $erro }}</p>
                        @endforeach
                        </div>
                    @endif
                @endif
                <p class="lead">{{ $ultima_atualizacao }}</p>
            </div>
        </div>
    </div>
@endsection
@push('styles')
@endpush
@push('scripts')
@endpush
