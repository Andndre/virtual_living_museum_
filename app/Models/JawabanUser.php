<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanUser extends Model
{
    protected $table = 'jawaban_user';

    protected $primaryKey = 'jawaban_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'materi_id',
        'jenis',
        'soal_id',
        'jawaban_user',
        'benar',
        'poin',
        'answered_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'materi_id' => 'integer',
        'soal_id' => 'integer',
        'benar' => 'boolean',
        'poin' => 'integer',
        'answered_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'materi_id');
    }
}
