<?php
//	UPDATE CAMPUS PROFILE
if(isset($_POST['changes_profile'])) {
	//	CHECK RECORD EXITS
	$sqllmscheck  = $dblms->querylms("SELECT adm_id
										FROM ".ADMINS."
										WHERE adm_username	=	'".cleanvars($_POST['adm_username'])."'
										AND is_deleted	=	'0'
										AND adm_id     !=	'".cleanvars($_POST['adm_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: profile.php", true, 301);
		exit();
	}else{	
		//	UPDATE QUERY
		$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET  
													adm_fullname	=	'".cleanvars($_POST['adm_fullname'])."' 
													, adm_email		=	'".cleanvars($_POST['adm_email'])."'  
													, adm_phone		=	'".cleanvars($_POST['adm_phone'])."' 
													WHERE adm_id	=	'".cleanvars($_POST['adm_id'])."'");

		$adm_id = cleanvars($_POST['adm_id']);

		//	CHECK FILE IMAGE
		if(isset($_POST['adm_photo']) && !empty($_POST['adm_photo'])){

			//	CONVERT CROPPED IMAGE DATA
			$data = $_POST['adm_photo'];
			$image_array_1 = explode(";", $data);
			$image_array_2 = explode(",", $image_array_1[1]);
			$data = base64_decode($image_array_2[1]);

			//	FILE NAME AND DIRECTORY
			$img_fileName	= to_seo_url(cleanvars($_POST['adm_username'])).'-'.$adm_id.".jpg";
			$img_dir 		= 'uploads/images/admins/';
			$originalImage	= $img_dir.$img_fileName;

			//	UPDATE IMAGE QUERY
			$sqllmsupload  = $dblms->querylms("UPDATE ".ADMINS." SET
														adm_photo		=	'".cleanvars($img_fileName)."'
														WHERE adm_id	=	'".cleanvars($adm_id)."' ");
			//	UPLOAD FILE TO DIRECTORY
			if($sqllmsupload){
				file_put_contents($originalImage, $data);
			}
		}
	
	if($sqllms){ 
		$remarks = 'Update Profile: "'.cleanvars($_POST['adm_username']).'" details';
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
															, '2'
															, NOW()
															, '".cleanvars($ip)."'
															, '".cleanvars($remarks)."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														)
									");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: profile.php", true, 301);
			exit();
		}
	}
}

if(isset($_POST['chnage_pass'])){
	//------------hashing---------------
	$salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
	$pass = $_POST['cnfrm_pass'];
	$password = hash('sha256', $pass . $salt);
	for ($round = 0; $round < 65536; $round++) {
		$password = hash('sha256', $password . $salt);
	}
	$sqllms  = $dblms->querylms("UPDATE ".ADMINS." SET 
													  adm_salt			=  '".cleanvars($salt)."' 
												  	, adm_userpass		= '".cleanvars($password)."' 
   											  		  WHERE adm_id		= '".$_SESSION['userlogininfo']['LOGINIDA']."'
											  ");
	// REMARKS
	if($sqllms){
		$remarks = 'Update Profile: "'.cleanvars($_POST['adm_username']).'" details';
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
			header("Location: profile.php", true, 301);
			exit();
	}
}

// UPDATE CAMPUS LOGO & CONTROLLER EXAM SIGN
if(isset($_POST['changes_campus'])) {
	$campus_id		= $_POST['campus_id'];
	$campus_regno	= $_POST['campus_regno'];
	// UPLOAD FILE
	if(!empty($_FILES['campus_logo']['name'])) { 
		$path_parts 	= pathinfo($_FILES["campus_logo"]["name"]);
		$extension 		= strtolower($path_parts['extension']);
		$img_dir		= 'uploads/images/campus/';
		
		$originalImage	= $img_dir.to_seo_url(cleanvars($campus_regno)).'_'.$campus_id.".".($extension);
		$img_fileName	= to_seo_url(cleanvars($campus_regno)).'_'.$campus_id.".".($extension);
		
		if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) {
			$values = array (
								"campus_logo"	=>	$img_fileName
							);
			$sqllmsupload = $dblms->Update(CAMPUS , $values , "WHERE campus_id = '".cleanvars($campus_id)."'");

			unset($sqllmsupload);
			$mode = '0644';			
			move_uploaded_file($_FILES['campus_logo']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
		}
	}
	// CONTROLLER EXAM SIGN
	if(!empty($_FILES['controller_exam_sign']['name'])) { 
		$path_parts 	= pathinfo($_FILES["controller_exam_sign"]["name"]);
		$extension 		= strtolower($path_parts['extension']);
		$img_dir		= 'uploads/images/controller_exam_sign/';
		
		$originalImage	= $img_dir.to_seo_url(cleanvars($campus_regno)).'_'.$campus_id.".".($extension);
		$img_fileName	= to_seo_url(cleanvars($campus_regno)).'_'.$campus_id.".".($extension);
		
		if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) {
			$values = array (
								"controller_exam_sign"	=>	$img_fileName
							);
			$sqllmsupload = $dblms->Update(CAMPUS , $values , "WHERE campus_id = '".cleanvars($campus_id)."'");

			unset($sqllmsupload);
			$mode = '0644';			
			move_uploaded_file($_FILES['controller_exam_sign']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
		}
	}

	// REMARKS
	if($sqllms){
		$remarks = 'Update Campus: "'.cleanvars($_POST['campus_name']).'" details';
		$values = array (
							 "id_user"		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"filename"		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
							,"action"		=>	'2'
							,"dated"		=>	date('Y-m-d h:i:s')
							,"ip"			=>	cleanvars($ip)
							,"remarks"		=>	cleanvars($remarks)
							,"id_campus"	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);
		$sqllms  = $dblms->insert(LOGS, $values);
		
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'info';
		header("Location: profile.php?view=campus", true, 301);
		exit();
	}
}

// UTILITIES
if(isset($_POST['add_utilities'])){
	$doa = date('Y-m-d', strtotime($_POST['principal_doa']));
	$value = array (
			 "status"				=>	'1'
			,"library"				=>	$_POST['library']
			,"science_lab"			=>	$_POST['science_lab']
			,"computer_lab"			=>	$_POST['computer_lab']
			,"security_armaments"	=>	$_POST['security_armaments']
			,"fire_extinguisher"	=>	$_POST['fire_extinguisher']
			,"student_hostel"		=>	$_POST['student_hostel']
			,"power_backup"			=>	$_POST['power_backup']
			,"sound_system"			=>	$_POST['sound_system']
			,"water_filter_cooler"	=>	$_POST['water_filter_cooler']
			,"firstaid_box"			=>	$_POST['firstaid_box']
			,"printer_photocopier"	=>	$_POST['printer_photocopier']
			,"montessori_kit"		=>	$_POST['montessori_kit']
			,"internet_cctv"		=>	$_POST['internet_cctv']
			,"lcd_projector"		=>	$_POST['lcd_projector']
			,"sport_kits"			=>	$_POST['sport_kits']
			,"id_campus"			=>	$_POST['campus_id']
			,"id_added"				=>	$_SESSION['userlogininfo']['LOGINIDA']
			,"date_added"			=>	date('Y-m-d h:i:s')
	);
	$sqllms  = $dblms->insert(CAMPUS_UTILITIES, $value);
	
	$latest_id = $dblms->lastestid();

	if($sqllms){		
		$remarks = 'Add Campus Utility ID: "'.cleanvars($latest_id).'" detail';
		$values = array (
							 "id_user"		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"filename"		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
							,"action"		=>	'1'
							,"dated"		=>	date('Y-m-d h:i:s')
							,"ip"			=>	cleanvars($ip)
							,"remarks"		=>	cleanvars($remarks)
							,"id_campus"	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);
		$sqllms  = $dblms->insert(LOGS, $values);
		
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: profile.php?view=campus", true, 301);
		exit();
	} // end checker
} 

// BIOGRAPHY
if(isset($_POST['submit_bio'])){
	$doa = date('Y-m-d', strtotime($_POST['principal_doa']));
	$values = array (
						 "bio_status"			=> '1'
						,"building_type"		=>	$_POST['building_type']
						,"building_area"		=>	$_POST['building_area']
						,"covered_area"			=>	$_POST['covered_area']
						,"total_rooms"			=>	$_POST['total_rooms']
						,"play_grounds"			=>	$_POST['play_grounds']
						,"washrooms"			=>	$_POST['washrooms']
						,"principal_name"		=>	$_POST['principal_name']
						,"principal_doa"		=>	$doa
						,"principal_phone"		=>	$_POST['principal_phone']
						,"second_phone"			=>	$_POST['second_phone']
						,"principal_whastapp"	=>	$_POST['principal_whastapp']
						,"principal_email"		=>	$_POST['principal_email']
						,"principal_edu"		=>	$_POST['principal_edu']
						,"principal_experience"	=>	$_POST['principal_experience']
						,"primary_bank"			=>	$_POST['primary_bank']
						,"primary_account"		=>	$_POST['primary_account']
						,"secondary_bank"		=>	$_POST['secondary_bank']
						,"secondary_account"	=>	$_POST['secondary_account']
						,"mec_president"		=>	$_POST['mec_president']
						,"mec_president_no"		=>	$_POST['mec_president_no']
						,"id_campus"			=>	$_POST['campus_id']
					);

	$sqlBioCheck = $dblms->querylms("SELECT bio_id
										FROM ".CAMPUS_BIOGRAPHY."
										WHERE id_campus = '".cleanvars($_POST['campus_id'])."'
										AND is_deleted	= '0'
										ORDER BY bio_id DESC LIMIT 1 ");
	if(mysqli_num_rows($sqlBioCheck)>0){
		$valBio = mysqli_fetch_array($sqlBioCheck);
		// UPDATE
		$values['id_modify']	=	$_SESSION['userlogininfo']['LOGINIDA'];
		$values['date_modify']	=	date('Y-m-d h:i:s');

		$sqllms = $dblms->Update(CAMPUS_BIOGRAPHY , $values , "WHERE bio_id = '".cleanvars($valBio['bio_id'])."'");
		$latest_id = $valBio['bio_id'];

		$action = '2';
		$comment = 'Updated';
	}else{
		$values['id_added']		=	$_SESSION['userlogininfo']['LOGINIDA'];
		$values['date_added']	=	date('Y-m-d h:i:s');

		$sqllms  = $dblms->insert(CAMPUS_BIOGRAPHY, $values);
		$latest_id = $dblms->lastestid();

		$action = '1';
		$comment = 'Added';
	}


	if($sqllms){
		$remarks = $comment.' Campus Biograpgy: '.cleanvars($latest_id).' detail';

		$values = array (
							 "id_user"		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"filename"		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
							,"action"		=>	cleanvars($action)
							,"dated"		=>	date('Y-m-d h:i:s')
							,"ip"			=>	cleanvars($ip)
							,"remarks"		=>	cleanvars($remarks)
							,"id_campus"	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);
		$sqllms  = $dblms->insert(LOGS, $values);
		
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully '.$comment.'.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: profile.php?view=campus", true, 301);
		exit();
	}
} 
?>