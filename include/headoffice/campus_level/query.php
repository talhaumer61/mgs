<?php 
//---- add record check if already exist-----------------
if(isset($_POST['submit_level'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT level_id  
										FROM ".CAMPUS_LEVELS." 
										WHERE level_name = '".cleanvars($_POST['level_name'])."' 
										AND level_code = '".cleanvars($_POST['level_code'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
//-------------if already exist -------------------------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: campus_level.php", true, 301);
		exit();
//------------if not exist--------------------------
	} else { 
//------------------------------------------------
	$classes = $_POST['level_classes'];
	$val_classses = implode(',',$classes);
//------------------------------------------------
	$sqllms  = $dblms->querylms("INSERT INTO ".CAMPUS_LEVELS."(
														level_status						, 
														level_code							,
														level_name							,  
														level_ordering  					,
														level_classes						,
														id_added							,
														date_added
													  )
	   											VALUES(
														'".cleanvars($_POST['level_status'])."'						, 
														'".cleanvars($_POST['level_code'])."'						,
														'".cleanvars($_POST['level_name'])."'						,
														'".cleanvars($_POST['level_ordering'])."'					,
														'".cleanvars($val_classses)."'								,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		,
														NOW()						
													  )"
							);
//-----------------------end---------------
	if($sqllms) { 
	
	$remarks = 'Add Campus Level: "'.cleanvars($_POST['level_name']).'" detail';
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
		header("Location: campus_level.php", true, 301);
		exit();
//--------------------------------------
	}
//--------------------------------------
	} // end checker
//--------------------------------------
} 
//----------------update reocrd----------------------
if(isset($_POST['changes_level'])) { 
//------------------------------------------------
$classes = $_POST['level_classes'];
$val_classses = implode(',',$classes);
//------------------------------------------------
$sqllms  = $dblms->querylms("UPDATE ".CAMPUS_LEVELS." SET  
													level_status	= '".cleanvars($_POST['level_status'])."'
												  , level_code		= '".cleanvars($_POST['level_code'])."' 
												  , level_name		= '".cleanvars($_POST['level_name'])."' 
												  , level_ordering	= '".cleanvars($_POST['level_ordering'])."' 
												  , level_classes	= '".cleanvars($val_classses)."' 
												  , id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , date_modify     = NOW()
   											  WHERE level_id		= '".cleanvars($_POST['level_id'])."'");
//--------------------------------------
	if($sqllms) { 
		
	//--------------------------------------
	$remarks = 'Update Campus Level: "'.cleanvars($_POST['level_name']).'" details';
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
			header("Location: campus_level.php", true, 301);
			exit();
//--------------------------------------
	}
//--------------------------------------
}

