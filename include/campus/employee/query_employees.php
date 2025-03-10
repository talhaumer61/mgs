<?php 
// INSERT RECORD
if(isset($_POST['submit_emply'])) { 
    $id_campus = (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']); 

	$sqllmscheck  = $dblms->querylms("SELECT emply_regno
										FROM ".EMPLOYEES."
										WHERE id_campus = '".cleanvars($id_campus)."'
										AND emply_regno = '".cleanvars($_POST['emply_regno'])."'
										AND is_deleted	= '0'  LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: employee.php?id_campus=$id_campus", true, 301);
		exit();
	} else { 
		// Reformating
		$dob = date('Y-m-d' , strtotime(cleanvars($_POST['emply_dob'])));
		$join_date = date('Y-m-d' , strtotime(cleanvars($_POST['emply_joindate'])));
		$class = implode(',', $_POST['id_class']);
	
		$sqllms  = $dblms->querylms("INSERT INTO ".EMPLOYEES."(
														  emply_status
														, emply_ordering 
														, emply_regno
														, emply_name 
														, emply_nic 
														, id_dept 
														, id_designation
														, id_type
														, id_class
														, emply_gender
														, emply_dob 
														, emply_joindate 
														, emply_education 
														, emply_experence
														, emply_religion
														, emply_bloodgroup  
														, emply_address 
														, emply_phone 
														, emply_email
														, emply_whatsapp
														, id_campus
														, id_added
														, date_added
													  )
	   											VALUES(
														  '".cleanvars($_POST['emply_status'])."'
														, '".cleanvars($_POST['emply_ordering'])."'
														, '".cleanvars($_POST['emply_regno'])."'
														, '".cleanvars($_POST['emply_name'])."'
														, '".cleanvars($_POST['emply_nic'])."'
														, '".cleanvars($_POST['id_dept'])."'	
														, '".cleanvars($_POST['id_designation'])."'
														, '".cleanvars($_POST['id_type'])."'
														, '".cleanvars($class)."'
														, '".cleanvars($_POST['emply_gender'])."'
														, '".cleanvars($dob)."'
														, '".cleanvars($join_date)."'	
														, '".cleanvars($_POST['emply_education'])."'
														, '".cleanvars($_POST['emply_experence'])."'
														, '".cleanvars($_POST['emply_religion'])."'
														, '".cleanvars($_POST['emply_bloodgroup'])."'	
														, '".cleanvars($_POST['emply_address'])."'
														, '".cleanvars($_POST['emply_phone'])."'
														, '".cleanvars($_POST['emply_email'])."'
														, '".cleanvars($_POST['emply_whatsapp'])."'
														, '".cleanvars($id_campus)."'
														, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, Now()
													)
							");
		$idsetup = $dblms->lastestid();	

		// FILE UPLOAD
		if(!empty($_FILES['emply_photo']['name'])) { 
			$img_dir		= "uploads/images/employees/";
			// $filesize		= formatSizeUnits($_FILES["dwnl_file"]["size"]);
			$path_parts 	= pathinfo($_FILES["emply_photo"]["name"]);
			$extension 		= strtolower($path_parts['extension']);

			if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 

				if (($_FILES["emply_photo"]["size"] > 200000)) {
					$_SESSION['msg']['status'] = '<div role="alert" class="alert alert-danger fade in"> <strong>Error!</strong> Uploaded image size should be less than <b>"200 KB"</b>.</div>';
				} else {
					$originalImage	= $img_dir.to_seo_url($_POST['emply_regno']).".".strtolower($extension);
					$img_fileName	= to_seo_url($_POST['emply_regno']).".".strtolower($extension);

					$sqllms  = $dblms->querylms("UPDATE ".EMPLOYEES." SET  
																	emply_photo		= '".cleanvars($img_fileName)."'
																	WHERE emply_id	= '".cleanvars($idsetup)."'
															");		
						//unset($sqllmsupload);
						$mode = '0644'; 	
						move_uploaded_file($_FILES['emply_photo']['tmp_name'],$originalImage);
						chmod ($originalImage, octdec($mode));
						$_SESSION['msg']['status'] = '<div role="alert" class="alert alert-success"> <strong>Success!</strong> Record update successfully. </div>';
				}
			} else { 
				$_SESSION['msg']['status'] = '<div role="alert" class="alert alert-danger fade in"> <strong>Error!</strong> Upload valid Image. Only PNG, GIF, JPG and JPEG are allowed. </div>';
			}
		}

		// REMARKS
		if($sqllms) { 
			$remarks = 'Add Employee: "'.cleanvars($_POST['emply_name']).'" Detail';
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
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: employee.php?id_campus=$id_campus", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_employee'])) { 
    $id_campus = (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']); 

	$sqllmscheck  = $dblms->querylms("SELECT emply_regno
										FROM ".EMPLOYEES."
										WHERE id_campus = '".cleanvars($id_campus)."'
										AND emply_regno = '".cleanvars($_POST['emply_regno'])."'
										AND is_deleted	= '0'
										AND emply_id   != '".cleanvars($_POST['emply_id'])."'  LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: employee.php?id_campus=$id_campus", true, 301);
		exit();
	} else { 
		// Reformating
		$dob = date('Y-m-d' , strtotime(cleanvars($_POST['emply_dob'])));
		$join_date = date('Y-m-d' , strtotime(cleanvars($_POST['emply_joindate'])));
		$class = implode(',', $_POST['id_class']);
		
		$sqllms  = $dblms->querylms("UPDATE ".EMPLOYEES." SET  
												  emply_status		= '".cleanvars($_POST['emply_status'])."'
												, emply_regno		= '".cleanvars($_POST['emply_regno'])."' 
												, emply_name		= '".cleanvars($_POST['emply_name'])."' 
												, emply_nic			= '".cleanvars($_POST['emply_nic'])."' 
												, id_dept			= '".cleanvars($_POST['id_dept'])."' 
												, id_designation	= '".cleanvars($_POST['id_designation'])."' 
												, id_type			= '".cleanvars($_POST['id_type'])."' 
												, id_class			= '".cleanvars($class)."' 
												, emply_gender		= '".cleanvars($_POST['emply_gender'])."' 
												, emply_dob			= '".cleanvars($dob)."' 
												, emply_joindate	= '".cleanvars($join_date)."' 
												, emply_education	= '".cleanvars($_POST['emply_education'])."' 
												, emply_experence	= '".cleanvars($_POST['emply_experence'])."' 
												, emply_religion	= '".cleanvars($_POST['emply_religion'])."' 
												, emply_bloodgroup	= '".cleanvars($_POST['emply_bloodgroup'])."' 
												, emply_address		= '".cleanvars($_POST['emply_address'])."' 
												, emply_phone		= '".cleanvars($_POST['emply_phone'])."' 
												, emply_email		= '".cleanvars($_POST['emply_email'])."' 
												, id_campus			= '".cleanvars($id_campus)."'
												, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
												, date_modify		= Now()
   											  	  WHERE emply_id	= '".cleanvars($_POST['emply_id'])."'");
		// Update Image
		if(!empty($_FILES['emply_photo']['name'])) { 
			$img_dir		= "uploads/images/employees/";
			//	$filesize		= formatSizeUnits($_FILES["dwnl_file"]["size"]);
			$path_parts 	= pathinfo($_FILES["emply_photo"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
		
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) { 
		
				if (($_FILES["emply_photo"]["size"] > 200000)) {
					$_SESSION['msg']['status'] = '<div role="alert" class="alert alert-danger fade in"> <strong>Error!</strong> Uploaded image size should be less than <b>"200 KB"</b>.</div>';
				} else {
					$originalImage	= $img_dir.to_seo_url($_POST['emply_regno']).".".strtolower($extension);
					$img_fileName	= to_seo_url($_POST['emply_regno']).".".strtolower($extension);
				
					$sqllms  = $dblms->querylms("UPDATE ".EMPLOYEES." SET  
																	emply_photo		= '".cleanvars($img_fileName)."'
															WHERE emply_id		= '".cleanvars($_POST['emply_id'])."'
															");		
					//unset($sqllmsupload);
					$mode = '0644'; 	
					move_uploaded_file($_FILES['emply_photo']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
					$_SESSION['msg']['status'] = '<div role="alert" class="alert alert-success"> <strong>Success!</strong> Record update successfully. </div>';
				}	
			} else { 
				$_SESSION['msg']['status'] = '<div role="alert" class="alert alert-danger fade in"> <strong>Error!</strong> Upload valid Image. Only PNG, GIF, JPG and JPEG are allowed. </div>';
			}
		}

		// REMARKS
		if($sqllms) { 
			$remarks = 'Update Employee: "'.cleanvars($_POST['employee_name']).'" Detail';
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
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: employee.php?id_campus=$id_campus", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$id_campus = (!empty($_GET['id_campus']) ? $_GET['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
	
	$sqllms  = $dblms->querylms("UPDATE ".EMPLOYEES." SET  
												  is_deleted				= '1'
												, id_deleted				= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted				= '".$ip."'
												, date_deleted				= NOW()
												  WHERE emply_id			= '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		$remarks = 'Employee Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: employee.php?id_campus=$id_campus", true, 301);
		exit();
	}
}

//----------------Bank Deatils insert record----------------------
if(isset($_POST['submit_account'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT account_name  
										FROM ".EMPLOYEES_BANKACCOUNTS." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND account_no = '".cleanvars($_POST['account_no'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: employee.php", true, 301);
		exit();
	} else { 
		$sqllms  = $dblms->querylms("INSERT INTO ".EMPLOYEES_BANKACCOUNTS."(
																		status						, 
																		id_emply					,
																		id_bank						, 
																		branch						, 
																		account_name				,
																		account_no					,
																		account_type				, 
																		id_campus 	
													 					 )
	   															VALUES(
																		'".cleanvars($_POST['status'])."'							, 
																		'".cleanvars($_POST['id_emply'])."'							,
																		'".cleanvars($_POST['id_bank'])."'							,
																		'".cleanvars($_POST['branch'])."'							,
																		'".cleanvars($_POST['account_name'])."'						,
																		'".cleanvars($_POST['account_no'])."'						,
																		'".cleanvars($_POST['account_type'])."'						,
																		'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	
													 				 )"
																	);
		if($sqllms) { 
			$remarks = 'Add Employee Bank Account: "'.cleanvars($_POST['account_no']).'" detail';
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
																'1'											, 
																NOW()										,
																'".cleanvars($ip)."'						,
																'".cleanvars($remarks)."'						,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: employee.php", true, 301);
			exit();
		}
	} 
} 

//----------------Employee Bank Account update reocrd----------------------
if(isset($_POST['changes_account'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".EMPLOYEES_BANKACCOUNTS." SET  
																	status				= '".cleanvars($_POST['status'])."'
												  				  , id_emply			= '".cleanvars($_POST['id_emply'])."' 
												 				  , id_bank				= '".cleanvars($_POST['id_bank'])."' 
												  				  , branch				= '".cleanvars($_POST['branch'])."' 
												  				  , account_name		= '".cleanvars($_POST['account_name'])."' 
												  				  , account_no			= '".cleanvars($_POST['account_no'])."' 
												  				  , account_type		= '".cleanvars($_POST['account_type'])."' 
												 				  , id_campus			= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
   											 				  WHERE id					= '".cleanvars($_POST['id'])."'  LIMIT 1");
	if($sqllms) { 
		$remarks = 'Update Employee Bank Account: "'.cleanvars($_POST['account_no']).'" details';
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
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'	, 
															'2'																, 
															NOW()															,
															'".cleanvars($ip)."'											,
															'".cleanvars($remarks)."'										,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														  )
									");
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: employee.php", true, 301);
		exit();
	}
}
// IMPORT RECORD
if (isset($_POST['import_submit'])) {
	foreach ($_POST['employee_name'] as $key => $value) {

		$sqllms	= $dblms->querylms("SELECT e.emply_regno, e.emply_ordering, c.campus_regno 
								FROM ".CAMPUS." c 
								LEFT JOIN ".EMPLOYEES." e ON c.campus_id = e.id_campus 
								WHERE c.campus_id = '".$_POST['id_campus']."' 
								ORDER BY e.emply_id DESC LIMIT 1");
		$rowsemployee = mysqli_fetch_array($sqllms);

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

		$regnoStr = EMP_PREFIX.$value_campus['group_code_numeric'].$value_campus['brand_code_numeric'].'-'.$value_campus['dist_code'].$value_campus['campus_code'].'-'.substr(date("Y"), -2);
		// REMOVE NULL SPACES
		$regnoStr = str_replace(' ', '', $regnoStr);
		$sqllmsstudentregno = $dblms->querylms("SELECT emply_regno FROM ".EMPLOYEES." 
												WHERE emply_regno LIKE '".$regnoStr."%'
												AND id_campus = '".cleanvars($_POST['id_campus'])."'
												ORDER by emply_regno DESC LIMIT 1 ");
		$value_regno = mysqli_fetch_array($sqllmsstudentregno);
		if(mysqli_num_rows($sqllmsstudentregno) < 1) {
			$regno	= $regnoStr.'-0001';
		}else{
			$regno = $value_regno['emply_regno'];
			$regno++;
		}

		$order = $rowsemployee['emply_ordering'] + 1;

		$con = array (
						 'emply_status' 	=>	$_POST['emply_status']
						,'emply_ordering' 	=>	$order
						,'emply_regno' 		=>	$regno
						,'emply_name' 		=>	$_POST['employee_name'][$key]
						,'id_type' 			=>	$_POST['id_type']
						,'id_dept' 			=>	$_POST['id_dept']
						,'id_designation' 	=>	$_POST['id_designation']
						,'emply_gender' 	=>	$_POST['gender'][$key]
						,'emply_dob' 		=>	date('Y-m-d',strtotime($_POST['date_of_birth'][$key]))
						,'emply_nic' 		=>	$_POST['cnic'][$key]
						,'emply_phone' 		=>	$_POST['phone'][$key]
						,'emply_whatsapp' 	=>	$_POST['phone'][$key]
						,'emply_email' 		=>	$_POST['email'][$key]
						,'emply_photo' 		=>	'defualt.png'
						,'id_campus' 		=>	$_POST['id_campus']
						,'id_added' 		=>	$_SESSION['userlogininfo']['LOGINIDA']
						,'date_added' 		=>	date('Y-m-d h:i:s')
		);

		$sqllms = $dblms->insert(EMPLOYEES, $con, $insert);
		
		$emply_id = $dblms->lastestid();
		// REMARKS
		$remarks = 'Imported Emplyoee ID: "'.cleanvars($emply_id).'" detail';
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
		header("Location: employee.php", true, 301);
		exit();
	}
}
?>
