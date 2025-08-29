<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property bool|mixed $is_unlocked
 * @property mixed|string $type
 */
class VirtualMuseumObject extends Model
{
    public $timestamps = false;

    protected $table = 'virtual_museum_object';

    protected $primaryKey = 'object_id';

    protected $fillable = [
        'situs_id',
        'museum_id',
        'nama',
        'gambar_real',
        'path_obj',
        'path_gambar_marker',
        'path_patt',
        'deskripsi',
    ];

    protected $casts = [
        'situs_id' => 'integer',
        'museum_id' => 'integer',
    ];

    // Relationships
    public function situsPeninggalan(): BelongsTo
    {
        return $this->belongsTo(SitusPeninggalan::class, 'situs_id', 'situs_id');
    }

    public function virtualMuseum(): BelongsTo
    {
        return $this->belongsTo(VirtualMuseum::class, 'museum_id', 'museum_id');
    }
}
