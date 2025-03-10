<?php 
// INSERT RECORD
if(isset($_POST['submit_inquiry'])) { 
	$id_campus = (isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS'];

	$sqllmscheck  = $dblms->querylms("SELECT cell_no, id_class
										FROM ".ADMISSIONS_INQUIRY." 
										WHERE id_campus	= '".cleanvars($id_campus)."' 
										AND cell_no		= '".cleanvars($_POST['cell_no'])."'
										AND id_class	= '".cleanvars($_POST['id_class'])."'
										AND is_deleted	= '0'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	} else {
		$values = array (
							 "status"			=> cleanvars($_POST['status'])
							,"form_no"			=> cleanvars($_POST['form_no'])
							,"name"				=> cleanvars($_POST['name'])
							,"fathername"		=> cleanvars($_POST['fathername'])
							,"gender"			=> cleanvars($_POST['gender'])
							,"cell_no"			=> cleanvars($_POST['cell_no'])
							,"address"			=> cleanvars($_POST['address'])
							,"dated"			=> cleanvars($dated)
							,"source"			=> cleanvars($_POST['source'])
							,"note"				=> cleanvars($_POST['note'])
							,"id_previousclass"	=> cleanvars($_POST['id_previousclass'])
							,"school"			=> cleanvars($_POST['school'])
							,"id_class"			=> cleanvars($_POST['id_class'])
							,"id_campus"		=> cleanvars($id_campus)
							,"id_added"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_added"		=> date('Y-m-d h:i:s')
						);		
		$sqllms  = $dblms->insert(ADMISSIONS_INQUIRY, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			sendRemark("Admission Inquiry Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_inquiry'])) { 
	$id_campus = (isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS'];

	$sqllmscheck  = $dblms->querylms("SELECT cell_no, id_class
										FROM ".ADMISSIONS_INQUIRY." 
										WHERE id_campus	= '".cleanvars($id_campus)."' 
										AND cell_no		= '".cleanvars($_POST['cell_no'])."'
										AND id_class	= '".cleanvars($_POST['id_class'])."'
										AND is_deleted	= '0'
										AND id		   != '".cleanvars($_POST['id'])."'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	} else {
		$values = array (
							 "status"			=> cleanvars($_POST['status'])
							,"form_no"			=> cleanvars($_POST['form_no'])
							,"name"				=> cleanvars($_POST['name'])
							,"fathername"		=> cleanvars($_POST['fathername'])
							,"gender"			=> cleanvars($_POST['gender'])
							,"cell_no"			=> cleanvars($_POST['cell_no'])
							,"address"			=> cleanvars($_POST['address'])
							,"dated"			=> cleanvars($dated)
							,"source"			=> cleanvars($_POST['source'])
							,"note"				=> cleanvars($_POST['note'])
							,"id_previousclass"	=> cleanvars($_POST['id_previousclass'])
							,"school"			=> cleanvars($_POST['school'])
							,"id_class"			=> cleanvars($_POST['id_class'])
							,"id_campus"		=> cleanvars($id_campus)
							,"id_modify"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_modify"		=> date('Y-m-d h:i:s')
						);	
		$sqllms = $dblms->Update(ADMISSIONS_INQUIRY , $values , "WHERE id = '".cleanvars($_POST['id'])."'");
		if($sqllms) { 
			$latestID = cleanvars($_POST['id']);
			sendRemark("Admission Inquiry Updated ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$values = array (
						 "is_deleted"	=>	'1'
						,"id_deleted"	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"	=>	cleanvars($ip)
						,"date_deleted"	=>	date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(ADMISSIONS_INQUIRY , $values , "WHERE id = '".cleanvars($_GET['deleteid'])."'");
	if($sqllms){
		sendRemark("Admission Inquiry Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Success", "Record Deleted.", "success");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	}
}
?>
