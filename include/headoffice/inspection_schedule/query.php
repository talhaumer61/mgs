<?php 
//---------------- Update ----------------------
if(isset($_POST['update_schedule'])) {
	//-------------- Parent -----------------
	$values = array(
		'date_modify'  	 => date('Y-m-d H:i:s')						,
		'id_modify' 	 => $_SESSION['userlogininfo']['LOGINIDA']	,
	);
	$sqllmsSched = $dblms->Update(INSPECTION_SCHEDULE , $values , "WHERE schedule_id = '".$_POST['schedule_id']."'");


	if($sqllmsSched) { 

		//----------- Child -------------------
		for($i=0; $i<count($_POST['id_campus']); $i++) {


			if(!empty($_POST['purposed_date'][$i]) && $_POST['purposed_date'][$i] != 'Select Visit Date') {
			
				$date = date("Y-m-d", strtotime($_POST['purposed_date'][$i]));

				// Check if Record Exist
				$sqllmscheck  = $dblms->querylms("SELECT id
														FROM ".INSPECTION_SCHEDULE_DET." 
														WHERE id_schedule = '".$_POST['schedule_id']."' 
														AND id_campus = '".$_POST['id_campus'][$i]."' LIMIT 1");
				if(mysqli_num_rows($sqllmscheck) > 0) {

					$valuecheck = mysqli_fetch_array($sqllmscheck);

					// Update
					$valuesDetUpdate = array(
						'purposed_date' 	=> $date	
					);
					$sqllmsDetUpdate = $dblms->Update(INSPECTION_SCHEDULE_DET , $valuesDetUpdate , "WHERE id = '".$valuecheck['id']."'");
					# Clear after query execution
					if($sqllmsDetUpdate){
						unset($valuesDetUpdate,$sqllmsDetUpdate);
					}
				} else {

					// Insert
					$valuesDetAdd = array(
						'id_schedule' 		=> $_POST['schedule_id']	,
						'id_campus' 		=> $_POST['id_campus'][$i]	,
						'purposed_date' 	=> $date	
					);
					$sqllmsDetAdd = $dblms->Insert(INSPECTION_SCHEDULE_DET , $valuesDetAdd);
					# Clear after query execution
					if($sqllmsDet){
						unset($valuesDetAdd,$sqllmsDetAdd);
					}
				}
			}
		}

		// Log
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