<?php

namespace Database\Seeders;

use App\Models\Guia;
use App\Models\Imagem;
use App\Services\ImagemService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use bfinlay\SpreadsheetSeeder\SpreadsheetSeeder;
use Intervention\Image\Facades\Image;

class GuiasSeeder extends SpreadsheetSeeder
{
    public function random_pic($dir)
    {
        $files = glob($dir . '/*.*');
        $file = array_rand($files);
        return $files[$file];
    }

    public function run()
    {
        $this->file = '/database/seeders/csv/guias.xlsx';
        $this->tablename = 'guias';
        $this->truncate = false;
        $this->timestamps = false;

        DB::connection()->unprepared('SET IDENTITY_INSERT [dbo].[guias] ON;');
        parent::run();
        DB::connection()->unprepared('SET IDENTITY_INSERT [dbo].[guias] OFF;');

        $guias = Guia::all();

        foreach($guias as $guia)
        {
            $img = $this->random_pic(realpath('./database/seeders/imagens/guias/'));
            $imagem = new Imagem();
            $imagem->name = basename($img);

            $imagem->imagem = (string) Image::make($img)
                ->resize(ImagemService::WIDTH, ImagemService::HEIGHT, function($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('data-url');

            $imagem->width = Image::make($img)->width();;
            $imagem->height = Image::make($img)->height();;
            $imagem->size = filesize($img);

            $guia->imagens()->save($imagem);
        }
    }
}
