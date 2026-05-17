<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoPeninggalan extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'video_peninggalans';

    protected $fillable = [
        'judul',
        'deskripsi',
        'link',
        'thumbnail',
    ];
}
