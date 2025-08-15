<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KritikSaran extends Model
{
    protected $table = 'kritik_saran';
    protected $primaryKey = 'ks_id';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'pesan',
        'created_at'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
