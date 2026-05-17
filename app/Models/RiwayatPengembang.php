<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatPengembang extends Model
{
    use HasFactory;
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
