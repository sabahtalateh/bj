<?php

namespace App\Controller;

use App\Service\SecurityService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use const App\config\SECURITY_SERVICE;

class LoginController extends BaseController
{
    public function login(array $params)
    {
        /** @var SecurityService $securityService */
        $securityService = $this->get(SECURITY_SERVICE);

        if (null !== $params['_token']) {
            return new RedirectResponse('/');
        }

        if ('GET' === $params['_method']) {
            return new Response($this->render('login.twig'));
        } else {
            $requestParams = $params['_data'];
            $email = $requestParams['email'];
            $password = $requestParams['password'];

            $authResult = $securityService->tryAuth($email, $password);
            if ($authResult->failed()) {
                return new Response($this->render('login.twig', ['errors' => ['Неверные данные']]));
            } else if ($authResult->succeed()) {
                $response = new RedirectResponse('/', 302);
                $response->headers->setCookie($authResult->getCookie());
                return $response;
            }
        }
    }

    public function logout()
    {
        $response = new RedirectResponse('/', 302);
        $response->headers->clearCookie('BJ_AUTH_USER');
        return $response;
    }
}
