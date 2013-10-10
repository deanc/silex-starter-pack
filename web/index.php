<?php

use Symfony\Component\HttpFoundation\Request;

$app = require_once __DIR__.'/../app/app.php';

// default route
$app->get('', function (Request $request) use ($app) {
    return 'App homepage';
})->bind('homepage');

// mount the user auth controller
$app->mount('/u', new DC\SilexStarterPack\Controller\User());

// mount the admin index controller - add any seperate admin controllers below here too
$app->mount('/a', new DC\SilexStarterPack\Controller\Admin\Index());

$app->run();