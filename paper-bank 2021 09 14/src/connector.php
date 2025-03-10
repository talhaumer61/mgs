<?php

if(file_exists("config.php")){
    require_once "config.php";
}else if (!isset($_ENV['ENV']) || empty($_ENV['ENV'])){
    die("Please specify your environment variables");
}

// Require Bootstrap
require_once("bootstrap.php");

// Require the config file
require_once('src/config/config.php');

// Helper function
require_once("src/utils/functions.php");
