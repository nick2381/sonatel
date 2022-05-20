<?php

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/../logs/error_log');

require dirname(__FILE__) . '/../vendor/autoload.php';