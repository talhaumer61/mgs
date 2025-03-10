<?php
error_reporting(0);
ob_start();
ob_clean();
session_start();
date_default_timezone_set("Asia/Karachi");
ini_set('memory_limit', '-1');

define('LMS_HOSTNAME'			, 'localhost');
define('LMS_NAME'				, 'neotericschools_mgs2021');
define('LMS_USERNAME'			, 'neotericschools_mgs');
define('LMS_USERPASS'			, 'uw#7P({9hx7z');

define('MAIL_SEND'              , 'mail_sent');
define('MAIL_SEND_DETAIL'		, 'mail_sent_detail');

define('CONTROLER'			    , (!empty($_REQUEST['control'])         ? cleanvars($_REQUEST['control'])          : ''));
define('ZONE'			        , (!empty($_REQUEST['zone'])            ? cleanvars($_REQUEST['zone'])             : ''));
define('VIEW'			        , (!empty($_REQUEST['view'])            ? cleanvars($_REQUEST['view'])             : ''));
define('EDIT_ID'                , (!empty($_REQUEST['edit_id'])         ? cleanvars($_REQUEST['edit_id'])          : ''));
define("SERVER_URL"			    , "https://mgs.gptech.pk/mailer/");
define('IP'				        , (!empty($_SERVER['REMOTE_ADDR'])      ? $_SERVER['REMOTE_ADDR']   : ''));



$page			                = (isset($_REQUEST['page']) && $_REQUEST['page'] != '') ? $_REQUEST['page'] : '';
$current_page	                = (isset($_REQUEST['page']) && $_REQUEST['page'] != '') ? $_REQUEST['page'] : 1;
$Limit			                = (isset($_REQUEST['Limit']) && $_REQUEST['Limit'] != '') ? $_REQUEST['Limit'] : '';
$do                             = '';
$redirection                    = '';


define('TOKKEN'				    , (!empty($_REQUEST['tokken'])          ? cleanvars($_REQUEST['tokken'])           : ''));

define('SENDER'				    , (!empty($_REQUEST['sender'])          ? cleanvars($_REQUEST['sender'])           : ''));
define('SENDER_NAME'			, (!empty($_REQUEST['senderName'])      ? cleanvars($_REQUEST['senderName'])       : ''));
define('RECEIVER'				, (!empty($_REQUEST['receiver'])         ? cleanvars($_REQUEST['receiver'])          : ''));
define('RECEIVER_NAME'			, (!empty($_REQUEST['receiverName'])     ? cleanvars($_REQUEST['receiverName'])      : ''));
define('CC'				        , (!empty($_REQUEST['cc'])              ? cleanvars($_REQUEST['cc'])               : ''));
define('CC_NAME'				, (!empty($_REQUEST['ccName'])          ? cleanvars($_REQUEST['ccName'])           : ''));
define('BCC'				    , (!empty($_REQUEST['bcc'])             ? cleanvars($_REQUEST['bcc'])              : ''));
define('BCC_NAME'				, (!empty($_REQUEST['bccName'])         ? cleanvars($_REQUEST['bccName'])          : ''));
define('SUBJECT'				, (!empty($_REQUEST['subject'])         ? cleanvars($_REQUEST['subject'])          : ''));
define('BODY'				    , (!empty($_REQUEST['body'])            ? cleanvars($_REQUEST['body'])             : ''));

define('MATCHING_TOKKEN'        , 'b08a9b259dc86a5fa2ab8f409614b38dbef1768edbab1d2a7281c2c963d5b5ed');
?>