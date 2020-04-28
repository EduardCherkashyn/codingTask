<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

$paths = [__DIR__.'/src/Entity'];
$isDevMode = true;

$dbParams = include 'migrations-db.php';

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode,null,
    null,
    false);
$entityManager = EntityManager::create($dbParams, $config);
