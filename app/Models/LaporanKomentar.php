<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanKomentar extends Model
{
    protected $table = 'laporan_komentar';
    protected $primaryKey = 'komentar_id';
    public $timestamps = false;
    
    protected $fillable = [
        'laporan_id',
        'user_id',
        'komentar',
        'created_at'
    ];

    protected $casts = [
        'laporan_id' => 'integer',
        'user_id' => 'integer',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function laporanPeninggalan(): BelongsTo
    {
        return $this->belongsTo(LaporanPeninggalan::class, 'laporan_id', 'laporan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
