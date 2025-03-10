<?php 
// INSERT RECORD
if(isset($_POST['submit_manual'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".EXAM_DOWNLOADS." 
										WHERE id_type	= '1'
										AND is_deleted	= '0'
										AND id_session	= '".cleanvars($_POST['id_session'])."'
										AND id_campus 	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {		
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: exam_manual.php", true, 301);
		exit();
	} else { 
		$values = array (
							 "status"			=>	cleanvars($_POST['status'])
							,"id_type"			=>	'1'
							,"note"				=>	cleanvars($_POST['note'])
							,"id_session"		=>	cleanvars($_POST['id_session'])
							,"id_campus"		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,"id_added"			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_added"		=>	date('Y-m-d G:i:s')
						);	
		$sqllms = $dblms->insert(EXAM_DOWNLOADS , $values);
		
		if($sqllms) { 
			$latestID = $dblms->lastestid();

			// FILE UPLOAD
			if(!empty($_FILES['file']['name'])) { 
				$path_parts 	= pathinfo($_FILES["file"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir 		= 'uploads/assessment_downloads/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['id_session'])).'-'.$latestID.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_POST['id_session'])).'-'.$latestID.".".($extension);
				if(in_array($extension , array('pdf','ppt', 'docx'))) { 
					$sqllmsupload  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS."
																SET file	= '".$img_fileName."'
																WHERE  id	= '".cleanvars($latestID)."'
														");
					unset($sqllmsupload);
					$mode = '0644'; 	
					move_uploaded_file($_FILES['file']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			// REMARKS
			sendRemark("Add Exam Manual ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: exam_manual.php", true, 301);
			exit();
		}
	}
} 

// UPDATED RECORD
if(isset($_POST['changes_manual'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT id  
										FROM ".EXAM_DOWNLOADS." 
										WHERE id_type	= '1'
										AND is_deleted	= '0'
										AND id_session	= '".cleanvars($_POST['id_session'])."'
										AND id_campus 	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND id			!= '".cleanvars($_POST['id'])."'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {		
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: exam_manual.php", true, 301);
		exit();
	} else { 
		$values = array (
							 "status"			=>	cleanvars($_POST['status'])
							,"id_type"			=>	'1'
							,"note"				=>	cleanvars($_POST['note'])
							,"id_session"		=>	cleanvars($_POST['id_session'])
							,"id_campus"		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,"id_added"			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_added"		=>	date('Y-m-d G:i:s')
						);	
		$sqllms = $dblms->Update(EXAM_DOWNLOADS , $values , "WHERE id = '".cleanvars($_POST['id'])."'");

		if($sqllms) { 
			$latestID = $_POST['id'];

			// FILE UPLOAD
			if(!empty($_FILES['file']['name'])) { 
				$path_parts 	= pathinfo($_FILES["file"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir 		= 'uploads/assessment_downloads/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['id_session'])).'-'.$latestID.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_POST['id_session'])).'-'.$latestID.".".($extension);
				if(in_array($extension , array('pdf','ppt', 'docx'))) { 
					$sqllmsupload  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS."
																SET file	= '".$img_fileName."'
																WHERE  id	= '".cleanvars($latestID)."'
														");
					unset($sqllmsupload);
					$mode = '0644'; 	
					move_uploaded_file($_FILES['file']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			// REMARKS
			sendRemark("Update Exam Manual ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: exam_manual.php", true, 301);
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
		sendRemark("Deleted Exam Manual ID: ".$latestID." Detail", '3');
		sessionMsg("Success", "Record Successfully Deleted.", "success");
		header("Location: exam_types.php", true, 301);
		exit();
	}
}
?>