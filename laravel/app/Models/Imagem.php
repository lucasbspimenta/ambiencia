<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\File;

class Imagem extends Model
{
    protected $fillable = [
        'name'
        , 'imagem'
        , 'width'
        , 'height'
        , 'size'
    ];

    public function imageable()
    {
        return $this->morphTo(__FUNCTION__, 'imageable_type', 'imageable_id');
    }

    public function getImagemAttribute()
    {
        if(!empty($this->attributes['imagem']))
            return route('imagem.show',[ 'imagem' => $this->id]);

        return null;
    }

    public function getImagemBinaryAttribute()
    {
        return $this->attributes['imagem'];
    }
}
