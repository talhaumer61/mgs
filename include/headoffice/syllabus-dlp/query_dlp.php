<?php 
// INSERT RECORD
if(isset($_POST['submit_syllabus'])){
	$sqllmscheck  = $dblms->querylms("SELECT syllabus_term, id_session, id_class, id_subject  
										FROM ".SYLLABUS." 
										WHERE syllabus_type	= '2'
										AND syllabus_term	= '".cleanvars($_POST['syllabus_term'])."' 
										AND id_session		= '".cleanvars($_POST['id_session'])."' 
										AND id_month		= '".cleanvars($_POST['id_month'])."'
										AND id_week			= '".cleanvars($_POST['id_week'])."'
										AND id_class		= '".cleanvars($_POST['id_class'])."'
										AND id_subject		= '".cleanvars($_POST['id_subject'])."'
										AND is_deleted		= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: syllabus_dlp.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("INSERT INTO ".SYLLABUS."(
															  syllabus_status 
															, syllabus_type 
															, syllabus_term 
															, id_session 
															, id_month 
															, id_week 
															, id_class 
															, id_subject
															, note
															, id_added 
															, date_added 	
														)
													VALUES(
															  '".cleanvars($_POST['syllabus_status'])."' 
															, '2'
															, '".cleanvars($_POST['syllabus_term'])."' 
															, '".cleanvars($_POST['id_session'])."'
															, '".cleanvars($_POST['id_month'])."'
															, '".cleanvars($_POST['id_week'])."'
															, '".cleanvars($_POST['id_class'])."'
															, '".cleanvars($_POST['id_subject'])."'
															, '".cleanvars($_POST['note'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, NOW()
														)"
								);
								
		$syllabus_id = $dblms->lastestid();
		
		// FILE UPLOAD
		if(!empty($_FILES['syllabus_file']['name'])){
			$path_parts 	= pathinfo($_FILES["syllabus_file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/dlp/';
			$originalImage	= $img_dir.to_seo_url($_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.get_week($_POST['id_week']).'-'.$_POST['id_class'].'-'.$syllabus_id).".".($extension);
			$img_fileName	= to_seo_url($_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.get_week($_POST['id_week']).'-'.$_POST['id_class'].'-'.$syllabus_id).".".($extension);
			
			if(in_array($extension , array('pdf','ppt', 'docx'))){
				$sqllmsupload  = $dblms->querylms("UPDATE ".SYLLABUS."
															SET syllabus_file	= '".$img_fileName."'
															WHERE  syllabus_id	= '".cleanvars($syllabus_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['syllabus_file']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		// FILE THUMBNAIL UPLOAD
		if(!empty($_FILES['file_thumbnail']['name'])){
			$path_parts 	= pathinfo($_FILES["file_thumbnail"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/dlp/thumbnail/';
			$originalImage	= $img_dir.to_seo_url('thumb-'.$_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.get_week($_POST['id_week']).'-'.$_POST['id_class'].'-'.$syllabus_id).".".($extension);
			$img_fileName	= to_seo_url('thumb-'.$_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.get_week($_POST['id_week']).'-'.$_POST['id_class'].'-'.$syllabus_id).".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'png', 'gif'))){
				$sqllmsupload  = $dblms->querylms("UPDATE ".SYLLABUS."
															SET file_thumbnail	= '".$img_fileName."'
															WHERE  syllabus_id	= '".cleanvars($syllabus_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['file_thumbnail']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		// REMARKS
		if($sqllms){
			$remarks = 'Add Syllabus: "'.cleanvars($_POST['id_class']).'" "'.cleanvars($_POST['id_subject']).'" detail';
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
			header("Location: syllabus_dlp.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_syllabus'])){
	$sqllmscheck  = $dblms->querylms("SELECT syllabus_term, id_session, id_class, id_subject  
										FROM ".SYLLABUS." 
										WHERE syllabus_type	= '2'
										AND syllabus_term	= '".cleanvars($_POST['syllabus_term'])."' 
										AND id_session		= '".cleanvars($_POST['id_session'])."' 
										AND id_month		= '".cleanvars($_POST['id_month'])."'
										AND id_week			= '".cleanvars($_POST['id_week'])."'
										AND id_class		= '".cleanvars($_POST['id_class'])."'
										AND id_subject		= '".cleanvars($_POST['id_subject'])."'
										AND is_deleted		= '0'
										and syllabus_id	   != '".cleanvars($_POST['syllabus_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: syllabus_dlp.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("UPDATE ".SYLLABUS." SET  
														  syllabus_status	= '".cleanvars($_POST['syllabus_status'])."'
														, syllabus_term		= '".cleanvars($_POST['syllabus_term'])."'
														, id_session		= '".cleanvars($_POST['id_session'])."'
														, id_month			= '".cleanvars($_POST['id_month'])."' 
														, id_week			= '".cleanvars($_POST['id_week'])."' 
														, id_class			= '".cleanvars($_POST['id_class'])."' 
														, id_subject		= '".cleanvars($_POST['id_subject'])."' 
														, note				= '".cleanvars($_POST['note'])."' 
														, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, date_modify		= NOW()
														  WHERE syllabus_id	= '".cleanvars($_POST['syllabus_id'])."'");										  
		$syllabus_id = $_POST['syllabus_id'];
		// FILE UPLOAD
		if(!empty($_FILES['syllabus_file']['name'])) { 
			$path_parts 	= pathinfo($_FILES["syllabus_file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/dlp/';
			$originalImage	= $img_dir.to_seo_url($_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.get_week($_POST['id_week']).'-'.$_POST['id_class'].'-'.$syllabus_id).".".($extension);
			$img_fileName	= to_seo_url($_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.get_week($_POST['id_week']).'-'.$_POST['id_class'].'-'.$syllabus_id).".".($extension);
			if(in_array($extension , array('pdf','ppt', 'docx'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".SYLLABUS."
															SET syllabus_file	= '".$img_fileName."'
															WHERE  syllabus_id	= '".cleanvars($syllabus_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['syllabus_file']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		// FILE THUMBNAIL UPLOAD
		if(!empty($_FILES['file_thumbnail']['name'])) { 
			$path_parts 	= pathinfo($_FILES["file_thumbnail"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/dlp/thumbnail/';
			$originalImage	= $img_dir.to_seo_url('thumb-'.$_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.get_week($_POST['id_week']).'-'.$_POST['id_class'].'-'.$syllabus_id).".".($extension);
			$img_fileName	= to_seo_url('thumb-'.$_POST['id_session'].'-'.get_monthtypes($_POST['id_month']).'-'.get_week($_POST['id_week']).'-'.$_POST['id_class'].'-'.$syllabus_id).".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'png', 'gif'))){
				$sqllmsupload  = $dblms->querylms("UPDATE ".SYLLABUS."
															SET file_thumbnail	= '".$img_fileName."'
															WHERE  syllabus_id	= '".cleanvars($syllabus_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['file_thumbnail']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		// REMARKS
		if($sqllms){
			$remarks = 'Update Syllabus: "'.cleanvars($_POST['id_class']).'" "'.cleanvars($_POST['id_subject']).'"details';
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
			header("Location: syllabus_dlp.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$sqllms  = $dblms->querylms("UPDATE ".SYLLABUS." SET  
												  is_deleted		= '1'
												, id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted		= '".$ip."'
												, date_deleted		= NOW()
												  WHERE syllabus_id	= '".cleanvars($_GET['deleteid'])."'");
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