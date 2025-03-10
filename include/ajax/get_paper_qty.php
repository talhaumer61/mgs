<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['method'])) {
 	$id_campus	= (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
	if(!empty($_POST['id_exam']) && !empty($_POST['id_class']) && !empty($_POST['id_section']) && !empty($_POST['id_subject'])){
		$condition = array(
								 'select'		 => 'COUNT(CASE WHEN ad.status = 1 THEN 1 ELSE NULL END) totalPresent, COUNT(CASE WHEN ad.status = 2 THEN 1 ELSE NULL END) totalAbsent'
								,'join'			 =>	'INNER JOIN '.EXAM_ATTENDANCE_DETAIL.' ad ON ad.id_setup = ea.id'
								,'where'         =>  array(
																 'ea.status'            =>	1
																,'ea.is_deleted'		=>	0
																,'ea.id_subject'		=>	cleanvars($_POST['id_subject'])
																,'ea.id_campus'			=>	cleanvars($id_campus)
																,'ea.id_class'			=>	cleanvars($_POST['id_class'])
																,'ea.id_section'        =>	cleanvars($_POST['id_section'])
																,'ea.id_exam'           =>	cleanvars($_POST['id_exam'])
																,'ea.id_session'        =>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														)
								,'return_type'   =>  'single'
		);
		$EXAM_ATTENDANCE = $dblms->getRows(EXAM_ATTENDANCE.' ea', $condition, $sql);
	}
    if($EXAM_ATTENDANCE){
        echo $EXAM_ATTENDANCE['totalPresent'];
    }else{
        echo'0';
    }
}
?>