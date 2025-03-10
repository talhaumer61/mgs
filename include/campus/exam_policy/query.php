<?php 
// ADD EXAM POLICY
if(isset($_POST['submit_policy'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".EXAM_DOWNLOADS." 
										WHERE id_type 	= '2' 
										AND id_exam 	= '".cleanvars($_POST['id_exam'])."' 
										AND id_session 	= '".cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'])."' 
										AND id_campus 	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: exam_policy.php", true, 301);
		exit();
	} else { 
		$sqllms  = $dblms->querylms("INSERT INTO ".EXAM_DOWNLOADS."(
														status								, 
														id_type								,
														id_exam								, 
														note								,
														id_session							,
														id_campus							,
														id_added							, 
														date_added 	
													  )
	   											VALUES(
														'".cleanvars($_POST['status'])."'								, 
														'2'																,
														'".cleanvars($_POST['id_exam'])."'								,
														'".cleanvars($_POST['note'])."'									,
														'".cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'])."'							,
														'".$_SESSION['userlogininfo']['LOGINCAMPUS']."'							,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
														NOW()
													  )"
							);							
		$id = $dblms->lastestid();
		if(!empty($_FILES['file']['name'])) { 
			$path_parts 	= pathinfo($_FILES["file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 		= 'uploads/assessment_downloads/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'].'-'.$_POST['id_exam'])).'-'.$id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'].'-'.$_POST['id_exam'])).'-'.$id.".".($extension);
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
			sendRemark("Assessment Policy Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Updated.", "success");
			header("Location: exam_policy.php", true, 301);
			exit();
		}
	} 
} 

// UPDATE RECORD
if(isset($_POST['changes_policy'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".EXAM_DOWNLOADS." 
										WHERE id_type 	= '2' 
										AND id_exam 	= '".cleanvars($_POST['id_exam'])."' 
										AND id_session 	= '".cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'])."' 
										AND id_campus 	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND id			!= '".cleanvars($_POST['id'])."'
										LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: exam_policy.php", true, 301);
		exit();
	} else { 
		$sqllms  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS." SET  
														  status			= '".cleanvars($_POST['status'])."'
														, id_exam			= '".cleanvars($_POST['id_exam'])."'   
														, note				= '".cleanvars($_POST['note'])."' 
														, id_session		= '".cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'])."' 
														, id_campus 		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, date_modify		= NOW()
														 WHERE id			= '".cleanvars($_POST['id'])."'
									");									  
		$id = $_POST['id'];
		if(!empty($_FILES['file']['name'])) { 
			$path_parts 	= pathinfo($_FILES["file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 		= 'uploads/assessment_downloads/';
			$originalImage	= $img_dir.to_seo_url(cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'].'-'.$_POST['id_exam'])).'-'.$id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_SESSION['userlogininfo']['EXAM_SESSION'].'-'.$_POST['id_exam'])).'-'.$id.".".($extension);
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
			sendRemark("Assessment Policy Updated ID: ".$id." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: exam_policy.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORDS
if(isset($_GET['deleteid'])) { 
	$latestID = $_GET['deleteid'];	
	$values = array (
						 "is_deleted"		=> '1'
						,"id_deleted"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"		=> cleanvars($ip)
						,"date_deleted"		=> date('Y-m-d G:i:s')
					);	
	$sqllms = $dblms->Update(EXAM_DOWNLOADS , $values , "WHERE id = '".cleanvars($latestID)."'");
	
	if($sqllms) {	
		sendRemark("Deleted Assessment Policy ID: ".$latestID." Detail", '3');
		sessionMsg("Success", "Record Successfully Deleted.", "success");
		header("Location: exam_policy.php", true, 301);
		exit();
	}
}
?>