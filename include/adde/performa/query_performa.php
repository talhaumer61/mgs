<?php 
//----------------Notification insert record----------------------
if(isset($_POST['submit_performa'])) {
	$sqllmscheck  = $dblms->querylms("SELECT id_campus,visit_month
										FROM ".CAMPUS_PERFORMA." 
										WHERE id_campus = '".cleanvars($_POST['id_campus'])."' 
										AND visit_month = '".cleanvars($_POST['visit_month'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {
		//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Error';
			$_SESSION['msg']['text'] 	= 'Record Already Exists';
			$_SESSION['msg']['type'] 	= 'error';
			header("Location: performa.php", true, 301);
			exit();
		//--------------------------------------
	} else if (empty($_POST['question_id'])){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'No Questions Submitted to Add';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: performa.php", true, 301);
		exit();
	}else { 
		//------------- Seprate The Values ----------------
		$values_camp = explode("|",$_POST['id_campus']);
		$camp_id	 = $values_camp[0];
		$id_ad	 	 = $values_camp[1];
		$id_de		 = $values_camp[2];
		//------------------------------------------------
		// -----------------Attachment--------------------
		if(!empty($_FILES['attach_file']['name'])) { 
			$path_parts 	= pathinfo($_FILES["attach_file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 		= 'uploads/visit_perfoma/';
			$originalImage	= $img_dir.to_seo_url(get_monthtypes(cleanvars($_POST['visit_month']))).'-'.$camp_id.".".($extension);
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'psd', 'docx'))) { 
				
				$img_fileName = to_seo_url(get_monthtypes(cleanvars($_POST['visit_month']))).'-'.$camp_id.".".($extension);
				$mode = '0644'; 
				move_uploaded_file($_FILES['attach_file']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			} else {
				$img_fileName = '';
			}
		}
		//------------------------------------------------
		$values = array(
			'id_campus'	  => $camp_id								,
			'attach_file' => $img_fileName							,
			'status'	  => '2'						,
			'visit_month' => $_POST['visit_month']					,
			'date_added'  => date('Y-m-d H:i:s')					,
			'id_added' 	  => $_SESSION['userlogininfo']['LOGINIDA']	,
			'id_ad' 	  => $id_ad									,	
			'id_de' 	  => $id_de										
		);
		$sqllms = $dblms->Insert(CAMPUS_PERFORMA , $values); 
		//--------------------------------------
		if($sqllms) { 
			//--------------------------------------
			$last_id =	$dblms->lastestid();
			$question_id 	= $_POST['question_id'];
			$rating 		= $_POST['rating'];
			$is_applicable 	= $_POST['is_applicable'];
			//--------------------------------------
			foreach ($question_id as $key => $questionid) {
				# code...
				$apcbl = '';
				$rtng  = '';
				if( isset ($is_applicable[$key])){
					$apcbl = $is_applicable[$key];
					$rtng = '';
				} else {
					$apcbl = '1';
					$rtng = $rating[$key];
				}
				$values_add = array(
					'id_setup' 		=> $last_id		,
					'id_question' 	=> $questionid	,
					'rating' 		=> $rtng		,
					'is_applicable' => $apcbl
			
				);
				$sqllms_add = $dblms->Insert(CAMPUS_PERFORMA_DET , $values_add);
				# Clear after query execution
				if($sqllms_add){
					unset($values_add,$sqllms_add);
				}
			}
			//--------------------------------------
			//--------------------------------------
			$remarks = 'Add Performa: "'.cleanvars($_POST['visit_month']).'" detail';
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
			header("Location: performa.php", true, 301);
			exit();
			//--------------------------------------
		}
		//--------------------------------------
	} // end checker
	//--------------------------------------
} 
//----------------Notification Update reocrd----------------------
if(isset($_POST['update_performa'])) { 
		//------------------------------------------------
		$edit_count = cleanvars($_POST['edit_count']) + 1;
		$camp_id = $_POST['id_campus'];
		// -----------------Attachment--------------------
		if(!isset($_FILES['attach_file']) || $_FILES['attach_file']['error'] == UPLOAD_ERR_NO_FILE) { 
			$img_fileName = $_POST['db_attach_file'];
		}else {
			$path_parts 	= pathinfo($_FILES["attach_file"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 		= 'uploads/visit_perfoma/';
			$originalImage	= $img_dir.to_seo_url(get_monthtypes(cleanvars($month = date("n")))).'-'.$camp_id.".".($extension);
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'psd', 'docx'))) 
			{ 
				$img_fileName = to_seo_url(get_monthtypes(cleanvars($month = date("n")))).'-'.$camp_id.".".($extension);
				$mode = '0644'; 
				if(isset($_POST['db_attach_file']) && !empty($_POST['db_attach_file'])){
					unlink($img_dir.$_POST['db_attach_file']);
				}
				move_uploaded_file($_FILES['attach_file']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}
		//-----------------------------------------------
		//------------------------------------------------
		$values = array(
			'attach_file' => $img_fileName							,
			'edit_count'  => cleanvars($edit_count)			,
			'date_modify' => date('Y-m-d H:i:s')			,
			'id_modify'   => cleanvars($_SESSION['userlogininfo']['LOGINIDA'])	
			
		);
		$sqllms = $dblms->Update(CAMPUS_PERFORMA , $values , "WHERE id = '".$_POST['performa_id']."'"); 
		//--------------------------------------
		if($sqllms) { 
			//--------------------------------------
			$sqlDel = $dblms->querylms("DELETE FROM ".CAMPUS_PERFORMA_DET." WHERE id_setup = '".cleanvars($_POST['performa_id'])."' ");
			//--------------------------------------
			$last_id 		= $_POST['performa_id'];
			$question_id 	= $_POST['question_id'];
			$rating 		= $_POST['rating'];
			$is_applicable 	= $_POST['is_applicable'];
			//--------------------------------------
			foreach ($question_id as $key => $questionid) {
				# code...
				$apcbl = '';
				$rtng  = '';
				if( isset ($is_applicable[$key])){
					$apcbl = $is_applicable[$key];
					$rtng = '';
				} else {
					$apcbl = '1';
					$rtng = $rating[$key];
				}
				$values_add = array(
					'id_setup' 		=> cleanvars($last_id)		,
					'id_question' 	=> cleanvars($questionid)	,
					'rating' 		=> cleanvars($rtng)			,
					'is_applicable' => cleanvars($apcbl)
			
				);
				$sqllms_add = $dblms->Insert(CAMPUS_PERFORMA_DET , $values_add);
				# Clear after query execution
				if($sqllms_add){
					unset($values_add,$sqllms_add);
				}
			}
			//--------------------------------------
			$remarks = 'Update Performa: "'.cleanvars($_POST['visit_month']).'" detail';
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
				header("Location: performa.php", true, 301);
				exit();
			//--------------------------------------
		}
		//--------------------------------------
}
?>