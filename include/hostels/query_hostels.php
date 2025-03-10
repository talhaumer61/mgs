<?php 
// INSERT RECORD HOSTEL
if(isset($_POST['submit_hostel'])) { 
	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

	$sqllmscheck  = $dblms->querylms("SELECT hostel_name  
										FROM ".HOSTELS." 
										WHERE id_campus = '".cleanvars($id_campus)."' 
										AND hostel_name = '".cleanvars($_POST['hostel_name'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: hostels.php", true, 301);
		exit();
	}else{
		$values = array (
							 "hostel_status"	=> cleanvars($_POST['hostel_status'])
							,"hostel_name"		=> cleanvars($_POST['hostel_name'])
							,"id_type"			=> cleanvars($_POST['id_type'])
							,"hostel_address"	=> cleanvars($_POST['hostel_address'])
							,"hostel_warden"	=> cleanvars($_POST['hostel_warden'])
							,"hostel_detail"	=> cleanvars($_POST['hostel_detail'])
							,"id_campus"		=> cleanvars($id_campus)
						);		
		$sqllms  = $dblms->insert(HOSTELS, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			sendRemark("Hostel Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: hostels.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD HOSTEL
if(isset($_POST['changes_hostel'])){
	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

	$sqllmscheck  = $dblms->querylms("SELECT hostel_name  
										FROM ".HOSTELS." 
										WHERE id_campus = '".cleanvars($id_campus)."' 
										AND hostel_name = '".cleanvars($_POST['hostel_name'])."'
										AND hostel_id  != '".cleanvars($_POST['hostel_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: hostels.php", true, 301);
		exit();
	}else{
		$values = array (
							 "hostel_status"	=> cleanvars($_POST['hostel_status'])
							,"hostel_name"		=> cleanvars($_POST['hostel_name'])
							,"id_type"			=> cleanvars($_POST['id_type'])
							,"hostel_address"	=> cleanvars($_POST['hostel_address'])
							,"hostel_warden"	=> cleanvars($_POST['hostel_warden'])
							,"hostel_detail"	=> cleanvars($_POST['hostel_detail'])
							,"id_campus"		=> cleanvars($id_campus)
						);	
		$sqllms = $dblms->Update(HOSTELS , $values , "WHERE hostel_id = '".cleanvars($_POST['hostel_id'])."'");

		$latestID = cleanvars($_POST['quote_id']);
		if($sqllms) { 
			sendRemark("Hostel Updated ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: hostels.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD HOSTEL
if(isset($_GET['hostel_id'])){
	$values = array (
						 "is_deleted"	=>	'1'
						,"id_deleted"	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"	=>	cleanvars($ip)
						,"date_deleted"	=>	date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(HOSTEL_ROOMS , $values , "WHERE room_id = '".cleanvars($_GET['hostel_id'])."'");
	if($sqllms){
		sendRemark("Hostel Room Deleted ID: ".$_GET['hostel_id']." Detail", '3');
		sessionMsg("Success", "Record Deleted.", "success");
		header("Location: hostelrooms.php", true, 301);
		exit();
	}
}

// INSERT RECORD ROOM
if(isset($_POST['submit_room'])) { 
	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

	$sqllmscheck  = $dblms->querylms("SELECT room_name  
										FROM ".HOSTEL_ROOMS." 
										WHERE id_campus	= '".cleanvars($id_campus)."' 
										AND id_hostel	= '".cleanvars($_POST['id_hostel'])."' 
										AND room_name	= '".cleanvars($_POST['room_name'])."'
										AND is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: hostelrooms.php", true, 301);
		exit();
	}else{
		$values = array (
							 "room_status"	=> cleanvars($_POST['room_status'])
							,"room_name"	=> cleanvars($_POST['room_name'])
							,"id_hostel"	=> cleanvars($_POST['id_hostel'])
							,"room_beds"	=> cleanvars($_POST['room_beds'])
							,"room_detail"	=> cleanvars($_POST['room_detail'])
							,"id_campus"	=> cleanvars($id_campus)
						);		
		$sqllms  = $dblms->insert(HOSTEL_ROOMS, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			sendRemark("Hostel Room Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: hostelrooms.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD ROOM
if(isset($_POST['changes_room'])){
	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

	$sqllmscheck  = $dblms->querylms("SELECT room_name  
										FROM ".HOSTEL_ROOMS." 
										WHERE id_campus	= '".cleanvars($id_campus)."' 
										AND id_hostel	= '".cleanvars($_POST['id_hostel'])."' 
										AND room_name	= '".cleanvars($_POST['room_name'])."'
										AND is_deleted	= '0'
										AND room_id	   != '".cleanvars($_POST['room_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: hostelrooms.php", true, 301);
		exit();
	}else{
		$values = array (
							 "room_status"	=> cleanvars($_POST['room_status'])
							,"room_name"	=> cleanvars($_POST['room_name'])
							,"id_hostel"	=> cleanvars($_POST['id_hostel'])
							,"room_beds"	=> cleanvars($_POST['room_beds'])
							,"room_detail"	=> cleanvars($_POST['room_detail'])
							,"id_campus"	=> cleanvars($id_campus)
						);		
		$sqllms = $dblms->Update(HOSTEL_ROOMS , $values , "WHERE room_id = '".cleanvars($_POST['room_id'])."'");
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			sendRemark("Hostel Room Updated ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: hostelrooms.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD ROOM
if(isset($_GET['room_id'])){
	$values = array (
						 "is_deleted"	=>	'1'
						,"id_deleted"	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"	=>	cleanvars($ip)
						,"date_deleted"	=>	date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(HOSTEL_ROOMS , $values , "WHERE room_id = '".cleanvars($_GET['room_id'])."'");
	if($sqllms){
		sendRemark("Hostel Room Deleted ID: ".$_GET['room_id']." Detail", '3');
		sessionMsg("Success", "Record Deleted.", "success");
		header("Location: hostelrooms.php", true, 301);
		exit();
	}
}
?>