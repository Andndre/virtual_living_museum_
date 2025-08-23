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
        'materi_id',
        'user_id',
        'thumbnail',
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

    public function virtualMuseum(): HasMany
    {
        return $this->hasMany(VirtualMuseum::class, 'situs_id', 'situs_id');
    }

    public function virtualMuseumObject(): HasMany
    {
        return $this->hasMany(VirtualMuseumObject::class, 'situs_id', 'situs_id');
    }

    public function aksesSitusUser(): HasMany
    {
        return $this->hasMany(AksesSitusUser::class, 'situs_id', 'situs_id');
    }
    
    /**
     * Get the URL for the thumbnail image
     *
     * @return string
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        
        return asset('images/placeholder/default-situs.png');
    }
}
