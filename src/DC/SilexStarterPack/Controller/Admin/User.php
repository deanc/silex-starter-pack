<?php

namespace DC\SilexStarterPack\Controller\Admin;

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

        $controllers->before(function () use ($app) {
            if(!$app['security.authorization_checker']->isGranted('ROLE_USERS')) {
                return $app->abort(403);
            }
        });

        $updateUser = function (Request $request, $id) use ($app) {

            $user = array();
            $currentPassword = '';
            if(!empty($id)) {
                $user = $app['db']->fetchAssoc("SELECT * FROM users WHERE id = ?", array($id));
                $currentPassword = $user['password'];
                unset($user['password']);
            }

            $allRoles = array_keys($app['security.role_hierarchy']);
            $roleChoices = array();
            foreach($allRoles AS $role) { $roleChoices[$role] = $role; }
            $form = $app['form.factory']->createBuilder(FormType::class, $user)
                ->add('username', TextType::class, array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Length(array(
                        'min' => USER_USERNAME_MIN_LENGTH,
                        'max' => USER_USERNAME_MAX_LENGTH
                    )))
                ))
                ->add('password', TextType::class, array(
                    'constraints' => array(new Assert\Length(array('min' => USER_PASSWORD_MIN_LENGTH)))
                ))
                ->add('email', EmailType::class, array(
                    'constraints' => array(new Assert\NotBlank(), new Assert\Email())
                ))
                ->add('roles', ChoiceType::class, array(
                    'choices' => $roleChoices,
                    'expanded' => true,
                    'label' => 'Role',
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                ))
                ->getForm();

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $encoder = $app['security.encoder_factory']->getEncoder('Symfony\Component\Security\Core\User\UserInterface');
                $now = $app['db']->fetchColumn("SELECT NOW()");

                if(empty($data['id'])) {
                    $app['db']->insert('users', array(
                        'username' => $data['username'],
                        'email' => $data['email'],
                        'password' => $encoder->encodePassword($data['password'], null),
                        'roles' => $data['roles']
                        ,'created' => $now
                        ,'updated' => $now
                    ));
                }
                else {

                    $updateData = array(
                        'username' => $data['username'],
                        'email' => $data['email'],
                        'roles' => $data['roles'],
                        'updated' => $now
                    );

                    // TODO: do string safe comparison using hash_equals and bump up php requirements to 5.6
                    if(!empty($data['password']) AND $encoder->encodePassword($data['password'], null) != $currentPassword) {
                        $updateData['password'] = $encoder->encodePassword($data['password'], null);
                    }

                    $app['db']->update('users', $updateData, array('id' => $user['id']));
                }

                // redirect somewhere
                return $app->redirect($app['url_generator']->generate('admin.user.list'));
            }

            return $app['twig']->render('admin/user/update.twig', array(
                'form' => $form->createView(),
                'id' => $id
            ));
        };

        $controllers->get('/{id}/edit', $updateUser)->method('GET|POST')->bind('admin.user.edit');
        $controllers->get('/add', $updateUser)->method('GET|POST')->bind('admin.user.add')->value('id', 0);

        // default route
        $controllers->get('/', function() use ($app) {

            $users = $app['db']->fetchAll("SELECT * FROM users ORDER BY id ASC");

            return $app['twig']->render('admin/user/list.twig', array(
                'users' => $users
            ));
        })->bind('admin.user.list');

        return $controllers;
    }
}