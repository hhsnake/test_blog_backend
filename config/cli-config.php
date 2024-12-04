<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use TestBlog\Kernel\Db;

require_once __DIR__ . "/bootstrap.php";
/** @var Db $db */
$db = require_once __DIR__ . '/db.php';

return ConsoleRunner::createHelperSet($db->entityManager);
