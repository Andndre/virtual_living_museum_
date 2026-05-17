<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPengembang extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'riwayat_pengembangs';

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
