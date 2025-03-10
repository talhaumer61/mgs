<?php
// INSERT RECORD
if(isset($_POST['submit_brand'])) {
	$sqllmscheck  = $dblms->querylms("SELECT brand_id  
										FROM ".BRANDS." 
										WHERE brand_name	= '".cleanvars($_POST['brand_name'])."' 
										AND brand_code		= '".cleanvars($_POST['brand_code'])."'
										AND is_deleted		= '0' LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: brand.php", true, 301);
		exit();
	}else{
		$values = array (
							 "brand_status"			=> cleanvars($_POST['brand_status'])
							,"brand_code"			=> cleanvars($_POST['brand_code'])
							,"brand_code_numeric"	=> cleanvars($_POST['brand_code_numeric'])
							,"brand_name"			=> cleanvars($_POST['brand_name'])
							,"brand_ordering"		=> cleanvars($_POST['brand_ordering'])
							,"brand_detail"			=> cleanvars($_POST['brand_detail'])
							,"id_added"				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_added"			=> date('Y-m-d h:i:s')
						);
		$sqllms  = $dblms->insert(BRANDS, $values);
		// LATEST ID
		$latestID = $dblms->lastestid();

		if($sqllms) {
			// UPLOAD FILE
			if(!empty($_FILES['brand_logo']['name'])){
				$path_parts 	= pathinfo($_FILES["brand_logo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir 		= 'uploads/images/brands/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['brand_name'])).'_'.$latestID.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_POST['brand_name'])).'_'.$latestID.".".($extension);
				
				if(in_array($extension , array('png','jpg', 'jpeg','JPG','JPEG','PNG'))){
					// UPDATE
					$values = array (
									"brand_logo"	=>	$img_fileName
								);
					$sqllmsupload = $dblms->Update(BRANDS , $values , "WHERE brand_id = '".cleanvars($latestID)."'");
					
					// MOVE FILE
					unset($sqllmsupload);
					$mode = '0644'; 	
					move_uploaded_file($_FILES['brand_logo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			
			// REMARKS
			$remarks = 'Add Brand ID: "'.cleanvars($latestID).'" detail';
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
			header("Location: brand.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_brand'])) {
	$sqllmscheck  = $dblms->querylms("SELECT brand_id  
										FROM ".BRANDS." 
										WHERE brand_name	= '".cleanvars($_POST['brand_name'])."' 
										AND brand_code		= '".cleanvars($_POST['brand_code'])."'
										AND is_deleted		= '0'
										AND brand_id	   != '".cleanvars($_POST['brand_id'])."' LIMIT 1
									");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: brand.php", true, 301);
		exit();
	}else{
		$values = array (
							 "brand_status"			=> cleanvars($_POST['brand_status'])
							,"brand_code"			=> cleanvars($_POST['brand_code'])
							,"brand_code_numeric"	=> cleanvars($_POST['brand_code_numeric'])
							,"brand_name"			=> cleanvars($_POST['brand_name'])
							,"brand_ordering"		=> cleanvars($_POST['brand_ordering'])
							,"brand_detail"			=> cleanvars($_POST['brand_detail'])
							,"id_modify"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_modify"			=> date('Y-m-d h:i:s')
						);
		$sqllms = $dblms->Update(BRANDS , $values , "WHERE brand_id = '".cleanvars($_POST['brand_id'])."'");
		// LATEST ID
		$latestID = $_POST['brand_id'];

		if($sqllms) {
			// UPLOAD FILE
			if(!empty($_FILES['brand_logo']['name'])){
				$path_parts 	= pathinfo($_FILES["brand_logo"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir 		= 'uploads/images/brands/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['brand_name'])).'_'.$latestID.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_POST['brand_name'])).'_'.$latestID.".".($extension);
				
				if(in_array($extension , array('png','jpg', 'jpeg','JPG','JPEG','PNG'))){
					// UPDATE
					$values = array (
									"brand_logo"	=>	$img_fileName
								);
					$sqllmsupload = $dblms->Update(BRANDS , $values , "WHERE brand_id = '".cleanvars($latestID)."'");
					
					// MOVE FILE
					unset($sqllmsupload);
					$mode = '0644'; 	
					move_uploaded_file($_FILES['brand_logo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}
			
			// REMARKS
			$remarks = 'Update Brand ID: "'.cleanvars($latestID).'" detail';
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
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: brand.php", true, 301);
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
	$sqllms = $dblms->Update(BRANDS , $values , "WHERE brand_id = '".cleanvars($_GET['deleteid'])."'");
	// LATEST ID
	$latestID = $_GET['deleteid'];

	if($sqllms) {		
		// REMARKS
		$remarks = 'Deleted Brand ID: "'.cleanvars($latestID).'" detail';
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
		header("Location: brand.php", true, 301);
		exit();
	}
}
?>