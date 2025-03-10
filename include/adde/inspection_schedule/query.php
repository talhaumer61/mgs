<?php 
// ADD RECORD
if(isset($_POST['submit_schedule'])) {
	$sqllmscheck  = $dblms->querylms("SELECT schedule_id
										FROM ".INSPECTION_SCHEDULE." 
										WHERE schedule_month = '".cleanvars($_POST['schedule_month'])."' 
										AND id_adde = '".cleanvars($_POST['id_adde'])."' AND is_deleted != '1' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck) > 0) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: performa.php", true, 301);
		exit();
	}  else { 
		
		$values = array(
							'schedule_satus'	  => 2							,
							'schedule_approval'	  => 2							,
							'schedule_month' 	  => $_POST['schedule_month']	,
							'id_adde'			  => $_POST['id_adde']			,
							'date_added'  		  => date('Y-m-d H:i:s')					,
							'id_added' 	  		  => $_SESSION['userlogininfo']['LOGINIDA']	,
						);
		$sqllmsSched = $dblms->Insert(INSPECTION_SCHEDULE , $values); 

		if($sqllmsSched) { 

			$last_id =	$dblms->lastestid();
			
			// DETAILS
			for($i=0; $i<count($_POST['id_campus']); $i++) {

				if(!empty($_POST['purposed_date'][$i]) && $_POST['purposed_date'][$i] != 'Select Visit Date'){
				
					$date = date("Y-m-d", strtotime($_POST['purposed_date'][$i]));
					$valuesDet =  array(
											'id_schedule' 		=> $last_id					,
											'id_campus' 		=> $_POST['id_campus'][$i]	,
											'purposed_date' 	=> $date	
									
										);
					$sqllmsDet = $dblms->Insert(INSPECTION_SCHEDULE_DET , $valuesDet);
					// UNSET VARIABLES
					if($valuesDet){
						unset($valuesDet,$sqllmsDet);
					}
				}
			}

			// LOG
			$remarks = 'Add Inspection Visit: "'.cleanvars($last_id).'" detail';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																id_user										, 
																filename									, 
																action										,
																dated										,
																ip											,
																remarks										, 
																id_campus				
															)
			
														VALUES(
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
																'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
																'1'											, 
																NOW()										,
																'".cleanvars($ip)."'						,
																'".cleanvars($remarks)."'						,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: inspectionSchedule.php", true, 301);
			exit();
		}
	}
} 


// UPDATE RECORD
if(isset($_POST['update_schedule'])) {

	$values = array(
		'date_modify'  	 => date('Y-m-d H:i:s')						,
		'id_modify' 	 => $_SESSION['userlogininfo']['LOGINIDA']	,
	);
	$sqllmsSched = $dblms->Update(INSPECTION_SCHEDULE , $values , "WHERE schedule_id = '".$_POST['schedule_id']."'");


	if($sqllmsSched) { 

		// REMOVE THE RECORD
		$sqllmscheck  = $dblms->querylms("DELETE FROM ".INSPECTION_SCHEDULE_DET."  WHERE id_schedule = '".$_POST['schedule_id']."'");

		// DETAILS
		for($i=0; $i<count($_POST['id_campus']); $i++) {

			if(!empty($_POST['purposed_date'][$i]) && $_POST['purposed_date'][$i] != 'Select Visit Date') {
			
				$date = date("Y-m-d", strtotime($_POST['purposed_date'][$i]));
				// ADD
				$valuesDetAdd = array(
										'id_schedule' 		=> $_POST['schedule_id']	,
										'id_campus' 		=> $_POST['id_campus'][$i]	,
										'purposed_date' 	=> $date	
									);
				$sqllmsDetAdd = $dblms->Insert(INSPECTION_SCHEDULE_DET , $valuesDetAdd);
				// UNSET VARIBALES
				if($sqllmsDetAdd){
					unset($valuesDetAdd,$sqllmsDetAdd);
				}
			}
		}

		// LOG
		$remarks = 'Add Inspection Visit: "'.cleanvars($last_id).'" detail';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															id_user										, 
															filename									, 
															action										,
															dated										,
															ip											,
															remarks										, 
															id_campus				
														)
		
													VALUES(
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
															'1'											, 
															NOW()										,
															'".cleanvars($ip)."'						,
															'".cleanvars($remarks)."'						,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														)
									");
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'info';
		header("Location: inspectionSchedule.php", true, 301);
		exit();
	}
} 


?>