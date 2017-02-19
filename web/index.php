<?php

use Symfony\Component\HttpFoundation\Request;

$app = require_once __DIR__.'/../app/app.php';

// default route
$app->get('/', function (Request $request) use ($app) {
    return 'App homepage';
})->bind('homepage');

// mount the admin index controller - add any seperate admin controllers below here too
$app->mount('/a/users', new DC\SilexStarterPack\Controller\Admin\User());
if(TWILIO_ENABLED) {
    $app->mount('/a/sms', new DC\SilexStarterPack\Controller\Admin\SMS());
}
$app->mount('/a/', new DC\SilexStarterPack\Controller\Admin\Index());

$app->mount('/u/', new DC\SilexStarterPack\Controller\User());


$app->run();