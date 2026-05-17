<?php

namespace App\Models;

use Database\Factories\EbookFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ebook extends Model
{
    /** @use HasFactory<EbookFactory> */
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
