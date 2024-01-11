<?php

use Core\EnvParser;

if (session_status() === PHP_SESSION_NONE) session_start();

define("DOC_ROOT", __DIR__ . "/../");

spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className);
    require DOC_ROOT . $className . '.php';
});

new EnvParser();

$config = require DOC_ROOT . "config.php";
require DOC_ROOT . "Core/functions.php";

require basepath("routes.php");