<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VirtualMuseum extends Model
{
    protected $table = 'virtual_museum';
    protected $primaryKey = 'museum_id';
    
    protected $fillable = [
        'situs_id',
        'nama',
        'path_obj'
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
}
