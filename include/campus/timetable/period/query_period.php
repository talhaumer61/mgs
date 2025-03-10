<?php 
// INSERT RECORD
if(isset($_POST['submit_period'])) { 
	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
    
	$sqllmscheck  = $dblms->querylms("SELECT period_name  
										FROM ".PERIODS." 
										WHERE id_campus	= '".cleanvars($id_campus)."' 
										AND period_name = '".cleanvars($_POST['period_name'])."'
										AND is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	} else { 
		$values = array (
							 "period_status"			=> cleanvars($_POST['period_status'])
							,"period_name"				=> cleanvars($_POST['period_name'])
							,"period_timestart"			=> cleanvars($_POST['period_timestart'])
							,"period_timeend"			=> cleanvars($_POST['period_timeend'])
							,"period_timestart_friday"	=> cleanvars($_POST['period_timestart_friday'])
							,"period_timeend_friday"	=> cleanvars($_POST['period_timeend_friday'])
							,"id_campus"				=> cleanvars($id_campus)
						);
		$sqllms  = $dblms->insert(PERIODS, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			sendRemark("Timetable Period Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_period'])) { 
	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
    
	$sqllmscheck  = $dblms->querylms("SELECT period_name  
										FROM ".PERIODS." 
										WHERE id_campus	= '".cleanvars($id_campus)."' 
										AND period_name = '".cleanvars($_POST['period_name'])."'
										AND is_deleted	= '0'
										AND period_id  != '".cleanvars($_POST['period_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	} else { 
		$values = array (
							 "period_status"			=> cleanvars($_POST['period_status'])
							,"period_name"				=> cleanvars($_POST['period_name'])
							,"period_timestart"			=> cleanvars($_POST['period_timestart'])
							,"period_timeend"			=> cleanvars($_POST['period_timeend'])
							,"period_timestart_friday"	=> cleanvars($_POST['period_timestart_friday'])
							,"period_timeend_friday"	=> cleanvars($_POST['period_timeend_friday'])
							,"id_campus"				=> cleanvars($id_campus)
						);
		$sqllms = $dblms->Update(PERIODS , $values , "WHERE period_id = '".cleanvars($_POST['period_id'])."'");
		if($sqllms) { 
			$latestID = $_POST['period_id'];
			sendRemark("Timetable Period Updated ID: ".$latestID." Detail", '2');
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
	$sqllms = $dblms->Update(PERIODS , $values , "WHERE period_id = '".cleanvars($_GET['deleteid'])."'");
	if($sqllms){
		sendRemark("Timetable Period Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Success", "Record Deleted.", "success");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	}
}
?>