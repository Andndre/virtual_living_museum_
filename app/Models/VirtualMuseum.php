<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property bool|mixed $is_unlocked
 * @property mixed|string $type
 */
class VirtualMuseum extends Model
{
    protected $table = 'virtual_museum';

    protected $primaryKey = 'museum_id';

    protected $fillable = [
        'situs_id',
        'nama',
        'path_obj',
        'path_audio',
    ];

    protected $casts = [
        'situs_id' => 'integer',
    ];

    // Relationships
    public function situsPeninggalan(): BelongsTo
    {
        return $this->belongsTo(SitusPeninggalan::class, 'situs_id', 'situs_id');
    }

    public function virtualMuseumObjects(): HasMany
    {
        return $this->hasMany(VirtualMuseumObject::class, 'museum_id', 'museum_id');
    }

    public function arMarkers(): HasMany
    {
        return $this->hasMany(ArMarker::class, 'museum_id', 'museum_id');
    }

    public function annotations(): HasMany
    {
        return $this->hasMany(ModelAnnotation::class, 'museum_id', 'museum_id');
    }
}
