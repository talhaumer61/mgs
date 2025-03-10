<?php 
echo'
<!doctype html>
<html class=" sidebar-dark sidebar-left-collapsed">
<head>
<!-- BASIC -->
<meta charset="UTF-8">';
//------------------------------------------
	include_once("header-css.php");
//------------------------------------------
echo '
</head>
<!-- loading-overlay-showing-->
<body class="" data-loading-overlay>

<section class="body">
<!-- INCLUDEING HEADER -->';
//------------------------------------------
	// include_once("header-top.php");
	include_once(get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/header-top.php");
//------------------------------------------
echo '
<div class="inner-wrapper">
<!-- INCLUDEING NAVIGATION -->';
//------------------------------------------
	include_once(get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/sidebar-left.php");
//------------------------------------------
$sqlstring	= "";
$adjacents	= 3;
if(!($Limit)) 	{ $Limit = 12; } 
if($page)		{ $start = ($page - 1) * $Limit; } else {	$start = 0;	}
?>