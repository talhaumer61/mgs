<?php 
// INSERT RECORD
if(isset($_POST['submit_res'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT res_id
										FROM ".LEARNING_RESOURCES." 
										WHERE id_session	= '".cleanvars($_POST['id_session'])."'
										AND week			= '".cleanvars($_POST['week'])."' 
										AND id_class		= '".cleanvars($_POST['id_class'])."'
										AND id_subject		= '".cleanvars($_POST['id_subject'])."' 
										AND is_deleted		= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck) > 0) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: learning_resources.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("INSERT INTO ".LEARNING_RESOURCES."(
															  res_status 
															, id_class 
															, id_subject
															, week 
															, id_session 
															, note
															, id_added 
															, date_added 	
														)
													VALUES(
															  '".cleanvars($_POST['res_status'])."' 
															, '".cleanvars($_POST['id_class'])."'
															, '".cleanvars($_POST['id_subject'])."'
															, '".cleanvars($_POST['week'])."'
															, '".cleanvars($_POST['id_session'])."'
															, '".cleanvars($_POST['note'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, NOW()
														)
									");			
		$res_id = $dblms->lastestid();
		if(!empty($_FILES['res_file']['name'])) { 
			$path_parts 	= pathinfo($_FILES["res_file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/learning_resources/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.$_POST['week'].'-'.$_POST['id_class'])).'-'.$res_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.$_POST['week'].'-'.$_POST['id_class'])).'-'.$res_id.".".($extension);
			if(in_array($extension , array('pdf','ppt', 'docx'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".LEARNING_RESOURCES."
															SET res_file	= '".$img_fileName."'
															WHERE  res_id	= '".cleanvars($res_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['res_file']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		if(!empty($_FILES['file_thumbnail']['name'])) { 
			$path_parts 	= pathinfo($_FILES["file_thumbnail"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/learning_resources/thumbnail/';
			$originalImage	= $img_dir.to_seo_url('thumb-'.$_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.$_POST['week'].'-'.$_POST['id_class'].'-'.$res_id.".".($extension));
			$img_fileName	= to_seo_url('thumb-'.$_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.$_POST['week'].'-'.$_POST['id_class'].'-'.$res_id.".".($extension));
			if(in_array($extension , array('jpg','png', 'jpeg', 'gif'))){
				$sqllmsupload  = $dblms->querylms("UPDATE ".LEARNING_RESOURCES."
															SET file_thumbnail	= '".$img_fileName."'
															WHERE  res_id		= '".cleanvars($res_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['file_thumbnail']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		if($sqllms){ 
			$remarks = 'Add Lerning Resource: "'.cleanvars($_POST['id_class']).'" "'.cleanvars($_POST['id_subject']).'" detail';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																  id_user 
																, filename 
																, action
																, dated
																, ip
																, remarks				
															)
			
														VALUES(
																  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'
																, '1' 
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: learning_resources.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_res'])){
	$sqllmscheck  = $dblms->querylms("SELECT res_id
										FROM ".LEARNING_RESOURCES." 
										WHERE id_session	= '".cleanvars($_POST['id_session'])."'
										AND week			= '".cleanvars($_POST['week'])."' 
										AND id_class		= '".cleanvars($_POST['id_class'])."'
										AND id_subject		= '".cleanvars($_POST['id_subject'])."' 
										AND is_deleted		= '0'
										AND res_id		   != '".cleanvars($_POST['res_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck) > 0) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: learning_resources.php", true, 301);
		exit();
	}else{ 
		$sqllms  = $dblms->querylms("UPDATE ".LEARNING_RESOURCES." SET  
														  res_status	= '".cleanvars($_POST['res_status'])."'
														, id_term		= '".cleanvars($_POST['id_term'])."'
														, id_session	= '".cleanvars($_POST['id_session'])."'
														, week			= '".cleanvars($_POST['week'])."' 
														, id_class		= '".cleanvars($_POST['id_class'])."'  
														, id_subject	= '".cleanvars($_POST['id_subject'])."'
														, note			= '".cleanvars($_POST['note'])."' 
														, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, date_modify	= NOW()
														  WHERE res_id	= '".cleanvars($_POST['res_id'])."'");
		$res_id = $_POST['res_id'];
		if(!empty($_FILES['res_file']['name'])) {
			$path_parts 	= pathinfo($_FILES["res_file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/learning_resources/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.$_POST['week'].'-'.$_POST['id_class'])).'_'.$res_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.$_POST['week'].'-'.$_POST['id_class'])).'_'.$res_id.".".($extension);
			if(in_array($extension , array('pdf','ppt', 'docx'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".LEARNING_RESOURCES."
																SET res_file	= '".$img_fileName."'
																WHERE res_id	= '".cleanvars($res_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['res_file']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		if(!empty($_FILES['file_thumbnail']['name'])) { 
			$path_parts 	= pathinfo($_FILES["file_thumbnail"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/learning_resources/thumbnail/';
			$originalImage	= $img_dir.to_seo_url('thumb-'.$_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.$_POST['week'].'-'.$_POST['id_class'].'-'.$res_id.".".($extension));
			$img_fileName	= to_seo_url('thumb-'.$_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.$_POST['week'].'-'.$_POST['id_class'].'-'.$res_id.".".($extension));
			if(in_array($extension , array('jpg','png', 'jpeg', 'gif'))){
				$sqllmsupload  = $dblms->querylms("UPDATE ".LEARNING_RESOURCES."
															SET file_thumbnail	= '".$img_fileName."'
															WHERE  res_id		= '".cleanvars($res_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['file_thumbnail']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		if($sqllms){
			$remarks = 'Update Learning Resource: "'.cleanvars($_POST['id_class']).'" details';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																id_user 
																, filename 
																, action
																, dated
																, ip
																, remarks			
															)
			
														VALUES(
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'
																, '2'
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
															)
											");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: learning_resources.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$sqllms  = $dblms->querylms("UPDATE ".LEARNING_RESOURCES." SET  
												  is_deleted		= '1'
												, id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted		= '".$ip."'
												, date_deleted		= NOW()
												  WHERE res_id		= '".cleanvars($_GET['deleteid'])."'");
	if($sqllms){
		$remarks = 'Employee Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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