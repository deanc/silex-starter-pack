<?php

use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;

require_once('bootstrap.php');
require_once('config.php');

$app = new Silex\Application();

// turn on debug mode
$app['debug'] = DEBUG_MODE;

// admin logins
$app['login.username'] = ADMIN_USERNAME;
$app['login.password'] = ADMIN_PASSWORD;

// enable translations
$app['locale'] = 'en';

$app->before(function () use ($app) {
    if ($locale = $app['request']->get('locale')) {
        $app['locale'] = $locale;
    }
});

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());

    $translator->addResource('yaml', __DIR__.'/../translations/en.yml', 'en');
    //$translator->addResource('yaml', __DIR__.'/../translations/fi.yml', 'fi');

    return $translator;
}));

// templates
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

// db
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => DB_DRIVER,
        'host'      => DB_HOST,
        'dbname'    => DB_NAME,
        'user'      => DB_USER,
        'password'  => DB_PASS,
    ),
));

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

$app['adminAuth'] = $app->protect(function(Request $request) use ($app) {
    if(!$app['session']->get('isAdminAuthenticated')) {
        //$app->abort(403, 'You cannot be here!');
        return $app->redirect($app['url_generator']->generate('admin_login'));
    }
});

return $app;