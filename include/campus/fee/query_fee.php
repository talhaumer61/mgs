<?php 
//----------------Feesetup insert record----------------------
if(isset($_POST['submit_feesetup'])) { 
	$id_campus = (!empty($_POST['id_campus']) ? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));

	$sqllmscheck  = $dblms->querylms("SELECT id_session, id_class, id_section
										FROM ".FEESETUP." 
										WHERE id_class 	= '".cleanvars($_POST['id_class'])."'
										AND id_section 	= '".cleanvars($_POST['id_section'])."'
										AND id_session 	= '".cleanvars($_POST['id_session'])."'
										AND id_campus = '".cleanvars($id_campus)."' 
										AND is_deleted != '1' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: feesetup.php", true, 301);
		exit();
//--------------------------------------
	} else { 
//------------------------Reformat Date------------------------
$date = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
//------------------------------------------------
	$sqllms  = $dblms->querylms("INSERT INTO ".FEESETUP."(
														status						, 
														dated						, 
														id_class					, 
														id_section					,
														id_session					, 
														id_campus 					,
														id_added					,
														date_added
													  )
	   											VALUES(
														'".cleanvars($_POST['status'])."'								, 
														'".cleanvars($date)."'								,
														'".cleanvars($_POST['id_class'])."'								,
														'".cleanvars($_POST['id_section'])."'							,
														'".cleanvars($_POST['id_session'])."'							,
														'".cleanvars($id_campus)."'		,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
														 Now()	
													  )"
													  );
						  
//-------------------------Get latest Id----------------------- 
$idsetup = $dblms->lastestid();	
//-------------------------Fee Setup Detail-----------------------
	
	for($i=1; $i<= count($_POST['id_cat']); $i++){
	$sqllms  = $dblms->querylms("INSERT INTO ".FEESETUPDETAIL."(
														id_setup		,
														id_cat			,
														amount			,
														type						
													  )
	   											VALUES(
														'".cleanvars($idsetup)."'						,
														'".cleanvars($_POST['id_cat'][$i])."'			,	
														'".cleanvars($_POST['amount'][$i])."'			,
														'".cleanvars($_POST['type'][$i])."'				
													  )"
													  );

												}
	}
//--------------------------------------
	if($sqllms) { 
//--------------------------------------
	$remarks = 'Add Feesetup: "'.cleanvars($_POST['dated']).'" detail';
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
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'				,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 		, 
															'1'																	, 
															NOW()																,
															'".cleanvars($ip)."'												,
															'".cleanvars($remarks)."'						,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
														  )
									");
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: feesetup.php", true, 301);
		exit();
//--------------------------------------

//--------------------------------------
	} // end checker
//--------------------------------------
} 
//----------------update Feeetup reocrd----------------------
if(isset($_POST['changes_feesetup'])) {
	$id_campus = (!empty($_POST['id_campus']) ? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));
	
//------------------------Reformat Date------------------------
$date = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
//------------------------------------------------
$sqllms  = $dblms->querylms("UPDATE ".FEESETUP." SET  
													status			= '".cleanvars($_POST['status'])."'
												  , dated			= '".cleanvars($date)."' 
												  , id_class		= '".cleanvars($_POST['id_class'])."' 
												  , id_section		= '".cleanvars($_POST['id_section'])."' 
												  , id_session		= '".cleanvars($_POST['id_session'])."'
												  , id_campus		= '".cleanvars($id_campus)."' 
												  , id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
												  ,	date_modify		= Now()
   											  WHERE id				= '".cleanvars($_POST['id'])."'");
											  
//----------------update Feeetup detail reocrd----------------------
for($i=1; $i<= count($_POST['id_cat']); $i++){
//---------------------------------------------
	if(!empty($_POST['id_edit'][$i])){
		$sqllmss  = $dblms->querylms("UPDATE ".FEESETUPDETAIL." SET  
															id_setup			= '".cleanvars($_POST['id'])."'
														,	id_cat			= '".cleanvars($_POST['id_cat'][$i])."'
														,	amount			= '".cleanvars($_POST['amount'][$i])."'
														, 	type				= '".cleanvars($_POST['type'][$i])."'
													WHERE id				= '".cleanvars($_POST['id_edit'][$i])."'");
	}
	else{
		$sqllms  = $dblms->querylms("INSERT INTO ".FEESETUPDETAIL."(
																		id_setup		,
																		id_cat			,
																		amount			,
																		type						
																	)
																VALUES(
																		'".cleanvars($_POST['id'])."'					,
																		'".cleanvars($_POST['id_cat'][$i])."'			,	
																		'".cleanvars($_POST['amount'][$i])."'			,
																		'".cleanvars($_POST['type'][$i])."'				
																	)"
									);
	}

}					  

//--------------------------------------
	if($sqllms) { 
//--------------------------------------
	$remarks = 'Update Feesetup: "'.cleanvars($_POST['type']).'" details';
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
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: feesetup.php", true, 301);
			exit();
//--------------------------------------
	}
//--------------------------------------
}


//---------------- Delete reocrd----------------------
if(isset($_GET['deleteid'])) { 
	//------------------------------------------------
	$sqllms  = $dblms->querylms("UPDATE ".FEESETUP." SET  
														  is_deleted			= '1'
														, id_deleted			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
														, ip_deleted			= '".$ip."'
														, date_deleted			= NOW()
													 WHERE id 			= '".cleanvars($_GET['deleteid'])."'");
	//--------------------------------------
		if($sqllms) { 
	//--------------------------------------
		$remarks = 'Feesetup Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
	//--------------------------------------
				$_SESSION['msg']['title'] 	= 'Warning';
				$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
				$_SESSION['msg']['type'] 	= 'warning';
				header("Location: feesetup.php", true, 301);
				exit();
	//--------------------------------------
		}
	//--------------------------------------
	}

?>
