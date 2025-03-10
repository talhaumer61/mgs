<?php 
// INSERT RECORD
if(isset($_POST['submit_zone'])){
	$sqllmscheck  = $dblms->querylms("SELECT zone_id  
										FROM ".ZONES." 
										WHERE zone_name	= '".cleanvars($_POST['zone_name'])."' 
										AND zone_code	= '".cleanvars($_POST['zone_code'])."'
										AND is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: zone.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("INSERT INTO ".ZONES."(
														  zone_status 
														, zone_code
														, zone_name  
														, zone_ordering
														, id_added
														, date_added
													)
	   											VALUES(
														  '".cleanvars($_POST['zone_status'])."' 
														, '".cleanvars($_POST['zone_code'])."'
														, '".cleanvars($_POST['zone_name'])."'
														, '".cleanvars($_POST['zone_ordering'])."'
														, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, NOW()						
													)"
							);
		if($sqllms){
			$remarks = 'Add Zone: "'.cleanvars($_POST['zone_name']).'" detail';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																id_user 
																, filename 
																, action
																, dated
																, ip
																, remarks 
																, id_campus				
															)		
														VALUES(
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'
																, '1' 
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: zone.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_zone'])){
	$sqllmscheck  = $dblms->querylms("SELECT zone_id  
										FROM ".ZONES." 
										WHERE zone_name	= '".cleanvars($_POST['zone_name'])."' 
										AND zone_code	= '".cleanvars($_POST['zone_code'])."'
										AND is_deleted	= '0'
										AND zone_id	   != '".cleanvars($_POST['zone_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: zone.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("UPDATE ".ZONES." SET  
													  zone_status		= '".cleanvars($_POST['zone_status'])."'
													, zone_code			= '".cleanvars($_POST['zone_code'])."' 
													, zone_name			= '".cleanvars($_POST['zone_name'])."' 
													, zone_ordering		= '".cleanvars($_POST['zone_ordering'])."'
													, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify		= NOW()
   													  WHERE zone_id		= '".cleanvars($_POST['zone_id'])."'");
		if($sqllms){
			$remarks = 'Update Zone: "'.cleanvars($_POST['zone_name']).'" details';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															  id_user 
															, filename 
															, action
															, dated
															, ip
															, remarks 
															, id_campus				
														)		
													VALUES(
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 
															, '2' 
															, NOW()
															, '".cleanvars($ip)."'
															, '".cleanvars($remarks)."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														)
									");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: zone.php", true, 301);
			exit();
		}
	}
}
?>