<?php
use Shuchkin\SimpleXLSX;
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
require_once 'xlsx_import/SimpleXLSX.php';
$xlsx = SimpleXLSX::parse($targetPath);
$rows = $xlsx->rows();
?>