<?php

namespace DC\SilexStarterPack\Task;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

require_once(__DIR__ . '/../../../../vendor/autoload.php');

class PasswordGenerator extends Command
{

    protected function configure()
    {
        $this->setName('starterpack:password:generator')
            ->setDescription('Generates a password for the admin control panel')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = require_once(__DIR__ . '/../../../../app/app_console.php');

        $encoder = $app['security.encoder_factory']->getEncoder('Symfony\Component\Security\Core\User\UserInterface');

        $dialog = $this->getHelperSet()->get('dialog');
        $password1 = $dialog->askHiddenResponse($output, 'Enter the password to generate: ');

        $dialog = $this->getHelperSet()->get('dialog');
        $password2 = $dialog->askHiddenResponse($output, 'Confirm the password: ');

        if($password1 === $password2) {

            $hash = $encoder->encodePassword($password1, null);

            $output->writeln("Password Hash = <info>$hash</info>");
        }
        else {
            $output->writeln("<error>Passwords don't match</error>");
        }
    }

}


