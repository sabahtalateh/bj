<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig\Environment;

class BaseController
{
    private ContainerBuilder $containerBuilder;
    private Environment $twig;
    private $config;
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        ContainerBuilder $containerBuilder,
        Environment $twig,
        array $config,
        UserService $userService
    )
    {
        $this->containerBuilder = $containerBuilder;
        $this->twig = $twig;
        $this->config = $config;
        $this->userService = $userService;
    }

    protected function render(string $template, array $vars = []): string
    {
        return $this->twig->render($template, $vars);
    }

    protected function get(string $id)
    {
        return $this->containerBuilder->get($id);
    }

    protected function config(): array
    {
        return $this->config;
    }

    protected function getLoggedInUser(?string $token): ?array
    {
        if (null === $token) {
            return null;
        }

        $loggedInEmail = base64_decode($token);

        $user = $this->userService->findUserByEmail($loggedInEmail);

        return $user;
    }
}