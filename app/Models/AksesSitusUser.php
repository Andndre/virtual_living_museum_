<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AksesSitusUser extends Model
{
    protected $table = 'akses_situs_user';

    protected $primaryKey = 'akses_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'situs_id',
        'status',
        'unlocked_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'situs_id' => 'integer',
        'unlocked_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function situsPeninggalan(): BelongsTo
    {
        return $this->belongsTo(SitusPeninggalan::class, 'situs_id', 'situs_id');
    }
}
