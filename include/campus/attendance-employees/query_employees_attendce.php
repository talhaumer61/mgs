<?php
// INSERT RECORD
if(isset($_POST['submit_attendance'])) { 
	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
	$dated = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
	
	$sqllmscheck  = $dblms->querylms("SELECT dated, id_session, id_campus
										FROM ".EMPLOYEES_ATTENDCE." 
										WHERE id_campus = '".cleanvars($id_campus)."' 
										AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
										AND dated =  '".$dated."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	} else {
		$values = array (
							 "status"			=> '1'
							,"dated"			=> cleanvars($dated)
							,"id_campus"		=> cleanvars($id_campus)
							,"id_session"		=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,"id_added"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_added"		=> date('Y-m-d h:i:s')
						);
		$sqllms  = $dblms->insert(EMPLOYEES_ATTENDCE, $values);
		if($sqllms) { 
			$latestID = $dblms->lastestid();
			// DETAIL
			for($i=1; $i<=COUNT($_POST['emply_ID']); $i++){
				$values = array (
									 "id_setup"		=> cleanvars($latestID)
									,"id_dept"		=> cleanvars($_POST['dept_ID'][$i])
									,"id_emply"		=> cleanvars($_POST['emply_ID'][$i])
									,"status"		=> cleanvars($_POST['arr'][$i])
								);
				$sqllms  = $dblms->insert(EMPLOYEES_ATTENDCE_DETAIL, $values);
			}
			sendRemark("Employee Attendance Added ID: ".$latestID." Detail", '1');
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['update_attendance'])) { 
	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
	$dated = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
	
	$values = array (
						 "status"			=> '1'
						,"dated"			=> cleanvars($dated)
						,"id_modify"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"date_modify"		=> date('Y-m-d h:i:s')
					);
	$sqllms = $dblms->Update(EMPLOYEES_ATTENDCE , $values , "WHERE id = '".cleanvars($_POST['id'])."'");
	if($sqllms) { 
		$latestID = $_POST['id'];
		// DETAIL
		$sqllms  = $dblms->querylms("DELETE FROM ".EMPLOYEES_ATTENDCE_DETAIL." WHERE  id_setup = '".cleanvars($latestID)."' ");

		for($i=1; $i<=COUNT($_POST['emply_ID']); $i++){
			$values = array (
								 "id_setup"		=> cleanvars($latestID)
								,"id_dept"		=> cleanvars($_POST['dept_ID'][$i])
								,"id_emply"		=> cleanvars($_POST['emply_ID'][$i])
								,"status"		=> cleanvars($_POST['arr'][$i])
							);
			$sqllms  = $dblms->insert(EMPLOYEES_ATTENDCE_DETAIL, $values);
		}
		sendRemark("Employee Attendance Updated ID: ".$latestID." Detail", '2');
		sessionMsg("Success", "Record Successfully Updated.", "info");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	}
}
?>