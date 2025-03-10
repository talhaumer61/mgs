<?php 
//---- make add check if already exist-----------------
if(isset($_POST['submit_province'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT prov_id  
										FROM ".PROVINCES." 
										WHERE prov_name = '".cleanvars($_POST['prov_name'])."' 
										AND prov_code = '".cleanvars($_POST['prov_code'])."'LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
//-------------if already exist -------------------------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: province.php", true, 301);
		exit();
//------------if not exist--------------------------
	} else { 
//------------------------------------------------
	$sqllms  = $dblms->querylms("INSERT INTO ".PROVINCES."(
														prov_status							, 
														prov_code							,
														prov_name							,  
														prov_ordering  						,
														id_added							,
														date_added
													  )
	   											VALUES(
														'".cleanvars($_POST['prov_status'])."'						, 
														'".cleanvars($_POST['prov_code'])."'						,
														'".cleanvars($_POST['prov_name'])."'						,
														'".cleanvars($_POST['prov_ordering'])."'					,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		,
														NOW()						
													  )"
							);
//-----------------------end---------------
	if($sqllms) { 
//--------------------------------------
	$remarks = 'Add Province: "'.cleanvars($_POST['prov_name']).'" detail';
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
		header("Location: province.php", true, 301);
		exit();
//--------------------------------------
	}
//--------------------------------------
	} // end checker
//--------------------------------------
} 
//----------------update reocrd----------------------
if(isset($_POST['changes_province'])) { 
//------------------------------------------------
$sqllms  = $dblms->querylms("UPDATE ".PROVINCES." SET  
													prov_status		= '".cleanvars($_POST['prov_status'])."'
												  , prov_code		= '".cleanvars($_POST['prov_code'])."' 
												  , prov_name		= '".cleanvars($_POST['prov_name'])."' 
												  , prov_ordering	= '".cleanvars($_POST['prov_ordering'])."' 
												  , id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , date_modify     = NOW()
   											  WHERE prov_id		= '".cleanvars($_POST['prov_id'])."'");
//--------------------------------------
	if($sqllms) { 
//--------------------------------------
	$remarks = 'Update Province: "'.cleanvars($_POST['prov_name']).'" details';
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
			header("Location: province.php", true, 301);
			exit();
//--------------------------------------
	}
//--------------------------------------
}

