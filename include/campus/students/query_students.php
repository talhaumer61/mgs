<?php 
// INSERT RECORD
if(isset($_POST['submit_student'])) { 
	$id_campus = (isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS'];
	$sqllmscheck  = $dblms->querylms("
										SELECT std_id
										FROM ".STUDENTS." 
										WHERE id_campus = '".cleanvars($id_campus)."' 
										AND is_deleted = '0'
										AND std_nic = '".cleanvars($_POST['std_nic'])."'
										LIMIT 1
									");

	// $sqllmscheck  = $dblms->querylms("
	// 									SELECT std_id
	// 									FROM ".STUDENTS." 
	// 									WHERE id_campus = '".cleanvars($id_campus)."' 
	// 									AND is_deleted = '0'
	// 									AND (
	// 											std_nic = '".cleanvars($_POST['std_nic'])."'
	// 											OR std_rollno = '".cleanvars($_POST['std_rollno'])."'
	// 									)
	// 									LIMIT 1
	// 								");
										
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php?id_campus='.$id_campus.''."", true, 301);
		exit();
	}else{
		// DATE VARIABLES
		$dob = date('Y-m-d' , strtotime(cleanvars($_POST['std_dob'])));
		$admissiondate = date('Y-m-d' , strtotime(cleanvars($_POST['std_admissiondate'])));
		$admission_year = date('Y' , strtotime(cleanvars($_POST['std_admissiondate'])));

		//--------------- Roll No -----------------
		// $newRollno = 0;
		// $sqllmsRoll	= $dblms->querylms("SELECT MAX(std_rollno) as rollno
		// 								FROM ".STUDENTS."
		// 								WHERE id_campus = '".$id_campus."'
		// 								AND id_class = '".$_POST['id_class']."'");
		// if(mysqli_num_rows($sqllmsRoll) > 0 ){
		// 	$valueRoll = mysqli_fetch_array($sqllmsRoll);
		// 	(int)$valueRoll['rollno'];
		// 	$newRollno = (int)$valueRoll['rollno'] + 1;
		// }
		// else{
		// 	$newRollno = 1;
		// }

		// GENERATE REGISTERATION NUMBER 
		$sqllmscampus = $dblms->querylms("SELECT  c.campus_code, cg.group_code_numeric, b.brand_code_numeric, d.dist_code
													FROM ".CAMPUS." c 
													INNER JOIN ".CAMPUS_GROUPS." cg ON cg.group_id = c.id_group
													INNER JOIN ".BRANDS." b ON b.brand_id = c.id_brand
													INNER JOIN ".DISTRICTS." d ON d.dist_id  = c.id_dist
													WHERE c.is_deleted = '0'
													AND c.campus_id = '".cleanvars($id_campus)."'
													LIMIT 1
												");
		$value_campus = mysqli_fetch_array($sqllmscampus);

		$regnoStr = STD_PREFIX.$value_campus['group_code_numeric'].$value_campus['brand_code_numeric'].'-'.$value_campus['dist_code'].$value_campus['campus_code'].'-'.substr(date("Y"), -2);

		$sqllmsstudentregno = $dblms->querylms("SELECT std_regno FROM ".STUDENTS." 
													WHERE std_regno LIKE '".$regnoStr."%'
													AND id_campus = '".cleanvars($id_campus)."'
													ORDER by std_regno DESC LIMIT 1 ");
		$value_regno = mysqli_fetch_array($sqllmsstudentregno);
		if(mysqli_num_rows($sqllmsstudentregno) < 1) {
			$regno	= $regnoStr.'-0001';
		}else{
			$regno = $value_regno['std_regno'];
			$regno++;
		}
															// echo '<br> <br>'. cleanvars($_POST['std_status']);
															// echo '<br> <br> '. cleanvars($_POST['std_name']);
															// echo '<br> <br>'. cleanvars($_POST['std_fathername']);
															// echo '<br> <br>'. cleanvars($_POST['std_gender']); 
															// echo '<br> <br>'. cleanvars($_POST['id_guardian']); 
															// echo '<br> <br>'. cleanvars($dob);
															// echo '<br> <br>'. cleanvars($_POST['std_bloodgroup']); 
															// echo '<br> <br>'. cleanvars($_POST['std_city']); 
															// echo '<br> <br>'. cleanvars($_POST['std_prev_school']); 
															// echo '<br> <br>'. cleanvars($_POST['std_familyno']);  
															// echo '<br> <br>'. cleanvars($_POST['std_nic']); 
															// echo '<br> <br>'. cleanvars($_POST['std_religion']); 
															// echo '<br> <br>'. cleanvars($_POST['std_phone']); 
															// echo '<br> <br>'. cleanvars($_POST['std_whatsapp']); 
															// echo '<br> <br>'. cleanvars($_POST['std_address']); 
															// echo '<br> <br>'. cleanvars($_POST['id_class']); 
															// echo '<br> <br>'. cleanvars($_POST['id_section']); 
															// echo '<br> <br>'. cleanvars($_POST['id_group']); 
															// echo '<br> <br>'. cleanvars($_POST['id_session']); 
															// echo '<br> <br>'. cleanvars($_POST['std_rollno']); 
															// echo '<br> <br>'. cleanvars($regno); 
															// echo '<br> <br>'. cleanvars($_POST['form_no']);
															// echo '<br> <br>'. cleanvars($admissiondate);
															// echo '<br> <br>'. cleanvars($id_campus);
															// echo '<br> <br>'. cleanvars($_SESSION['userlogininfo']['LOGINIDA']);
															// exit;
		// $sqllms  = $dblms->querylms("INSERT INTO ".STUDENTS."(
		// 													  std_status 
		// 													, std_name
		// 													, std_fathername  
		// 													, std_gender  
		// 													, id_guardian  
		// 													, std_dob  
		// 													, std_bloodgroup
		// 													, std_city 
		// 													, std_prev_school 
		// 													, std_familyno
		// 													, std_nic  
		// 													, std_religion  
		// 													, std_phone 
		// 													, std_whatsapp 
		// 													, std_address  
		// 													, id_class  
		// 													, id_section  
		// 													, id_group  
		// 													, id_session  
		// 													, std_rollno  
		// 													, std_regno
		// 													, admission_formno  
		// 													, std_admissiondate
		// 													, id_campus
		// 													, id_added  
		// 													, date_added
		// 													, is_hostel
		// 												)
		// 											VALUES(
		// 													  '".cleanvars($_POST['std_status'])."' 
		// 													, '".cleanvars($_POST['std_name'])."'
		// 													, '".cleanvars($_POST['std_fathername'])."'
		// 													, '".cleanvars($_POST['std_gender'])."' 
		// 													, '".cleanvars($_POST['id_guardian'])."' 
		// 													, '".cleanvars($dob)."'
		// 													, '".cleanvars($_POST['std_bloodgroup'])."' 
		// 													, '".cleanvars($_POST['std_city'])."' 
		// 													, '".cleanvars($_POST['std_prev_school'])."' 
		// 													, '".cleanvars($_POST['std_familyno'])."'  
		// 													, '".cleanvars($_POST['std_nic'])."' 
		// 													, '".cleanvars($_POST['std_religion'])."' 
		// 													, '".cleanvars($_POST['std_phone'])."' 
		// 													, '".cleanvars($_POST['std_whatsapp'])."' 
		// 													, '".cleanvars($_POST['std_address'])."' 
		// 													, '".cleanvars($_POST['id_class'])."' 
		// 													, '".cleanvars($_POST['id_section'])."' 
		// 													, '".cleanvars($_POST['id_group'])."' 
		// 													, '".cleanvars($_POST['id_session'])."' 
		// 													, '".cleanvars($_POST['std_rollno'])."' 
		// 													, '".cleanvars($regno)."' 
		// 													, '".cleanvars($_POST['form_no'])."'
		// 													, '".cleanvars($admissiondate)."'
		// 													, '".cleanvars($id_campus)."'
		// 													, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
		// 													, NOW()
		// 													, '2'
		// 												)"
		// 						);

				$sqllms  = $dblms->querylms("INSERT INTO ".STUDENTS."(
															  std_status 
															, std_name
															, std_fathername  
															, std_gender  
															, id_guardian  
															, std_dob  
															, std_bloodgroup
															, std_city 
															, std_familyno
															, std_nic  
															, std_religion  
															, std_phone 
															, std_whatsapp 
															, std_address  
															, id_class  
															, id_section  
															, id_group  
															, id_session  
															, std_rollno  
															, std_regno
															, admission_formno  
															, std_admissiondate
															, id_campus
															, id_added  
															, date_added
															, is_hostel
														)
													VALUES(
															  '".cleanvars($_POST['std_status'])."' 
															, '".cleanvars($_POST['std_name'])."'
															, '".cleanvars($_POST['std_fathername'])."'
															, '".cleanvars($_POST['std_gender'])."' 
															, '".cleanvars($_POST['id_guardian'])."' 
															, '".cleanvars($dob)."'
															, '".cleanvars($_POST['std_bloodgroup'])."' 
															, '".cleanvars($_POST['std_city'])."' 
															, '".cleanvars($_POST['std_familyno'])."'  
															, '".cleanvars($_POST['std_nic'])."' 
															, '".cleanvars($_POST['std_religion'])."' 
															, '".cleanvars($_POST['std_phone'])."' 
															, '".cleanvars($_POST['std_whatsapp'])."' 
															, '".cleanvars($_POST['std_address'])."' 
															, '".cleanvars($_POST['id_class'])."' 
															, '".cleanvars($_POST['id_section'])."' 
															, '".cleanvars($_POST['id_group'])."' 
															, '".cleanvars($_POST['id_session'])."' 
															, '".cleanvars($newRollno)."' 
															, '".cleanvars($regno)."' 
															, '".cleanvars($_POST['form_no'])."'
															, '".cleanvars($admissiondate)."'
															, '".cleanvars($id_campus)."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, NOW()
															, '2'
														)"
								);
		$std_id = $dblms->lastestid();

		// UPLOAD PROFILE PHOTO
		
		if(isset($_POST['std_photo']) && !empty($_POST['std_photo'])){
			//	CONVERT CROPPED IMAGE DATA
			$data = $_POST['std_photo'];
			$image_array_1 = explode(";", $data);
			$image_array_2 = explode(",", $image_array_1[1]);
			$data = base64_decode($image_array_2[1]);

			//	FILE NAME AND DIRECTORY
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".jpg";
			$img_dir 		= 'uploads/images/students/';
			$originalImage	= $img_dir.$img_fileName;

			//	UPDATE QUERY
			$sqllmsupload  = $dblms->querylms("UPDATE ".STUDENTS." SET
														std_photo		= '".cleanvars($img_fileName)."'
														WHERE std_id	= '".cleanvars($std_id)."' ");
			//	UPLOAD FILE TO DIRECTORY
			if($sqllmsupload){
				file_put_contents($originalImage, $data);
			}
		}
		

		// if(!empty($_FILES['std_photo']['name'])) { 
		// 	$path_parts 	= pathinfo($_FILES["std_photo"]["name"]);
		// 	$extension 		= strtolower($path_parts['extension']);
		// 	$img_dir		= 'uploads/images/students/';
			
		// 	$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
		// 	$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			
		// 	if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'docx', 'pdf'))) {
		// 		$values = array (
		// 							"std_photo"	=>	$img_fileName
		// 						);
		// 		$sqllmsupload = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($std_id)."'");

		// 		unset($sqllmsupload);
		// 		$mode = '0644';			
		// 		move_uploaded_file($_FILES['std_photo']['tmp_name'],$originalImage);
		// 		chmod ($originalImage, octdec($mode));
		// 	}
		// }



		// UPLOAD ID CARD
		if(!empty($_FILES['std_idcard']['name'])) { 
			$path_parts 	= pathinfo($_FILES["std_idcard"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/images/students/id_card/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'docx', 'pdf'))) {
				$values = array (
									"std_idcard"	=>	$img_fileName
								);
				$sqllmsupload = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($std_id)."'");

				unset($sqllmsupload);
				$mode = '0644';			
				move_uploaded_file($_FILES['std_idcard']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		
		// UPLOAD FATHER ID CARD
		if(!empty($_FILES['std_fatheridcard']['name'])) { 
			$path_parts 	= pathinfo($_FILES["std_fatheridcard"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/images/students/father_id_card/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'docx', 'pdf'))) {
				$values = array (
									"std_fatheridcard"	=>	$img_fileName
								);
				$sqllmsupload = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($std_id)."'");

				unset($sqllmsupload);
				$mode = '0644';			
				move_uploaded_file($_FILES['std_fatheridcard']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		// UPLOAD BIRTH CERTIFICATE
		if(!empty($_FILES['std_birthcertificate']['name'])) { 
			$path_parts 	= pathinfo($_FILES["std_birthcertificate"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/images/students/birth_certificate/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'docx', 'pdf'))) {
				$values = array (
									"std_birthcertificate"	=>	$img_fileName
								);
				$sqllmsupload = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($std_id)."'");

				unset($sqllmsupload);
				$mode = '0644';			
				move_uploaded_file($_FILES['std_birthcertificate']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		// UPLOAD LEAVING CERTIFICATE
		if(!empty($_FILES['std_leavingcertificate']['name'])) { 
			$path_parts 	= pathinfo($_FILES["std_leavingcertificate"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/images/students/leaving_certificate/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'docx', 'pdf'))) {
				$values = array (
									"std_leavingcertificate"	=>	$img_fileName
								);
				$sqllmsupload = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($std_id)."'");

				unset($sqllmsupload);
				$mode = '0644';			
				move_uploaded_file($_FILES['std_leavingcertificate']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		// UPLOAD OTHER DOCUMENTS
		if(!empty($_FILES['std_otherdocuments']['name'])) { 
			$path_parts 	= pathinfo($_FILES["std_otherdocuments"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/images/students/other_documents/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'docx', 'pdf'))) {
				$values = array (
									"std_otherdocuments"	=>	$img_fileName
								);
				$sqllmsupload = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($std_id)."'");

				unset($sqllmsupload);
				$mode = '0644';			
				move_uploaded_file($_FILES['std_otherdocuments']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		if($sqllms){
			// $sqllmsfeesetup	= $dblms->querylms("SELECT fs.id, d.id, d.id_setup, d.id_cat, d.amount, c.cat_id, c.cat_name
			// 									FROM ".FEESETUP." fs
			// 									INNER JOIN ".FEESETUPDETAIL." d ON d.id_setup = fs.id 
			// 									INNER JOIN ".FEE_CATEGORY." c ON c.cat_id = d.id_cat												 
			// 									WHERE fs.is_deleted	= '0'
			// 									AND fs.id_campus	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
			// 									AND fs.id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
			// 									AND fs.status		= '1'
			// 									AND fs.id_class		= '".$_POST['id_class']."'
			// 									AND fs.id_section	= '".$_POST['id_section']."'
			// 									ORDER BY c.cat_id ASC");
			// $toalAmount = 0;
			// while($value_feesetup = mysqli_fetch_array($sqllmsfeesetup)){
			// 	$toalAmount = $toalAmount + $value_feesetup['amount'];
			// 	$feeDetail[] = array('id_cat'=>$value_feesetup['id_cat'], 'amount'=>$value_feesetup['amount']);
			// }

			// //-----------------Reformat Date------------------
			// $challandate = date('Ym');
			// $issue_date = $admissiondate;
			// $due_date = date('Y-m-d' , strtotime($issue_date. ' + 15 days'));
			// $id_month = date('m' , strtotime($issue_date));
			
			// //----------------------Challan Number-------------------------
			// $sqllmschallan = $dblms->querylms("SELECT challan_no FROM ".FEES." 
			// 									WHERE challan_no LIKE '".$challandate."%'  
			// 									ORDER by challan_no DESC LIMIT 1 ");
			// $rowchallan = mysqli_fetch_array($sqllmschallan);
			// if(mysqli_num_rows($sqllmschallan) < 1) {
			// 	$challano = $challandate.'00001';
			// }else{
			// 	$challano = ($rowchallan['challan_no'] +1);
			// }

			// $sqllmsFee  = $dblms->querylms("INSERT INTO ".FEES."(
			// 													  status 
			// 													, id_type
			// 													, challan_no 
			// 													, id_session
			// 													, id_month
			// 													, id_class 
			// 													, id_section
			// 													, inquiry_formno
			// 													, id_std
			// 													, issue_date
			// 													, due_date
			// 													, total_amount
			// 													, id_campus
			// 													, id_added
			// 													, date_added
			// 												)
			// 											VALUES(
			// 													  '2'
			// 													, '1'
			// 													, '".cleanvars($challano)."'
			// 													, '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' 
			// 													, '".cleanvars($id_month)."'
			// 													, '".cleanvars($_POST['id_class'])."'
			// 													, '".cleanvars($_POST['id_section'])."'   
			// 													, '".cleanvars($_POST['form_no'])."'
			// 													, '".cleanvars($std_id)."'
			// 													, '".cleanvars($issue_date)."' 
			// 													, '".cleanvars($due_date)."'
			// 													, '".cleanvars($toalAmount)."'
			// 													, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
			// 													, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
			// 													, Now()	
			// 												)"
			// 											);
			// //------------------- Chllans Details ---------------------
			// if($sqllmsFee){
			// 	$challan_id = $dblms->lastestid();

			// 	foreach($feeDetail as $det){
			// 		$sqllms  = $dblms->querylms("INSERT INTO ".FEE_PARTICULARS."(
			// 																		  id_fee
			// 																		, id_cat
			// 																		, amount						
			// 																	)

			// 																VALUES(
			// 																		  '".cleanvars($challan_id)."'
			// 																		, '".cleanvars($det['id_cat'])."'
			// 																		, '".cleanvars($det['amount'])."'			
			// 																	)
			// 									");

			// 	}
			// 	//-------------------- Make Log ------------------------
			// 	$remarks = 'Fee Challan genrate at the time admission.';
			// 	$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
			// 																	  id_user 
			// 																	, filename 
			// 																	, action
			// 																	, challan_no
			// 																	, dated
			// 																	, ip
			// 																	, remarks 
			// 																	, id_campus				
			// 																)

			// 															VALUES(
			// 																	  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
			// 																	, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'
			// 																	, '1'
			// 																	, '".cleanvars($challano)."'
			// 																	, NOW()
			// 																	, '".cleanvars($ip)."'
			// 																	, '".cleanvars($remarks)."'
			// 																	, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
			// 																)
			// 									");
			// }

			// REMARKS
			$remarks = 'Added Student ID: "'.cleanvars($std_id).'" detail';
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
																, '1'
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
																, '".cleanvars($id_campus)."'			
															)
										");
			sessionMsg("Successfully", "Record Successfully Added.", "success");
			header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php?id_campus='.$id_campus.''."", true, 301);
			exit();
		}
	}
}
 
// UPDATE RECORD
if(isset($_POST['changes_student'])) {
	$id_campus = (isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS'];

	if(isset($_POST['filtered_class'])){
		$filter = '?id_campus='.$id_campus.'&id_class='.$_POST['filtered_class'].'&show_students';
	}else{
		$filter = '?id_campus='.$id_campus.'';
	}
	$sqllmscheck  = $dblms->querylms("
										SELECT std_id
										FROM ".STUDENTS."
										WHERE is_deleted = '0'
										AND id_campus = '".cleanvars($id_campus)."'
										AND std_id != '".cleanvars($_POST['std_id'])."'
										AND std_nic = '".cleanvars($_POST['std_nic'])."'
										LIMIT 1
									");

	// $sqllmscheck  = $dblms->querylms("
	// 									SELECT std_id
	// 									FROM ".STUDENTS."
	// 									WHERE is_deleted = '0'
	// 									AND id_campus = '".cleanvars($id_campus)."'
	// 									AND std_id != '".cleanvars($_POST['std_id'])."'
	// 									AND (
	// 										std_nic = '".cleanvars($_POST['std_nic'])."'
	// 										OR std_rollno = '".cleanvars($_POST['std_rollno'])."'
	// 									)
	// 									LIMIT 1
	// 								");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: students.php$filter", true, 301);
		exit();
	}else{
		
		//	DATE VARIABLES
		$dob = date('Y-m-d' , strtotime(cleanvars($_POST['std_dob'])));
		$admissiondate = date('Y-m-d' , strtotime(cleanvars($_POST['std_admissiondate'])));
		$admission_year = date('Y' , strtotime(cleanvars($_POST['std_admissiondate'])));

		//For Campus Short Code
		$sqllmscampus = $dblms->querylms("SELECT campus_code FROM ".CAMPUS." WHERE campus_id = '".cleanvars($id_campus)."' LIMIT 1");
		$value_campus = mysqli_fetch_array($sqllmscampus);

		$campus_code = str_replace('LHS-',"",$value_campus['campus_code']);
		$student_name = substr($_POST['std_name'], 0, 3);

		$sqllms  = $dblms->querylms("UPDATE ".STUDENTS." SET  
														  std_status		=	'".cleanvars($_POST['std_status'])."'
														, std_name			=	'".cleanvars($_POST['std_name'])."' 
														, std_fathername	=	'".cleanvars($_POST['std_fathername'])."' 
														, std_gender		=	'".cleanvars($_POST['std_gender'])."' 
														, id_guardian		=	'".cleanvars($_POST['id_guardian'])."' 
														, std_dob			=	'".cleanvars($dob)."' 
														, std_bloodgroup	=	'".cleanvars($_POST['std_bloodgroup'])."' 
														, std_city			=	'".cleanvars($_POST['std_city'])."' 
														, std_prev_school	=	'".cleanvars($_POST['std_prev_school'])."' 
														, std_familyno		=	'".cleanvars($_POST['std_familyno'])."' 
														, std_nic			=	'".cleanvars($_POST['std_nic'])."' 
														, std_religion		=	'".cleanvars($_POST['std_religion'])."' 
														, std_phone			=	'".cleanvars($_POST['std_phone'])."' 
														, std_whatsapp		=	'".cleanvars($_POST['std_whatsapp'])."' 
														, std_address		=	'".cleanvars($_POST['std_address'])."' 
														, id_class			=	'".cleanvars($_POST['id_class'])."' 
														, id_section		=	'".cleanvars($_POST['id_section'])."' 
														, id_group			=	'".cleanvars($_POST['id_group'])."'
														, std_rollno		=	'".cleanvars($_POST['std_rollno'])."' 
														, admission_formno	=	'".cleanvars($_POST['admission_formno'])."' 
														, std_admissiondate	=	'".cleanvars($admissiondate)."'   
														, id_campus			=	'".cleanvars($id_campus)."' 
														, id_modify			=	'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, date_modify		=	NOW()
														WHERE std_id		=	'".cleanvars($_POST['std_id'])."'");
											
		$std_id = cleanvars($_POST['std_id']);
		
		// UPLOAD PROFILE PHOTO
		/*
		if(isset($_POST['std_photo']) && !empty($_POST['std_photo'])){
			//	CONVERT CROPPED IMAGE DATA
			$data = $_POST['std_photo'];
			$image_array_1 = explode(";", $data);
			$image_array_2 = explode(",", $image_array_1[1]);
			$data = base64_decode($image_array_2[1]);

			//	FILE NAME AND DIRECTORY
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".jpg";
			$img_dir 		= 'uploads/images/students/';
			$originalImage	= $img_dir.$img_fileName;

			//	UPDATE QUERY
			$sqllmsupload  = $dblms->querylms("UPDATE ".STUDENTS." SET
														std_photo		= '".cleanvars($img_fileName)."'
														WHERE std_id	= '".cleanvars($std_id)."' ");
			//	UPLOAD FILE TO DIRECTORY
			if($sqllmsupload){
				file_put_contents($originalImage, $data);
			}
		}
		*/

		if(!empty($_FILES['std_photo']['name'])) { 
			$path_parts 	= pathinfo($_FILES["std_photo"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/images/students/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'docx', 'pdf'))) {
				$values = array (
									"std_photo"	=>	$img_fileName
								);
				$sqllmsupload = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($std_id)."'");

				unset($sqllmsupload);
				$mode = '0644';			
				move_uploaded_file($_FILES['std_photo']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		
		// UPLOAD ID CARD
		if(!empty($_FILES['std_idcard']['name'])) { 
			$path_parts 	= pathinfo($_FILES["std_idcard"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/images/students/id_card/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'docx', 'pdf'))) {
				$values = array (
									"std_idcard"	=>	$img_fileName
								);
				$sqllmsupload = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($std_id)."'");

				unset($sqllmsupload);
				$mode = '0644';			
				move_uploaded_file($_FILES['std_idcard']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		// UPLOAD FATHER ID CARD
		if(!empty($_FILES['std_fatheridcard']['name'])) { 
			$path_parts 	= pathinfo($_FILES["std_fatheridcard"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/images/students/father_id_card/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'docx', 'pdf'))) {
				$values = array (
									"std_fatheridcard"	=>	$img_fileName
								);
				$sqllmsupload = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($std_id)."'");

				unset($sqllmsupload);
				$mode = '0644';			
				move_uploaded_file($_FILES['std_fatheridcard']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		// UPLOAD BIRTH CERTIFICATE
		if(!empty($_FILES['std_birthcertificate']['name'])) { 
			$path_parts 	= pathinfo($_FILES["std_birthcertificate"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/images/students/birth_certificate/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'docx', 'pdf'))) {
				$values = array (
									"std_birthcertificate"	=>	$img_fileName
								);
				$sqllmsupload = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($std_id)."'");

				unset($sqllmsupload);
				$mode = '0644';			
				move_uploaded_file($_FILES['std_birthcertificate']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		// UPLOAD LEAVING CERTIFICATE
		if(!empty($_FILES['std_leavingcertificate']['name'])) { 
			$path_parts 	= pathinfo($_FILES["std_leavingcertificate"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/images/students/leaving_certificate/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'docx', 'pdf'))) {
				$values = array (
									"std_leavingcertificate"	=>	$img_fileName
								);
				$sqllmsupload = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($std_id)."'");

				unset($sqllmsupload);
				$mode = '0644';			
				move_uploaded_file($_FILES['std_leavingcertificate']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		// UPLOAD OTHER DOCUMENTS
		if(!empty($_FILES['std_otherdocuments']['name'])) { 
			$path_parts 	= pathinfo($_FILES["std_otherdocuments"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir		= 'uploads/images/students/other_documents/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['std_name'])).'-'.$std_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'docx', 'pdf'))) {
				$values = array (
									"std_otherdocuments"	=>	$img_fileName
								);
				$sqllmsupload = $dblms->Update(STUDENTS , $values , "WHERE std_id = '".cleanvars($std_id)."'");

				unset($sqllmsupload);
				$mode = '0644';			
				move_uploaded_file($_FILES['std_otherdocuments']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		
		if($sqllms) { 
			$remarks = 'Updated Student ID: "'.cleanvars($std_id).'" details';
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
																, '".cleanvars($id_campus)."'	
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: students.php$filter", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$sqllms  = $dblms->querylms("UPDATE ".STUDENTS." SET  
												  is_deleted	=	'1'
												, id_deleted	=	'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted	=	'".$ip."'
												, date_deleted	=	NOW()
												  WHERE std_id	=	'".cleanvars($_GET['deleteid'])."'");
	if($sqllms) {
		$remarks = 'Student Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: students.php", true, 301);
		exit();
	}
}

// IMPORT RECORD
if (isset($_POST['import_submit'])) {
	foreach ($_POST['student_name'] as $key => $value) {
		// DATE VARIABLES
		$dob = date('Y-m-d',strtotime(cleanvars(strtotime($_POST['date_of_birth'][$key]))));
		$admissiondate = date('Y-m-d');
		$admission_year = date('Y');

		//--------------- Roll No -----------------
		$newRollno = 0;
		$sqllmsRoll	= $dblms->querylms("SELECT MAX(std_rollno) as rollno
										FROM ".STUDENTS."
										WHERE id_campus = '".$_POST['id_campus']."'
										AND id_class = '".$_POST['id_class']."'");
		if(mysqli_num_rows($sqllmsRoll) > 0 ){
			$valueRoll = mysqli_fetch_array($sqllmsRoll);
			(int)$valueRoll['rollno'];
			$newRollno = (int)$valueRoll['rollno'] + 1;
		} else {
			$newRollno = 1;
		}

		// GENERATE REGISTERATION NUMBER 
		$sqllmscampus = $dblms->querylms("SELECT  c.campus_code, cg.group_code_numeric, b.brand_code_numeric, d.dist_code
													FROM ".CAMPUS." c 
													INNER JOIN ".CAMPUS_GROUPS." cg ON cg.group_id = c.id_group
													INNER JOIN ".BRANDS." b ON b.brand_id = c.id_brand
													INNER JOIN ".DISTRICTS." d ON d.dist_id  = c.id_dist
													WHERE c.is_deleted = '0'
													AND c.campus_id = '".cleanvars($_POST['id_campus'])."'
													LIMIT 1
												");
		$value_campus = mysqli_fetch_array($sqllmscampus);

		$regnoStr = STD_PREFIX.$value_campus['group_code_numeric'].$value_campus['brand_code_numeric'].'-'.$value_campus['dist_code'].$value_campus['campus_code'].'-'.substr(date("Y"), -2);

		$sqllmsstudentregno = $dblms->querylms("SELECT std_regno FROM ".STUDENTS." 
													WHERE std_regno LIKE '".$regnoStr."%'
													AND id_campus = '".cleanvars($_POST['id_campus'])."'
													ORDER by std_regno DESC LIMIT 1 ");
		$value_regno = mysqli_fetch_array($sqllmsstudentregno);
		if(mysqli_num_rows($sqllmsstudentregno) < 1) {
			$regno	= $regnoStr.'-0001';
		}else{
			$regno = $value_regno['std_regno'];
			$regno++;
		}

		$con = array (
						 'std_status' 		=>	$_POST['std_status']
						,'std_rollno' 		=>	$newRollno
						,'std_regno' 		=>	$regno
						,'admission_formno' =>	$_POST['admission_id'][$key]
						,'std_name' 		=>	$_POST['student_name'][$key]
						,'std_fathername' 	=>	$_POST['father_name'][$key]
						,'std_familyno' 	=>	$_POST['family_no'][$key]
						,'std_gender' 		=>	$_POST['gender'][$key]
						,'std_dob' 			=>	date('Y-m-d',strtotime($_POST['date_of_birth'][$key]))
						,'std_admissiondate'=>	date('Y-m-d',strtotime($_POST['adm_date'][$key]))
						,'std_nic' 			=>	$_POST['cnic'][$key]
						,'std_phone' 		=>	$_POST['mobile_1'][$key]
						,'std_whatsapp' 	=>	$_POST['mobile_2'][$key]
						,'std_address' 		=>	$_POST['adress'][$key]
						,'id_campus' 		=>	$_POST['id_campus']
						,'id_class' 		=>	$_POST['id_class']
						,'is_hostel' 		=>	$_POST['is_hostel']
						,'id_section' 		=>	$_POST['id_section']
						,'id_session' 		=>	$_SESSION['userlogininfo']['ACADEMICSESSION']
						,'id_added' 		=>	$_SESSION['userlogininfo']['LOGINIDA']
						,'date_added' 		=>	date('Y-m-d h:i:s')
		);

		$sqllms = $dblms->insert(STUDENTS, $con, $insert);

		$std_id = $dblms->lastestid();
		// REMARKS
		$remarks = 'Imported Student ID: "'.cleanvars($std_id).'" detail';
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
															, '1'
															, NOW()
															, '".cleanvars($ip)."'
															, '".cleanvars($remarks)."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														)
									");
	}

		if($sqllms){
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: students.php", true, 301);
			exit();
		}
}
?>