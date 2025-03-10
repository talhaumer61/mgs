<?php 
// INSERT EARNING
if(isset($_POST['submit_earning'])){
	$dated = date('Y-m-d', strtotime($_POST['dated']));
	$due_date = date('Y-m-d', strtotime($_POST['due_date']));
	$values = array (
						 'trans_status'		=>	'1'
						,'trans_title'		=>	cleanvars($_POST['trans_title'])
						,'trans_type'		=>	'1'
						,'trans_amount'		=>	cleanvars($_POST['trans_amount'])
						,'voucher_no'		=>	cleanvars($_POST['voucher_no'])
						,'bill_number'		=>	cleanvars($_POST['bill_number'])
						,'trans_method'		=>	cleanvars($_POST['trans_method'])
						,'trans_note'		=>	cleanvars($_POST['trans_note'])
						,'dated'			=>	cleanvars($dated)
						,'due_date'			=>	cleanvars($due_date)
						,'id_head'			=>	cleanvars($_POST['id_head'])
						,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						,'id_added'			=>	$_SESSION['userlogininfo']['LOGINIDA']
						,'date_added'		=>	date('Y-m-d h:i:s')
					);
	$sqlInsert  = $dblms->insert(ACCOUNT_TRANS, $values);
	
	// LATEST ID
	$latestId = $dblms->lastestid();

	// REMARKS
	if($sqlInsert){		
		$remarks = 'Add Earning ID: "'.cleanvars($latestId).'" detail';
		$values = array (
							 'id_user'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
							,'action'		=>	'1'
							,'dated'		=>	date('Y-m-d h:i:s')
							,'ip'			=>	cleanvars($ip)
							,'remarks'		=>	cleanvars($remarks)
							,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);
		$sqlRemarks  = $dblms->insert(LOGS, $values);
		
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: earning.php", true, 301);
		exit();
	}
}

// UPDATE EARNING
if(isset($_POST['changes_earning'])){
	$dated = date('Y-m-d', strtotime($_POST['dated']));
	$due_date = date('Y-m-d', strtotime($_POST['due_date']));
	$values = array (
						 'trans_status'		=>	'1'
						,'trans_title'		=>	cleanvars($_POST['trans_title'])
						,'trans_type'		=>	'1'
						,'trans_amount'		=>	cleanvars($_POST['trans_amount'])
						,'voucher_no'		=>	cleanvars($_POST['voucher_no'])
						,'bill_number'		=>	cleanvars($_POST['bill_number'])
						,'trans_method'		=>	cleanvars($_POST['trans_method'])
						,'trans_note'		=>	cleanvars($_POST['trans_note'])
						,'dated'			=>	cleanvars($dated)
						,'due_date'			=>	cleanvars($due_date)
						,'id_head'			=>	cleanvars($_POST['id_head'])
						,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						,'id_modify'		=>	$_SESSION['userlogininfo']['LOGINIDA']
						,'date_modify'		=>	date('Y-m-d h:i:s')
					);
	$sqlUpdate = $dblms->Update(ACCOUNT_TRANS , $values , "WHERE trans_id = '".cleanvars($_POST['trans_id'])."'");
	
	// LATEST ID
	$latestId = cleanvars($_POST['trans_id']);

	// REMARKS
	if($sqlUpdate){		
		$remarks = 'Update Earning ID: "'.cleanvars($latestId).'" detail';
		$values = array (
							 'id_user'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
							,'action'		=>	'2'
							,'dated'		=>	date('Y-m-d h:i:s')
							,'ip'			=>	cleanvars($ip)
							,'remarks'		=>	cleanvars($remarks)
							,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);
		$sqlRemarks  = $dblms->insert(LOGS, $values);
		
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'info';
		header("Location: earning.php", true, 301);
		exit();
	}
}

// INSERT COSTING
if(isset($_POST['submit_costing'])){
	$dated = date('Y-m-d', strtotime($_POST['dated']));
	$values = array (
						 'trans_status'		=>	'1'
						,'trans_title'		=>	cleanvars($_POST['trans_title'])
						,'trans_type'		=>	'2'
						,'trans_amount'		=>	cleanvars($_POST['trans_amount'])
						,'voucher_no'		=>	cleanvars($_POST['voucher_no'])
						,'trans_method'		=>	cleanvars($_POST['trans_method'])
						,'trans_note'		=>	cleanvars($_POST['trans_note'])
						,'dated'			=>	cleanvars($dated)
						,'id_head'			=>	cleanvars($_POST['id_head'])
						,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						,'id_added'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_added'		=>	date('Y-m-d h:i:s')
					);
	$sqlInsert  = $dblms->insert(ACCOUNT_TRANS, $values);
	
	// LATEST ID
	$latestId = $dblms->lastestid();

	// REMARKS
	if($sqlInsert){
		// UPLOAD FILE
		if(!empty($_FILES['receipt_image']['name'])){
			//File Extension
			$path_parts	= pathinfo($_FILES["receipt_image"]["name"]);
			$extension	= strtolower($path_parts['extension']);
			//Check File extension
			if(in_array($extension , array('jpeg','jpg', 'png',  'JPEG' , 'JPG' , 'PNG'))){
				// FILE PATH AND NAME
				$img_dir		= 'uploads/images/expense-receipt/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['trans_title'])).'-'.$latestId.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_POST['trans_title'])).'-'.$latestId.".".($extension);

				//Update File Name in DB
				$values = array (
									'receipt_image'	=>	cleanvars($img_fileName)
								);
				$sqllmsupload = $dblms->Update(ACCOUNT_TRANS , $values , "WHERE trans_id = '".cleanvars($latestId)."'");

				unset($sqllmsupload);
				//Move File to the Directory
				$mode = '0644';
				move_uploaded_file($_FILES['receipt_image']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		$remarks = 'Add Costing ID: "'.cleanvars($latestId).'" detail';
		$values = array (
							 'id_user'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
							,'action'		=>	'1'
							,'dated'		=>	date('Y-m-d h:i:s')
							,'ip'			=>	cleanvars($ip)
							,'remarks'		=>	cleanvars($remarks)
							,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);
		$sqlRemarks  = $dblms->insert(LOGS, $values);
		
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: costing.php", true, 301);
		exit();
	}
}

// UPDATE COSTING
if(isset($_POST['changes_costing'])){
	$dated = date('Y-m-d', strtotime($_POST['dated']));
	$values = array (
						 'trans_status'		=>	'1'
						,'trans_title'		=>	cleanvars($_POST['trans_title'])
						,'trans_type'		=>	'2'
						,'trans_amount'		=>	cleanvars($_POST['trans_amount'])
						,'voucher_no'		=>	cleanvars($_POST['voucher_no'])
						,'trans_method'		=>	cleanvars($_POST['trans_method'])
						,'trans_note'		=>	cleanvars($_POST['trans_note'])
						,'dated'			=>	cleanvars($dated)
						,'id_head'			=>	cleanvars($_POST['id_head'])
						,'id_campus'		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						,'id_modify'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'date_modify'		=>	date('Y-m-d h:i:s')
					);
	$sqlUpdate = $dblms->Update(ACCOUNT_TRANS , $values , "WHERE trans_id = '".cleanvars($_POST['trans_id'])."'");
	
	// LATEST ID
	$latestId = cleanvars($_POST['trans_id']);

	// REMARKS
	if($sqlUpdate){
		// UPLOAD FILE
		if(!empty($_FILES['receipt_image']['name'])){
			//File Extension
			$path_parts	= pathinfo($_FILES["receipt_image"]["name"]);
			$extension	= strtolower($path_parts['extension']);
			//Check File extension
			if(in_array($extension , array('jpeg','jpg', 'png',  'JPEG' , 'JPG' , 'PNG'))){
				// FILE PATH AND NAME
				$img_dir		= 'uploads/images/expense-receipt/';
				$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['trans_title'])).'-'.$latestId.".".($extension);
				$img_fileName	= to_seo_url(cleanvars($_POST['trans_title'])).'-'.$latestId.".".($extension);

				//Update File Name in DB
				$values = array (
									'receipt_image'	=>	cleanvars($img_fileName)
								);
				$sqllmsupload = $dblms->Update(ACCOUNT_TRANS , $values , "WHERE trans_id = '".cleanvars($latestId)."'");

				unset($sqllmsupload);
				//Move File to the Directory
				$mode = '0644';
				move_uploaded_file($_FILES['receipt_image']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		$remarks = 'Update Costing ID: "'.cleanvars($latestId).'" detail';
		$values = array (
							 'id_user'		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
							,'action'		=>	'2'
							,'dated'		=>	date('Y-m-d h:i:s')
							,'ip'			=>	cleanvars($ip)
							,'remarks'		=>	cleanvars($remarks)
							,'id_campus'	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);
		$sqlRemarks  = $dblms->insert(LOGS, $values);
		
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'info';
		header("Location: costing.php", true, 301);
		exit();
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$sqllms  = $dblms->querylms("UPDATE ".ACCOUNT_TRANS." SET  
												  is_deleted	=	'1'
												, id_deleted	=	'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted	=	'".$ip."'
												, date_deleted	=	NOW()
												  WHERE trans_id=	'".cleanvars($_GET['deleteid'])."'");
	if($sqllms) {
		$remarks = 'Costing Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
		header("Location: costing.php", true, 301);
		exit();
	}
}
?>