<?php

use TestBlog\Kernel\App;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/bootstrap.php";
$db = require_once __DIR__ . "/../config/db.php";

App::getInstance()->setDb($db)->run();
