<div>
    <div>
        <div wire:loading style="position: absolute; width: 100%; height: 100%; background-color: #f5f5f5; z-index: 1; opacity:0.5; left: 0; top: 0;">
            <div class="d-flex justify-content-center align-middle align-items-stretch">
                <img class="mt-5" src="{{ asset('images/ajax-loader-mini.gif') }}" />
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- News jumbotron -->
                <div class="jumbotron text-center hoverable p-4 mb-0">

                    <!-- Grid row -->
                    <div class="row">

                        @if($guia->imagens && $guia->imagens->count() > 0)
                        <div class="col-md-4 offset-md-1 mx-3 my-3">

                            <!-- Featured image
                            <div class="view overlay">
                                <img src="https://mdbootstrap.com/img/Photos/Others/laptop-sm.jpg" class="img-fluid" alt="Sample image for first version of blog listing">
                                <a>
                                    <div class="mask rgba-white-slight"></div>
                                </a>
                            </div>
                            -->
                            <!--Carousel Wrapper-->
                            <div id="carousel-thumb" class="carousel slide carousel-fade carousel-thumbnails" data-ride="carousel">
                                <!--Slides-->
                                <div class="carousel-inner" role="listbox">
                                    @forelse($guia->imagens as $key => $foto)
                                    <div class="carousel-item @if($key == 0) active @endif">
                                        <img class="d-block w-100" src="{{ $foto->imagem }}">
                                    </div>
                                    @empty
                                    <div class="carousel-item">
                                        <img class="d-block w-100" src="{{  asset('images/image_placeholder.png')  }}"
                                             alt="Third slide">
                                    </div>
                                    @endforelse
                                </div>
                                <!--/.Slides-->
                                <!--Controls-->
                                <a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Anterior</span>
                                </a>
                                <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Próximo</span>
                                </a>
                                <!--/.Controls-->
                                <ol class="carousel-indicators">
                                    @forelse($guia->imagens as $key => $foto)
                                        <li data-target="#carousel-thumb" data-slide-to="{{$key}}" class="@if($key == 0) active @endif">
                                            <img src="{{ $foto->imagem  }}" width="100">
                                        </li>
                                    @empty
                                        <li data-target="#carousel-thumb" data-slide-to="0" class="active">
                                            <img class="d-block w-100" src="{{  asset('images/image_placeholder.png')  }}">
                                        </li>
                                    @endforelse
                                </ol>
                            </div>
                            <!--/.Carousel Wrapper-->

                        </div>
                        @endif
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col text-md-left ml-3 mt-3">

                            <!-- Excerpt -->
                            <div class="d-flex justify-content-between">
                                <h6 class="h6 pb-1 text-futura text-black-50">
                                    @if($guia->checklistItem)
                                    <span class="d-inline-block pr-1" style="background-color: {{ $guia->checklistItem->macroitem->cor ?? $guia->checklistItem->cor }}; width: 5px; height: 9px;"></span>
                                    {{ $guia->checklistItem->macroitem->nome ?? $guia->checklistItem->nome  }}
                                    @endif
                                </h6>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <h4 class="h4 mb-4 text-caixaAzul text-futurabold">{{ $guia->checklistItem->nome ?? ''  }}</h4>

                            <p class="font-weight-normal mb-3">
                                {{ $guia->descricao ?? '' }}
                            </p>


                            @if($guia->itens && $guia->itens->count() > 0 )
                            <div class="w-100 accordion_one mb-3">
                                <div class="panel-group" id="accordion_oneRight">
                                    @foreach($guia->itens as $item)
                                    <div class="panel panel-default">
                                        @if(!empty($item->resposta))
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion_oneRight" href="#collapseFiveRightone" aria-expanded="false">
                                                        {{  $item->pergunta ?? '' }}
                                                    </a>
                                                </h4>
                                            </div>
                                        @else
                                            <div class="panel-heading afirmativa">
                                                <h4 class="panel-title">

                                                       <p class="mb-0">{{  $item->pergunta ?? '' }}</p>

                                                </h4>
                                            </div>
                                        @endif

                                        @if(!empty($item->resposta))
                                        <div id="collapseFiveRightone" class="panel-collapse collapse" aria-expanded="false" role="tablist">
                                            <div class="panel-body">
                                                <div class="text-accordion">
                                                    <p>{{$item->resposta}}</p>
                                                </div>
                                            </div> <!-- end of panel-body -->
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <!--end of /.panel-group-->
                            </div>
                            @endif
                            <p class="font-weight-normal text-black-50 mb-3">Ult. Atualização: {{ Date('d/m/Y H:i', strtotime($guia->updated_at))  }}</p>
                            <button wire:loading.attr="disabled" type="button" class="btn btn-secondary btn-sm ml-0" data-dismiss="modal">Fechar</button>

                        </div>
                        <!-- Grid column -->

                    </div>
                    <!-- Grid row -->

                </div>
                <!-- News jumbotron -->
            </div>
        </div>
    </div>
</div>
