<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tugas extends Model
{
    protected $table = 'tugas';

    protected $primaryKey = 'tugas_id';

    protected $fillable = [
        'materi_id',
        'judul',
        'deskripsi',
        'gambar',
    ];

    /**
     * Get the URL for the tugas image
     */
    public function getGambarUrlAttribute(): ?string
    {
        if (! $this->gambar) {
            return null;
        }

        return asset('storage/'.$this->gambar);
    }

    /**
     * Get the materi that owns the tugas
     */
    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'materi_id');
    }
}
