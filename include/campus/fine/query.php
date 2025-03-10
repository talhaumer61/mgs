<?php
// FINE ADD
if(isset($_POST['submit_fine'])) {
	$id_campus = (!empty($_POST['id_campus']) ? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));

	$date = date('Y-m-d' , strtotime(cleanvars($_POST['date'])));
	$yearmonth 	= date('Y-m', strtotime(cleanvars($_POST['yearmonth'])));
	$sqllmscheck  = $dblms->querylms("SELECT id_std, id_cat, date, id_session, id_campus
										FROM ".SCHOLARSHIP." 
										WHERE id_class	= '".cleanvars($_POST['id_class'])."' 
										AND id_std		= '".cleanvars($_POST['id_std'])."' 
										AND id_cat		= '".cleanvars($_POST['id_cat'])."'
										AND yearmonth	= '".cleanvars($yearmonth)."'
										AND id_session	= '".cleanvars($_POST['id_session'])."' 
										AND id_campus	= '".cleanvars($id_campus)."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: fine.php", true, 301);
		exit();
	}else{
		$values = array (
							 'status'			=> cleanvars($_POST['status'])
							,'id_type'			=> '3'
							,'id_cat'			=> cleanvars($_POST['id_cat'])
							,'id_class'			=> cleanvars($_POST['id_class'])
							,'id_std'			=> cleanvars($_POST['id_std'])
							,'amount'			=> cleanvars($_POST['amount'])
							,'note'				=> cleanvars($_POST['note'])
							,'date'				=> cleanvars($date)
							,'yearmonth'		=> cleanvars($yearmonth)
							,'id_session'		=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_campus'		=> cleanvars($id_campus)
							,'id_added'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added'		=> date('Y-m-d h:i:s')
						);
		$sqlInsert  = $dblms->insert(SCHOLARSHIP, $values);

		// LATEST ID
		$latestId = $dblms->lastestid();
		
		// REMARKS
		if($sqlInsert){
			sendRemark("Add Fine ID: ".cleanvars($latestId)." detail", '1');
			sessionMsg("Success", "Record Added Successfully.", "success");
			header("Location: fine.php", true, 301);
			exit();
		}
	}
}

// FINE UPDATE
if(isset($_POST['changes_fine'])) { 
	$id_campus = (!empty($_POST['id_campus']) ? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));

	$date = date('Y-m-d' , strtotime(cleanvars($_POST['date'])));
	$yearmonth 	= date('Y-m', strtotime(cleanvars($_POST['yearmonth'])));
	$sqllmscheck  = $dblms->querylms("SELECT id_std, id_cat, date, id_session, id_campus
										FROM ".SCHOLARSHIP." 
										WHERE id_class	= '".cleanvars($_POST['id_class'])."' 
										AND id_std		= '".cleanvars($_POST['id_std'])."' 
										AND id_cat		= '".cleanvars($_POST['id_cat'])."'
										AND yearmonth	= '".cleanvars($yearmonth)."'
										AND id_session	= '".cleanvars($_POST['id_session'])."' 
										AND id_campus	= '".cleanvars($id_campus)."'
										AND id		   != '".cleanvars($_POST['id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: fine.php", true, 301);
		exit();
	}else{
		$values = array (
							 'status'			=> cleanvars($_POST['status'])
							,'id_type'			=> '3'
							,'id_cat'			=> cleanvars($_POST['id_cat'])
							,'id_class'			=> cleanvars($_POST['id_class'])
							,'id_std'			=> cleanvars($_POST['id_std'])
							,'amount'			=> cleanvars($_POST['amount'])
							,'note'				=> cleanvars($_POST['note'])
							,'date'				=> cleanvars($date)
							,'yearmonth'		=> cleanvars($yearmonth)
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
			sendRemark("Update Fine ID: ".cleanvars($latestId)." detail", '2');
			sessionMsg("Success", "Record Updated Successfully.", "info");
			header("Location: fine.php", true, 301);
			exit();
		}
	}
}

// FINE DELETE
if(isset($_GET['deleteid'])) { 
	$values = array (
						 "is_deleted"		=> '1'
						,"id_deleted"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"ip_deleted"		=> cleanvars($ip)
						,"date_deleted"		=> date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(SCHOLARSHIP , $values , "WHERE id = '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		sendRemark("Fine Deleted ID: ".$_GET['deleteid']." Detail", '3');
		sessionMsg("Success", "Record Deleted Successfully.", "success");
		header("Location: fine.php", true, 301);
		exit();
	}
}
?>