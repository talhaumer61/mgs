<?php
// ADD EXAM ATTENDANCE
if(isset($_POST['submit_marks'])) {
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
	if($dblms->getRows(EXAM_MARKS, $condition)) {
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
							,'id_added'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'	=>	date('Y-m-d G:i:s')
		);
		$sqllms	= $dblms->insert(EXAM_MARKS, $values);
		if ($sqllms) {
			$latestID = $dblms->lastestid();

			for($i=0; $i<=sizeof($_POST['id_std']); $i++){
				if(!empty($_POST['id_std'][$i])) {
					$values = array(
										 'id_setup'			=>	cleanvars($latestID)
										,'id_std'			=>	cleanvars($_POST['id_std'][$i])
										,'rollno'			=>	cleanvars($_POST['rollno'][$i])
										,'regno'			=>	cleanvars($_POST['regno'][$i])
										,'obtain_marks'		=>	cleanvars($_POST['obtain_marks'][$i])
										,'total_marks'		=>	cleanvars($_POST['total_marks'][$i])
										,'remarks'			=>	cleanvars($_POST['remarks'][$i])
					);
					if($_POST['obtain_marks'][$i]>=$_POST['passing_marks'][$i]){
						$values['status']=1;
					}
					else{
						$values['status']=2;
					} 
					$sqllms	= $dblms->insert(EXAM_MARKS_DETAILS, $values);
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
if(isset($_POST['update_marks'])) {
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
	if($dblms->getRows(EXAM_MARKS, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		$values = array(
							 'status'		=>	1
							,'is_publish'	=>	cleanvars($_POST['is_publish'])
							,'id_modify'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'	=>	date('Y-m-d G:i:s')
		);
		$sqllms = $dblms->Update(EXAM_MARKS, $values , "WHERE id = '".cleanvars($_POST['id'])."'");
		if ($sqllms) {
			$latestID = $_POST['id'];
			// DELETE OLD RECORD
			$sqllmsdelte = $dblms->querylms("DELETE FROM ".EXAM_MARKS_DETAILS." WHERE id_setup = '".$latestID."'");
			// INSERT NEW RECORD
			for($i=0; $i<=sizeof($_POST['id_std']); $i++){
				if(!empty($_POST['id_std'][$i])) {
					$values = array(
										'id_setup'			=>	cleanvars($latestID)
										,'id_std'			=>	cleanvars($_POST['id_std'][$i])
										,'rollno'			=>	cleanvars($_POST['rollno'][$i])
										,'regno'			=>	cleanvars($_POST['regno'][$i])
										,'obtain_marks'		=>	cleanvars($_POST['obtain_marks'][$i])
										,'total_marks'		=>	cleanvars($_POST['total_marks'][$i])
										,'remarks'			=>	cleanvars($_POST['remarks'][$i])
					);
					if($_POST['obtain_marks'][$i]>=$_POST['passing_marks'][$i]){
						$values['status']=1;
					}
					else{
						$values['status']=2;
					}  
					$sqllms	= $dblms->insert(EXAM_MARKS_DETAILS, $values);
				}
			}
			sendRemark('Attendance Marked ID: "'.cleanvars($latestID).' detail', 2);
			sessionMsg("Success", "Record Successfully Added.", "info");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// DELETE EXAM MARKS
if(isset($_GET['deleteid'])){
	$latestID = $_GET['deleteid'];
	$values = array(
					  'is_deleted'		=> '1'
					, 'id_deleted'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
					, 'ip_deleted'		=> cleanvars($ip)
					, 'date_deleted'	=> date('Y-m-d H:i:s')
				);
	$sqllms = $dblms->Update(EXAM_MARKS , $values, "WHERE id = '".cleanvars($latestID)."'");

	// REMARKS
	if($sqllms){
		sendRemark("Delete ".moduleName(false)." ID: ".$latestID." Detail", '3');
		sessionMsg("Success", "Record Successfully Deleted.", "success");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}
?>