<?php

use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use DC\SilexStarterPack\Provider\UserProvider;

require_once('bootstrap.php');
require_once('config.php');

$app = new Silex\Application();

// turn on debug mode
$app['debug'] = DEBUG_MODE;

// enable translations
$app['locale'] = 'en_GB';

$app->before(function (Request $request) use ($app) {
    if ($locale = $request->get('locale')) {
        $app['locale'] = $locale;
        $app['session']->set('locale', $locale);
    }
    else if ($locale = $app['session']->get('locale')) {
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
    'locale_fallbacks' => array('en_GB'),
));

$app['translator'] = $app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());

    $translator->addResource('yaml', __DIR__.'/../translations/en_GB.yml', 'en_GB');
    //$translator->addResource('yaml', __DIR__.'/../translations/fi_FI.yml', 'fi_FI');

    return $translator;
});

$app->register(new Silex\Provider\FormServiceProvider());

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
$app->register(new Silex\Provider\DoctrineServiceProvider(), $dbOptions);

$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin_secured_area' => array(
            'pattern' => '^/a/',
            'remember_me' => array(
                'key'                => ADMIN_UNIQUE_RANDOM_KEY,
                'always_remember_me' => true,
                /* Other options */
            ),
            'form' => array(
                'login_path' => '/login'
                , 'check_path' => '/a/login_check'
                , 'default_target_path' => '/a/'
            ),
            'logout' => array('logout_path' => '/a/logout', 'target_url' => '/login'),
            'users' => function () use ($app) {
                return new UserProvider($app['db']);
            }
        )
    ,'anonymous' => array(
            'anonymous' => true
        )
    )
));
$app['security.role_hierarchy'] = array(
    'ROLE_ADMIN' => array('ROLE_USER', 'ROLE_USERS', 'ROLE_SMS', 'ROLE_ALLOWED_TO_SWITCH'),
    'ROLE_SUPERMOD' => array('ROLE_USER', 'ROLE_SMS'),
    'ROLE_MOD' => array('ROLE_USER', 'ROLE_SMS'),
    'ROLE_USER' => array()
);
$app->register(new Silex\Provider\RememberMeServiceProvider());

//if(FORCE_HTTPS) {
//    $app['security.access_rules'] = array(
//        array('^/', 'IS_AUTHENTICATED_ANONYMOUSLY', 'https'),
//    );
//}

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('admin/login.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
});

if(TWILIO_ENABLED) {
    $app['twilio'] = function () use ($app) {
        return new \DC\SilexStarterPack\Utility\SMS($app);
    };
}

return $app;