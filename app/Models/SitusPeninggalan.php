<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SitusPeninggalan extends Model
{
    protected $table = 'situs_peninggalan';
    protected $primaryKey = 'situs_id';
    public $timestamps = false;
    
    protected $fillable = [
        'nama',
        'alamat',
        'deskripsi',
        'lat',
        'lng',
        'masa',
        'materi_id',
        'user_id'
    ];

    protected $casts = [
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'materi_id' => 'integer',
        'user_id' => 'integer',
    ];

    // Relationships
    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'materi_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function virtualMuseumObject(): HasMany
    {
        return $this->hasMany(VirtualMuseumObject::class, 'situs_id', 'situs_id');
    }

    public function aksesSitusUser(): HasMany
    {
        return $this->hasMany(AksesSitusUser::class, 'situs_id', 'situs_id');
    }
}
