<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materi extends Model
{
    protected $table = 'materi';
    protected $primaryKey = 'materi_id';
    
    protected $fillable = [
        'judul',
        'deskripsi',
        'urutan',
        'gambar_sampul'
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    // Relationships
    public function pretest(): HasMany
    {
        return $this->hasMany(Pretest::class, 'materi_id', 'materi_id');
    }

    public function posttest(): HasMany
    {
        return $this->hasMany(Posttest::class, 'materi_id', 'materi_id');
    }

    public function jawabanUser(): HasMany
    {
        return $this->hasMany(JawabanUser::class, 'materi_id', 'materi_id');
    }

    public function ebook(): HasMany
    {
        return $this->hasMany(Ebook::class, 'materi_id', 'materi_id');
    }

    public function situsPeninggalan(): HasMany
    {
        return $this->hasMany(SitusPeninggalan::class, 'materi_id', 'materi_id');
    }

    public function progressMateri(): HasMany
    {
        return $this->hasMany(ProgressMateri::class, 'materi_id', 'materi_id');
    }
}
