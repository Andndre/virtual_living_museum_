<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressMateri extends Model
{
    protected $table = 'progress_materi';
    protected $primaryKey = 'progress_id';
    public $timestamps = false; // We use last_updated instead
    
    protected $fillable = [
        'user_id',
        'materi_id',
        'status',
        'last_updated'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'materi_id' => 'integer',
        'last_updated' => 'datetime',
    ];

    // Status constants
    const STATUS_BELUM_MULAI = 'belum_mulai';
    const STATUS_PRETEST_SELESAI = 'pretest_selesai';
    const STATUS_EBOOK_SELESAI = 'ebook_selesai';
    const STATUS_MUSEUM_SELESAI = 'museum_selesai';
    const STATUS_POSTTEST_SELESAI = 'posttest_selesai';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'materi_id');
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_POSTTEST_SELESAI;
    }

    public function isPretestCompleted(): bool
    {
        return in_array($this->status, [
            self::STATUS_PRETEST_SELESAI,
            self::STATUS_EBOOK_SELESAI,
            self::STATUS_MUSEUM_SELESAI,
            self::STATUS_POSTTEST_SELESAI
        ]);
    }

    public function isEbookCompleted(): bool
    {
        return in_array($this->status, [
            self::STATUS_EBOOK_SELESAI,
            self::STATUS_MUSEUM_SELESAI,
            self::STATUS_POSTTEST_SELESAI
        ]);
    }

    public function isMuseumCompleted(): bool
    {
        return in_array($this->status, [
            self::STATUS_MUSEUM_SELESAI,
            self::STATUS_POSTTEST_SELESAI
        ]);
    }

    public function isPosttestCompleted(): bool
    {
        return $this->status === self::STATUS_POSTTEST_SELESAI;
    }

    // Static helper to get or create progress record
    public static function getOrCreate($userId, $materiId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId, 'materi_id' => $materiId],
            ['status' => self::STATUS_BELUM_MULAI, 'last_updated' => now()]
        );
    }

    // Update progress status
    public function updateStatus($status)
    {
        $this->update([
            'status' => $status,
            'last_updated' => now()
        ]);
    }

    // Get next required status based on current
    public function getNextStatus()
    {
        switch ($this->status) {
            case self::STATUS_BELUM_MULAI:
                return self::STATUS_PRETEST_SELESAI;
            case self::STATUS_PRETEST_SELESAI:
                return self::STATUS_EBOOK_SELESAI;
            case self::STATUS_EBOOK_SELESAI:
                return self::STATUS_MUSEUM_SELESAI;
            case self::STATUS_MUSEUM_SELESAI:
                return self::STATUS_POSTTEST_SELESAI;
            default:
                return null; // Already completed
        }
    }

    // Get progress percentage
    public function getProgressPercentage()
    {
        switch ($this->status) {
            case self::STATUS_BELUM_MULAI:
                return 0;
            case self::STATUS_PRETEST_SELESAI:
                return 25;
            case self::STATUS_EBOOK_SELESAI:
                return 50;
            case self::STATUS_MUSEUM_SELESAI:
                return 75;
            case self::STATUS_POSTTEST_SELESAI:
                return 100;
            default:
                return 0;
        }
    }
}
