<?php

namespace App\Traits;

use Illuminate\Support\Facades\Hash;

trait ChecksPasswords
{
    /**
     * Cek password support multi-algoritma: bcrypt, MD5, SHA1, SHA256
     */
    protected function checkPassword(string $plain, string $hashed): bool
    {
        // 1. Bcrypt (Laravel default)
        if (str_starts_with($hashed, '$2y$') || str_starts_with($hashed, '$2a$')) {
            return Hash::check($plain, $hashed);
        }

        // 2. MD5 (32 karakter hex)
        if (strlen($hashed) === 32 && ctype_xdigit($hashed)) {
            return md5($plain) === $hashed;
        }

        // 3. SHA1 (40 karakter hex)
        if (strlen($hashed) === 40 && ctype_xdigit($hashed)) {
            return sha1($plain) === $hashed;
        }

        // 4. SHA256 (64 karakter hex)
        if (strlen($hashed) === 64 && ctype_xdigit($hashed)) {
            return hash('sha256', $plain) === $hashed;
        }

        return false;
    }
}