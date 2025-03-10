<?php
//STATIONARY CATEGORY ADD
if(isset($_POST['submit_cat'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT cat_name, cat_code
										FROM ".INVENTORY_CATEGORY." 
										WHERE cat_name = '".cleanvars($_POST['cat_name'])."'
										OR cat_code = '".cleanvars($_POST['cat_code'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: stationary_category.php", true, 301);
		exit();
	} else { 
		$sqllms  = $dblms->querylms("INSERT INTO ".INVENTORY_CATEGORY."(
															cat_status							, 
															cat_name							, 
															cat_code							, 
															cat_detail							, 
															id_campus			
														)
													VALUES(
															'".cleanvars($_POST['cat_status'])."'		, 
															'".cleanvars($_POST['cat_name'])."'			, 
															'".cleanvars($_POST['cat_code'])."'			,
															'".cleanvars($_POST['cat_detail'])."'		,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														)"
									);
		if($sqllms) { 
			$remarks = 'Add Stationary Category: "'.cleanvars($_POST['cat_name']).'" detail';
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
			header("Location: stationary_category.php", true, 301);
			exit();
		}
	} 
} 

//STATIONARY CATEGORY UPDATE
if(isset($_POST['changes_cat'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".INVENTORY_CATEGORY." SET  
																cat_status			= '".cleanvars($_POST['cat_status'])."'
																, cat_name			= '".cleanvars($_POST['cat_name'])."'
																, cat_code			= '".cleanvars($_POST['cat_code'])."'
																, cat_detail		= '".cleanvars($_POST['cat_detail'])."' 
															WHERE cat_id			= '".cleanvars($_POST['cat_id'])."'");
	if($sqllms) { 
	$remarks = 'Update Stationary Category: "'.cleanvars($_POST['cat_name']).'" details';
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
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: stationary_category.php", true, 301);
			exit();
	}
}

//DELETE RECORD
if(isset($_GET['deleteid'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".INVENTORY_CATEGORY." SET  
														  is_deleted			= '1'
														, id_deleted			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, ip_deleted			= '".$ip."'
														, date_deleted			= NOW()
													 WHERE cat_id   			= '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		$remarks = 'Stationary Category Deleted ID: "'.cleanvars($_GET['id']).'" details';
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
															'3'											, 
															NOW()										,
															'".cleanvars($ip)."'						,
															'".cleanvars($remarks)."'						,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
									");
			$_SESSION['msg']['title'] 	= 'Warning';
			$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
			$_SESSION['msg']['type'] 	= 'warning';
			header("Location: stationary_category.php", true, 301);
			exit();
	}
}
?>