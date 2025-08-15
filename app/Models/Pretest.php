<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pretest extends Model
{
    protected $table = 'pretest';
    protected $primaryKey = 'pretest_id';
    public $timestamps = true;
    
    protected $fillable = [
        'materi_id',
        'pertanyaan',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'jawaban_benar'
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
