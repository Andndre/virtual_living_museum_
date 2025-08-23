<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanGambar extends Model
{
    protected $table = 'laporan_gambar';

    protected $primaryKey = 'gambar_id';

    public $timestamps = false;

    protected $fillable = [
        'laporan_id',
        'path_gambar',
    ];

    protected $casts = [
        'laporan_id' => 'integer',
    ];

    // Relationships
    public function laporanPeninggalan(): BelongsTo
    {
        return $this->belongsTo(LaporanPeninggalan::class, 'laporan_id', 'laporan_id');
    }
}
