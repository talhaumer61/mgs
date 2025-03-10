<?php
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

include_once("include/header.php");
if(Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '78', 'view' => '1'))){
	header("Location: ".BASE_URL."/paper-bank");
}
include_once("include/".get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/dashboard.php");
include_once("include/footer.php");
?>