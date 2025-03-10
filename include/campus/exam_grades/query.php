<?php 
// INSERT RECORD
if(isset($_POST['submit_grade'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT grade_name  
										FROM ".GRADESYSTEM." 
										WHERE id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND grade_name	= '".cleanvars($_POST['grade_name'])."'
										AND is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: exam_grades.php", true, 301);
		exit();
	} else { 
		$values = array (
							 "grade_status"			=>	cleanvars($_POST['grade_status'])
							,"grade_name"			=>	cleanvars($_POST['grade_name'])
							,"grade_point"			=>	cleanvars($_POST['grade_point'])
							,"grade_lowermark"		=>	cleanvars($_POST['grade_lowermark'])
							,"grade_uppermark"		=>	cleanvars($_POST['grade_uppermark'])
							,"grade_comment"		=>	cleanvars($_POST['grade_comment'])
							,"id_campus"			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,"id_added"				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_added"			=>	date('Y-m-d G:i:s')
						);	
		$sqllms = $dblms->insert(GRADESYSTEM , $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();			
			sendRemark("Add Grade ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: exam_grades.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_grade'])) { 	
	$sqllmscheck  = $dblms->querylms("SELECT grade_name  
										FROM ".GRADESYSTEM." 
										WHERE id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND grade_name	= '".cleanvars($_POST['grade_name'])."'
										AND is_deleted	= '0'
										AND grade_id	!= '".cleanvars($_POST['grade_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: exam_grades.php", true, 301);
		exit();
	} else { 
		$values = array (
							 "grade_status"			=>	cleanvars($_POST['grade_status'])
							,"grade_name"			=>	cleanvars($_POST['grade_name'])
							,"grade_point"			=>	cleanvars($_POST['grade_point'])
							,"grade_lowermark"		=>	cleanvars($_POST['grade_lowermark'])
							,"grade_uppermark"		=>	cleanvars($_POST['grade_uppermark'])
							,"grade_comment"		=>	cleanvars($_POST['grade_comment'])
							,"id_campus"			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,"id_modify"			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_modify"			=>	date('Y-m-d G:i:s')
						);
		$sqllms = $dblms->Update(GRADESYSTEM , $values , "WHERE grade_id = '".cleanvars($_POST['grade_id'])."'");
		if($sqllms) { 
			$latestID = cleanvars($_POST['grade_id']);
			sendRemark("Update Exam Grade ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: exam_grades.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORDS
if(isset($_GET['deleteid'])) { 
	$latestID = $_GET['deleteid'];	
	$values = array (
						 "is_deleted"		=> '1'
						,"id_deleted"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"		=> cleanvars($ip)
						,"date_deleted"		=> date('Y-m-d G:i:s')
					);	
	$sqllms = $dblms->Update(GRADESYSTEM , $values , "WHERE grade_id = '".cleanvars($latestID)."'");
	
	if($sqllms) {	
		sendRemark("Deleted Exam Grade ID: ".$latestID." Detail", '3');
		sessionMsg("Success", "Record Successfully Deleted.", "success");
		header("Location: exam_grades.php", true, 301);
		exit();
	}
}
?>