<?php 
//---------------- insert record ----------------------
if(isset($_POST['submit_bio'])) { 
	//------------------------------------------------
	$doa = date('Y-m-d', strtotime($_POST['principal_doa']));
	//------------------------------------------------

		$value = array (
				"bio_status" 				=> 1													,
				"id_ad" 					=> $_POST['id_ad']										,
				"id_de" 					=> $_POST['id_de']										,
				"building_type" 			=> $_POST['building_type']								,
				"building_area" 			=> $_POST['building_area']								,
				"covered_area" 				=> $_POST['covered_area']								,
				"total_rooms" 				=> $_POST['total_rooms']								,
				"play_grounds" 				=> $_POST['play_grounds']								,
				"washrooms" 				=> $_POST['washrooms']									,
				"principal_name" 			=> $_POST['principal_name']								,
				"principal_doa" 			=> $doa													,
				"principal_phone" 			=> $_POST['principal_phone']							,
				"second_phone" 				=> $_POST['second_phone']								,
				"principal_whastapp" 		=> $_POST['principal_whastapp']							,
				"principal_email" 			=> $_POST['principal_email']							,
				"principal_edu" 			=> $_POST['principal_edu']								,
				"principal_experience" 		=> $_POST['principal_experience']						,
				"primary_bank" 				=> $_POST['primary_bank']								,
				"primary_account" 			=> $_POST['primary_account']							,
				"secondary_bank" 			=> $_POST['secondary_bank']								,
				"secondary_account" 		=> $_POST['secondary_account']							,
				"mec_president" 			=> $_POST['mec_president']								,
				"mec_president_no" 			=> $_POST['mec_president_no']							,
				"id_campus" 				=> $_POST['id_campus'] 									,
				"id_added" 					=> $_SESSION['userlogininfo']['LOGINIDA']				,
				"date_added" 				=> date('Y-m-d h:i:s')
		);

		$sqllms  = $dblms->insert(CAMPUS_BIOGRAPHY, $value);

	// echo $sqllms;
	// exit();
//--------------------------------------
	$latest_id = $dblms->lastestid();	
//--------------------------------------

	if($sqllms) { 
	//--------------------------------------
	$remarks = 'Add Campus Biograpgy: "'.cleanvars($_POST['latest_id']).'" detail';
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
	//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: campus-biography.php", true, 301);
		exit();
	//--------------------------------------
	} // end checker
//--------------------------------------
} 
?>
