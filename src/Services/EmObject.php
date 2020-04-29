<?php
/**
 * Created by PhpStorm.
 * User: eduardcherkashyn
 * Date: 2020-04-29
 * Time: 10:40
 */

namespace App\Services;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class EmObject
{
    private $em;

    public function __construct()
    {
        $paths = [__DIR__.'/../Entity'];
        $isDevMode = true;

        $dbParams = [
            'dbname' => 'coding_task',
            'user' => 'root',
            'password' => '123123',
            'host' => 'task_php_mysql',
            'driver' => 'pdo_mysql'
        ];

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode,null,
            null,
            false);
        $this->em = EntityManager::create($dbParams, $config);

    }

    public function getEm()
    {
        return $this->em;
    }
}
