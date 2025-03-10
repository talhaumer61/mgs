<?php 
if(isset($_POST['submit_settings'])){
	// DELETE PREVIOUS RECORDS
	$sqllms  = $dblms->querylms("UPDATE ".SETTINGS." SET  
													  status			= '2'
													, is_deleted		= '1'
													, date_modify		= NOW()
													, id_modify   		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													  WHERE id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	
									");

	// ADD NEW ONE
	$sqllms  = $dblms->querylms("INSERT INTO ".SETTINGS."(
														  status 
														, adm_session
														, acd_session 
														, exam_session
														, id_campus
														, date_added
														, id_added					
													)
	   											VALUES(
													   	  '1'
														, '".cleanvars($_POST['adm_session'])."' 
														, '".cleanvars($_POST['acd_session'])."' 
														, '".cleanvars($_POST['exam_session'])."' 
														, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														, NOW()
														, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'					
													)
									");
	$latest_id = $dblms->lastestid();

	// REMARKS
	if($sqllms){
		$remarks = 'New Setting Added ID: "'.cleanvars($latest_id).'" detail';
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
		header("Location: settings.php", true, 301);
		exit();
	}
}
?>
