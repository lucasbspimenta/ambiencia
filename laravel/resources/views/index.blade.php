@extends('layouts.app')
@section('title', 'Checklist')
@section('content')
    <div class="container-fluid mb-5">

        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1" style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Painel
                </h4>
            </div>
        </div>
        <hr class="mt-2 mb-3"/>
        <div class="row mb-4">
            <div class="col-4 ">
                <livewire:painel.proximos-agendamentos />
            </div>
            <div class="col-4">
                <livewire:painel.checklists-pendentes />
            </div>
            <div class="col-4">
                <livewire:painel.visitas-por-periodo />
                <livewire:painel.visitas-por-tipo />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-6">
                <livewire:painel.inconformidade-por-item />
            </div>
            <div class="col-6">
                <livewire:painel.inconformidade-por-macroitem />
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
