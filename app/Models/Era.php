<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Era extends Model
{
  protected $table = 'era';

  protected $primaryKey = 'era_id';

  protected $fillable = [
    'kode',
    'nama',
    'rentang_waktu',
    'urutan',
  ];

  protected $casts = [
    'urutan' => 'integer',
  ];

  public function materi(): HasMany
  {
    return $this->hasMany(Materi::class, 'era_id', 'era_id');
  }
}
