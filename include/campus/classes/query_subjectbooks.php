<?php 
// INSERT RECORD
if(isset($_POST['submit_book'])){
	$sqllmscheck = array ( 
							 'select' 		=>	'id'
							,'where' 		=>	array( 
														 'id_class'		=> cleanvars($_POST['id_class'])
														,'id_subject'	=> cleanvars($_POST['id_subject'])
														,'id_campus'	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'is_deleted'	=> '0'
													)
							,'return_type' 	=> 'count' 
						); 
	$rowsQueryCheck  = $dblms->getRows(SUBJECT_BOOKS, $sqllmscheck);
	if($rowsQueryCheck) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: classsubjects.php", true, 301);
		exit();
	}else{ 
		$values = array (
							 "status"		=>	cleanvars($_POST['status'])
							,"id_class"		=>	cleanvars($_POST['id_class'])
							,"id_subject"	=>	cleanvars($_POST['id_subject'])
							,"type"			=>	cleanvars($_POST['type'])
							,"name"			=>	cleanvars($_POST['name'])
							,"edition"		=>	cleanvars($_POST['edition'])
							,"publisher"	=>	cleanvars($_POST['publisher'])
							,"id_campus"	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,"id_added"		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_added"	=>	date('Y-m-d h:i:s')
						);		
		$sqllms  = $dblms->insert(SUBJECT_BOOKS, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			sendRemark("Add Subject Book ID: ".cleanvars($latestID)." detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: classsubjects.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_book'])){
	$sqllmscheck = array ( 
							 'select' 		=>	'id'
							,'where' 		=>	array( 
														 'id_class'		=> cleanvars($_POST['id_class'])
														,'id_subject'	=> cleanvars($_POST['id_subject'])
														,'id_campus'	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
														,'is_deleted'	=> '0'
													)													
							,'not_equal' 	=>	array( 
														'id'			=> cleanvars($_POST['id'])
													)
							,'return_type' 	=> 'count' 
						); 
	$rowsQueryCheck  = $dblms->getRows(SUBJECT_BOOKS, $sqllmscheck);
	if($rowsQueryCheck) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: classsubjects.php", true, 301);
		exit();
	}else{ 
		$values = array (
							 "status"		=>	cleanvars($_POST['status'])
							,"id_class"		=>	cleanvars($_POST['id_class'])
							,"id_subject"	=>	cleanvars($_POST['id_subject'])
							,"type"			=>	cleanvars($_POST['type'])
							,"name"			=>	cleanvars($_POST['name'])
							,"edition"		=>	cleanvars($_POST['edition'])
							,"publisher"	=>	cleanvars($_POST['publisher'])
							,"id_campus"	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,"id_added"		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_added"	=>	date('Y-m-d h:i:s')
						);	
		$sqllms = $dblms->Update(SUBJECT_BOOKS , $values , "WHERE id = '".cleanvars($_POST['id'])."'");

		if($sqllms) {
			$latestID = cleanvars($_POST['id']);
			sendRemark("Updated Subject Book ID: ".cleanvars($latestID)." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: classsubjects.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {	
	$values = array (
						 "is_deleted"	=>	'1'
						,"id_deleted"	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"	=>	cleanvars($ip)
						,"date_deleted"	=>	date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(SUBJECT_BOOKS , $values , "WHERE id = '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		sendRemark("Subject Book Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Success", "Record Deleted.", "success");
		header("Location: classsubjects.php", true, 301);
		exit();
	}
}