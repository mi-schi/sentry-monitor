<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use EnlightenedDC\Sentry\Monitor\Api\Parser;
use EnlightenedDC\Sentry\Monitor\Api\Client;
use EnlightenedDC\Sentry\Monitor\Api\Converter;
use EnlightenedDC\Sentry\Monitor\Api\Builder;
use Symfony\Component\HttpFoundation\JsonResponse;

$app = new Application();

$app['debug'] = true;

$app['config_path'] = __DIR__ . '/config.yml';

$app['api.parser'] = $app->share(function ($app) {
    return new Parser($app['config_path']);
});

$app['api.client'] = $app->share(function () {
    return new Client();
});

$app['api.converter'] = $app->share(function ($app) {
    return new Converter($app['api.client']);
});

$app['api.builder'] = $app->share(function ($app) {
    return new Builder($app['api.parser'], $app['api.converter']);
});

$app->register(new TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../resources/views',
]);

$app->get('/{id}', function () use ($app) {
    return $app['twig']->render('index.twig');
});

$app->get('/api/{id}', function ($id) use ($app) {
    return new JsonResponse($app['api.builder']->getSequences($id));
});

return $app;
