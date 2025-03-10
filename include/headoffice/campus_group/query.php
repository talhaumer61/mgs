<?php
// INSERT RECORD
if(isset($_POST['submit_group'])){
	$sqllmscheck  = $dblms->querylms("SELECT group_id  
										FROM ".CAMPUS_GROUPS." 
										WHERE group_name	= '".cleanvars($_POST['group_name'])."' 
										AND group_code		= '".cleanvars($_POST['group_code'])."'
										AND is_deleted		= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: campus_group.php", true, 301);
		exit();
	}else{ 
		$values = array (
							 "group_status"			=> cleanvars($_POST['group_status'])
							,"group_code"			=> cleanvars($_POST['group_code'])
							,"group_code_numeric"	=> cleanvars($_POST['group_code_numeric'])
							,"group_name"			=> cleanvars($_POST['group_name'])
							,"group_ordering"		=> cleanvars($_POST['group_ordering'])
							,"group_detail"			=> cleanvars($_POST['group_detail'])
							,"id_added"				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_added"			=> date('Y-m-d h:i:s')
						);
		$sqllms  = $dblms->insert(CAMPUS_GROUPS, $values);
		// LATEST ID
		$latestID = $dblms->lastestid();

		if($sqllms) {
			// UPLOAD FILE
			if(!empty($_FILES['group_logo']['name'])){
				$path_parts 	= pathinfo($_FILES["group_logo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir 		= 'uploads/images/campus_groups/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['group_name'])).'_'.$latestID.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_POST['group_name'])).'_'.$latestID.".".($extension);
				
				if(in_array($extension , array('png','jpg', 'jpeg','JPG','JPEG','PNG'))){
					// UPDATE
					$values = array (
									"group_logo"	=>	$img_fileName
								);
					$sqllmsupload = $dblms->Update(CAMPUS_GROUPS , $values , "WHERE group_id = '".cleanvars($latestID)."'");
					
					// MOVE FILE
					unset($sqllmsupload);
					$mode = '0644'; 	
					move_uploaded_file($_FILES['group_logo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			
			// REMARKS
			$remarks = 'Add Campus Group ID: "'.cleanvars($latestID).'" detail';
			$values = array (
								 "id_user"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
								,"action"		=> '1'
								,"dated"		=> date('Y-m-d h:i:s')
								,"ip"			=> cleanvars($ip)
								,"remarks"		=> cleanvars($remarks)
								,"id_campus"	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							);
			$sqllms  = $dblms->insert(LOGS, $values);

			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: campus_group.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_group'])){
	$sqllmscheck  = $dblms->querylms("SELECT group_id  
										FROM ".CAMPUS_GROUPS." 
										WHERE group_name	= '".cleanvars($_POST['group_name'])."' 
										AND group_code		= '".cleanvars($_POST['group_code'])."'
										AND is_deleted		= '0'
										AND group_id	   != '".cleanvars($_POST['group_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: campus_group.php", true, 301);
		exit();
	}else{ 
		$values = array (
							 "group_status"			=> cleanvars($_POST['group_status'])
							,"group_code"			=> cleanvars($_POST['group_code'])
							,"group_code_numeric"	=> cleanvars($_POST['group_code_numeric'])
							,"group_name"			=> cleanvars($_POST['group_name'])
							,"group_ordering"		=> cleanvars($_POST['group_ordering'])
							,"group_detail"			=> cleanvars($_POST['group_detail'])
							,"id_modify"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_modify"			=> date('Y-m-d h:i:s')
						);
		$sqllms = $dblms->Update(CAMPUS_GROUPS , $values , "WHERE group_id = '".cleanvars($_POST['group_id'])."'");
		// LATEST ID
		$latestID = $_POST['group_id'];

		if($sqllms) {
			// UPLOAD FILE
			if(!empty($_FILES['group_logo']['name'])){
				$path_parts 	= pathinfo($_FILES["group_logo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir 		= 'uploads/images/campus_groups/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['group_name'])).'_'.$latestID.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_POST['group_name'])).'_'.$latestID.".".($extension);
				
				if(in_array($extension , array('png','jpg', 'jpeg','JPG','JPEG','PNG'))){
					// UPDATE
					$values = array (
									"group_logo"	=>	$img_fileName
								);
					$sqllmsupload = $dblms->Update(CAMPUS_GROUPS , $values , "WHERE group_id = '".cleanvars($latestID)."'");
					
					// MOVE FILE
					unset($sqllmsupload);
					$mode = '0644'; 	
					move_uploaded_file($_FILES['group_logo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			
			// REMARKS
			$remarks = 'Updated Campus Group ID: "'.cleanvars($latestID).'" detail';
			$values = array (
								 "id_user"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
								,"action"		=> '2'
								,"dated"		=> date('Y-m-d h:i:s')
								,"ip"			=> cleanvars($ip)
								,"remarks"		=> cleanvars($remarks)
								,"id_campus"	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							);
			$sqllms  = $dblms->insert(LOGS, $values);

			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: campus_group.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$values = array (
						 "is_deleted"		=> '1'
						,"ip_deleted"		=> cleanvars($ip)
						,"id_deleted"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"date_modify"		=> date('Y-m-d h:i:s')
					);
	$sqllms = $dblms->Update(CAMPUS_GROUPS , $values , "WHERE group_id = '".cleanvars($_GET['deleteid'])."'");
	// LATEST ID
	$latestID = $_GET['deleteid'];

	if($sqllms) {		
		// REMARKS
		$remarks = 'Deleted Campus Group ID: "'.cleanvars($latestID).'" detail';
		$values = array (
							 "id_user"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
							,"action"		=> '3'
							,"dated"		=> date('Y-m-d h:i:s')
							,"ip"			=> cleanvars($ip)
							,"remarks"		=> cleanvars($remarks)
							,"id_campus"	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);
		$sqllms  = $dblms->insert(LOGS, $values);

		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: campus_group.php", true, 301);
		exit();
	}
}
?>