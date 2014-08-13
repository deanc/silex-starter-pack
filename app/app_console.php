<?php

require_once('bootstrap.php');

require_once('config.php');

$app = new Silex\Application();

// turn on debug mode
$app['debug'] = DEBUG_MODE;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => DB_DRIVER,
        'host'      => DB_HOST,
        'dbname'    => DB_NAME,
        'user'      => DB_USER,
        'password'  => DB_PASS,
    ),
));

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    
));

return $app;