<?php 
// ADD RECORD
if(isset($_POST['submit_teacher'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT adm_username 
										FROM ".ADMINS." 
										WHERE adm_username = '".cleanvars($_POST['adm_username'])."' AND adm_logintype = '3'
										AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: teacherlogin.php", true, 301);
		exit();
	} else { 
		// HASHING=
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $_POST['adm_userpass'];
		$password = hash('sha256', $pass . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
		$id_cmapus = (isset($_POST['id_campus']) && !empty($_POST['id_campus']))? $_POST['id_campus']: $_SESSION['userlogininfo']['LOGINCAMPUS'];
		$sqllms  = $dblms->querylms("INSERT INTO ".ADMINS."(
															adm_status						,  
															adm_logintype					, 
															adm_username					, 
															adm_salt						,
															adm_userpass					,
															adm_fullname					,
															adm_email						, 
															adm_phone						,
															id_dept							,
															id_campus 	
														)
													VALUES(
															'".cleanvars($_POST['adm_status'])."'		, 
															'3'											,
															'".cleanvars($_POST['adm_username'])."'		,
															'".cleanvars($salt)."'						,
															'".cleanvars($password)."'					,
															'".cleanvars($_POST['adm_fullname'])."'		,
															'".cleanvars($_POST['adm_email'])."'		,
															'".cleanvars($_POST['adm_phone'])."'		,
															'".cleanvars($_POST['id_dept'])."'			,
															'".cleanvars($id_cmapus)."'	
														)"
								);

		$adm_id = $dblms->lastestid();	

		// ADD ROLES
		if($sqllms) { 
			$sqllmsemply  = $dblms->querylms("UPDATE ".EMPLOYEES." SET id_loginid = '".(cleanvars($adm_id))."' 
													WHERE emply_id 	  = '".cleanvars($_POST['id_employe'])."'");
			unset($sqllmsemply);
			$remarks = 'Add Teacher Login: "'.cleanvars($_POST['adm_username']).'"';
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
			header("Location: teacherlogin.php", true, 301);
			exit();
		}
	}
} 

// UPDATE RECORD
if(isset($_POST['changes_teacher'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
													  adm_status			= '".cleanvars($_POST['adm_status'])."'
													, adm_type			= '".cleanvars($_POST['adm_type'])."' 
													, adm_username		= '".cleanvars($_POST['adm_username'])."' 
													, adm_fullname		= '".cleanvars($_POST['adm_fullname'])."' 
													, adm_email			= '".cleanvars($_POST['adm_email'])."'  
													, adm_phone			= '".cleanvars($_POST['adm_phone'])."'
												WHERE adm_id			= '".cleanvars($_POST['adm_id'])."'");										  
	if($sqllms) { 
		$remarks = 'Update Admin: "'.cleanvars($_POST['adm_username']).'" details';
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
		header("Location: teacherlogin.php", true, 301);
		exit();
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