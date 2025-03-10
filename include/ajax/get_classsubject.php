<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_class']) && !isset($_POST['id_exam'])) {
	$class = $_POST['id_class'];
	echo '
	<div class="form-group mb-md">
		<label class="col-md-3 control-label">Subject <span class="required">*</span></label>
		<div class="col-md-9">
			<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_subject">
				<option value="">Select</option>';
				if($_POST['syllabus_breakdown'] == "syllabus_breakdown"){echo'<option value="0">All Subjects</option>';}
					$sqllmscls	= $dblms->querylms("SELECT subject_id, subject_code, subject_name 
											FROM ".CLASS_SUBJECTS."
											WHERE subject_status = '1' AND id_class = '".$class."' AND is_deleted != '1'
											ORDER BY subject_name ASC");
					while($valuecls = mysqli_fetch_array($sqllmscls)) {
						echo '<option value="'.$valuecls['subject_id'].'">'.$valuecls['subject_code'].' - '.$valuecls['subject_name'].'</option>';
					}
			echo '
			</select>
		</div>
	</div>';
}

elseif(isset($_POST['id_class']) && isset($_POST['id_exam'])) {
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
?>