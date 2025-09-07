<?php

namespace App\Helper;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class TokenHelper
{
    /**
     * Generate token
     *
     * @param int $userId
     * @param int $ttlMinutes
     * @return string
     */
    public static function generate(int $userId, int $ttlMinutes = 10): string
    {
        $expiry = Carbon::now()->addMinutes($ttlMinutes)->timestamp;
        $data = $userId . '|' . $expiry;
        $signature = hash_hmac('sha256', $data, Config::get('app.token_secret'));

        return base64_encode($userId . '|' . $expiry . '|' . $signature);
    }

    /**
     * Verify token and return data
     *
     * @param string $token
     * @return array
     */
    public static function verify(string $token): array
    {
        $decoded = base64_decode($token, true);

        if ($decoded === false) {
            return ['valid' => false, 'message' => 'Token tidak valid (decode gagal)'];
        }

        $parts = explode('|', $decoded);

        if (count($parts) !== 3) {
            return ['valid' => false, 'message' => 'Token rusak'];
        }

        [$userId, $expiry, $signature] = $parts;

        // cek expiry
        if (Carbon::now()->timestamp > (int)$expiry) {
            return ['valid' => false, 'message' => 'Token expired'];
        }

        // cek signature
        $validSignature = hash_hmac('sha256', $userId . '|' . $expiry, Config::get('app.token_secret'));

        if (!hash_equals($validSignature, $signature)) {
            return ['valid' => false, 'message' => 'Signature tidak cocok'];
        }

        return [
            'valid' => true,
            'user_id' => (int)$userId,
            'expiry' => (int)$expiry,
            'message' => 'Token valid',
        ];
    }
}
