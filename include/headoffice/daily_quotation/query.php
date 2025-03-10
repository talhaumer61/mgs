<?php 
// INSERT RECORD
if(isset($_POST['add_quote'])) { 
	$date = date("Y-m-d",strtotime(cleanvars($_POST['date'])));
	$sqllmscheck = array ( 
							'select' 		=>	'quote_id'
							,'where' 		=>	array( 
														 'is_deleted'	=> '0'
														,'quote_type'	=> cleanvars($_POST['quote_type'])
														,'quote_msg'	=> cleanvars($_POST['quote_msg'])
														,'date'    		=> cleanvars($date)
													)
							,'return_type' 	=> 'count' 
						); 
	$rowsQueryCheck  = $dblms->getRows(DAILY_QUOTATION, $sqllmscheck);
	if($rowsQueryCheck) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: daily_quotation.php", true, 301);
		exit();
	} else { 
		$values = array (
							 "quote_type"	=>	cleanvars($_POST['quote_type'])
							,"quote_msg"	=>	cleanvars($_POST['quote_msg'])
							,"date"			=>	cleanvars($date)
							,"id_added"		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_added"	=>	date('Y-m-d h:i:s')
						);		
		$sqllms  = $dblms->insert(DAILY_QUOTATION, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			sendRemark("Daily Quotation Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: daily_quotation.php", true, 301);
			exit();
		}
	} 
}

// UPDATE RECORD
if(isset($_POST['update_quote'])) { 
	$date = date("Y-m-d",strtotime(cleanvars($_POST['date'])));
	$id = cleanvars($_POST['id']);
	$sqllmscheck = array ( 
							'select' 		=>	'quote_id'
							,'where' 		=>	array( 
														 'is_deleted'	=> '0'
														,'quote_type'	=> cleanvars($_POST['quote_type'])
														,'quote_msg'	=> cleanvars($_POST['quote_msg'])
														,'date'    		=> cleanvars($date)
													)
							,'not_equal' 	=>	array( 
														'quote_id'		=>	$id 
													)	
							,'return_type'	=>	'count' 
						); 
	$rowsQueryCheck  = $dblms->getRows(DAILY_QUOTATION, $sqllmscheck);	
	if($rowsQueryCheck) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: daily_quotation.php", true, 301);
		exit();
	} else { 
		$values = array (
							 "quote_type"	=>	cleanvars($_POST['quote_type'])
							,"quote_msg"	=>	cleanvars($_POST['quote_msg'])
							,"date"			=>	cleanvars($date)
							,"id_modify"	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_modify"	=>	date('Y-m-d h:i:s')
						);		
		$sqllms = $dblms->Update(DAILY_QUOTATION , $values , "WHERE quote_id = '".cleanvars($_POST['quote_id'])."'");

		$latestID = cleanvars($_POST['quote_id']);
		if($sqllms) { 
			sendRemark("Daily Quptation Updated ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: daily_quotation.php", true, 301);
			exit();
		}
	}
}

// DELTE RECORD
if(isset($_GET['deleteid'])) {	
	$values = array (
						 "is_deleted"	=>	'1'
						,"id_deleted"	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"	=>	cleanvars($ip)
						,"date_deleted"	=>	date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(DAILY_QUOTATION , $values , "WHERE quote_id = '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		sendRemark("Daily Quotation Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Success", "Record Deleted.", "success");
		header("Location: daily_quotation.php", true, 301);
		exit();
	}
}
?>