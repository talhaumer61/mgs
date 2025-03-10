<?php 
if(isset($_POST['promote_students'])) {

	$id_campus = ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
    
	//If Section 
	if(isset($_POST['id_section'])){
		$sectionCheck = "AND id_section = '".cleanvars($_POST['id_section'])."'";
		$sectionUpdate = "".cleanvars($_POST['id_section'])."";
	} else{
		$sectionCheck = "AND id_section = '0'";
		$sectionUpdate = "0";
	}
	
	// All Students
	for($i=1; $i <= (COUNT($_POST['id_std'])); $i++){
		if(isset($_POST['is_promote'][$i])){

			//Check rollno if already exist then increment
			$sqllms_rollno	= $dblms->querylms("SELECT std_rollno
												FROM ".STUDENTS."	
												WHERE std_id != '' 
												AND id_session = '".cleanvars($_POST['id_session'])."'
												AND id_class = '".cleanvars($_POST['id_class'])."'
												$sectionCheck AND std_rollno = '".$i."'
												AND id_campus = '".$id_campus."' ");
												
			if(mysqli_num_rows($sqllms_rollno) > 0){
				$rollno = $i + 1;
			}
			else{
				$rollno = $i;
			}
			$sqllms  = $dblms->querylms("UPDATE ".STUDENTS." SET  
															  id_class			= '".cleanvars($_POST['id_class'])."' 
															, id_session		= '".cleanvars($_POST['id_session'])."'
															, id_section		= '".$sectionUpdate."'
															, std_rollno		= '".$rollno."'
															, id_campus			= '".cleanvars($id_campus)."' 
															, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, date_modify		= NOW()
															  WHERE std_id		= '".cleanvars($_POST['id_std'][$i])."'");	
			if($sqllms) { 
				$sqllmslog  = $dblms->querylms("INSERT INTO ".STD_PROMOTE_LOG." (
																	  id_std 
																	, class_from
																	, class_to
																	, session_from
																	, session_to
																	, dated
																	, reason
																	, id_campus
																	, id_added
																	, date_added
																)
				
															VALUES(
																	  '".cleanvars($_POST['id_std'][$i])."'
																	, '".cleanvars($_POST['class_from'])."'
																	, '".cleanvars($_POST['id_class'])."'
																	, '".cleanvars($_POST['session_from'])."'
																	, '".cleanvars($_POST['id_session'])."'
																	, NOW()
																	, '".cleanvars($_POST['reason'][$i])."'
																	, '".cleanvars($id_campus)."'
																	, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																	, NOW()
																)
															");
			}
		}
	}

	if($sqllms) { 
		sessionMsg("Success", "Record Successfully Updated.", "info");
		header("Location: ".strstr(basename($_SERVER['REQUEST_URI']), '.php', true).'.php'."", true, 301);
		exit();
	}
}
?>