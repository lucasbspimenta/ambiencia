<?php

namespace App\Http\Controllers;

use App\Models\ChecklistResposta;
use App\Models\Imagem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Intervention\Image\Facades\Image;

class ImagemController extends Controller
{

    public function show($imagem, Request $request)
    {
        if($request->tipo == 'Resposta')
            $img = Image::make(ChecklistResposta::findOrFail($imagem)->foto_binary);
        else
            $img = Image::make(Imagem::findOrFail($imagem)->imagem_binary);

        return $img->response();
    }


}
