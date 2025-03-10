<?php
// ADD EXAM DATESHEET
if(isset($_POST['submit_datesheet'])) { 
	$condition	=	array ( 
								 'select' 	=> "id_session, id_exam, id_class, id_section"
								,'where' 	=> array( 
								 						 'id_campus'	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_session'	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_exam'		=> cleanvars($_POST['exam'])
														,'id_class'		=> cleanvars($_POST['class'])
														,'id_section'	=> cleanvars($_POST['section'])
														,'is_deleted'	=> '0'
												)
								,'return_type' 	=> 'single' 
	); 
	if($dblms->getRows(DATESHEET, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: exam_datesheet.php", true, 301);
		exit();
	} else {
		$values = array(
							 'status'		=>	cleanvars($_POST['status'])
							,'id_exam'		=>	cleanvars($_POST['exam'])
							,'id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_class'		=>	cleanvars($_POST['class'])
							,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'	=>	date('Y-m-d G:i:s')
		); 
		$sqllms	= $dblms->insert(DATESHEET, $values);
		if ($sqllms) {
			$idsetup = $dblms->lastestid();	
			for($i=0; $i<=sizeof($_POST['id_subject']); $i++){
				if(!empty($_POST['id_subject'][$i]) && !empty($_POST['dated'][$i]) && !empty($_POST['start_time'][$i]) && !empty($_POST['end_time'][$i]) ) {
					$dated = date("Y-m-d", strtotime($_POST['dated'][$i]));
					$values = array(
										 'id_setup'		=>	cleanvars($idsetup)
										,'paper_no'		=>	cleanvars($_POST['paper_no'][$i])
										,'id_subject'	=>	cleanvars($_POST['id_subject'][$i])
										,'dated'		=>	cleanvars($dated)
										,'start_time'	=>	cleanvars($_POST['start_time'][$i])
										,'total_marks'	=>	cleanvars($_POST['total_marks'][$i])
										,'passing_marks'=>	cleanvars($_POST['passing_marks'][$i])
										,'end_time'		=>	cleanvars($_POST['end_time'][$i])
					); 
					$sqllms	= $dblms->insert(DATESHEET_DETAIL, $values);
				}
			}
			sendRemark('Add Datesheet ID: "'.cleanvars($idsetup).' detail', 1);
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: exam_datesheet.php", true, 301);
			exit();
		}
	}
} 

// EDIT EXAM DATESHEET
if(isset($_POST['change_datesheet'])) { 
	$condition	=	array ( 
								 'select' 		=> "id_session, id_exam, id_class, id_section"
								,'where' 		=> array( 
								 						 'id_campus'	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'id_session'	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_exam'		=> cleanvars($_POST['exam'])
														,'id_class'		=> cleanvars($_POST['class'])
														,'id_section'	=> cleanvars($_POST['section'])
														,'is_deleted'	=> '0'
													)
								,'not_equal'	=>	array(
															'id'		=> cleanvars($_POST['id'])
														)
								,'return_type' 	=> 'single' 
	); 
	if($dblms->getRows(DATESHEET, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: exam_datesheet.php", true, 301);
		exit();
	} else {
		$values = array(
							'status'		=>	cleanvars($_POST['status'])
							,'id_exam'		=>	cleanvars($_POST['id_exam'])
							,'id_modify'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_modify'	=>	date('Y-m-d G:i:s')
		); 
		$sqllms = $dblms->Update(DATESHEET , $values , "WHERE id = '".cleanvars($_POST['id'])."'");
		if($sqllms) { 
			$sqllmsdelte  = $dblms->querylms("DELETE FROM ".DATESHEET_DETAIL." WHERE id_setup = '".$_POST['id']."'");
			for($i=0; $i<=sizeof($_POST['id_subject']); $i++){
				if(!empty($_POST['id_subject'][$i]) && !empty($_POST['dated'][$i]) && !empty($_POST['start_time'][$i]) && !empty($_POST['end_time'][$i]) ){
					$dated = date("Y-m-d", strtotime($_POST['dated'][$i]));
					$values = array(
										'id_setup'		=>	cleanvars($_POST['id'])
										,'paper_no'		=>	cleanvars($_POST['paper_no'][$i])
										,'id_subject'	=>	cleanvars($_POST['id_subject'][$i])
										,'dated'		=>	cleanvars($dated)
										,'start_time'	=>	cleanvars($_POST['start_time'][$i])
										,'end_time'		=>	cleanvars($_POST['end_time'][$i])
										,'total_marks'	=>	cleanvars($_POST['total_marks'][$i])
										,'passing_marks'=>	cleanvars($_POST['passing_marks'][$i])
					); 
					$sqllms	= $dblms->insert(DATESHEET_DETAIL, $values);										
				}
			}
			sendRemark('Update Datesheet ID: "'.cleanvars($_POST['id']).' details', 2);
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: exam_datesheet.php", true, 301);
			exit();
		}
	}
}

// DELETE EXAM DATESHEET
if(isset($_GET['deleteid_datesheet'])){
	$latestID = $_GET['deleteid_datesheet'];
	// Array
	$values = array(
					  'is_deleted'		=> '1'
					, 'id_deleted'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
					, 'ip_deleted'		=> cleanvars($ip)
					, 'date_deleted'	=> date('Y-m-d H:i:s')
				);
	$sqllms = $dblms->Update(DATESHEET , $values, "WHERE id = '".cleanvars($latestID)."'");

	// REMARKS
	if($sqllms){ 
		sendRemark("Delete Exam Datesheet ID: ".$latestID." Detail", '3');
		sessionMsg("Success", "Record Successfully Deleted.", "success");
		header("Location: exam_datesheet.php", true, 301);
		exit();
	}
}

// INSERT EXAM INSTRUCTIONS
if(isset($_POST['submit_inst'])){
	$sqllmscheck  = $dblms->querylms("SELECT id_examtype, id_class, id_campus 
										FROM ".EXAM_INSTRUCTIONS." 
										WHERE id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND id_examtype	= '".cleanvars($_POST['id_examtype'])."'
										AND id_class	= '".cleanvars($_POST['id_class'])."' 
										AND is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck) > 0) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: exam_datesheet.php?view=instructions", true, 301);
		exit();
	}else{

		// Array
		$values = array(
						  'status'			=> cleanvars($_POST['status'])
						, 'id_examtype'		=> cleanvars($_POST['id_examtype'])
						, 'id_class'		=> cleanvars($_POST['id_class'])
						, 'instructions'	=> cleanvars($_POST['instructions'])
						, 'id_campus'		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						, 'id_added'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						, 'date_added'		=> date('Y-m-d H:i:s')
					);
		$sqllms = $dblms->Insert(EXAM_INSTRUCTIONS , $values); 
		
		// REMARKS
		if($sqllms){
			$latestID = $dblms->lastestid();
			sendRemark("Add Exam Instructions ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: exam_datesheet.php?view=instructions", true, 301);
			exit();
		}
	}
}

// UPDATE EXAM INSTRUCTIONS
if(isset($_POST['update_inst'])){
	$sqllmscheck  = $dblms->querylms("SELECT id_examtype, id_class, id_campus 
										FROM ".EXAM_INSTRUCTIONS." 
										WHERE id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND id_examtype	= '".cleanvars($_POST['id_examtype'])."'
										AND id_class	= '".cleanvars($_POST['id_class'])."' 
										AND is_deleted	= '0'
										AND id		   != '".cleanvars($_POST['id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck) > 0) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: exam_datesheet.php?view=instructions", true, 301);
		exit();
	}else{
		// Array
		$values = array(
						  'status'			=> cleanvars($_POST['status'])
						, 'id_examtype'		=> cleanvars($_POST['id_examtype'])
						, 'id_class'		=> cleanvars($_POST['id_class'])
						, 'instructions'	=> cleanvars($_POST['instructions'])
						, 'id_campus'		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						, 'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						, 'date_modify'		=> date('Y-m-d H:i:s')
					);
		$sqllms = $dblms->Update(EXAM_INSTRUCTIONS , $values, "WHERE id = '".cleanvars($_POST['id'])."'");
		
		// REMARKS
		if($sqllms){
			$latestID = $_POST['id'];			
			sendRemark("Update Exam Instructions ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: exam_datesheet.php?view=instructions", true, 301);
			exit();
		}
	}
} 

// DELETE EXAM INSTRUCTIONS
if(isset($_GET['deleteid'])){
	$latestID = $_GET['deleteid'];
	// Array
	$values = array(
					  'is_deleted'		=> '1'
					, 'id_deleted'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
					, 'ip_deleted'		=> cleanvars($ip)
					, 'date_deleted'	=> date('Y-m-d H:i:s')
				);
	$sqllms = $dblms->Update(EXAM_INSTRUCTIONS , $values, "WHERE id = '".cleanvars($latestID)."'");

	// REMARKS
	if($sqllms){ 
		sendRemark("Delete Exam Instructions ID: ".$latestID." Detail", '3');
		sessionMsg("Success", "Record Successfully Deleted.", "success");
		header("Location: exam_datesheet.php?view=instructions", true, 301);
		exit();
	}
}
?>