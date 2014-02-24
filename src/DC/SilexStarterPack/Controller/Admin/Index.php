<?php

namespace DC\SilexStarterPack\Controller\Admin;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Index implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        // default route
        $controllers->get('/', function() use ($app) {
            return $app['twig']->render('admin/index.twig');
        })->bind('admin_index');

        return $controllers;
    }
}