<?php
session_id(str_replace('.', '', $_SERVER['REMOTE_ADDR']));
session_start();
require_once '../vendor/autoload.php';
require_once '../bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Twig\Environment;

$request = Request::createFromGlobals();
$routes = include '../src/app.php';

$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);
$request->attributes->add($matcher->match($request->getPathInfo()));

$controllerString = $request->attributes->get('_controller');

list($class, $method) = explode('::', $controllerString);
$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    \Twig\Environment::class => function (): Environment {
        $loader = new Twig\Loader\FilesystemLoader('../templates');
        return new Twig\Environment($loader);
    }
]);
$container = $builder->build();
$controller = $container->get($class);

$argumentResolver = new HttpKernel\Controller\ArgumentResolver();

try {
    $arguments = $argumentResolver->getArguments($request, [$class, $method]);
    $response = call_user_func_array([$controller, $method], $arguments);
} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response->send();
