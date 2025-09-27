<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoPeninggalan extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'judul',
        'deskripsi',
        'link',
        'thumbnail',
    ];
}
