<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once '../vendor/autoload.php';

$json = json_decode(file_get_contents('../game_data/questions.json'), true);

$config = [];
$config = ['enable_toggle' => false];
$config['contestants'] = $json['contestants'];
$config['players'] = array_map(function(array $contestant_info) {
    return ucfirst(strtolower($contestant_info['name']));
}, $json['contestants']);

$config['display_host'] = false;


$loader = new Twig_Loader_Filesystem('views');

$twig = new Twig_Environment($loader);

$router = new \League\Route\RouteCollection();

$router->get('/', function (Request $request, Response $response) use ($twig, $config) {
    $response->setContent($twig->render('index.html.twig', [ 'players' => $config['players'] ]));
    return $response;
});

$router->get('/play', function (Request $request, Response $response, array $args) use ($twig, $config) {
    return new \Symfony\Component\HttpFoundation\RedirectResponse('/');
});

$router->get('/obs', function (Request $request, Response $response, array $args) use ($twig, $config) {
    $response->setContent(
        $twig->render(
            'obs.html.twig',
            [ 'players' => $config['players'], 'display_host' => $config['display_host'], 'contestants' => $config['contestants'] ]
        )
    );
    return $response;
});

$router->get('/play/{player}', function (Request $request, Response $response, array $args) use ($twig, $config) {
    $player = ucfirst(strtolower($args['player']));

    if (!in_array($player, $config['players'])) {
        return new \Symfony\Component\HttpFoundation\RedirectResponse('/');
    }
    $response->setContent($twig->render('play.html.twig', [ 'players' => $config['players'], 'user' => $player , 'contestants' => $config['contestants']]));
    return $response;
});

$router->get(
    '/all',
    function (Request $request, Response $response, array $args) use ($twig, $config) {
        $response->setContent(
            $twig->render(
                'all.html.twig',
                ['players' => $config['players'], 'display_host' => $config['display_host'], 'contestants' => $config['contestants']]
            )
        );

        return $response;
    }
);

$router->addRoute('GET', '/admin', function (Request $request, Response $response) use ($twig, $config) {
    $response->setContent($twig->render('admin.html.twig', [ 'players' => $config['players'], 'contestants' => $config['contestants'], 'enable_toggle' => $config['enable_toggle'] ]));
    return $response;
});

$dispatcher = $router->getDispatcher();
$request = Request::createFromGlobals();

$response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

$response->send();
