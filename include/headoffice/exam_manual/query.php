<?php 
//---------------- insert record ----------------------
if(isset($_POST['submit_manual'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".EXAM_DOWNLOADS." 
										WHERE id_type = '1' AND id_session = '".cleanvars($_POST['id_session'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: exam_manual.php", true, 301);
		exit();
//--------------------------------------
	} else { 
//------------------------------------------------
	$sqllms  = $dblms->querylms("INSERT INTO ".EXAM_DOWNLOADS."(
														status								, 
														id_type								,
														note								,
														id_session							,
														id_added							, 
														date_added 	
													  )
	   											VALUES(
														'".cleanvars($_POST['status'])."'								, 
														'1'																,
														'".cleanvars($_POST['note'])."'									,
														'".cleanvars($_POST['id_session'])."'							,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
														NOW()
													  )"
							);
							
	$id = $dblms->lastestid();
	//--------------------------------------
	if(!empty($_FILES['file']['name'])) { 
	//--------------------------------------
		$path_parts 	= pathinfo($_FILES["file"]["name"]);
		$extension 		= strtolower($path_parts['extension']);
		$img_dir 		= 'uploads/assessment_downloads/';
	//--------------------------------------
		$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['id_session'])).'_'.$id.".".($extension);
		$img_fileName	= to_seo_url(cleanvars($_POST['id_session'])).'_'.$id.".".($extension);
	//--------------------------------------
		if(in_array($extension , array('pdf','ppt', 'docx'))) { 
	//--------------------------------------
			$sqllmsupload  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS."
															SET file  = '".$img_fileName."'
														 WHERE  id	  = '".cleanvars($id)."'");
			unset($sqllmsupload);
			$mode = '0644'; 
	//--------------------------------------	
			move_uploaded_file($_FILES['file']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
	//--------------------------------------
		}
	//--------------------------------------
	}
//-----------------------end---------------

//--------------------------------------
	if($sqllms) { 
//--------------------------------------
	$remarks = 'Assessment Manual Added ID: '.$id.'" detail';
		$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
															id_user										, 
															filename									, 
															action										,
															dated										,
															ip											,
															remarks				
														  )
		
													VALUES(
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
															'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 	, 
															'1'																, 
															NOW()															,
															'".cleanvars($ip)."'											,
															'".cleanvars($remarks)."'			
														  )
									");
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: exam_manual.php", true, 301);
		exit();
//--------------------------------------
	}
//--------------------------------------
	} // end checker
//--------------------------------------
} 
//---------------- Update reocrd ----------------------
if(isset($_POST['changes_manual'])) { 
//------------------------------------------------
$sqllms  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS." SET  
													status			= '".cleanvars($_POST['status'])."' 
												  , note			= '".cleanvars($_POST['note'])."' 
												  , id_session		= '".cleanvars($_POST['id_session'])."' 
												  , id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												  , date_modify		= NOW()
   											  WHERE id				= '".cleanvars($_POST['id'])."'");

//--------------------------------------										  
	$id = $_POST['id'];
	//--------------------------------------
	if(!empty($_FILES['file']['name'])) { 
	//--------------------------------------
		$path_parts 	= pathinfo($_FILES["file"]["name"]);
		$extension 		= strtolower($path_parts['extension']);
		$img_dir 	= 'uploads/assessment_downloads/';
	//--------------------------------------
		$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['id_session'])).'_'.$id.".".($extension);
		$img_fileName	= to_seo_url(cleanvars($_POST['id_session'])).'_'.$id.".".($extension);
	//--------------------------------------
		if(in_array($extension , array('pdf','ppt', 'docx'))) { 
	//--------------------------------------
			$sqllmsupload  = $dblms->querylms("UPDATE ".EXAM_DOWNLOADS."
															SET file = '".$img_fileName."'
													 WHERE  id		 = '".cleanvars($id)."'");
			unset($sqllmsupload);
			$mode = '0644'; 
	//--------------------------------------	
			move_uploaded_file($_FILES['file']['tmp_name'],$originalImage);
			chmod ($originalImage, octdec($mode));
	//--------------------------------------
		}
	//--------------------------------------
	}
//-----------------------end---------------

	if($sqllms) { 
//--------------------------------------
	$remarks = 'Assessment Policy Updated ID"'.cleanvars($_POST['id']).'" details';
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
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: exam_manual.php", true, 301);
			exit();
//--------------------------------------
	}
//--------------------------------------
}
