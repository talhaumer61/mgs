<?php 
// INSERT RECORD
if(isset($_POST['submit_city'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT city_id  
										FROM ".TEHSIL_CITIES." 
										WHERE city_name	= '".cleanvars($_POST['city_name'])."' 
										AND city_code	= '".cleanvars($_POST['city_code'])."'
										and is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: city.php", true, 301);
		exit();
	}else{
		// EXPLODE ARRAY
		$values = explode("|",$_POST['id_values']);
		$dist = $values[0];
		$prov = $values[1];

		$sqllms  = $dblms->querylms("INSERT INTO ".TEHSIL_CITIES."(
														  city_status 
														, city_code
														, city_name  
														, city_ordering
														, id_dist
														, id_prov
														, id_added
														, date_added
													)
	   											VALUES(
														  '".cleanvars($_POST['city_status'])."' 
														, '".cleanvars($_POST['city_code'])."'
														, '".cleanvars($_POST['city_name'])."'
														, '".cleanvars($_POST['city_ordering'])."'
														, '".cleanvars($dist)."'
														, '".cleanvars($prov)."'
														, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, NOW()						
													)"
							);
		if($sqllms) { 
			$remarks = 'Add City: "'.cleanvars($_POST['city_name']).'" detail';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																id_user 
																,filename 
																,action
																,dated
																,ip
																,remarks 
																,id_campus				
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
			header("Location: city.php", true, 301);
			exit();
		}
	}
} 

// UPDATE RECORD
if(isset($_POST['changes_city'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT city_id  
										FROM ".TEHSIL_CITIES." 
										WHERE city_name	= '".cleanvars($_POST['city_name'])."' 
										AND city_code	= '".cleanvars($_POST['city_code'])."'
										AND is_deleted	= '0'
										AND city_id	   != '".cleanvars($_POST['city_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: city.php", true, 301);
		exit();
	}else{
		// EXPLODE ARRAY
		$values = explode("|",$_POST['id_values']);
		$dist = $values[0];
		$prov = $values[1];
		
		$sqllms  = $dblms->querylms("UPDATE ".TEHSIL_CITIES." SET  
														  city_status		= '".cleanvars($_POST['city_status'])."'
														, city_code			= '".cleanvars($_POST['city_code'])."' 
														, city_name			= '".cleanvars($_POST['city_name'])."' 
														, city_ordering		= '".cleanvars($_POST['city_ordering'])."' 
														, id_dist			= '".cleanvars($dist)."' 
														, id_prov			= '".cleanvars($prov)."' 
														, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, date_modify       = NOW()
														  WHERE city_id		= '".cleanvars($_POST['city_id'])."'");
		if($sqllms) {
			$remarks = 'Update City: "'.cleanvars($_POST['city_name']).'" details';
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
			header("Location: city.php", true, 301);
			exit();
		}
	}
}
?>