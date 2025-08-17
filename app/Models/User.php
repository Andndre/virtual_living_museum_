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
