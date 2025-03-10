<?php 
// INSERT RECORD
if(isset($_POST['submit_particular'])){
	$sqllmscheck  = $dblms->querylms("SELECT part_name  
										FROM ".ROYALTY_PARTICULARS." 
										WHERE part_name	= '".cleanvars($_POST['part_name'])."'
										AND is_deleted	= '0' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: royaltyParticulars.php", true, 301);
		exit();
	}else{
		$amountType = 0;
		$partFor = '';
		$months = '';
		
		// FIXED(1) OR PERCENT(2)
		if($_POST['part_amount_type']) {
			$amountType = $_POST['part_amount_type'];
		}
		// STUDENT(1), CLASS(2), LUMP SUM(3)
		if($_POST['part_for']){
			$partFor = $_POST['part_for'];
		}
		// MONTHS
		if($_POST['part_months']){
			$months = implode(",",$_POST['part_months']);
		}
		// INSERT QUERY
		$sqllms  = $dblms->querylms("INSERT INTO ".ROYALTY_PARTICULARS."(
															  part_status	 
															, part_name
															, part_for
															, part_type
															, part_amount_type
															, part_months
															, part_detail 
															, id_added
															, date_added
														)
													VALUES(
															  '".cleanvars($_POST['part_status'])."' 
															, '".cleanvars($_POST['part_name'])."'
															, '".cleanvars($partFor)."'
															, '".cleanvars($_POST['part_type'])."'
															, '".cleanvars($amountType)."'
															, '".cleanvars($months)."'
															, '".cleanvars($_POST['part_detail'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, Now()														
														)
									");
		// LATEST ID
		$latestID = $dblms->lastestid();	
		// REMARKS
		if($sqllms){
			$remarks = 'Add Royalty Particular ID#'.$latestID.'';
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
			header("Location: royaltyParticulars.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_particular'])){
	$sqllmscheck  = $dblms->querylms("SELECT part_name  
										FROM ".ROYALTY_PARTICULARS." 
										WHERE part_name	= '".cleanvars($_POST['part_name'])."'
										AND is_deleted	= '0'
										AND part_id	   != '".cleanvars($_POST['part_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: royaltyParticulars.php", true, 301);
		exit();
	}else{
		$amountType = 0;
		$partFor = '';
		$months = '';
		
		// FIXED(1) OR PERCENT(2)
		if($_POST['part_amount_type']) {
			$amountType = $_POST['part_amount_type'];
		}
		// STUDENT(1), CLASS(2), LUMP SUM(3)
		if($_POST['part_for']){
			$partFor = $_POST['part_for'];
		}
		// MONTHS
		if($_POST['part_months']){
			$months = implode(",",$_POST['part_months']);
		}
		// UPDATE QUERY
		$sqllms  = $dblms->querylms("UPDATE ".ROYALTY_PARTICULARS." SET  
														  part_status		= '".cleanvars($_POST['part_status'])."'
														, part_name			= '".cleanvars($_POST['part_name'])."' 
														, part_for			= '".cleanvars($partFor)."' 
														, part_type			= '".cleanvars($_POST['part_type'])."' 
														, part_amount_type	= '".cleanvars($amountType)."' 
														, part_months		= '".cleanvars($months)."' 
														, part_detail		= '".cleanvars($_POST['part_detail'])."'
														, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		
														, date_modify		= Now()
														  WHERE part_id		= '".cleanvars($_POST['part_id'])."'");
		// REMARKS
		if($sqllms){
			$remarks = 'Update Particular ID#"'.cleanvars($_POST['part_id']).'"';
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
			header("Location: royaltyParticulars.php", true, 301);
			exit();
		}
	}
}
?>