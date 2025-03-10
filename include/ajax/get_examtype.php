<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_campus'])) {
    $condition = array(
						 'select'		=>  't.type_id, t.type_status, t.type_name'
						,'join'			=>	'INNER JOIN '.DATESHEET.' d on d.id_exam = t.type_id'
						,'where'        =>  array(
													 't.type_status'	=>	1
													,'t.is_deleted'  	=>	0
													,'d.id_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
												)
						,'search_by'	=>	'AND (d.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR d.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
						,'group_by'		=>	' d.id_exam'
						,'return_type'  =>  'all'
	);
	$EXAM_TYPES = $dblms->getRows(EXAM_TYPES.' t', $condition);
    if($EXAM_TYPES){
        echo '<option value="">Select</option>';
        foreach ($EXAM_TYPES as $key => $value) {
            echo '<option value="'.$value['type_id'].'">'.$value['type_name'].'</option>';
        }
    }else{
        echo '<option value="">No Record Found</option>';
    }
}
?>