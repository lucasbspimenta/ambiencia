<div class="col col-auto pl-0">
    <figure class="figure">
        <div style="width: 100px;"><small class="text-truncate text-caixaAzul d-block">{{$resposta->item->nome}}</small></div>
        <div style="height: 100px; width: 100px;">
            @if(!is_null($resposta->foto))
                <a class="image-popup-link h-100 w-100" href="{{ $resposta->foto ? $resposta->foto : asset('images/image_placeholder.jpg') }}">
                    <img class="img-thumbnail rounded" style="width: inherit; height: inherit; object-fit: cover;" src="{{ $resposta->foto }}">
                </a>
            @else
                <div class="upload-btn-wrapper h-100 w-100">
                    <button class="btn-upload h-100 w-100">Enviar</button>
                    <input type="file" wire:model="foto" />
                </div>
            @endif
        </div>
        @if(!is_null($resposta->foto))
        <figcaption class="figure-caption text-center"><a class="text-danger" href="#" wire:click="removerFoto">Remover</a></figcaption>
        @endif
    </figure>
</div>
