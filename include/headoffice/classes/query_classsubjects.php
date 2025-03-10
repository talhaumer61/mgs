<?php 
// INSERT RECORD
if(isset($_POST['submit_subject'])){
	$sqllmscheck = array ( 
							 'select' 		=>	'subject_code'
							,'where' 		=>	array( 
														 'subject_code'	=> cleanvars($_POST['subject_code'])
														,'is_deleted'	=> '0'
													)
							,'return_type' 	=> 'count' 
						); 
	$rowsQueryCheck  = $dblms->getRows(CLASS_SUBJECTS, $sqllmscheck);
	if($rowsQueryCheck) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: classsubjects.php", true, 301);
		exit();
	}else{ 
		$values = array (
							 "subject_status"	=>	cleanvars($_POST['subject_status'])
							,"subject_code"		=>	cleanvars($_POST['subject_code'])
							,"subject_name"		=>	cleanvars($_POST['subject_name'])
							,"id_class"			=>	cleanvars($_POST['id_class'])
						);		
		$sqllms  = $dblms->insert(CLASS_SUBJECTS, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			sendRemark("Add Subject ID: ".cleanvars($latestID)." detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: classsubjects.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_subject'])){
	$sqllmscheck = array ( 
							 'select' 		=>	'subject_code'
							,'where' 		=>	array( 
														 'subject_code'	=> cleanvars($_POST['subject_code'])
														,'is_deleted'	=> '0'
													)
							,'not_equal' 	=>	array( 
														'subject_id'	=> cleanvars($_POST['subject_id'])
													)
							,'return_type' 	=> 'count'
						); 
	$rowsQueryCheck  = $dblms->getRows(CLASS_SUBJECTS, $sqllmscheck);
	if($rowsQueryCheck) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: classsubjects.php", true, 301);
		exit();
	}else{ 
		$values = array (
							 "subject_status"	=>	cleanvars($_POST['subject_status'])
							,"subject_code"		=>	cleanvars($_POST['subject_code'])
							,"subject_name"		=>	cleanvars($_POST['subject_name'])
							,"id_class"			=>	cleanvars($_POST['id_class'])
						);	
		$sqllms = $dblms->Update(CLASS_SUBJECTS , $values , "WHERE subject_id = '".cleanvars($_POST['subject_id'])."'");

		$latestID = cleanvars($_POST['subject_id']);
		if($sqllms) { 
			sendRemark("Updated Subject ID: ".cleanvars($latestID)." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: classsubjects.php", true, 301);
			exit();
		}
	}
}

// DELTE RECORD
if(isset($_GET['deleteid'])) {	
	$values = array (
						 "is_deleted"	=>	'1'
						,"id_deleted"	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"	=>	cleanvars($ip)
						,"date_deleted"	=>	date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(CLASS_SUBJECTS , $values , "WHERE subject_id = '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		sendRemark("Subject Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Success", "Record Deleted.", "success");
		header("Location: classsubjects.php", true, 301);
		exit();
	}
}