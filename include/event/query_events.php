<?php 
// EVENTS INSERT
if(isset($_POST['submit_event'])) { 
	$id_campus = (!empty($_POST['id_campus']) ? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));

	$sqllmscheck  = $dblms->querylms("SELECT event_to  
										FROM ".EVENTS." 
										WHERE id_campus = '".cleanvars($id_campus)."' 
										AND event_to = '".cleanvars($_POST['event_to'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {		
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: event.php", true, 301);
		exit();
		
	} else { 
		
		$date_from = date('Y-m-d' , strtotime(cleanvars($_POST['date_from'])));
		$date_to   = date('Y-m-d' , strtotime(cleanvars($_POST['date_to'])));

		if($_SESSION['userlogininfo']['LOGINTYPE'] == 1){
			if(isset($_POST['to_campus'])){ $campus = '1';}else{$campus = '2';}
		}
		if(isset($_POST['to_staff'])){ $staff = '1';}else{ $staff = '2';}
		if(isset($_POST['to_parent'])){ $parent = '1';}else{ $parent = '2';}
		if(isset($_POST['to_student'])){ $student = '1';}else{ $student = '2';}

		$sqllms  = $dblms->querylms("INSERT INTO ".EVENTS."(
															status						, 
															id_type						,
															subject						, 
															detail						,
															date_from					,
															date_to						, 
															to_campus					, 
															to_staff					,
															to_parent					, 
															to_student					,
															event_to 					,	
															id_campus 		
														)
													VALUES(
															'".cleanvars($_POST['status'])."'		, 
															'".cleanvars($_POST['id_type'])."'		, 
															'".cleanvars($_POST['subject'])."'		,
															'".cleanvars($_POST['detail'])."'		,
															'".cleanvars($date_from)."'				,
															'".cleanvars($date_to)."'				,
															'".cleanvars($campus)."'				,
															'".cleanvars($staff)."'					,
															'".cleanvars($parent)."'				,
															'".cleanvars($student)."'				,
															'".cleanvars($_POST['event_to'])."'		,
															'".cleanvars($id_campus)."'	
														)"
								);
		if($sqllms) { 

			$remarks = 'Add Event: "'.cleanvars($_POST['subject']).'" detail';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																id_user										, 
																filename									, 
																action										,
																dated										,
																ip											,
																remarks										, 
																id_campus				
															)
			
														VALUES(
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
																'1'											, 
																NOW()										,
																'".cleanvars($ip)."'						,
																'".cleanvars($remarks)."'						,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: event.php", true, 301);
			exit();
		}
	}
} 

// EVENTS UPDATE
if(isset($_POST['changes_event'])) { 
	$id_campus = (!empty($_POST['id_campus']) ? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));

	$date_from = date('Y-m-d' , strtotime(cleanvars($_POST['date_from'])));
	$date_to   = date('Y-m-d' , strtotime(cleanvars($_POST['date_to'])));

	if($_SESSION['userlogininfo']['LOGINTYPE'] == 1){
		if(isset($_POST['to_campus'])){ $campus = '1';}else{$campus = '2';}
	}
	if(isset($_POST['to_staff'])){ $staff = '1';}else{$staff = '2';}
	if(isset($_POST['to_parent'])){ $parent = '1';}else{ $parent = '2';}
	if(isset($_POST['to_student'])){ $student = '1';}else{ $student = '2';}
	
	$sqllms  = $dblms->querylms("UPDATE ".EVENTS." SET  
														status			= '".cleanvars($_POST['status'])."'
													, id_type			= '".cleanvars($_POST['id_type'])."' 
													, subject			= '".cleanvars($_POST['subject'])."' 
													, detail			= '".cleanvars($_POST['detail'])."' 
													, date_from			= '".cleanvars($date_from)."' 
													, date_to			= '".cleanvars($date_to )."' 
													, event_to			= '".cleanvars($_POST['event_to'])."'  
													, to_campus			= '".cleanvars($campus)."' 
													, to_staff			= '".cleanvars($staff)."' 
													, to_parent			= '".cleanvars($parent)."' 
													, to_student		= '".cleanvars($student)."' 
													, id_campus			= '".cleanvars($id_campus)."' 
												WHERE id				= '".cleanvars($_POST['id'])."'");
	if($sqllms) { 
		
		$remarks = 'Update event: "'.cleanvars($_POST['event_name']).'" details';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															id_user										, 
															filename									, 
															action										,
															dated										,
															ip											,
															remarks										, 
															id_campus				
														  )
		
													VALUES(
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
															'2'											, 
															NOW()										,
															'".cleanvars($ip)."'						,
															'".cleanvars($remarks)."'						,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														  )
									");
									
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: event.php", true, 301);
			exit();
			
	}
	
}

//DELETE RECORD
if(isset($_GET['deleteid'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".EVENTS." SET  
														  is_deleted			= '1'
														, id_deleted			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, ip_deleted			= '".$ip."'
														, date_deleted			= NOW()
													 WHERE id    			= '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		$remarks = 'Event Deleted ID: "'.cleanvars($_GET['id']).'" details';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															id_user										, 
															filename									, 
															action										,
															dated										,
															ip											,
															remarks										, 
															id_campus				
															)
		
													VALUES(
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
															'3'											, 
															NOW()										,
															'".cleanvars($ip)."'						,
															'".cleanvars($remarks)."'						,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
									");
			$_SESSION['msg']['title'] 	= 'Warning';
			$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
			$_SESSION['msg']['type'] 	= 'warning';
			header("Location: event.php", true, 301);
			exit();
	}
}