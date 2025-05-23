<?php 
//----------------Employee AttendanceIinsert record----------------------
if(isset($_POST['submit_attendce'])) { 
//------------------------------------------------
$dated = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
//------------------------------------------------
	$sqllmscheck  = $dblms->querylms("SELECT dated  
										FROM ".EMPLOYEES_ATTENDCE." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND dated = '".cleanvars($dated)."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: attendance_employees.php", true, 301);
		exit();
//--------------------------------------
	} else { 
//------------------------------------------------
	$sqllms  = $dblms->querylms("INSERT INTO ".EMPLOYEES_ATTENDCE."(
														dated							, 
														id_session						, 
														id_campus 						,
														id_added						,
														date_added	
													  )
	   											VALUES(
														'".cleanvars($dated)."'											, 
														'".cleanvars("2019-20")."'										,
														'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'  		,
														NOW()
													  ) ");
//--------------------------------------
	if($sqllms) { 
//--------------------------------------
	$remarks = 'Add Employee Attendance: "'.cleanvars($dated).'" detail';
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
		header("Location: attendance_employees.php", true, 301);
		exit();
//--------------------------------------
	}
//--------------------------------------
	} // end checker
//--------------------------------------
} 
//----------------Fee Category Update reocrd----------------------
if(isset($_POST['changes_feecat'])) { 
//------------------------------------------------
$sqllms  = $dblms->querylms("UPDATE ".FEE_CATEGORY." SET  
													cat_status			= '".cleanvars($_POST['cat_status'])."'
												  , cat_name			= '".cleanvars($_POST['cat_name'])."' 
												  , cat_detail			= '".cleanvars($_POST['cat_detail'])."'
												  , id_campus			= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
   											  WHERE cat_id			= '".cleanvars($_POST['cat_id'])."'");
//--------------------------------------
	if($sqllms) { 
//--------------------------------------
	$remarks = 'Update Category: "'.cleanvars($_POST['cat_name']).'" details';
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
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
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
			header("Location: fee-category.php", true, 301);
			exit();
//--------------------------------------
	}
//--------------------------------------
}

//----------------Feesetup insert record----------------------
if(isset($_POST['submit_feesetup'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT id_session, id_class, id_section
										FROM ".FEESETUP." 
										WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND id_session 	= '".cleanvars($_POST['id_session'])."'
										AND id_class 	= '".cleanvars($_POST['id_class'])."'
										AND id_section 	= '".cleanvars($_POST['id_section'])."' LIMIT 1");
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
														'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
														 Now()	
													  )"
													  );
						  
//------------------------------------------------ Fee Setup Detail
$idsetup = $dblms->lastestid();	
//------------------------------------------------
	
	for($i=1; $i<= count($_POST['id_cat']); $i++){
	$sqllms  = $dblms->querylms("INSERT INTO ".FEESETUPDETAIL."(
														id_setup		,
														id_cat			,
														duration		,
														amount			,
														type						
													  )
	   											VALUES(
														'".cleanvars($idsetup)."'						,
														'".cleanvars($_POST['id_cat'][$i])."'			,	
														'".cleanvars($_POST['duration'][$i])."'			,
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
	
//------------------------Reformat Date------------------------
$date = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
//------------------------------------------------
$sqllms  = $dblms->querylms("UPDATE ".FEESETUP." SET  
													status			= '".cleanvars($_POST['status'])."'
												  , dated			= '".cleanvars($date)."' 
												  , id_class		= '".cleanvars($_POST['id_class'])."' 
												  , id_section		= '".cleanvars($_POST['id_section'])."' 
												  , id_session		= '".cleanvars($_POST['id_session'])."'
												  , id_campus		= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
												  , id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
												  ,	date_modify		= Now()
   											  WHERE id				= '".cleanvars($_GET['id'])."'");
											  
//----------------update Feeetup detail reocrd----------------------
for($i=1; $i<= count($_POST['id_cat']); $i++){
//---------------------------------------------
											  
$sqllmss  = $dblms->querylms("UPDATE ".FEESETUPDETAIL." SET  
														amount			= '".cleanvars($_POST['amount'][$i])."'
												 	 , duration			= '".cleanvars($_POST['duration'][$i])."' 
												 	 , type				= '".cleanvars($_POST['type'][$i])."'
   											 	 WHERE id				= '".cleanvars($_POST['id_edit'][$i])."'");
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
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: feesetup.php", true, 301);
			exit();
//--------------------------------------
	}
//--------------------------------------
}
