<?php

namespace App;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Twig\Environment as TwigEnvironment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader as TwigFilesystemLoader;
use const App\config\USER_SERVICE;

class Kernel
{
    private ContainerBuilder $container;
    private RouteCollection $routes;
    private TwigEnvironment $twig;
    private array $appConfig;

    public function __construct()
    {
        $this->appConfig = require_once(__DIR__ . '/config/app.php');

        $this->container = require_once(__DIR__ . '/config/container.php');
        $this->routes = require_once(__DIR__ . '/config/routes.php');

        $loader = new TwigFilesystemLoader(__DIR__ . '/views/');
        $this->twig = new TwigEnvironment($loader, [
            'debug' => $this->appConfig['twig']['debug'],
        ]);

        if ($this->appConfig['twig']['debug']) {
            $this->twig->addExtension(new DebugExtension());
        }
    }

    public function respond(Request $request): Response
    {
        $context = new RequestContext();
        $context->fromRequest($request);
        $matcher = new UrlMatcher($this->routes, $context);

        $params = $matcher->match($request->getPathInfo());

        $controllerClass = $params['_controller'];
        $controller = new $controllerClass($this->container, $this->twig, $this->appConfig, $this->container->get(USER_SERVICE));
        $action = $params['_action'];
        $params['_query'] = $request->query->all();
        $params['_headers'] = $request->headers->all();
        $params['_method'] = $request->getMethod();
        $params['_token'] = $request->cookies->get('BJ_AUTH_USER');
        $params['_data'] = $request->request->all();

        $referrers = $request->headers->all()['referer'] ?? [];
        $params['_referrer'] = count($referrers) !== 0 ? $referrers[0] : null;
        $params['_requestUri'] = $request->getRequestUri();


        return $controller->$action($params);
    }
}