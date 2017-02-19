<?php

namespace DC\SilexStarterPack\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class User implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        if(USER_ENABLE_PROFILE_PAGES OR $app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
            $controllers->get('/{id}/profile', function ($id) use ($app) {

                $user = $app['db']->fetchAll("SELECT * FROM users WHERE id = ?", array($id));
                unset($user['password']);

                return $app['twig']->render('user/view.twig', array(
                    'user' => $user
                ));
            })->bind('user.view');
        }

        return $controllers;
    }
}