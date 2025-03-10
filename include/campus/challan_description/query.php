<?php 
// INSERT RECORD
if(isset($_POST['add_ChallanDes'])) {
	$sqllmscheck = array ( 
							'select' 	=> '
												chl_desc_id 
											',
							'where' 	=> array( 
													  'is_deleted'  	=> '0'
													, 'id_class'   		=> '0'
													, 'late_fee_type'   => cleanvars($_POST['late_fee_type'])
													, 'chl_desc'    	=> cleanvars($_POST['chl_desc'])
													, 'id_campus'		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
												),
							'return_type' 	=> 'count' 
						); 
	$rowsQueryCheck  = $dblms->getRows(CHALLAN_DESCRIPTION, $sqllmscheck);
	if($rowsQueryCheck) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: challan_description.php", true, 301);
		exit();
	} else { 
		$late_fee_type_comma_sep = $_POST['id_latefeetype'].','.implode(',', $_POST['late_fee_type']);
		$values = array (
							"chl_desc_status"	=>	2
						);		
		$sqllms = $dblms->Update(CHALLAN_DESCRIPTION , $values , "WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'");

		$values = array (
							 "chl_desc_status"	=>	cleanvars($_POST['chl_desc_status'])
							,"late_fee_type"	=>	cleanvars($late_fee_type_comma_sep)
							,"id_class"			=>	'0'
							,"id_campus"		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,"chl_desc"			=>	cleanvars($_POST['chl_desc'])
							,"id_added"			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_added"		=>	date('Y-m-d h:i:s')
						);	
		$sqllms	= $dblms->insert(CHALLAN_DESCRIPTION, $values);

		if($sqllms) { 
			$latestID = $dblms->lastestid();
			sendRemark("Challan Desciption Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: challan_description.php", true, 301);
			exit();
		}
	} 
}

// UPDATE RECORD
if(isset($_POST['update_ChallanDes'])) {
	$id = cleanvars($_POST['chl_desc_id']);
	$sqllmscheck = array ( 
							'select' 	=> '
												chl_desc_id 
											',
							'where' 	=> array( 
													  'is_deleted'  	=> '0'
													, 'id_class'   		=> '0'
													, 'late_fee_type'   => cleanvars($_POST['late_fee_type'])
													, 'chl_desc'    	=> cleanvars($_POST['chl_desc'])
													, 'id_campus'		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
												),
							'not_equal' => array( 
													'chl_desc_id'	=>	$id 	
												),		
							'return_type' 	=> 'count' 
						); 
	$rowsQueryCheck  = $dblms->getRows(CHALLAN_DESCRIPTION, $sqllmscheck);	
	if($rowsQueryCheck) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: challan_description.php", true, 301);
		exit();
	} else { 		
		$late_fee_type_comma_sep = $_POST['id_latefeetype'].','.implode(',', $_POST['late_fee_type']);

		if ($_POST['chl_desc_status'] == 1):
			$values = array (
								"chl_desc_status"	=>	2
							);		
			$sqllms = $dblms->Update(CHALLAN_DESCRIPTION , $values , "WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'");
		endif;
		$values = array (
							 "chl_desc_status"	=>	cleanvars($_POST['chl_desc_status'])
							,"late_fee_type"	=>	cleanvars($late_fee_type_comma_sep)
							,"id_class"			=>	'0'
							,"id_campus"		=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
							,"chl_desc"			=>	cleanvars($_POST['chl_desc'])
							,"id_modify"		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_modify"		=>	date('Y-m-d h:i:s')
						);		
		$sqllms = $dblms->Update(CHALLAN_DESCRIPTION , $values , "WHERE chl_desc_id = '".$id."'");

		$latestID = $id;
		if($sqllms) { 
			sendRemark("Challan Desciption Updated ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: challan_description.php", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])) {
	$values = array (
						 "is_deleted"	=>	'1'
						,"id_deleted"	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"	=>	cleanvars($ip)
						,"date_deleted"	=>	date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(CHALLAN_DESCRIPTION , $values , "WHERE chl_desc_id = '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		sendRemark("Challan Desciption Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Success", "Record Deleted.", "success");
		header("Location: challan_description.php", true, 301);
		exit();
	}
}
?>