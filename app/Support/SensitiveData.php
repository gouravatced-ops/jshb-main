<?php

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class SensitiveData
{
    public static function encrypt(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = trim($value);

        if ($normalized === '') {
            return null;
        }

        return Crypt::encryptString($normalized);
    }

    public static function decrypt(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (DecryptException) {
            return $value;
        }
    }

    public static function hash(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = trim($value);

        if ($normalized === '') {
            return null;
        }

        return hash('sha256', mb_strtolower($normalized));
    }

    public static function mask(?string $value, int $tail = 5): string
    {
        $plain = self::decrypt($value);

        if ($plain === null || $plain === '') {
            return 'XXXXX';
        }

        $length = mb_strlen($plain);

        if ($length <= $tail) {
            return str_repeat('X', $length);
        }

        return mb_substr($plain, 0, $length - $tail) . str_repeat('X', $tail);
    }
}
