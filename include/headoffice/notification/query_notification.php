<?php 
// NOTIFICATION INSERT
if(isset($_POST['submit_notification'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT not_title  
										FROM ".NOTIFICATIONS." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND not_title = '".cleanvars($_POST['not_title'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: notifications.php", true, 301);
		exit();
		
	} else { 
				
		$dated = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));

		if($_SESSION['userlogininfo']['LOGINTYPE'] == 1){
			if(isset($_POST['to_campus'])){ $campus = '1';}else{$campus = '2';}
		}
		if(isset($_POST['to_staff'])){ $staff = '1';}else{ $staff = '2';}
		if(isset($_POST['to_parent'])){ $parent = '1';}else{ $parent = '2';}
		if(isset($_POST['to_student'])){ $student = '1';}else{ $student = '2';}

		$sqllms  = $dblms->querylms("INSERT INTO ".NOTIFICATIONS."(
															not_status					, 
															id_type						,
															not_title					,	
															dated						,
															date_from					,
															date_to						,
															not_description				,
															to_campus					, 
															to_staff					,
															to_parent					, 
															to_student					,
															id_session					,
															id_campus 					,
															id_added					,
															date_added
														)
													VALUES(
															'".cleanvars($_POST['not_status'])."'							, 
															'".cleanvars($_POST['id_type'])."'								, 
															'".cleanvars($_POST['not_title'])."'							,
															'".cleanvars($dated)."'											,
															'".date('Y-m-d' , strtotime($_POST['date_from']))."'			,
															'".date('Y-m-d' , strtotime($_POST['date_to']))."'				,
															'".cleanvars($_POST['not_description'])."'						,
															'".cleanvars($campus)."'										,
															'".cleanvars($staff)."'											,
															'".cleanvars($parent)."'										,
															'".cleanvars($student)."'										,
															'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
															Now()														
														)"
								);
							
		if($sqllms) { 
			
			$remarks = 'Add Notification: "'.cleanvars($_POST['not_title']).'" detail';
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
			header("Location: notifications.php", true, 301);
			exit();
			
		}
	
	} 
	
} 

// NOTIFICATION UPDATE
if(isset($_POST['changes_notification'])) { 
	
	$dated = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
	if($_SESSION['userlogininfo']['LOGINTYPE'] == 1){
		if(isset($_POST['to_campus'])){ $campus = '1';}else{$campus = '2';}
	}
	if(isset($_POST['to_staff'])){ $staff = '1';}else{$staff = '2';}
	if(isset($_POST['to_parent'])){ $parent = '1';}else{ $parent = '2';}
	if(isset($_POST['to_student'])){ $student = '1';}else{ $student = '2';}

	$sqllms  = $dblms->querylms("UPDATE ".NOTIFICATIONS." SET  
														  not_status		= '".cleanvars($_POST['not_status'])."'
														, id_type			= '".cleanvars($_POST['id_type'])."' 
														, not_title			= '".cleanvars($_POST['not_title'])."' 
														, dated				= '".cleanvars($dated)."' 
														, date_from			= '".date('Y-m-d' , strtotime($_POST['date_from']))."' 
														, date_to			= '".date('Y-m-d' , strtotime($_POST['date_to']))."' 
														, not_description	= '".cleanvars($_POST['not_description'])."'
														, to_campus			= '".cleanvars($campus)."' 
														, to_staff			= '".cleanvars($staff)."' 
														, to_parent			= '".cleanvars($parent)."' 
														, to_student		= '".cleanvars($student)."' 
														, id_session		= '".cleanvars($_POST['id_session'])."' 
														, id_campus			= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
														, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		
														, date_modify		= Now()
													WHERE not_id = '".cleanvars($_POST['not_id'])."'");

	if($sqllms) { 
		
		$remarks = 'Update Notification: "'.cleanvars($_POST['title']).'" details';
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
		header("Location: notifications.php", true, 301);
		exit();	
	}
}

//DELETE RECORD
if(isset($_GET['deleteid'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".NOTIFICATIONS." SET  
														  is_deleted			= '1'
														, id_deleted			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, ip_deleted			= '".$ip."'
														, date_deleted			= NOW()
													 WHERE not_id    			= '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		$remarks = 'Notification Deleted ID: "'.cleanvars($_GET['id']).'" details';
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
			header("Location: notifications.php", true, 301);
			exit();
	}
}
?>