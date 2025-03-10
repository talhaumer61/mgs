<?php 
// INSERT RECORD
if(isset($_POST['submit_parent'])){
	$sqllmscheck  = $dblms->querylms("SELECT adm_username 
										FROM ".ADMINS." 
										WHERE adm_username = '".cleanvars($_POST['adm_username'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: parentlogin.php", true, 301);
		exit();
	}else{
		// EXPLODE ARRAY
		// $aray		=	explode('|', $_POST['id_std']);
		$id_std     =	$_POST['std_familyno'];

		// HASHING
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $_POST['adm_userpass'];
		$password = hash('sha256', $pass . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
		$sqllms  = $dblms->querylms("INSERT INTO ".ADMINS."(
														  adm_status  
														, adm_logintype 
														, adm_username 
														, adm_salt
														, adm_userpass
														, adm_fullname
														, adm_phone
														, id_campus 	
													)
	   											VALUES(
														  '".cleanvars($_POST['adm_status'])."' 
														, '4'
														, '".cleanvars($_POST['adm_username'])."'
														, '".cleanvars($salt)."'
														, '".cleanvars($password)."'
														, '".cleanvars($_POST['adm_fullname'])."'
														, '".cleanvars($_POST['adm_phone'])."'
														, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	
													)"
								);
		// LATEST ID
		$adm_id = $dblms->lastestid();
		// REMARKS
		if($sqllms){
			$sqllmsemply  = $dblms->querylms("UPDATE ".STUDENTS."
												SET id_loginid = '".(cleanvars($adm_id))."' 
												WHERE std_familyno = '".cleanvars($id_std)."'
											");
			unset($sqllmsemply);
			
			$remarks = 'Add Parent Login: "'.cleanvars($_POST['adm_username']).'"';
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
			header("Location: parentlogin.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_parent'])){
	$sqllmscheck  = $dblms->querylms("SELECT adm_username 
										FROM ".ADMINS." 
										WHERE adm_username = '".cleanvars($_POST['adm_username'])."'
										AND adm_id		  != '".cleanvars($_POST['adm_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: parentlogin.php", true, 301);
		exit();
	}else{
		// HASHING
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $_POST['adm_userpass'];
		$password = hash('sha256', $pass . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}

		$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
													  adm_status		= '".cleanvars($_POST['adm_status'])."'
													, adm_username		= '".cleanvars($_POST['adm_username'])."' 
													, adm_fullname		= '".cleanvars($_POST['adm_fullname'])."'
													, adm_phone			= '".cleanvars($_POST['adm_phone'])."'
													, adm_salt			= '".cleanvars($salt)."'
													, adm_userpass		= '".cleanvars($password)."'
													  WHERE adm_id		= '".cleanvars($_POST['adm_id'])."'");
		if($sqllms){
			$remarks = 'Update Admin: "'.cleanvars($_POST['adm_username']).'"';
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
			header("Location: parentlogin.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
												  is_deleted	=	'1'
												, id_deleted	=	'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted	=	'".$ip."'
												, date_deleted	=	NOW()
												  WHERE adm_id	=	'".cleanvars($_GET['deleteid'])."'");
	if($sqllms) {
		$remarks = 'Campus Login Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: campuslogin.php", true, 301);
		exit();
	}
}
?>