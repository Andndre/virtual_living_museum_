<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ebook extends Model
{
    /** @use HasFactory<\Database\Factories\EbookFactory> */
    use HasFactory;
    protected $table = 'ebook';

    protected $primaryKey = 'ebook_id';

    public $timestamps = false;

    protected $fillable = [
        'materi_id',
        'judul',
        'path_file',
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
