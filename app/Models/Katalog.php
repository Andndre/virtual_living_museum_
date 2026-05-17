<?php

namespace App\Models;

use Database\Factories\KatalogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class Katalog extends Model
{
    /** @use HasFactory<KatalogFactory> */
    use HasFactory;

    protected $fillable = [
        'path_pdf',
    ];

    // Accessor biar bisa pakai $katalog->pdf_url di blade
    public function getPdfUrlAttribute(): ?string
    {
        if (! $this->path_pdf) {
            return null;
        }

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->path_pdf);
    }

    // Accessor biar dapat ukuran file dengan mudah
    public function getFileSizeAttribute()
    {
        if ($this->path_pdf && Storage::disk('public')->exists($this->path_pdf)) {
            return round(Storage::disk('public')->size($this->path_pdf) / 1024 / 1024, 2).' MB';
        }

        return null;
    }
}
