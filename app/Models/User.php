<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo',
        'level_sekarang',
        'progress_level_sekarang'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // level saat ini logic:
    // 0 berarti level 1 belum selesai
    // 1 berarti 1 sudah selesai / sedang progress level 2
    // dst.

    // mapping progress. contoh: 1 berarti pre test sudah selesai
    public const PRE_TEST = 1;
    public const EBOOK = 2;
    public const VIRTUAL_LIVING_MUSEUM = 3;
    public const POST_TEST = 4;

    public function incrementLevel(): void
    {
        $this->increment('level_sekarang');
    }

    // Ini dijalankan ketika user menyelesaikan progress TERBARU, JIKA MEMBUKA YANG SEBELUMNYA TIDAK DIANGGAP
    public function incrementProgressLevel(): void {
        // jika sudah 4, maka reset menjadi 0, dan increment level
        if ($this->progress_level_sekarang >= 4) {
            $this->progress_level_sekarang = 0;
            $this->incrementLevel();
        } else {
            $this->increment('progress_level_sekarang');
        }
    }

    // Relationships
    public function jawabanUser(): HasMany
    {
        return $this->hasMany(JawabanUser::class, 'user_id', 'id');
    }

    public function situsPeninggalan(): HasMany
    {
        return $this->hasMany(SitusPeninggalan::class, 'user_id', 'id');
    }

    public function aksesSitusUser(): HasMany
    {
        return $this->hasMany(AksesSitusUser::class, 'user_id', 'id');
    }

    public function kritikSaran(): HasMany
    {
        return $this->hasMany(KritikSaran::class, 'user_id', 'id');
    }

    public function laporanPeninggalan(): HasMany
    {
        return $this->hasMany(LaporanPeninggalan::class, 'user_id', 'id');
    }

    public function laporanKomentar(): HasMany
    {
        return $this->hasMany(LaporanKomentar::class, 'user_id', 'id');
    }

    public function laporanSuka(): HasMany
    {
        return $this->hasMany(LaporanSuka::class, 'user_id', 'id');
    }

    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class, 'user_id', 'id');
    }

    public function progressMateri(): HasMany
    {
        return $this->hasMany(ProgressMateri::class, 'user_id', 'id');
    }
}
