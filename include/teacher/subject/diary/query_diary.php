<?php 
//----------------insert record----------------------
if(isset($_POST['submit_diary'])) { 
	//------------------------------------------------
	$dated = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
	//------------------------------------------------
	$sqllmscheck  = $dblms->querylms("SELECT note, id_session, id_class, id_subject, id_campus  
										FROM ".DIARY." 
										WHERE note = '".cleanvars($_POST['note'])."'  
										AND dated  = '".$dated."'
										AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
										AND id_class   = '".cleanvars($_POST['id_class'])."'
										AND id_section = '".cleanvars($_POST['id_section'])."'
										AND id_subject = '".cleanvars($_POST['id_subject'])."'
										AND id_teacher = '".cleanvars($value_emp['emply_id'])."'
										AND id_campus  = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
	//--------------------------------------
		$ref = '?id='.$_POST['id_subject'].'&section='.$_POST['id_section'].'&class='.$_POST['id_class'].'&view=diary';
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: subject.php$ref", true, 301);
		exit();
	//--------------------------------------
	} else { 
	//------------------------------------------------
		
	$sqllms  = $dblms->querylms("INSERT INTO ".DIARY."(
														status							, 
														note							, 
														dated							, 
														id_session							, 
														id_class							,
														id_section							, 
														id_subject							,
														id_teacher							,
														id_campus							,
														id_added							, 
														date_added 	
													  )
	   											VALUES(
														'".cleanvars($_POST['status'])."'							, 
														'".cleanvars($_POST['note'])."'							, 
														'".cleanvars($dated)."'											, 
														'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	,
														'".cleanvars($_POST['id_class'])."'								,
														'".cleanvars($_POST['id_section'])."'							,
														'".cleanvars($_POST['id_subject'])."'							,
														'".cleanvars($value_emp['emply_id'])."'							,
														'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
														NOW()
													  )"
							);
		$id_latest = $dblms->lastestid();
	//--------------------------------------
	if($sqllms) { 
	//--------------------------------------
		$remarks = 'Add Diary ID: "'.$id_latest.'"';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															id_user										, 
															filename									, 
															action										,
															dated										,
															ip											,
															remarks				
														  )
		
													VALUES(
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
															'1'											, 
															NOW()										,
															'".cleanvars($ip)."'						,
															'".cleanvars($remarks)."'			
														  )
									");
	//--------------------------------------
		$ref = '?id='.$_POST['id_subject'].'&section='.$_POST['id_section'].'&class='.$_POST['id_class'].'&view=diary';
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: subject.php$ref", true, 301);
		exit();
	//--------------------------------------
	}
	//--------------------------------------
	} // end checker
	//--------------------------------------
} 
//----------------Update reocrd----------------------
if(isset($_POST['changes_diary'])) { 
//------------------------------------------------
$dated = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
	$sqllms  = $dblms->querylms("UPDATE ".DIARY." SET  
													status		= '".cleanvars($_POST['status'])."'
												  ,	note		= '".cleanvars($_POST['note'])."'
												  , dated		= '".cleanvars($dated)."' 
												  , id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , date_modify		= NOW()
   											  		WHERE id		= '".cleanvars($_POST['id'])."'");


	if($sqllms) { 
//--------------------------------------
	$remarks = 'Update Diary ID: "'.cleanvars($_POST['ann_id']).'"';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															id_user										, 
															filename									, 
															action										,
															dated										,
															ip											,
															remarks			
														  )
		
													VALUES(
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'	,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' , 
															'2'											, 
															NOW()										,
															'".cleanvars($ip)."'						,
															'".cleanvars($remarks)."'		
														  )
									");
//--------------------------------------
			$ref = '?id='.$_GET['id'].'&section='.$_GET['section'].'&class='.$_GET['class'].'&view=diary';
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location:  subject.php$ref", true, 301);
			exit();
//--------------------------------------
	}
//--------------------------------------
}
