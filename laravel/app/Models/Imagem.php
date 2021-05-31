<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
