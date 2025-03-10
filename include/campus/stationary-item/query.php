<?php

//STATIONARY ITEM ADDD
if(isset($_POST['submit_item'])) { 
	
	$sqllmscheck  = $dblms->querylms("SELECT item_name, item_code 
										FROM ".INVENTORY_ITEMS." 
										WHERE item_name = '".cleanvars($_POST['item_name'])."' 
										OR item_code = '".cleanvars($_POST['item_code'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: stationary_item.php", true, 301);
		exit();
		
	} else { 
		
		$sqllms  = $dblms->querylms("INSERT INTO ".INVENTORY_ITEMS."(
															item_status				,
															id_cat					,  
															item_name				, 
															item_code				, 
															school_price			,
															std_price				,
															item_detail		 		,
															id_campus
														)
													VALUES(
															'".cleanvars($_POST['item_status'])."'		, 
															'".cleanvars($_POST['id_cat'])."'			, 
															'".cleanvars($_POST['item_name'])."'		,
															'".cleanvars($_POST['item_code'])."'		,
															'".cleanvars($_POST['school_price'])."'		,
															'".cleanvars($_POST['std_price'])."'		,
															'".cleanvars($_POST['item_detail'])."'		,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														)"
								);
		if($sqllms) { 
			$remarks = 'Add Stationary Item: "'.cleanvars($_POST['item_name']).'" detail';
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
			header("Location: stationary_item.php", true, 301);
			exit();
		}
	} 
} 

//STATIONARY ITEM UPDATE
if(isset($_POST['changes_item'])) { 

	if($_POST['id_supplier'] == 1) {
		$sqllms  = $dblms->querylms("UPDATE ".INVENTORY_ITEMS." SET  
														  item_status	= '".cleanvars($_POST['item_status'])."'
														, school_price	= '".cleanvars($_POST['school_price'])."' 
														, std_price		= '".cleanvars($_POST['std_price'])."' 
													WHERE item_id		= '".cleanvars($_POST['item_id'])."'");
	} else {
		$sqllms  = $dblms->querylms("UPDATE ".INVENTORY_ITEMS." SET  
														  item_status	= '".cleanvars($_POST['item_status'])."'
														, item_name		= '".cleanvars($_POST['item_name'])."' 
														, id_cat		= '".cleanvars($_POST['id_cat'])."' 
														, item_code		= '".cleanvars($_POST['item_code'])."' 
														, school_price	= '".cleanvars($_POST['school_price'])."' 
														, std_price		= '".cleanvars($_POST['std_price'])."' 
														, item_detail	= '".cleanvars($_POST['item_detail'])."'  
													WHERE item_id		= '".cleanvars($_POST['item_id'])."'");
	}
	
	if($sqllms) { 
		$remarks = 'Update Stationary Item: "'.cleanvars($_POST['item_name']).'" details';
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
			header("Location: stationary_item.php", true, 301);
			exit();
	}
}

//DELETE RECORD
if(isset($_GET['deleteid'])) { 
	$sqllms  = $dblms->querylms("UPDATE ".INVENTORY_ITEMS." SET  
														  is_deleted			= '1'
														, id_deleted			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, ip_deleted			= '".$ip."'
														, date_deleted			= NOW()
													 WHERE item_id   			= '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		$remarks = 'Stationary Item Deleted ID: "'.cleanvars($_GET['id']).'" details';
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
			header("Location: stationary_item.php", true, 301);
			exit();
	}
}
?>