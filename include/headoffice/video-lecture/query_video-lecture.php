<?php 
// INSERT RECORD
if(isset($_POST['submit_video'])){
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".VIDEO_LECTURE." 
										WHERE title		= '".cleanvars($_POST['title'])."' 
										AND id_class	= '".cleanvars($_POST['id_class'])."'
										AND id_subject	= '".cleanvars($_POST['id_subject'])."' 
										AND id_session	= '".cleanvars($_POST['id_session'])."'
										AND is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: video-lecture.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("INSERT INTO ".VIDEO_LECTURE."(
														  status 
														, title 
														, facebook_code 
														, youtube_code 
														, id_class 
														, id_subject
														, id_session
														, id_added 
														, date_added 	
													)
	   											VALUES(
														  '".cleanvars($_POST['status'])."' 
														, '".cleanvars($_POST['title'])."' 
														, '".cleanvars($_POST['facebook_code'])."' 
														, '".cleanvars($_POST['youtube_code'])."' 
														, '".cleanvars($_POST['id_class'])."'
														, '".cleanvars($_POST['id_subject'])."'
														, '".cleanvars($_POST['id_session'])."'
														, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, NOW()
													)"
								);
		$video_id = $dblms->lastestid();
		// FILE THUMBNAIL UPLOAD
		if(!empty($_FILES['thumbnail']['name'])) { 
			$path_parts 	= pathinfo($_FILES["thumbnail"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/video_lectures/thumbnail/';
			$originalImage	= $img_dir.to_seo_url($_POST['id_class'].'-'.$_POST['id_subject'].'-'.$video_id).".".($extension);
			$img_fileName	= to_seo_url($_POST['id_class'].'-'.$_POST['id_subject'].'-'.$video_id).".".($extension);

			if(in_array($extension , array('jpg','jpeg', 'png', 'gif'))){
				$sqllmsupload  = $dblms->querylms("UPDATE ".VIDEO_LECTURE."
															SET thumbnail	= '".$img_fileName."'
															WHERE id		= '".cleanvars($video_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['thumbnail']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		// REMARKS
		if($sqllms){
			$remarks = 'Add Video Lesson #: '.$video_id.' "'.cleanvars($_POST['id_class']).'" "'.cleanvars($_POST['id_subject']).'" detail';
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
			header("Location: video-lecture.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['change_video'])){
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".VIDEO_LECTURE." 
										WHERE title		= '".cleanvars($_POST['title'])."' 
										AND id_class	= '".cleanvars($_POST['id_class'])."'
										AND id_subject	= '".cleanvars($_POST['id_subject'])."' 
										AND id_session	= '".cleanvars($_POST['id_session'])."'
										AND is_deleted	= '0'
										AND id		   != '".cleanvars($_POST['video_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: video-lecture.php", true, 301);
		exit();
	}else{
		$sqllms  = $dblms->querylms("UPDATE ".VIDEO_LECTURE." SET  
															  status		= '".cleanvars($_POST['status'])."'
															, title			= '".cleanvars($_POST['title'])."'
															, facebook_code	= '".cleanvars($_POST['facebook_code'])."' 
															, youtube_code	= '".cleanvars($_POST['youtube_code'])."' 
															, id_class		= '".cleanvars($_POST['id_class'])."' 
															, id_subject	= '".cleanvars($_POST['id_subject'])."' 
															, id_session	= '".cleanvars($_POST['id_session'])."' 
															, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, date_modify	= NOW()
															  WHERE id		= '".cleanvars($_POST['video_id'])."'");
		$video_id = $_POST['video_id'];
		// FILE THUMBNAIL UPLOAD
		if(!empty($_FILES['thumbnail']['name'])) { 
			$path_parts 	= pathinfo($_FILES["thumbnail"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/video_lectures/thumbnail/';
			$originalImage	= $img_dir.to_seo_url($_POST['id_class'].'-'.$_POST['id_subject'].'-'.$video_id).".".($extension);
			$img_fileName	= to_seo_url($_POST['id_class'].'-'.$_POST['id_subject'].'-'.$video_id).".".($extension);

			if(in_array($extension , array('jpg','jpeg', 'png', 'gif'))){
				$sqllmsupload  = $dblms->querylms("UPDATE ".VIDEO_LECTURE."
															SET thumbnail	= '".$img_fileName."'
															WHERE id		= '".cleanvars($video_id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 	
				move_uploaded_file($_FILES['thumbnail']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		// REMARKS
		if($sqllms){
			$remarks = 'Update Video Lesson #: '.$video_id.' "'.cleanvars($_POST['id_class']).'" "'.cleanvars($_POST['id_subject']).'"details';
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
			header("Location: video-lecture.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$sqllms  = $dblms->querylms("UPDATE ".VIDEO_LECTURE." SET  
												  is_deleted		= '1'
												, id_deleted		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted		= '".$ip."'
												, date_deleted		= NOW()
												  WHERE id			= '".cleanvars($_GET['deleteid'])."'");
	if($sqllms){
		$remarks = 'Video Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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