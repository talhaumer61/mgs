<?php 


// EVENTS UPDATE
if(isset($_POST['approve_announcement'])) { 
	$id_campus = (!empty($_POST['id_campus']) ? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));
	
	$sqllms  = $dblms->querylms("UPDATE ".ANNOUNCEMENT." SET  
														ann_status			= '1'
														WHERE ann_id		= '".cleanvars($_POST['ann_id'])."' AND 
														id_campus			= '".cleanvars($id_campus)."'");
	if($sqllms) { 
		
		$remarks = 'Approved Annoucment: "'.cleanvars($_POST['ann_id']).'"';
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
			header("Location: campus_announcements.php", true, 301);
			exit();
			
	}
	
}

//DELETE RECORD
if(isset($_GET['deleteid'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".ANNOUNCEMENT." SET  
														  is_deleted			= '1'
														, id_deleted			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, ip_deleted			= '".$ip."'
														, date_deleted			= NOW()
													 WHERE ann_id    			= '".cleanvars($_GET['deleteid'])."'");
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
			header("Location: campus_announcements.php", true, 301);
			exit();
	}
}