<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPengembang extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'judul',
        'tahun',
        'tahun_selesai',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'date',
            'tahun_selesai' => 'date',
        ];
    }
}
