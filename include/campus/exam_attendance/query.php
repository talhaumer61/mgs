<?php
// ADD EXAM ATTENDANCE
if(isset($_POST['submit_mark_attendance'])) {
	$condition	=	array ( 
								 'select' 	=> 'id'
								,'where' 	=> array( 
								 						 'id_campus'	=>	cleanvars($_POST['id_campus'])
														,'id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_exam'		=>	cleanvars($_POST['id_exam'])
														,'id_class'		=>	cleanvars($_POST['id_class'])
														,'id_section'	=>	cleanvars($_POST['id_section'])
														,'id_subject'	=>	cleanvars($_POST['id_subject'])
														,'id_datesheet'	=>	cleanvars($_POST['id_datesheet'])
														,'is_deleted'	=>	0
													)
								,'return_type' 	=> 'single' 
	); 
	if($dblms->getRows(EXAM_ATTENDANCE, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		$values = array(
							 'status'		=>	1
							,'is_publish'	=>	cleanvars($_POST['is_publish'])
							,'id_campus'	=>	cleanvars($_POST['id_campus'])
							,'id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_exam'		=>	cleanvars($_POST['id_exam'])
							,'id_class'		=>	cleanvars($_POST['id_class'])
							,'id_section'	=>	cleanvars($_POST['id_section'])
							,'id_subject'	=>	cleanvars($_POST['id_subject'])
							,'id_datesheet'	=>	cleanvars($_POST['id_datesheet'])
							,'dated'		=>	date('Y-m-d')
							,'id_added'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'	=>	date('Y-m-d G:i:s')
		);
		$sqllms	= $dblms->insert(EXAM_ATTENDANCE, $values);
		if ($sqllms) {
			$latestID = $dblms->lastestid();

			for($i=0; $i<=sizeof($_POST['id_std']); $i++){
				if(!empty($_POST['id_std'][$i]) && !empty($_POST['rollno'][$i]) && !empty($_POST['regno'][$i])) {
					$values = array(
										 'id_setup'		=>	cleanvars($latestID)
										,'id_std'		=>	cleanvars($_POST['id_std'][$i])
										,'rollno'		=>	cleanvars($_POST['rollno'][$i])
										,'regno'		=>	cleanvars($_POST['regno'][$i])
										,'status'		=>	cleanvars($_POST['status'][$i])
										,'remarks'		=>	cleanvars($_POST['remarks'][$i])
					); 
					$sqllms	= $dblms->insert(EXAM_ATTENDANCE_DETAIL, $values);
				}
			}
			sendRemark('Attendance Marked ID: "'.cleanvars($latestID).' detail', 1);
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// EDIT EXAM ATTENDANCE
if(isset($_POST['update_attendance'])) {
	$condition	=	array ( 
								 'select' 		=> 'id'
								,'where' 		=> array( 
															 'id_campus'	=>	cleanvars($_POST['id_campus'])
															,'id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
															,'id_exam'		=>	cleanvars($_POST['id_exam'])
															,'id_class'		=>	cleanvars($_POST['id_class'])
															,'id_section'	=>	cleanvars($_POST['id_section'])
															,'id_subject'	=>	cleanvars($_POST['id_subject'])
															,'id_datesheet'	=>	cleanvars($_POST['id_datesheet'])
															,'is_deleted'	=>	0
														)
								,'not_equal'	=>	array(
															'id'			=>	cleanvars($_POST['id'])
														)
								,'return_type' 	=> 'single' 
	); 
	if($dblms->getRows(EXAM_ATTENDANCE, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		$values = array(
							 'status'		=>	1
							,'is_publish'	=>	cleanvars($_POST['is_publish'])
							,'dated'		=>	date('Y-m-d')
							,'id_modify'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'	=>	date('Y-m-d G:i:s')
		);
		$sqllms = $dblms->Update(EXAM_ATTENDANCE, $values , "WHERE id = '".cleanvars($_POST['id'])."'");
		if ($sqllms) {
			$latestID = $_POST['id'];
			// DELETE OLD RECORD
			$sqllmsdelte = $dblms->querylms("DELETE FROM ".EXAM_ATTENDANCE_DETAIL." WHERE id_setup = '".$latestID."'");
			// INSERT NEW RECORD
			for($i=0; $i<=sizeof($_POST['id_std']); $i++){
				if(!empty($_POST['id_std'][$i]) && !empty($_POST['rollno'][$i]) && !empty($_POST['regno'][$i])) {
					$values = array(
										 'id_setup'		=>	cleanvars($latestID)
										,'id_std'		=>	cleanvars($_POST['id_std'][$i])
										,'rollno'		=>	cleanvars($_POST['rollno'][$i])
										,'regno'		=>	cleanvars($_POST['regno'][$i])
										,'status'		=>	cleanvars($_POST['status'][$i])
										,'remarks'		=>	cleanvars($_POST['remarks'][$i])
					); 
					$sqllms	= $dblms->insert(EXAM_ATTENDANCE_DETAIL, $values);
				}
			}
			sendRemark('Attendance Marked ID: "'.cleanvars($latestID).' detail', 2);
			sessionMsg("Success", "Record Successfully Added.", "info");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// DELETE EXAM ATTENDANCE
if(isset($_GET['deleteid'])){
	$latestID = $_GET['deleteid'];
	$values = array(
					  'is_deleted'		=> '1'
					, 'id_deleted'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
					, 'ip_deleted'		=> cleanvars($ip)
					, 'date_deleted'	=> date('Y-m-d H:i:s')
				);
	$sqllms = $dblms->Update(EXAM_ATTENDANCE , $values, "WHERE id = '".cleanvars($latestID)."'");

	// REMARKS
	if($sqllms){
		sendRemark("Delete ".moduleName(false)." ID: ".$latestID." Detail", '3');
		sessionMsg("Success", "Record Successfully Deleted.", "success");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}
?>