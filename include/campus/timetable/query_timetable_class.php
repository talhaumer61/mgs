<?php 
// INSERT RECORD
if(isset($_POST['submit_timetable'])) { 
	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

	$sqllmscheck  = $dblms->querylms("SELECT id_session, id_class, id_section 
										FROM ".TIMETABLE." 
										WHERE id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
										AND id_class	= '".cleanvars($_POST['class'])."' 
										AND id_section	= '".cleanvars($_POST['section'])."'
										AND is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	}else{
		$id_offdayValCommaSep = '';
		$offdayArray = array();
		foreach($_POST['id_offdayVal'] as $key => $val):
			if (isset($_POST['id_offday'][$key])):
				array_push($offdayArray, $val);
				$id_offdayValCommaSep = implode(",", $offdayArray);
			endif;
		endforeach;
		$sqllms  = $dblms->querylms("INSERT INTO ".TIMETABLE."(
														  status 
														, id_session 
														, id_class 
														, id_section
														, id_offday
														, id_campus
														, id_added
														, date_added						
													  )
	   											VALUES(
														  '".cleanvars($_POST['status'])."'
														, '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
														, '".cleanvars($_POST['id_class'])."'
														, '".cleanvars($_POST['id_section'])."'
														, '".cleanvars($id_offdayValCommaSep)."'	
														, '".cleanvars($id_campus)."'
														, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, NOW()				
													  )"
							);			
		if($sqllms) { 	
			$latestID = $dblms->lastestid();

			for($i=0; $i<=sizeof($_POST['id_subject']); $i++){
				$ij++;
				$kij = $_POST['day'][$i];
				if(($_POST['day'][$i] != $_POST['id_offday'][$kij]) && !empty($_POST['day'][$i]) && !empty($_POST['id_subject'][$i]) && !empty($_POST['id_period'][$i])){
					$sqllmsdetail  = $dblms->querylms("INSERT INTO ".TIMETABEL_DETAIL."(
																					id_setup 
																					, day 
																					, id_subject 
																					, id_room
																					, id_period
																					, id_teacher						
																				)
																			VALUES(
																					'".cleanvars($latestID)."' 
																					, '".cleanvars($_POST['day'][$i])."'
																					, '".cleanvars($_POST['id_subject'][$i])."'
																					, '".cleanvars($_POST['id_room'][$i])."'
																					, '".cleanvars($_POST['id_period'][$i])."'
																					, '".cleanvars($_POST['id_emply'][$i])."'
																				)
														");
				}
			}
			
			$latestID = $dblms->lastestid();
			sendRemark("Timetable Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['change_timetable'])) {
	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

	$id_offdayValCommaSep = '';
	$offdayArray = array();
	foreach($_POST['id_offdayVal'] as $key => $val):
		if (isset($_POST['id_offday'][$key])):
			array_push($offdayArray, $val);
			$id_offdayValCommaSep = implode(",", $offdayArray);
		endif;
	endforeach;
	$sqllms  = $dblms->querylms("UPDATE ".TIMETABLE." SET  
													  status			= '".cleanvars($_POST['status'])."'
													, id_offday			= '".cleanvars($id_offdayValCommaSep)."'
													, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify		=  NOW()
													  WHERE id			= '".cleanvars($_POST['id'])."'");
	// REMARKS AND DETAIL
	if($sqllms) {		
		$latestID = $_POST['id'];

		// DELETE OLD DETAIL
		$sqllmsdelte  = $dblms->querylms("DELETE FROM ".TIMETABEL_DETAIL." WHERE id_setup = '".$latestID."'");

		// INSERT NEW DETAIL
		for($i=0; $i<=sizeof($_POST['id_subject']); $i++){
			$kij = $_POST['day'][$i];
			if(($_POST['day'][$i] != $_POST['id_offday'][$kij]) && !empty($_POST['day'][$i]) && !empty($_POST['id_subject'][$i]) && !empty($_POST['id_period'][$i])){
		
				$sqllmsdetail  = $dblms->querylms("INSERT INTO ".TIMETABEL_DETAIL."(
																					  id_setup 
																					, day 
																					, id_subject 
																					, id_room
																					, id_period
																					, id_teacher						
																				)
																			VALUES(
																					  '".cleanvars($latestID)."'
																					, '".cleanvars($_POST['day'][$i])."'
																					, '".cleanvars($_POST['id_subject'][$i])."'
																					, '".cleanvars($_POST['id_room'][$i])."'
																					, '".cleanvars($_POST['id_period'][$i])."'
																					, '".cleanvars($_POST['id_emply'][$i])."'
																				)
												");
			}
		}
		
		sendRemark("Timetable Updated ID: ".$latestID." Detail", '2');
		sessionMsg("Success", "Record Successfully Updated.", "info");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
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
	$sqllms = $dblms->Update(TIMETABLE , $values , "WHERE id = '".cleanvars($_GET['deleteid'])."'");
	if($sqllms){
		sendRemark("Timetable Period Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Success", "Record Deleted.", "warning");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	}
}
?>