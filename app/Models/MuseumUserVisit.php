<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MuseumUserVisit extends Model
{
    public $timestamps = true;
    protected $table = 'museum_user_visits';
    protected $fillable = [
        'user_id',
        'museum_id',
        'visited_at',
    ];
}
