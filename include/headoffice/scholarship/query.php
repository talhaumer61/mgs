<?php 
//----------------Scholarship Cat insert record----------------------
if(isset($_POST['submit_cat'])) {
	$sqllmscheck  = $dblms->querylms("SELECT cat_name, cat_type  
										FROM ".SCHOLARSHIP_CAT." 
										WHERE cat_name = '".cleanvars($_POST['cat_name'])."'
										AND cat_type = '1'
										AND is_deleted = '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: scholarship_category.php", true, 301);
		exit();
	}else{ 
	$sqllms  = $dblms->querylms("INSERT INTO ".SCHOLARSHIP_CAT."(
														  cat_status 
														, cat_type
														, cat_name
														, cat_detail 
														, id_campus							 	
													  )
	   											VALUES(
														  '".cleanvars($_POST['cat_status'])."' 
														, '1'
														, '".cleanvars($_POST['cat_name'])."' 
														, '".cleanvars($_POST['cat_detail'])."' 
														, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
													  )"
								);
		if($sqllms) {
			$remarks = 'Add Scholarship Category: "'.cleanvars($_POST['cat_name']).'" detail';
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
			header("Location: scholarship_category.php", true, 301);
			exit();
		}
	} // end checker
} 

//----------------Scholarship Cat Update reocrd----------------------
if(isset($_POST['changes_cat'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT cat_name, cat_type  
										FROM ".SCHOLARSHIP_CAT." 
										WHERE cat_name = '".cleanvars($_POST['cat_name'])."'
										AND cat_type = '1'
										AND is_deleted = '0'
										AND cat_id != '".cleanvars($_POST['cat_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: scholarship_category.php", true, 301);
		exit();
	}else{ 
		$sqllms  = $dblms->querylms("UPDATE ".SCHOLARSHIP_CAT." SET  
														cat_status	= '".cleanvars($_POST['cat_status'])."'
														, cat_name		= '".cleanvars($_POST['cat_name'])."'	
														, cat_detail	= '".cleanvars($_POST['cat_detail'])."' 
														, id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														WHERE cat_id	= '".cleanvars($_POST['cat_id'])."'");

		if($sqllms) { 
			$remarks = 'Update Scholarship Category: "'.cleanvars($_POST['cat_name']).'" details';
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
			header("Location: scholarship_category.php", true, 301);
			exit();
		}
	}
}
?>