<?php

namespace DC\SilexStarterPack\Controller\Admin;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SMS implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->before(function () use ($app) {
            if(!$app['security.authorization_checker']->isGranted('ROLE_SMS')) {
                return $app->abort(403);
            }
        });
        // default route
        $controllers->get('/', function() use ($app) {
            $smss = $app['db']->fetchAll("SELECT * FROM sms ORDER BY created ASC");
            return $app['twig']->render('admin/sms/list.twig', array(
                'smss' => $smss
            ));
        })->bind('admin.sms.list');

        return $controllers;
    }
}