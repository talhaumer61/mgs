<?php 
// INSERT RECORD
if(isset($_POST['submit_district'])) {
	$sqllmscheck  = $dblms->querylms("SELECT dist_id  
										FROM ".DISTRICTS." 
										WHERE dist_name	= '".cleanvars($_POST['dist_name'])."' 
										AND dist_code	= '".cleanvars($_POST['dist_code'])."'
										AND is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: district.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("INSERT INTO ".DISTRICTS."(
														  dist_status 
														, dist_code
														, dist_name  
														, dist_ordering
														, id_prov
														, id_added
														, date_added
													)
	   											VALUES(
														  '".cleanvars($_POST['dist_status'])."' 
														, '".cleanvars($_POST['dist_code'])."'
														, '".cleanvars($_POST['dist_name'])."'
														, '".cleanvars($_POST['dist_ordering'])."'
														, '".cleanvars($_POST['id_prov'])."'
														, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, NOW()						
													)"
							);
		if($sqllms){
			$remarks = 'Add District: "'.cleanvars($_POST['dist_name']).'" detail';
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
			header("Location: district.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_district'])){	
	$sqllmscheck  = $dblms->querylms("SELECT dist_id  
										FROM ".DISTRICTS." 
										WHERE dist_name	= '".cleanvars($_POST['dist_name'])."' 
										AND dist_code	= '".cleanvars($_POST['dist_code'])."'
										AND is_deleted	= '0'
										AND dist_id	   != '".cleanvars($_POST['dist_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: district.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("UPDATE ".DISTRICTS." SET  
													  dist_status		= '".cleanvars($_POST['dist_status'])."'
													, dist_code			= '".cleanvars($_POST['dist_code'])."' 
													, dist_name			= '".cleanvars($_POST['dist_name'])."' 
													, dist_ordering		= '".cleanvars($_POST['dist_ordering'])."'
													, id_prov			= '".cleanvars($_POST['id_prov'])."' 
													, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify     	= NOW()
													  WHERE dist_id		= '".cleanvars($_POST['dist_id'])."'");
		if($sqllms){
			$remarks = 'Update District: "'.cleanvars($_POST['dist_name']).'" details';
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
			header("Location: district.php", true, 301);
			exit();
		}
	}

}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$sqllms  = $dblms->querylms("UPDATE ".DISTRICTS." SET  
												  is_deleted	=	'1'
												, id_deleted	=	'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted	=	'".$ip."'
												, date_deleted	=	NOW()
												  WHERE dist_id	=	'".cleanvars($_GET['deleteid'])."'");
	if($sqllms) {
		$remarks = 'District Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
															, '3'
															, NOW()
															, '".cleanvars($ip)."'
															, '".cleanvars($remarks)."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														)
									");
		$_SESSION['msg']['title'] 	= 'Warning';
		$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
		$_SESSION['msg']['type'] 	= 'warning';
		header("Location: district.php", true, 301);
		exit();
	}
}
?>