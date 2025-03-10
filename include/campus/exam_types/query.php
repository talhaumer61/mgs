<?php 
// INSERT RECORD
if(isset($_POST['submit_type'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT type_id  
										FROM ".EXAM_TYPES." 
										WHERE type_name = '".cleanvars($_POST['type_name'])."'
										AND is_deleted	= '0'
										AND id_campus 	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: exam_types.php", true, 301);
		exit();
	} else { 
		$values = array (
							 "type_status"		=> cleanvars($_POST['type_status'])
							,"type_name"		=> cleanvars($_POST['type_name'])
							,"type_details"		=> cleanvars($_POST['type_details'])
							,"id_campus"		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);	
		$sqllms = $dblms->insert(EXAM_TYPES , $values);
		
		if($sqllms) { 
			$latestID = $dblms->lastestid();			
			sendRemark("Add Exam Type ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: exam_types.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORDS
if(isset($_POST['changes_type'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT type_id  
										FROM ".EXAM_TYPES." 
										WHERE type_name = '".cleanvars($_POST['type_name'])."'
										AND is_deleted	= '0'
										AND id_campus 	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND type_id 	!= '".cleanvars($_POST['type_id'])."'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {		
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: exam_types.php", true, 301);
		exit();
	} else { 
		$values = array (
							 "type_status"		=> cleanvars($_POST['type_status'])
							,"type_name"		=> cleanvars($_POST['type_name'])
							,"type_details"		=> cleanvars($_POST['type_details'])
							,"id_campus"		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);	
		$sqllms = $dblms->Update(EXAM_TYPES , $values , "WHERE type_id = '".cleanvars($_POST['type_id'])."'");
		
		if($sqllms) { 
			$latestID = cleanvars($_POST['type_id']);			
			sendRemark("Update Exam Type ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: exam_types.php", true, 301);
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
	$sqllms = $dblms->Update(EXAM_TYPES , $values , "WHERE type_id = '".cleanvars($latestID)."'");
	
	if($sqllms) {	
		sendRemark("Deleted Exam Type ID: ".$latestID." Detail", '3');
		sessionMsg("Success", "Record Successfully Deleted.", "success");
		header("Location: exam_types.php", true, 301);
		exit();
	}
}
?>