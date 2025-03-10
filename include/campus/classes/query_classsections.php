<?php 
// INSERT RECORD
if(isset($_POST['submit_section'])) { 
	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
    
	$sqllmscheck  = $dblms->querylms("SELECT section_name  
										FROM ".CLASS_SECTIONS." 
										WHERE id_campus		= '".cleanvars($id_campus)."' 
										AND section_name	= '".cleanvars($_POST['section_name'])."'
										AND id_class		= '".cleanvars($_POST['id_class'])."'
										AND is_deleted		= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	} else { 
		$values = array (
							 "section_status"		=> cleanvars($_POST['section_status'])
							,"section_name"			=> cleanvars($_POST['section_name'])
							,"section_strength"		=> cleanvars($_POST['section_strength'])
							,"id_class"				=> cleanvars($_POST['id_class'])
							,"id_campus"			=> cleanvars($id_campus)
						);		
		$sqllms  = $dblms->insert(CLASS_SECTIONS, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			sendRemark("Class Section Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_section'])) { 
	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
    
	$sqllmscheck  = $dblms->querylms("SELECT section_name  
										FROM ".CLASS_SECTIONS." 
										WHERE id_campus		= '".cleanvars($id_campus)."' 
										AND section_name	= '".cleanvars($_POST['section_name'])."'
										AND id_class		= '".cleanvars($_POST['id_class'])."'
										AND is_deleted		= '0'
										AND section_id	   != '".cleanvars($_POST['section_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	} else { 
		$values = array (
							 "section_status"		=> cleanvars($_POST['section_status'])
							,"section_name"			=> cleanvars($_POST['section_name'])
							,"section_strength"		=> cleanvars($_POST['section_strength'])
							,"id_class"				=> cleanvars($_POST['id_class'])
							,"id_campus"			=> cleanvars($id_campus)
						);
		$sqllms = $dblms->Update(CLASS_SECTIONS , $values , "WHERE section_id = '".cleanvars($_POST['section_id'])."'");
		if($sqllms) { 
			$latestID = $_POST['section_id'];
			sendRemark("Class Section Updated ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$id_campus = ((isset($_GET['id_campus']) && !empty($_GET['id_campus'])) ? $_GET['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

	$values = array (
						 "is_deleted"	=>	'1'
						,"id_deleted"	=>	cleanvars($id_campus)
						,"ip_deleted"	=>	cleanvars($ip)
						,"date_deleted"	=>	date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(CLASS_SECTIONS , $values , "WHERE section_id = '".cleanvars($_GET['deleteid'])."'");
	if($sqllms){
		sendRemark("Class Section Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Success", "Record Deleted.", "success");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	}
}
?>