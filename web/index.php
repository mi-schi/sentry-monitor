<?php

if (file_exists(__DIR__ . '/scripts' . $_SERVER['SCRIPT_NAME'])) {
    readfile(__DIR__ . '/scripts' . $_SERVER['SCRIPT_NAME']);

    return;
}

require_once __DIR__ . '/../vendor/autoload.php';

use MS\Sentry\Monitor\Application as App;
use MS\Sentry\Monitor\Service\Diagram\SequenceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;

$app = new App;
$app['debug'] = true;

$app['diagram.sequence.provider'] = $app->share(function ($app) {
    return new SequenceProvider($app['db']);
});

$app->register(new TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../resources/views',
]);

$app->get('/{organisation}/{days}/{scale}', function () use ($app) {
    return $app['twig']->render('index.twig');
});

$app->get('/api/{organisation}/{days}/{scale}', function ($organisation, $days, $scale) use ($app) {
    return new JsonResponse($app['diagram.sequence.provider']->getSequences($organisation, $days, $scale));
});

$app->run();
