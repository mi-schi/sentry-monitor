<?php

if (file_exists(__DIR__ . '/scripts' . $_SERVER['SCRIPT_NAME'])) {
    readfile(__DIR__ . '/scripts' . $_SERVER['SCRIPT_NAME']);

    return;
}

require_once __DIR__ . '/../vendor/autoload.php';

use MS\Sentry\Monitor\Application as App;
use MS\Sentry\Monitor\Service\Diagram\SequenceProvider;
use MS\Sentry\Monitor\Service\Diagram\ProjectFinder;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;

$app = new App;
$app['debug'] = true;

$app['diagram.project.finder'] = $app->share(function ($app) {
    return new ProjectFinder($app['db']);
});

$app['diagram.sequence.provider'] = $app->share(function ($app) {
    return new SequenceProvider($app['db']);
});

$app->register(new TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../resources/views',
]);

$app->get('/{configuration}', function () use ($app) {
    return $app['twig']->render('index.twig');
});

$app->get('/api/{configuration}', function ($configuration) use ($app) {
    list($organisation, $days, $scale, $project) = explode(',', $configuration);
    $projects = $app['diagram.project.finder']->getProjects($organisation, $project);

    return new JsonResponse($app['diagram.sequence.provider']->getSequences($projects, $days, $scale));
});

$app->run();
