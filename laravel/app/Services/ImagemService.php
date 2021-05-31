<?php

namespace App\Services;

use App\Models\Guia;
use App\Models\Imagem;
use Exception;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\TemporaryUploadedFile;

class ImagemService
{
    const WIDTH = 1024;
    const HEIGHT = 768;

    public function criar(TemporaryUploadedFile $uploadObject): ?Imagem
    {
        if($uploadObject->getRealPath() && file_exists($uploadObject->getRealPath()))
        {
            $imagem = new Imagem();
            $imagem->name = $uploadObject->getFilename();
            $imagem->imagem = $this->processaImagem($uploadObject->getRealPath());
            $imagem->width = Image::make($uploadObject->getRealPath())->width();;
            $imagem->height = Image::make($uploadObject->getRealPath())->height();;
            $imagem->size = $uploadObject->getSize();

            return $imagem;
        }

        return null;
    }

    public function processaImagem($filepath) {
        return (string) Image::make($filepath)
            ->resize(self::WIDTH, self::HEIGHT, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('data-url');
    }

    public function findById($id)
    {
        return Image::findOrFail($id);
    }

    public function existsById($id)
    {
        return Image::where('id', $id )->exists($id);
    }

    public function excluir($id)
    {
        return Image::findOrFail($id)->delete();
    }
}
