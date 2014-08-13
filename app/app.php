<?php

use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once('bootstrap.php');
require_once('config.php');

$app = new Silex\Application();

// turn on debug mode
$app['debug'] = DEBUG_MODE;

// enable translations
$app['locale'] = 'en_GB';

$app->before(function () use ($app) {
    if ($locale = $app['request']->get('locale')) {
        $app['locale'] = $locale;
    }
});

// do some security stuff
$app->after(function (Request $request, Response $response) {
    $response->headers->set('X-Frame-Options', 'DENY');
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('X-UA-Compatible', 'IE=edge');
});

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());

    $translator->addResource('yaml', __DIR__.'/../translations/en_GB.yml', 'en_GB');
    //$translator->addResource('yaml', __DIR__.'/../translations/fi_FI.yml', 'fi_FI');

    return $translator;
}));

// templates
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

// db
$dbOptions = array(
    'db.options' => array(
        'driver'   => DB_DRIVER,
        'host'      => DB_HOST,
        'dbname'    => DB_NAME,
        'user'      => DB_USER,
        'password'  => DB_PASS
    )
);
if(DB_FORCE_UTF8) {
    $dbOptions['db.options']['driverOptions'] = array(
        1002=>'SET NAMES utf8'
    );
}

// db
$app->register(new Silex\Provider\DoctrineServiceProvider(), $dbOptions);

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^/a',
            'form' => array(
                'login_path' => '/login'
                , 'check_path' => '/a/login_check'
                , 'default_target_path' => 'default_security_target'
            ),
            'logout' => array('logout_path' => '/a/logout'),
            'users' => array(
                // raw password is foo
                'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
            ),
        )
    )
));
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('admin/login.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
});

//$app['adminAuth'] = $app->protect(function(Request $request) use ($app) {
//    if(!$app['session']->get('isAdminAuthenticated')) {
//        //$app->abort(403, 'You cannot be here!');
//        return $app->redirect($app['url_generator']->generate('admin_login'));
//    }
//});

return $app;