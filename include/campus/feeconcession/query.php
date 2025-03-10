<?php
// CAT INSERT
if(isset($_POST['submit_cat'])) {
	$sqllmscheck  = $dblms->querylms("SELECT cat_name, cat_type, id_campus  
										FROM ".SCHOLARSHIP_CAT." 
										WHERE cat_name	= '".cleanvars($_POST['cat_name'])."'
										AND cat_type	= '2'
										AND id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: feeconcession_cat.php", true, 301);
		exit();
	} else { 
		$values = array (
							 'cat_status'		=> cleanvars($_POST['cat_status'])
							,'cat_type'			=> '2'
							,'cat_name'			=> cleanvars($_POST['cat_name'])
							,'cat_detail'		=> cleanvars($_POST['cat_detail'])
							,'id_campus'		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);
		$sqlInsert  = $dblms->insert(SCHOLARSHIP_CAT, $values);

		// LATEST ID
		$latestId = $dblms->lastestid();
		
		// REMARKS
		if($sqlInsert){
			sendRemark("Concession Category Added ID: ".cleanvars($latestId)." detail", '1');
			sessionMsg("Success", "Record Added Successfully.", "success");
			header("Location: feeconcession_cat.php", true, 301);
			exit();
		}
	}
}

// CAT UPDATE
if(isset($_POST['changes_cat'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT cat_name, cat_type, id_campus  
										FROM ".SCHOLARSHIP_CAT." 
										WHERE cat_name	= '".cleanvars($_POST['cat_name'])."'
										AND cat_type	= '2'
										AND id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
										AND cat_id	   != '".cleanvars($_POST['cat_id'])."'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: feeconcession_cat.php", true, 301);
		exit();
	} else { 
		$values = array (
							 'cat_status'		=> cleanvars($_POST['cat_status'])
							,'cat_name'			=> cleanvars($_POST['cat_name'])
							,'cat_detail'		=> cleanvars($_POST['cat_detail'])
							,'id_campus'		=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);						
		$sqllms = $dblms->Update(SCHOLARSHIP_CAT , $values , "WHERE cat_id = '".cleanvars($_POST['cat_id'])."'");

		// LATEST ID
		$latestId = cleanvars($_POST['id']);

		// REMARKS
		if($sqllms){			
			sendRemark("Concession Category Updated ID: ".cleanvars($latestId)." detail", '2');
			sessionMsg("Success", "Record Updated Successfully.", "info");
			header("Location: feeconcession_cat.php", true, 301);
			exit();
		}
	}
}

// CAT DELETE
if(isset($_GET['deleteid_cat'])) { 
	$values = array (
						 "is_deleted"		=> '1'
						,"id_deleted"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"		=> cleanvars($ip)
						,"date_deleted"		=> date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(SCHOLARSHIP_CAT , $values , "WHERE cat_id = '".cleanvars($_GET['deleteid_cat'])."'");
	if($sqllms) { 
		sendRemark("Concession Category Deleted ID: ".cleanvars($_GET['deleteid'])." Detail", '3');
		sessionMsg("Success", "Record Deleted Successfully.", "success");
		header("Location: feeconcession_cat.php", true, 301);
		exit();
	}
}

// CONCESSION INSERT
if(isset($_POST['submit_feeconcession'])) {
	$id_campus = (!empty($_POST['id_campus']) ? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));

	$start_date = date('Y-m-d' , strtotime(cleanvars($_POST['start_date'])));
	$end_date = date('Y-m-d' , strtotime(cleanvars($_POST['end_date'])));

	$sqllmscheck  = $dblms->querylms("SELECT id_std, id_cat, id_session, id_campus
										FROM ".SCHOLARSHIP." 
										WHERE id_class	= '".cleanvars($_POST['id_class'])."'
										AND id_std		= '".cleanvars($_POST['id_std'])."'
										AND id_cat		= '".cleanvars($_POST['id_cat'])."'
										AND id_session	= '".cleanvars($_POST['id_session'])."'
										AND (start_date BETWEEN '".$start_date."' AND '".$end_date."')
										AND id_campus	= '".cleanvars($id_campus)."'
										AND id_type		= '2'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: feeconcession.php", true, 301);
		exit();
	} else { 
		$values = array (
							 'status'			=> cleanvars($_POST['status'])
							,'id_type'			=> '2'
							,'id_cat'			=> cleanvars($_POST['id_cat'])
							,'id_class'			=> cleanvars($_POST['id_class'])
							,'id_std'			=> cleanvars($_POST['id_std'])
							,'concession_type'	=> cleanvars($_POST['concession_type'])
							,'percent'			=> cleanvars($_POST['percent'])
							,'amount'			=> cleanvars($_POST['amount'])
							,'id_feecat'		=> cleanvars($_POST['id_feecat'])
							,'start_date'		=> cleanvars($start_date)
							,'end_date'			=> cleanvars($end_date)
							,'note'				=> cleanvars($_POST['note'])
							,'id_session'		=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_campus'		=> cleanvars($id_campus)
							,'id_added'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'		=> date('Y-m-d h:i:s')
						);
		$sqllms  = $dblms->insert(SCHOLARSHIP, $values);

		// LATEST ID
		$latestId = $dblms->lastestid();
		
		// REMARKS
		if($sqllms){
			sendRemark("Add Fee Concession ID: ".cleanvars($latestId)." detail", '1');
			sessionMsg("Success", "Record Added Successfully.", "success");
			header("Location: feeconcession.php", true, 301);
			exit();
		}
	}
}

// CONCESSION UPDATE
if(isset($_POST['changes_feeconcession'])) {
	$id_campus = (!empty($_POST['id_campus']) ? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));

	$start_date = date('Y-m-d' , strtotime(cleanvars($_POST['start_date'])));
	$end_date = date('Y-m-d' , strtotime(cleanvars($_POST['end_date'])));

	$sqllmscheck  = $dblms->querylms("SELECT id_std, id_cat, id_session, id_campus
										FROM ".SCHOLARSHIP." 
										WHERE id_class	= '".cleanvars($_POST['id_class'])."'
										AND id_std		= '".cleanvars($_POST['id_std'])."'
										AND id_cat		= '".cleanvars($_POST['id_cat'])."'
										AND id_session	= '".cleanvars($_POST['id_session'])."'
										AND (start_date BETWEEN '".$start_date."' AND '".$end_date."')
										AND id_campus	= '".cleanvars($id_campus)."'
										AND id_type		= '2'
										AND id		   != '".cleanvars($_POST['id'])."'
										LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: feeconcession.php", true, 301);
		exit();
	} else { 
		$values = array (
							 'status'			=> cleanvars($_POST['status'])
							,'id_type'			=> '2'
							,'id_cat'			=> cleanvars($_POST['id_cat'])
							,'id_class'			=> cleanvars($_POST['id_class'])
							,'id_std'			=> cleanvars($_POST['id_std'])
							,'concession_type'	=> cleanvars($_POST['concession_type'])
							,'percent'			=> cleanvars($_POST['percent'])
							,'amount'			=> cleanvars($_POST['amount'])
							,'id_feecat'		=> cleanvars($_POST['id_feecat'])
							,'start_date'		=> cleanvars($start_date)
							,'end_date'			=> cleanvars($end_date)
							,'note'				=> cleanvars($_POST['note'])
							,'id_session'		=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_campus'		=> cleanvars($id_campus)
							,'id_added'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'		=> date('Y-m-d h:i:s')
						);
		$sqllms = $dblms->Update(SCHOLARSHIP , $values , "WHERE id = '".cleanvars($_POST['id'])."'");

		// LATEST ID
		$latestId = cleanvars($_POST['id']);
		
		// REMARKS
		if($sqllms){
			sendRemark("Update Fee Concession ID: ".cleanvars($latestId)." detail", '2');
			sessionMsg("Success", "Record Updated Successfully.", "info");
			header("Location: feeconcession.php", true, 301);
			exit();
		}
	}
}

// CONCESSION DELETE
if(isset($_GET['deleteid'])) { 
	$values = array (
						 "is_deleted"		=> '1'
						,"id_deleted"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"		=> cleanvars($ip)
						,"date_deleted"		=> date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(SCHOLARSHIP , $values , "WHERE id = '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		sendRemark("Concession Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Success", "Record Deleted Successfully.", "success");
		header("Location: feeconcession.php", true, 301);
		exit();
	}
}
?>