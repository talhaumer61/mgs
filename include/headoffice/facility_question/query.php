<?php 
//---- add record check if already exist-----------------
if(isset($_POST['submit_question'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT question_id  
										FROM ".FACILITY_QESTIONS." 
										WHERE question_name = '".cleanvars($_POST['question_name'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
//-------------if already exist -------------------------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: facility_question.php", true, 301);
		exit();
//------------if not exist--------------------------
	} else { 
//------------------------------------------------
	$sqllms  = $dblms->querylms("INSERT INTO ".FACILITY_QESTIONS."(
														question_status							, 
														question_name							,  
														question_ordering  						,
														id_cat									,
														id_added								,
														date_added
													  )
	   											VALUES(
														'".cleanvars($_POST['question_status'])."'						, 
														'".cleanvars($_POST['question_name'])."'						,
														'".cleanvars($_POST['question_ordering'])."'					,
														'".cleanvars($_POST['id_cat'])."'								,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
														NOW()						
													  )"
							);
//-----------------------end---------------
	if($sqllms) { 
	
	$remarks = 'Add Facility Question: "'.cleanvars($_POST['question_name']).'" detail';
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
		header("Location: facility_question.php", true, 301);
		exit();
//--------------------------------------
	}
//--------------------------------------
	} // end checker
//--------------------------------------
} 
//----------------update reocrd----------------------
if(isset($_POST['changes_question'])) { 
//------------------------------------------------
$sqllms  = $dblms->querylms("UPDATE ".FACILITY_QESTIONS." SET  
													question_status		= '".cleanvars($_POST['question_status'])."'
												  , question_name		= '".cleanvars($_POST['question_name'])."' 
												  , question_ordering	= '".cleanvars($_POST['question_ordering'])."' 
												  , id_cat				= '".cleanvars($_POST['id_cat'])."' 
												  , id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , date_modify     	= NOW()
   											  WHERE question_id			= '".cleanvars($_POST['question_id'])."'");
//--------------------------------------
	if($sqllms) { 
		
	//--------------------------------------
	$remarks = 'Update Facility Question: "'.cleanvars($_POST['question_name']).'" details';
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
															'2'											, 
															NOW()										,
															'".cleanvars($ip)."'						,
															'".cleanvars($remarks)."'						,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														  )
									");
//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: facility_question.php", true, 301);
			exit();
//--------------------------------------
	}
//--------------------------------------
}

