@extends('layouts.app')
@section('title', 'Itens do Checklist - Administração')
@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col col-md-auto d-flex align-items-center">
                <h4 class="text-caixaAzul text-futurabold">
                    <span class="mr-1" style="clip-path: polygon(100% 0, 0 100%, 100% 100%); background-color: #fd7e14; width: 18px; height: 18px; display: inline-block;"></span>
                    Itens do Checklist
                </h4>
            </div>
            <div class="col d-flex justify-content-end">
                <button id="botao_adicionar_topo" onClick="abrirModalChecklistItem();" class="btn btn-sm btn-primary" >
                    <i class="fas fa-plus"></i>
                    Novo Item do Checklist
                </button>

            </div>
        </div>
        <hr class="mt-2 mb-3"/>
        <div class="col-12">
            <div class="card">
                <div class="card-body ">
                    <div class="table-responsive">
                        <table id="tabela_checklist_itens" class="table table-hover table-sm ">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Foto Obrigatória</th>
                                        <th>Subitens</th>
                                        <th>Situação</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                            @foreach($itens->where('is_macroitem',true) as $item)
                                <thead style="border-left: 10px solid {{ $item->cor }}; background-color: #f3f7f9">
                                    <tr>
                                        <th style="cursor: pointer" data-toggle="collapse" data-target="#subitens_{{$item->id}}" aria-expanded="false" aria-controls="collapseExample">
                                                <b>{{ $item->nome }}</b>
                                        </th>
                                        <th>{{ $item->descricao }}</th>
                                        <th>
                                            @if($item->foto == 'S')
                                                <span class="badge badge-success z-depth-0">Sim</span>
                                            @else
                                                <span class="badge badge-light z-depth-0">Não</span>
                                            @endif
                                        </th>
                                        <th>
                                            <div class="d-flex justify-content-around">
                                                <span class="d-inline-block">{{ $item->subitens->count() }}</span>
                                                <button onclick="adicionarChecklistSubItem({{ $item->id }})" type="button" class="btn btn-xs btn-primary m-0"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;Subitem</button>
                                            </div>
                                        </th>
                                        <th>
                                            @if($item->situacao)
                                                <span class="badge badge-success z-depth-0">Ativo</span>
                                            @else
                                                <span class="badge badge-light z-depth-0">Inativo</span>
                                            @endif
                                        </th>
                                        <th>
                                            <div class="d-flex justify-content-around">
                                                <button onclick="editarChecklistItem({{ $item->id }})" type="button" class="btn btn-xs btn-primary m-0"><i class="fa fa-edit" aria-hidden="true"></i></button>
                                                <button onclick="excluirChecklistItem({{ $item->id }})" type="button" class="btn btn-xs btn-danger m-0"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="collapse show" id="subitens_{{$item->id}}" style="border-left: 10px solid {{ $item->cor }};border-bottom: 2px solid #dee2e6;">
                                    @foreach($item->subitens as $subitem)
                                        <tr>
                                            <td style="padding-left: 20px;">{{ Str::title($subitem->nome) }}</td>
                                            <td>{{ $subitem->descricao }}</td>
                                            <td>
                                                @if($subitem->foto == 'S')
                                                    <span class="badge badge-success z-depth-0">Sim</span>
                                                @else
                                                    <span class="badge badge-light z-depth-0">Não</span>
                                                @endif
                                            </td>
                                            <td>&nbsp;</td>
                                            <td>
                                                @if($subitem->situacao)
                                                    <span class="badge badge-success z-depth-0">Ativo</span>
                                                @else
                                                    <span class="badge badge-light z-depth-0">Inativo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-around">
                                                    <button onclick="editarChecklistItem({{ $subitem->id }})" type="button" class="btn btn-xs btn-primary m-0"><i class="fa fa-edit" aria-hidden="true"></i></button>
                                                    <button onclick="excluirChecklistItem({{ $subitem->id }})" type="button" class="btn btn-xs btn-danger m-0"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_checklistitem" tabindex="-1" role="dialog" aria-labelledby="modal_checklistitem" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <livewire:checklist-item.cadastro :macroitens="$itens->where('is_macroitem',true)" />
            </div>
        </div>
    </div>
@endsection
@push('styles')
@endpush
@push('scripts')
    <script>

        document.addEventListener('DOMContentLoaded', function() {

            var options = {
                backdrop: 'static',
                keyboard: true,
                show: false,
                focus: true
            };

            $('#modal_checklistitem').modal(options);

            $('#modal_checklistitem').on('hide.bs.modal', (e) => {$('#modal_checklistitem form').trigger("reset"); });
            $('#modal_checklistitem').on('hidden.bs.modal', (e) => Livewire.emit('limpar'));

        });

        window.addEventListener('triggerSucesso', (event) => {
            toastr.success('Item: '+ event.detail +' gravado com sucesso!');
            $('#modal_checklistitem').modal('hide');
        });

        window.addEventListener('triggerSucessoExclusao', (event) => {
            toastr.success('Item do Checklist excluído com sucesso!');
            $('#modal_checklistitem').modal('hide');
        });

        window.addEventListener('triggerError', (event) => {
            toastr.error('Erro ao gravar item: '+ event.detail);
        });

        function excluirChecklistItem(checklistitemID) {

            Swal.fire({
                title: 'Você tem certeza?',
                text: "O item será excluído junto com registros vinculados",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, tenho certeza!',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    Livewire.emit('excluirChecklistItem', checklistitemID);
                } else {
                    console.log("Canceled");
                }
            });
        }

        function abrirModalChecklistItem() {

            $('#modal_checklistitem').off('show.bs.modal');
            $('#modal_checklistitem').off('shown.bs.modal');
            //$('#modal_checklistitem').on('shown.bs.modal', (e) => ativaJavascriptsModal());
            $('#modal_checklistitem').modal('show');
        }

        function editarChecklistItem(checklistitemID){
            $('#modal_checklistitem').off('show.bs.modal');
            $('#modal_checklistitem').off('shown.bs.modal');
            $('#modal_checklistitem').on('show.bs.modal', (e) => Livewire.emit('carregaChecklistItem',checklistitemID));
            //$('#modal_checklistitem').on('shown.bs.modal', (e) => ativaJavascriptsModal());
            $('#modal_checklistitem').modal('show');
        }

        function adicionarChecklistSubItem(checklistitemID) {
            $('#modal_checklistitem').off('show.bs.modal');
            $('#modal_checklistitem').off('shown.bs.modal');
            $('#modal_checklistitem').on('show.bs.modal', (e) => Livewire.emit('defineItemPai',checklistitemID));
            //$('#modal_checklistitem').on('shown.bs.modal', (e) => ativaJavascriptsModal());
            $('#modal_checklistitem').modal('show');
        }

    </script>
@endpush

