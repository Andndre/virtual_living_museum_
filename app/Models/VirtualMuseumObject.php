<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VirtualMuseumObject extends Model
{
    protected $table = 'virtual_museum_object';
    protected $primaryKey = 'object_id';
    public $timestamps = false;
    
    protected $fillable = [
        'situs_id',
        'nama',
        'gambar_real',
        'path_obj',
        'path_gambar',
        'path_patt',
        'deskripsi'
    ];

    protected $casts = [
        'situs_id' => 'integer',
    ];

    // Relationships
    public function situsPeninggalan(): BelongsTo
    {
        return $this->belongsTo(SitusPeninggalan::class, 'situs_id', 'situs_id');
    }
}
