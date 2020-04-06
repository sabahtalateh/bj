<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Cookie;

class AuthResult
{
    private const SUCCESS = 'SUCCESS';
    private const FAILURE = 'FAILURE';

    private string $status;
    private ?Cookie $cookie;

    public function __construct(string $status, ?Cookie $cookie = null)
    {
        $this->status = $status;
        $this->cookie = $cookie;
    }

    public static function success(Cookie $cookie)
    {
        return new AuthResult(self::SUCCESS, $cookie);
    }

    public static function failure()
    {
        return new AuthResult(self::FAILURE);
    }

    public function succeed()
    {
        return $this->status === self::SUCCESS;
    }

    public function getCookie(): ?Cookie
    {
        return $this->cookie;
    }

    public function failed()
    {
        return $this->status === self::FAILURE;
    }
}