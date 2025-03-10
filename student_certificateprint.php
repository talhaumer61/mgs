<?php
//-----------------------------------------------
// error_reporting(all);
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once ("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
//-----------------------------------------------
if(!empty($_SESSION['userlogininfo']['LOGINCAMPUS'])){
	$sql = " WHERE campus_id = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' ";
}
else{
	$sql = "";
}
//------------------------ CAMPUS INFO -----------------------
$sqllmscampus	= $dblms->querylms("SELECT campus_id, campus_code, campus_name, campus_address, campus_phone
									FROM ".CAMPUS." $sql LIMIT 1");
$value_camp = mysqli_fetch_array($sqllmscampus);

//------------------- STUDENT DETAILS ---------------------------
$sqllms_std	= $dblms->querylms("SELECT  s.std_id, s.std_status, s.std_name, s.std_fathername, s.std_gender, 
                                    s.std_nic, s.std_phone, s.id_class, s.id_session,
                                    s.std_rollno, s.std_regno, s.std_photo, c.class_name
                                    FROM ".STUDENTS." 		s
                                    INNER JOIN ".CLASSES."  c  ON c.class_id = s.id_class
                                    WHERE s.std_id != '' AND s.is_deleted != '1'
                                    AND s.std_id = '".$_GET['id']."'
                                    AND s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' LIMIT 1
                                ");
if(mysqli_num_rows($sqllms_std) > 0){              
    //-----------------------------------------------------                   
    $value_std = mysqli_fetch_array($sqllms_std);
    //-----------------------------------------------------

echo '<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Fee Challan Form</title>
<style type="text/css">
body {overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";  }
@media all {
	.page-break	{ display: none; }
}

@media print {
	.page-break	{ display: block; page-break-before: always; }
	@page { 
		size: A4 landscape;
	   margin: 4mm 4mm 4mm 4mm; 
	}
}
h1 { text-align:left; margin:0; margin-top:0; margin-bottom:0px; font-size:26px; font-weight:700; text-transform:uppercase; }
.spanh1 { font-size:14px; font-weight:normal; text-transform:none; text-align:right; float:right; margin-top:10px; }
h2 { text-align:left; margin:0; margin-top:0; margin-bottom:1px; font-size:24px; font-weight:700; text-transform:uppercase; }
.spanh2 { font-size:20px; font-weight:700; text-transform:none; }
h3 { text-align:center; margin:0; margin-top:0; margin-bottom:1px; font-size:18px; font-weight:700; text-transform:uppercase; }
h4 { 
	text-align:center; margin:0; margin-bottom:1px; font-weight:normal; font-size:15px; font-weight:700; word-spacing:0.1em;  
}
td { padding-bottom:4px; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; }
.line1 { border:1px solid #333; width:100%; margin-top:2px; margin-bottom:5px; }
.payable { border:2px solid #000; padding:2px; text-align:center; font-size:14px; }

.paid:after
{
    content:"PAID";
	
    position:absolute;
    top:30%;
    left:20%;
    z-index:1;
    font-family:Arial,sans-serif;
    -webkit-transform: rotate(-5deg); /* Safari */
    -moz-transform: rotate(-5deg); /* Firefox */
    -ms-transform: rotate(-5deg); /* IE */
    -o-transform: rotate(-5deg); /* Opera */
    transform: rotate(-5deg);
    font-size:250px;
    color:green;
    background:#fff;
    border:solid 4px yellow;
    padding:5px;
    border-radius:5px;
    zoom:1;
    filter:alpha(opacity=50);
    opacity:0.1;
    -webkit-text-shadow: 0 0 2px #c00;
    text-shadow: 0 0 2px #c00;
    box-shadow: 0 0 2px #c00;
}
</style>
<link rel="shortcut icon" href="images/favicon/favicon.ico">
</head>

<body>
<div id="print" style="orientation: landscape">
	<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css"/>
	<link rel="stylesheet" href="assets/stylesheets/theme.css"/>

	<link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css"/>
	<script src="assets/vendor/jquery/jquery.js"></script>
	
	<br>
	
		<img src="uploads/logo.png" style="max-height : 100px;">
		<center style="margin-top: -100px;  text-align:right">
			<h3 style="font-weight: 100;">Minhaj Education Society <span style="text-transform: capitalize;">('.$value_camp['campus_name'].')</span></h3>
			<p>
                Date: '.$value_std['cir_dated'].' <br>
                Ref# '.$value_std['cir_refrence'].'
            </p>
		</center>
	<br>
    <div class="center">
        <b>Subject: </b> <u>'.$value_std['cir_subject'].'</u>
    </div>
	<section class="panel mt-md">
        <p>
        This situation has left me unable to continue my education in this school and my father doesn’t want to leave his family behind either. Therefore, I have to request for a school-leaving certificate which will enable me to get admission in another school. I will be very thankful to you if you approve this request of mine and grant me a school leaving certificate.</p>
		<div>
            <b>Regards: </b>  '.$value_std['cir_regards'].' <br>
            <b>'.$value_std['designation_name'].'
		</div>
	</section>
</div>
</body>';
}
else{
    echo'<h1 style="text-align: center; margin-top: 50px; color: red;">No Record Found!</h1>';
}
echo'
<script type="text/javascript" language="javascript1.2">
 //Do print the page
if (typeof(window.print) != "undefined") {
    window.print();
}
</script>
</html>';
?>

