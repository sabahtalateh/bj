<?php

namespace App\Security;

class Password
{
    public static function encrypt(string $input): string
    {
        return password_hash($input, CRYPT_BLOWFISH);
    }

    public static function verify(string $input, string $hash): bool
    {
        return password_verify($input, $hash);
    }
}