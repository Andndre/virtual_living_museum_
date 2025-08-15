<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ebook extends Model
{
    protected $table = 'ebook';
    protected $primaryKey = 'ebook_id';
    public $timestamps = false;
    
    protected $fillable = [
        'materi_id',
        'judul',
        'path_file'
    ];

    protected $casts = [
        'materi_id' => 'integer',
    ];

    // Relationships
    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'materi_id');
    }
}
