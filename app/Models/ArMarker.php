<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArMarker extends Model
{
  public $timestamps = false;

  protected $table = 'ar_marker';

  protected $primaryKey = 'marker_id';

  protected $fillable = [
    'situs_id',
    'museum_id',
    'nama',
    'path_gambar_marker',
    'path_patt',
  ];

  protected $casts = [
    'situs_id' => 'integer',
    'museum_id' => 'integer',
  ];

  public function situsPeninggalan(): BelongsTo
  {
    return $this->belongsTo(SitusPeninggalan::class, 'situs_id', 'situs_id');
  }

  public function virtualMuseum(): BelongsTo
  {
    return $this->belongsTo(VirtualMuseum::class, 'museum_id', 'museum_id');
  }

  public function virtualMuseumObjects(): HasMany
  {
    return $this->hasMany(VirtualMuseumObject::class, 'marker_id', 'marker_id');
  }
}
