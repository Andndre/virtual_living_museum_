<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Katalog extends Model
{
    protected $fillable = [
        'path_pdf',
    ];

    // Accessor biar bisa pakai $katalog->pdf_url di blade
    public function getPdfUrlAttribute()
    {
        return $this->path_pdf
            ? Storage::disk('public')->url($this->path_pdf)
            : null;
    }

    // Accessor biar dapat ukuran file dengan mudah
    public function getFileSizeAttribute()
    {
        if ($this->path_pdf && Storage::disk('public')->exists($this->path_pdf)) {
            return round(Storage::disk('public')->size($this->path_pdf) / 1024 / 1024, 2) . ' MB';
        }
        return null;
    }
}
