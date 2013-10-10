<?php

namespace DC\SilexStarterPack\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class User implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // init
        $app['login.username'] = (isset($app['login.username']))? $app['login.username']: "demo";
        $app['login.password'] = (isset($app['login.password']))? $app['login.password']: "123456";
        $app['login.redirect'] = (isset($app['login.redirect']))? $app['login.redirect']: "admin_index";
        $app['login.basic_login_response'] = function() {
            $response = new Response();
            $response->headers->set('WWW-Authenticate', sprintf('Basic realm="%s"', 'Basic Login'));
            $response->setStatusCode(401, 'Please sign in.');
            return $response;
        };

        // controllers
        $controllers = $app['controllers_factory'];

        // login
        $controllers->get('/', function (Request $request, Application $app) {
            $username = $request->server->get('PHP_AUTH_USER', false);
            $password = $request->server->get('PHP_AUTH_PW');

            if ($app['login.username'] === $username && $app['login.password'] === $password) {
                $app['session']->set('isAuthenticated', true);
                return $app->redirect($app['url_generator']->generate($app['login.redirect']));
            }
            return $app['login.basic_login_response'];
        })->bind('login');

        // logout
        $controllers->get('/logout', function (Request $request, Application $app) {
            $app['session']->set('isAuthenticated', false);
            $_SESSION = array();
            return $app['login.basic_login_response'];
        })->bind('logout');

        return $controllers;
    }
}