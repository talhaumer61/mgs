<?php 
// INSERT RECORD
if(isset($_POST['makeLogin'])){
	$sqllmscheck  = $dblms->querylms("SELECT adm_username
										FROM ".ADMINS." 
										WHERE adm_username	= '".cleanvars($_POST['adm_username'])."'
										AND is_deleted		= '0'
										AND id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: addeLogin.php", true, 301);
		exit();
	}else{
		//------------hashing---------------
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $_POST['adm_userpass'];
		$password = hash('sha256', $pass . $salt);
		for($round = 0; $round < 65536; $round++){
			$password = hash('sha256', $password . $salt);
		}
		//------------hashing---------------
		$sqllms  = $dblms->querylms("INSERT INTO ".ADMINS."(
															  adm_status 
															, adm_type 
															, adm_logintype 
															, adm_username 
															, adm_salt
															, adm_userpass
															, adm_fullname
															, adm_email 
															, adm_phone
															, id_campus 	
														)VALUES(
															  '".cleanvars($_POST['adm_status'])."' 
															, '6'
															, '6'
															, '".cleanvars($_POST['adm_username'])."'
															, '".cleanvars($salt)."'
															, '".cleanvars($password)."'
															, '".cleanvars($_POST['adm_fullname'])."'
															, '".cleanvars($_POST['adm_email'])."'
															, '".cleanvars($_POST['adm_phone'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														)
									");
		//Latest ID
		$adm_id = $dblms->lastestid();

		// REMARKS
		if($sqllms){
			// Update LoginId In Employee table
			$sqllmsemply  = $dblms->querylms("UPDATE ".EMPLOYEES." SET 
															id_loginid		= '".cleanvars($adm_id)."' 
															WHERE emply_id	= '".cleanvars($_POST['id_employee'])."'
											");
			// On the basis of type get the Employees
			if($_POST['id_type'] == 1){
				$type = "AD";
			}elseif($_POST['id_type'] == 2){
				$type = "DE";
			}else{
				$type = "AD / DE";
			}

			$remarks = 'Add '.$type.' account, LoginID: "'.$adm_id.'"';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																	  id_user 
																	, filename 
																	, action
																	, dated
																	, ip
																	, remarks 
																	, id_campus				
																)VALUES(
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
			header("Location: addeLogin.php", true, 301);
			exit();
		}
	}
} 

// UPDATE RECORD
if(isset($_POST['updateLogin'])){
	$sqllmscheck  = $dblms->querylms("SELECT adm_username
										FROM ".ADMINS." 
										WHERE adm_username	= '".cleanvars($_POST['adm_username'])."'
										AND is_deleted		= '0'
										AND id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND adm_id		   != '".cleanvars($_POST['adm_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: addeLogin.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
														  adm_status	= '".cleanvars($_POST['adm_status'])."'
														, adm_email		= '".cleanvars($_POST['adm_email'])."'  
														, adm_phone		= '".cleanvars($_POST['adm_phone'])."'
														  WHERE adm_id	= '".cleanvars($_POST['adm_id'])."'
									");
		// REMARKS
		if($sqllms){
			$remarks = 'Update AD/DE Account, LoginID: "'.cleanvars($_POST['adm_id']).'"';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																	  id_user 
																	, filename 
																	, action
																	, dated
																	, ip
																	, remarks 
																	, id_campus				
																)VALUES(
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
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: addeLogin.php", true, 301);
			exit();
		}
	}
}

// UPDATE PASSWORD
if(isset($_POST['update_pass'])){
	//------------hashing---------------
	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
	$pass = $_POST['adm_userpass'];
	$password = hash('sha256', $pass . $salt);
	for ($round = 0; $round < 65536; $round++) {
		$password = hash('sha256', $password . $salt);
	}
	//------------hashing---------------

	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
												  adm_salt		= '".cleanvars($salt)."' 
												, adm_userpass	= '".cleanvars($password)."'
												, id_modify     = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, date_modify   = Now()
												  WHERE adm_id	= '".cleanvars($_POST['adm_id'])."'
								");
	// REMARKS
	if($sqllms){
		$remarks = 'Update AD/DE Password, LoginID: "'.cleanvars($_POST['adm_id']).'"';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																  id_user 
																, filename 
																, action
																, dated
																, ip
																, remarks 
																, id_campus				
															)VALUES(
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
		$_SESSION['msg']['type'] 	= 'info';
		header("Location: addeLogin.php", true, 301);
		exit();
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
												  is_deleted		= '1'
												, id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted		= '".$ip."'
												, date_deleted		= NOW()
												  WHERE adm_id		= '".cleanvars($_GET['deleteid'])."'");
	if($sqllms){
		$remarks = 'AD/DE Account Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		$requestedPage = strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php';
		$_SESSION['msg']['title'] 	= 'Warning';
		$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
		$_SESSION['msg']['type'] 	= 'warning';		
		header("Location: $requestedPage", true, 301);
		exit();
	}
}
?>