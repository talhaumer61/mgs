<?php

$currentDir = __DIR__ . "/database";

require("bootstrap.php");

if ($handle = opendir($currentDir)) {

  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      require_once("$currentDir/" . $entry);
      echo "$entry migrated successfully \n";
    }
  }

  closedir($handle);
}

echo "Table Migrated successfully";