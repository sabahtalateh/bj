<?php

namespace App\Service;

use App\Security\Password;
use Symfony\Component\HttpFoundation\Cookie;

class SecurityService extends BaseService
{
    private $config;
    private UserService $userService;

    public function __construct($config, UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
        $this->config = $config;
    }

    public function tryAuth(string $login, string $password): AuthResult
    {
        $user = $this->userService->findUserByEmail($login);

        if (null === $user) {
            return AuthResult::failure();
        }

        if (Password::verify($password, $user['pwd_hash'])) {
            $authCookieLifetime = $this->config['security']['auth_cookie_lifetime'];
            $cookie = Cookie::create('BJ_AUTH_USER', base64_encode($user['email']), time() + $authCookieLifetime);
            return AuthResult::success($cookie);
        } else {
            return AuthResult::failure();
        }
    }
}
