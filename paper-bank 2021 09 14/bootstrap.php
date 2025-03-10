<?php

// Autoloader
require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
  "driver" => $_ENV['DBDRIVER'],
  "host" => $_ENV['HOST'],
  "database" => $_ENV['DBNAME'],
  "username" => $_ENV['USERNAME'],
  "password" => $_ENV['PASSWORD'],
  "port" => $_ENV['PORT'] ?? 3306
]);



$capsule->setAsGlobal();

$capsule->bootEloquent();
