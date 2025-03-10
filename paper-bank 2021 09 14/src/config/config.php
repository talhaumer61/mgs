<?php

// Define the SITENAME
define('SITENAME', $_ENV['SITENAME']);

// Define the APPROOT
// your path to the /src/ folder
define('APPROOT', dirname(dirname(__FILE__)));

// Define the URLROOT
define('URLROOT', $_ENV['URLROOT']);

define('PARENTROOT', $_ENV['PARENTROOT']);

// DATABASE CONFIDENTIALS
define('HOST', $_ENV['HOST']);
define('USERNAME', $_ENV['USERNAME']);
define('PASSWORD', $_ENV['PASSWORD']);
define('DBNAME', $_ENV['DBNAME']);
/**
 * Either "production" or "development"
 */
define('ENV', $_ENV['ENV']); // "production" || "development"

// Pagination Constants
define("LIMIT", $_ENV['LIMIT']);

define("DEFAULT_CONTROLLER", $_ENV['DEFAULT_CONTROLLER']);
define("DEFAULT_METHOD", $_ENV['DEFAULT_METHOD']);
