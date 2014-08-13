<?php

require_once(__DIR__ . '/../app/config.php');
require_once(__DIR__ . '/../vendor/autoload.php');

use Symfony\Component\Console\Application;

$console = new Application;

$tasks = array();
foreach (new DirectoryIterator(__DIR__ . '/../src/DC/SilexStarterPack/Task') as $fileInfo) {
    if($fileInfo->isDot()) continue;

    $class = 'DC\\SilexStarterPack\\Task\\' . str_replace('.' . $fileInfo->getExtension(), '', $fileInfo->getFilename());

    $console->add(new $class());
}

$console->run();