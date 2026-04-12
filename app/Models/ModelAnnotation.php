<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelAnnotation extends Model
{
    protected $table = 'model_annotations';

    protected $primaryKey = 'annotation_id';

    protected $fillable = [
        'museum_id',
        'label',
        'position_x',
        'position_y',
        'position_z',
        'is_visible',
        'display_order',
    ];

    protected $casts = [
        'position_x' => 'float',
        'position_y' => 'float',
        'position_z' => 'float',
        'is_visible' => 'boolean',
        'display_order' => 'integer',
    ];

    public function museum(): BelongsTo
    {
        return $this->belongsTo(VirtualMuseum::class, 'museum_id', 'museum_id');
    }
}
