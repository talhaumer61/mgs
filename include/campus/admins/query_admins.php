<?php 
// INSERT RECORD
if(isset($_POST['submit_admin'])){
	$sqllmscheck  = $dblms->querylms("SELECT adm_username 
										FROM ".ADMINS." 
										WHERE id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND adm_username	= '".cleanvars($_POST['adm_username'])."'
										AND is_deleted		= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: admins.php", true, 301);
		exit();
	} else {
		// SUB CAMPUSES		
		$id_subcampus		= array();
		$id_subcampusComma 	= "";
		foreach($_POST['id_subcampus'] as $key => $val):
			if($val != 'all'){
				array_push($id_subcampus, $val);
				$id_subcampusComma = implode(",", $id_subcampus);	
			}	
		endforeach;

		// PASSWORD
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $_POST['adm_userpass'];
		$password = hash('sha256', $pass . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}

		$values = array (
							 "adm_status"		=> cleanvars($_POST['adm_status'])
							,"adm_type"			=> '6'
							,"adm_logintype"	=> '2'
							,"adm_username"		=> cleanvars($_POST['adm_username'])
							,"adm_salt"			=> cleanvars($salt)
							,"adm_userpass"		=> cleanvars($password)
							,"adm_fullname"		=> cleanvars($_POST['adm_fullname'])
							,"adm_email"		=> cleanvars($_POST['adm_email'])
							,"adm_phone"		=> cleanvars($_POST['adm_phone'])
							,"id_subcampus"		=> cleanvars($id_subcampusComma)
							,"id_campus"		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_added'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA']) 
							,'date_added'		=> date('Y-m-d H:i:s')
						);	
		$sqllms  = $dblms->insert(ADMINS, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();

			// UPDATE EMPLOYEE
			$values = array (
								"id_loginid"	=> cleanvars($latestID)
							);	
			$sqlUpdate = $dblms->Update(EMPLOYEES , $values , "WHERE emply_id = '".cleanvars($_POST['id_emply'])."'");
			
			// PROFILE PHOTO UPLOAD
			if(!empty($_FILES['adm_photo']['name'])) { 
				$path_parts 	= pathinfo($_FILES["adm_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir 		= 'uploads/images/admins/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['adm_fullname'].'-'.$_POST['adm_type'])).'_'.$latestID.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_POST['adm_fullname'].'-'.$_POST['adm_type'])).'_'.$latestID.".".($extension);
				if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) {
					$uploadVal = array (
										"adm_photo"		=> cleanvars($img_fileName)
									);	
					$sqllmsupload = $dblms->Update(ADMINS , $uploadVal , "WHERE adm_id = '".cleanvars($latestID)."'");
					unset($sqllmsupload);
					$mode = '0644'; 	
					move_uploaded_file($_FILES['adm_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// INSERT ROLES PERMISSIONS
			$arraychecked = $_POST['right_name'];
			for($ichk=0; $ichk<=sizeof($_POST['right_name']); $ichk++) {
				if(((!empty($_POST['delete'][$ichk])) || (!empty($_POST['edit'][$ichk])) || (!empty($_POST['view'][$ichk])) || (!empty($_POST['add'][$ichk])))){
					$values = array (
										 "right_name"	=> cleanvars($_POST['right_name'][$ichk])
										,"added"		=> cleanvars($_POST['added'][$ichk])
										,"updated"		=> cleanvars($_POST['updated'][$ichk])
										,"deleted"		=> cleanvars($_POST['deleted'][$ichk])
										,"view"			=> cleanvars($_POST['view'][$ichk])
										,"right_type"	=> cleanvars($_POST['right_type'][$ichk])
										,"id_adm"		=> cleanvars($latestID)
									);		
					$sqllms  = $dblms->insert(ADMIN_ROLES, $values);
				}
			}
			sendRemark("Admin Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: admins.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_admin'])) {
	$sqllmscheck  = $dblms->querylms("SELECT adm_username 
										FROM ".ADMINS." 
										WHERE id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND adm_username	= '".cleanvars($_POST['adm_username'])."'
										AND is_deleted		= '0'
										AND adm_id		   != '".cleanvars($_POST['adm_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: admins.php", true, 301);
		exit();
	} else {
		// SUB CAMPUSES		
		$id_subcampus		= array();
		$id_subcampusComma 	= "";
		foreach($_POST['id_subcampus'] as $key => $val):
			if($val != 'all'){
				array_push($id_subcampus, $val);
				$id_subcampusComma = implode(",", $id_subcampus);	
			}	
		endforeach;

		$values = array (
							 "adm_status"		=> cleanvars($_POST['adm_status'])
							,"adm_username"		=> cleanvars($_POST['adm_username'])
							,"adm_fullname"		=> cleanvars($_POST['adm_fullname'])
							,"adm_email"		=> cleanvars($_POST['adm_email'])
							,"adm_phone"		=> cleanvars($_POST['adm_phone'])
							,"id_subcampus"		=> cleanvars($id_subcampusComma)
							,"id_campus"		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,'id_modify'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA']) 
							,'date_modify'		=> date('Y-m-d H:i:s')
						);
		$sqlUpdate = $dblms->Update(ADMINS , $values , "WHERE adm_id = '".cleanvars($_POST['adm_id'])."'");

		if($sqllms) { 
			$latestID = $_POST['adm_id'];

			// PASSWORD
			if(!empty($_POST['adm_userpass'])){
				$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
				$pass = $_POST['adm_userpass'];
				$password = hash('sha256', $pass . $salt);
				for ($round = 0; $round < 65536; $round++) {
					$password = hash('sha256', $password . $salt);
				}
				$passVal = array (
										 "adm_salt"			=> cleanvars($salt)
										,"adm_userpass"		=> cleanvars($password)
									);	
				$sqlPass = $dblms->Update(ADMINS , $passVal , "WHERE adm_id = '".cleanvars($latestID)."'");
			}
			
			// PROFILE PHOTO UPLOAD
			if(!empty($_FILES['adm_photo']['name'])) { 
				$path_parts 	= pathinfo($_FILES["adm_photo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir 		= 'uploads/images/admins/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['adm_fullname'].'-'.$_POST['adm_type'])).'_'.$latestID.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_POST['adm_fullname'].'-'.$_POST['adm_type'])).'_'.$latestID.".".($extension);
				if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) {
					$uploadVal = array (
										"adm_photo"		=> cleanvars($img_fileName)
									);	
					$sqllmsupload = $dblms->Update(ADMINS , $uploadVal , "WHERE adm_id = '".cleanvars($latestID)."'");
					unset($sqllmsupload);
					$mode = '0644'; 	
					move_uploaded_file($_FILES['adm_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			
			// DELETE OLD ROLES
			$sqllms  = $dblms->querylms("DELETE FROM ".ADMIN_ROLES." WHERE id_adm = '".cleanvars($latestID)."'");

			// INSERT ROLES PERMISSIONS
			$arraychecked = $_POST['right_name'];
			for($ichk=0; $ichk<=sizeof($_POST['right_name']); $ichk++) {
				if(((!empty($_POST['delete'][$ichk])) || (!empty($_POST['edit'][$ichk])) || (!empty($_POST['view'][$ichk])) || (!empty($_POST['add'][$ichk])))){
					$values = array (
										 "right_name"	=> cleanvars($_POST['right_name'][$ichk])
										,"added"		=> cleanvars($_POST['added'][$ichk])
										,"updated"		=> cleanvars($_POST['updated'][$ichk])
										,"deleted"		=> cleanvars($_POST['deleted'][$ichk])
										,"view"			=> cleanvars($_POST['view'][$ichk])
										,"right_type"	=> cleanvars($_POST['right_type'][$ichk])
										,"id_adm"		=> cleanvars($latestID)
									);		
					$sqllms  = $dblms->insert(ADMIN_ROLES, $values);
				}
			}
			sendRemark("Admin Updated ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: admins.php", true, 301);
			exit();
		}
	}



	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
													adm_status			= '".cleanvars($_POST['adm_status'])."'
												  , adm_type			= '".cleanvars($_POST['adm_type'])."' 
												  , adm_username		= '".cleanvars($_POST['adm_username'])."' 
												  , adm_fullname		= '".cleanvars($_POST['adm_fullname'])."' 
												  , adm_email			= '".cleanvars($_POST['adm_email'])."'  
												  , adm_phone			= '".cleanvars($_POST['adm_phone'])."'
												  , id_campus			= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
   											  WHERE adm_id			= '".cleanvars($_POST['adm_id'])."'");
	
	$adm_id = cleanvars($_POST['adm_id']);
	if(!empty($_FILES['adm_photo']['name'])) { 
		$path_parts 	= pathinfo($_FILES["adm_photo"]["name"]);
		$extension 		= strtolower($path_parts['extension']);
		$img_dir 	= 'uploads/images/admins/';
		$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['adm_fullname'].'-'.$_POST['adm_type'])).'_'.$adm_id.".".($extension);
		$img_fileName	= to_seo_url(cleanvars($_POST['adm_fullname'].'-'.$_POST['adm_type'])).'_'.$adm_id.".".($extension);
		if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 
			$sqllmsupload  = $dblms->querylms("UPDATE ".ADMINS."
															SET adm_photo = '".$img_fileName."'
													 WHERE  adm_id		  = '".cleanvars($adm_id)."'");
			unset($sqllmsupload);
			$mode = '0644'; 	
			move_uploaded_file($_FILES['adm_photo']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
		}
	}

	// DELETE ROLES
	$sqllms  = $dblms->querylms("DELETE FROM ".ADMIN_ROLES." WHERE id_adm= '".cleanvars($adm_id)."'");
								
	$arraychecked = $_POST['right_name'];
	for($ichk=0; $ichk<=sizeof($_POST['right_name']); $ichk++) {
		if(((!empty($_POST['delete'][$ichk])) || (!empty($_POST['edit'][$ichk])) || (!empty($_POST['view'][$ichk])) || (!empty($_POST['add'][$ichk])))) {				
			$sqllms  = $dblms->querylms("INSERT INTO ".ADMIN_ROLES."(
														right_name				, 
														added					, 
														updated					, 
														deleted					,
														view					, 
														right_type				,
														id_adm					
													  )
	   											VALUES(
														'".cleanvars($_POST['right_name'][$ichk])."'		, 
														'".cleanvars($_POST['added'][$ichk])."'				,
														'".cleanvars($_POST['updated'][$ichk])."'			,
														'".cleanvars($_POST['deleted'][$ichk])."'			,
														'".cleanvars($_POST['view'][$ichk])."'				,
														'".cleanvars($_POST['right_type'][$ichk])."'		,
														'".cleanvars($adm_id)."'				
													  )"
							);
		}
	}
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
		header("Location: admins.php", true, 301);
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