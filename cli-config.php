<?php

require 'vendor/autoload.php';

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Helper\HelperSet;

$paths = [__DIR__.'/src/Entity'];
$isDevMode = true;

$dbParams = include 'migrations-db.php';

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode,null,
    null,
    false);
$entityManager = EntityManager::create($dbParams, $config);

return new HelperSet([
    'em' => new EntityManagerHelper($entityManager),
    'db' => new ConnectionHelper($entityManager->getConnection()),
]);
