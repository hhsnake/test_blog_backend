<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Doctrine\ORM\Tools\Setup;

$dbParams = [
    'driver'   => 'pdo_mysql',
    'host'     => '127.0.0.1',
    'user'     => 'user',
    'password' => 'password',
    'dbname'   => 'test_blog',
];

// Настройка Doctrine
$isDevMode = true;
$entitiesPath = [__DIR__ . '/../src/Entity'];
$dbConfig = ORMSetup::createAnnotationMetadataConfiguration($entitiesPath, $isDevMode);
$dbConfig->setNamingStrategy(new UnderscoreNamingStrategy());

// Настройка кэша
$cache = new FilesystemAdapter();
$dbConfig->setQueryCache($cache);
$dbConfig->setResultCache($cache);
$dbConfig->setMetadataCache($cache);

$connection = DriverManager::getConnection($dbParams, $dbConfig);
$entityManager = EntityManager::create($connection, $dbConfig);

// Создаём экземпляр ManagerRegistry
//$managerRegistry = new CustomManagerRegistry();
//$managerRegistry->setManager('default', $entityManager);

return new \TestBlog\Kernel\Db($connection, $entityManager);
