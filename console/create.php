<?php

require_once __DIR__ . '/../config/bootstrap.php';

/** @var Db $db */
$db = require_once __DIR__ . '/../config/db.php';

$tool = new \Doctrine\ORM\Tools\SchemaTool($db->entityManager);

$classes = array(
    $db->entityManager->getClassMetadata('TestBlog\Entity\User'),
    $db->entityManager->getClassMetadata('TestBlog\Entity\Blog')
);
$tool->createSchema($classes);
