<?php 
//---- add record check if already exist-----------------
if(isset($_POST['submit_cat'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT cat_id  
										FROM ".FACILITY_CATS." 
										WHERE cat_name = '".cleanvars($_POST['cat_name'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
//-------------if already exist -------------------------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: facility_cat.php", true, 301);
		exit();
//------------if not exist--------------------------
	} else { 
//------------------------------------------------
	$sqllms  = $dblms->querylms("INSERT INTO ".FACILITY_CATS."(
														cat_status							, 
														cat_name							,  
														cat_ordering  						,
														id_added							,
														date_added
													  )
	   											VALUES(
														'".cleanvars($_POST['cat_status'])."'						, 
														'".cleanvars($_POST['cat_name'])."'							,
														'".cleanvars($_POST['cat_ordering'])."'						,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		,
														NOW()						
													  )"
							);
//-----------------------end---------------
	if($sqllms) { 
	
	$remarks = 'Add Campus Facility Cat: "'.cleanvars($_POST['cat_name']).'" detail';
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
		header("Location: facility_cat.php", true, 301);
		exit();
//--------------------------------------
	}
//--------------------------------------
	} // end checker
//--------------------------------------
} 
//----------------update reocrd----------------------
if(isset($_POST['changes_cat'])) { 
//------------------------------------------------
$sqllms  = $dblms->querylms("UPDATE ".FACILITY_CATS." SET  
													cat_status	= '".cleanvars($_POST['cat_status'])."'
												  , cat_name		= '".cleanvars($_POST['cat_name'])."' 
												  , cat_ordering	= '".cleanvars($_POST['cat_ordering'])."' 
												  , id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , date_modify     = NOW()
   											  WHERE cat_id		= '".cleanvars($_POST['cat_id'])."'");
//--------------------------------------
	if($sqllms) { 
		
	//--------------------------------------
	$remarks = 'Update Campus Facility Cat: "'.cleanvars($_POST['cat_name']).'" details';
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
			header("Location: facility_cat.php", true, 301);
			exit();
//--------------------------------------
	}
//--------------------------------------
}

