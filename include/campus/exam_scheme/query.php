<?php 
// ADD EXAM SCHEME
if(isset($_POST['submit_scheme'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".EXAM_DOWNLOADS." 
										WHERE id_type 	= '3' 
										AND id_exam 	= '".cleanvars($_POST['id_exam'])."' 
										AND id_month 	= '".cleanvars($_POST['id_month'])."' 
										AND id_class 	= '".cleanvars($_POST['id_class'])."' 
										AND id_session 	= '".cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'])."' 
										AND id_campus 	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: exam_scheme.php", true, 301);
		exit();
	} else { 
		$sqllms  = $dblms->querylms("INSERT INTO ".EXAM_DOWNLOADS."(
															status								, 
															id_type								,
															id_exam								, 
															id_month							,  
															id_class							, 
															note								,
															id_session							,
															id_campus							,
															id_added							, 
															date_added 	
														)
													VALUES(
															'".cleanvars($_POST['status'])."'								, 
															'3'																,
															'".cleanvars($_POST['id_exam'])."'								,
															'".cleanvars($_POST['id_month'])."'								,
															'".cleanvars($_POST['id_class'])."'								,
															'".cleanvars($_POST['note'])."'									,
															'".cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'])."'							,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
															NOW()
														)"
									);
							
		$id = $dblms->lastestid();
		if(!empty($_FILES['file']['name'])) { 
			$path_parts 	= pathinfo($_FILES["file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 	= 'uploads/assessment_downloads/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'].'-'.$_POST['id_class'].'-'.$_POST['id_month'])).'_'.$id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'].'-'.$_POST['id_class'].'-'.$_POST['id_month'])).'_'.$id.".".($extension);
			if(in_array($extension , array('pdf','ppt', 'docx'))) { 
				$sqllmsupload  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS."
																SET file  = '".$img_fileName."'
															WHERE  id	  = '".cleanvars($id)."'");
				unset($sqllmsupload);
				$mode = '0644'; 
				move_uploaded_file($_FILES['file']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		if($sqllms) { 
			$remarks = 'Assessment Scheme Added ID: '.$id.'" detail';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																id_user										, 
																filename									, 
																action										,
																dated										,
																ip											,
																remarks				
															)
			
														VALUES(
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 	, 
																'1'																, 
																NOW()															,
																'".cleanvars($ip)."'											,
																'".cleanvars($remarks)."'			
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: exam_scheme.php", true, 301);
			exit();
		}
	}
} 
// UPDATE EXAM SCHEME
if(isset($_POST['changes_scheme'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS." SET  
														status			= '".cleanvars($_POST['status'])."'
													, id_exam			= '".cleanvars($_POST['id_exam'])."'   
													, id_month			= '".cleanvars($_POST['id_month'])."' 
													, id_class			= '".cleanvars($_POST['id_class'])."'  
													, note				= '".cleanvars($_POST['note'])."' 
													, id_session		= '".cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'])."' 
													, id_campus			= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
													, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													, date_modify		= NOW()
												WHERE id				= '".cleanvars($_POST['id'])."'");									  
	$id = $_POST['id'];
	if(!empty($_FILES['file']['name'])) { 
		$path_parts 	= pathinfo($_FILES["file"]["name"]);
		$extension 		= strtolower($path_parts['extension']);
		$img_dir 	= 'uploads/assessment_downloads/';
		$originalImage	= $img_dir.to_seo_url(cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'].'-'.$_POST['id_class'].'-'.$_POST['id_month'])).'_'.$id.".".($extension);
		$img_fileName	= to_seo_url(cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'].'-'.$_POST['id_class'].'-'.$_POST['id_month'])).'_'.$id.".".($extension);
		if(in_array($extension , array('pdf','ppt', 'docx'))) { 
			$sqllmsupload  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS."
															SET file = '".$img_fileName."'
													 WHERE  id		 = '".cleanvars($id)."'");
			unset($sqllmsupload);
			$mode = '0644'; 
			move_uploaded_file($_FILES['file']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
		}
	}

	if($sqllms) { 
	$remarks = 'Assessment Scheme Updated ID"'.cleanvars($_POST['id']).'" details';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															id_user										, 
															filename									, 
															action										,
															dated										,
															ip											,
															remarks			
														  )
		
													VALUES(
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
															'2'											, 
															NOW()										,
															'".cleanvars($ip)."'						,
															'".cleanvars($remarks)."'		
														  )
									");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: exam_scheme.php", true, 301);
			exit();
	}
}
// DELETE EXAM SCHEME
if(isset($_GET['deleteid'])){

	$sqllms  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS." SET  
													is_deleted				= '1'
												  , id_deleted				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , ip_deleted				= '".$ip."'
												  , date_deleted			= NOW()
											  WHERE id						= '".cleanvars($_GET['deleteid'])."'");

	if($sqllms) { 
		$remarks = 'Assessment Scheme Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
																'3'											, 
																NOW()										,
																'".cleanvars($ip)."'						,
																'".cleanvars($remarks)."'						,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
																)
										");
		$_SESSION['msg']['title'] 	= 'Warning';
		$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
		$_SESSION['msg']['type'] 	= 'warning';
		header("Location: exam_scheme.php", true, 301);
		exit();
	}
}