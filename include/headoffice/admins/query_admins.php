<?php 
// INSERT RECORD
if(isset($_POST['submit_admin'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT adm_username, id_campus 
										FROM ".ADMINS." 
										WHERE id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND adm_username	= '".cleanvars($_POST['adm_fullname'])."'
										AND is_deleted		= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: admins.php", true, 301);
		exit();
	}else{
		// HASHING
		$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
		$pass = $_POST['adm_userpass'];
		$password = hash('sha256', $pass . $salt);
		for ($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
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
														)VALUES(
															  '".cleanvars($_POST['adm_status'])."' 
															, '".cleanvars($_POST['adm_type'])."'
															, '1'
															, '".cleanvars($_POST['adm_username'])."'
															, '".cleanvars($salt)."'
															, '".cleanvars($password)."'
															, '".cleanvars($_POST['adm_fullname'])."'
															, '".cleanvars($_POST['adm_email'])."'
															, '".cleanvars($_POST['adm_phone'])."'
														)
										");
		// LATEST ID
		$adm_id = $dblms->lastestid();

		// IMAGE UPLOAD
		if(!empty($_FILES['adm_photo']['name'])) { 
			$path_parts 	= pathinfo($_FILES["adm_photo"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/images/admins/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['adm_fullname'].'-'.$_POST['adm_type'])).'_'.$adm_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['adm_fullname'].'-'.$_POST['adm_type'])).'_'.$adm_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".ADMINS."
															SET adm_photo	= '".$img_fileName."'
															WHERE  adm_id	= '".cleanvars($adm_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['adm_photo']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		// ADD ROLES
		$arraychecked = $_POST['right_name'];
		//	 foreach($_POST['right_name'] as $key => $value){
		for($ichk=0; $ichk<=sizeof($_POST['right_name']); $ichk++) {
			if(((!empty($_POST['delete'][$ichk])) || (!empty($_POST['edit'][$ichk])) || (!empty($_POST['view'][$ichk])) || (!empty($_POST['add'][$ichk])))){				
				$sqllms  = $dblms->querylms("INSERT INTO ".ADMIN_ROLES."(
																			  right_name 
																			, added 
																			, updated 
																			, deleted
																			, reporting
																			, view 
																			, right_type
																			, id_adm					
																		)VALUES(
																			  '".cleanvars($_POST['right_name'][$ichk])."' 
																			, '".cleanvars($_POST['added'][$ichk])."'
																			, '".cleanvars($_POST['updated'][$ichk])."'
																			, '".cleanvars($_POST['deleted'][$ichk])."'
																			, '".cleanvars($_POST['reporting'][$ichk])."'
																			, '".cleanvars($_POST['view'][$ichk])."'
																			, '".cleanvars($_POST['right_type'][$ichk])."'
																			, '".cleanvars($adm_id)."'				
																		)
											");
			}
		}
		// REAMARKS
		if($sqllms){ 
			$remarks = 'Add Admin: "'.cleanvars($_POST['adm_username']).'" detail';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																	  id_user 
																	, filename 
																	, action
																	, dated
																	, ip
																	, remarks			
																)VALUES(
																	  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																	, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'
																	, '1'
																	, NOW()
																	, '".cleanvars($ip)."'
																	, '".cleanvars($remarks)."'
																)");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: admins.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_admin'])) { 
	
	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
															adm_status			= '".cleanvars($_POST['adm_status'])."'
															, adm_type			= '".cleanvars($_POST['adm_type'])."' 
															, adm_email			= '".cleanvars($_POST['adm_email'])."'  
															, adm_phone			= '".cleanvars($_POST['adm_phone'])."'
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
								
	// ADD ROLES
	$arraychecked = $_POST['right_name'];
	//	 foreach($_POST['right_name'] as $key => $value){
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
																	) VALUES (
																		'".cleanvars($_POST['right_name'][$ichk])."'		, 
																		'".cleanvars($_POST['added'][$ichk])."'				,
																		'".cleanvars($_POST['updated'][$ichk])."'			,
																		'".cleanvars($_POST['deleted'][$ichk])."'			,
																		'".cleanvars($_POST['view'][$ichk])."'				,
																		'".cleanvars($_POST['right_type'][$ichk])."'		,
																		'".cleanvars($adm_id)."'				
																	)");
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
																remarks			
															) VALUES (
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
																'2'											, 
																NOW()										,
																'".cleanvars($ip)."'						,
																'".cleanvars($remarks)."'	
															)");
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: admins.php", true, 301);
		exit();
	}
}

// CHANFE PASSWORD
if(isset($_POST['update_pass'])) {
    // HASHING
    $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
    $pass = cleanvars($_POST['adm_pass']);
    $password = hash('sha256', $pass . $salt);
    for ($round = 0; $round < 65536; $round++) {
        $password = hash('sha256', $password . $salt);
    }
    $sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET
																		adm_salt      =  '".cleanvars($salt)."'
																	, adm_userpass  = '".cleanvars($password)."'
																	, id_modify     = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																	, date_modify   = Now()
																	WHERE adm_id        = '".cleanvars($_POST['adm_id'])."' ");
    if($sqllms) {
        $remarks = 'Change Admin(HeadOffice) Password ID#:"'.cleanvars($_POST['adm_id']).'" details';
        $sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																id_user                                     ,
																filename                                    ,
																action                                      ,
																dated                                       ,
																ip                                          ,
																remarks                                     ,
																id_campus              
															) VALUES (
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' ,
																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' ,
																'2'                                         ,
																NOW()                                       ,
																'".cleanvars($ip)."'                        ,
																'".cleanvars($remarks)."'                       ,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'          
															) ");
        $_SESSION['msg']['title']   = 'Successfully';
        $_SESSION['msg']['text']    = 'Record Successfully Updated.';
        $_SESSION['msg']['type']    = 'info';
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