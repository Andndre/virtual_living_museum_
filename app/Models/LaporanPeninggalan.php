<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaporanPeninggalan extends Model
{
    protected $table = 'laporan_peninggalan';

    protected $primaryKey = 'laporan_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'nama_peninggalan',
        'alamat',
        'lat',
        'lng',
        'deskripsi',
        'created_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function laporanGambar(): HasMany
    {
        return $this->hasMany(LaporanGambar::class, 'laporan_id', 'laporan_id');
    }

    public function laporanKomentar(): HasMany
    {
        return $this->hasMany(LaporanKomentar::class, 'laporan_id', 'laporan_id');
    }

    public function laporanSuka(): HasMany
    {
        return $this->hasMany(LaporanSuka::class, 'laporan_id', 'laporan_id');
    }
}
