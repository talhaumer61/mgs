<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_class']) && !isset($_POST['id_exam'])) {
	$class = $_POST['id_class']; 
	echo'<option value="">Select</option>';
	$sqlSubject	= $dblms->querylms("SELECT subject_id, subject_code, subject_name 
									FROM ".CLASS_SUBJECTS."
									WHERE id_class		= '".cleanvars($class)."'
									AND subject_status	= '1'
									AND is_deleted		= '0'
									ORDER BY subject_name ASC");
	while($valSubject = mysqli_fetch_array($sqlSubject)) {
		echo '<option value="'.$valSubject['subject_id'].'">'.$valSubject['subject_name'].' - '.$valSubject['subject_code'].'</option>';
	}
}

elseif(!isset($_POST['method']) && isset($_POST['id_class']) && isset($_POST['id_exam'])) {
    $id_campus = (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

	// EXAM SUBJECTS
	$condition = array(
						 'select'       =>  'sb.subject_id, sb.subject_code, sb.subject_name, dd.dated'
						,'join'			=>	'INNER JOIN '.DATESHEET_DETAIL.' dd on dd.id_subject = sb.subject_id 
											 INNER JOIN '.DATESHEET.' d on d.id = dd.id_setup'
						,'where'        =>  array(
													 'sb.subject_status'	=> 1
													,'sb.is_deleted'  		=> 0
													,'d.id_session'			=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
													,'d.id_exam'  			=> cleanvars($_POST['id_exam'])
													,'d.id_class'  			=> cleanvars($_POST['id_class'])
												)
						,'search_by'	=>	' AND (d.id_campus = '.$id_campus.' OR d.id_campus = '. $_SESSION['userlogininfo']['LOGINCAMPUS'].' OR d.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
						,'return_type'  =>  'all'
	);
	$CLASS_SUBJECTS = $dblms->getRows(CLASS_SUBJECTS.' sb', $condition);

	if($CLASS_SUBJECTS){
		echo'<option value="">Select</option>';
		foreach ($CLASS_SUBJECTS as $key => $val) {
			echo '<option value="'.$val['subject_id'].'">'.$val['subject_name'].' - '.$val['dated'].'</option>';
		}
	}else{							
		echo'<option value="">No Record Found</option>';
	}
}
elseif(isset($_POST['method']) == 'attendance_marked_subjects' && isset($_POST['id_class']) && isset($_POST['id_exam'])) {
    $id_campus = (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

	$condition = array(
                             'select'       =>  'sb.subject_id, sb.subject_name, sb.subject_code, dd.dated'
                            ,'join'			=>	'INNER JOIN '.CLASS_SUBJECTS.' sb ON sb.subject_id = et.id_subject
													INNER JOIN '.DATESHEET_DETAIL.' dd on dd.id_subject = sb.subject_id 
											 		INNER JOIN '.DATESHEET.' d on d.id = dd.id_setup' 
                            ,'where'        =>  array(
                                                         'sb.subject_status' 	=> 1
                                                        ,'sb.is_deleted' 		=> 0
                                                        ,'et.is_deleted' 		=> 0
                                                        ,'et.is_publish' 		=> 1
                                                        ,'et.id_session'  		=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                        ,'et.id_exam'  			=> cleanvars($_POST['id_exam'])
                                                        ,'et.id_class'  		=> cleanvars($_POST['id_class'])
                                            )
							,'search_by'	=>	' AND (et.id_campus = '.$id_campus.' OR et.id_campus = '. $_SESSION['userlogininfo']['LOGINCAMPUS'].' OR et.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
                            ,'order_by'  	=>  'sb.subject_id DESC'
                            ,'group_by'  	=>  'sb.subject_id'
                            ,'return_type'  =>  'all'
    );
	$EXAM_ATTENDANCE = $dblms->getRows(EXAM_ATTENDANCE.' et', $condition);
	if ($EXAM_ATTENDANCE) {
        echo'<option value="">Select</option>';
        foreach ($EXAM_ATTENDANCE AS $key => $val) {
            echo '<option value="'.$val['subject_id'].'">'.$val['subject_name'].' - '.$val['dated'].'</option>';
        }
    } else {
        echo '<option value="">No Record Found</option>';
    }
}
?>