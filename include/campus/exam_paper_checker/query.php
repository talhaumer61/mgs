<?php
// ADD EXAM PAPER CHECKER
if(isset($_POST['add_record'])) {
	$condition	=	array ( 
								 'select' 	=> 'id'
								,'where' 	=> array( 
								 						 'id_campus'	=>	cleanvars($_POST['id_campus'])
														,'id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'id_exam'		=>	cleanvars($_POST['id_exam'])
														,'id_class'		=>	cleanvars($_POST['id_class'])
														,'id_section'	=>	cleanvars($_POST['id_section'])
														,'id_subject'	=>	cleanvars($_POST['id_subject'])
														,'is_deleted'	=>	0
													)
								,'return_type' 	=> 'single' 
	); 
	if($dblms->getRows(EXAM_PAPER_CHECKER, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		if($_POST['paper_qty'] != 0 && !empty($_POST['paper_qty'])){
			$values = array(
								 'id_campus'			=>	cleanvars($_POST['id_campus'])
								,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
								,'id_exam'				=>	cleanvars($_POST['id_exam'])
								,'id_class'				=>	cleanvars($_POST['id_class'])
								,'id_section'			=>	cleanvars($_POST['id_section'])
								,'id_subject'			=>	cleanvars($_POST['id_subject'])
								,'id_emply'				=>	cleanvars($_POST['id_emply'])
								,'paper_qty'			=>	cleanvars($_POST['paper_qty'])
								,'date_handover'		=>	date('Y-m-d', strtotime($_POST['date_handover']))
								,'date_submission'		=>	date('Y-m-d', strtotime($_POST['date_submission']))
								,'id_added'				=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_added'			=>	date('Y-m-d G:i:s')
			);
			$sqllms	= $dblms->insert(EXAM_PAPER_CHECKER, $values);
			if ($sqllms) {
				$latestID = $dblms->lastestid();
				sendRemark(moduleName(false).' Assigned. ID: "'.cleanvars($latestID).' detail', 1);
				sessionMsg("Success", "Record Successfully Added.", "success");
				header("Location: ".moduleName().".php", true, 301);
				exit();
			}
		}else{
			sessionMsg("Warning", "There must be some paper quantity to assign.", "warning");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// EDIT EXAM PAPER CHECKER
if(isset($_POST['edit_record'])) {
	$condition	=	array ( 
								 'select' 		=> 'id'
								,'where' 		=> array( 
															 'id_campus'	=>	cleanvars($_POST['id_campus'])
															,'id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
															,'id_exam'		=>	cleanvars($_POST['id_exam'])
															,'id_class'		=>	cleanvars($_POST['id_class'])
															,'id_section'	=>	cleanvars($_POST['id_section'])
															,'id_subject'	=>	cleanvars($_POST['id_subject'])
															,'is_deleted'	=>	0
														)
								,'not_equal'	=>	array(
															'id'			=>	cleanvars($_POST['id'])
														)
								,'return_type' 	=> 'single' 
	); 
	if($dblms->getRows(EXAM_PAPER_CHECKER, $condition)) {
		sessionMsg("Error", "Record Already Exist.", "error");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	} else {
		if($_POST['paper_qty'] != 0 && !empty($_POST['paper_qty'])){
			$values = array(
								 'id_campus'			=>	cleanvars($_POST['id_campus'])
								,'id_session'			=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
								,'id_exam'				=>	cleanvars($_POST['id_exam'])
								,'id_class'				=>	cleanvars($_POST['id_class'])
								,'id_section'			=>	cleanvars($_POST['id_section'])
								,'id_subject'			=>	cleanvars($_POST['id_subject'])
								,'id_emply'				=>	cleanvars($_POST['id_emply'])
								,'paper_qty'			=>	cleanvars($_POST['paper_qty'])
								,'date_handover'		=>	date('Y-m-d', strtotime($_POST['date_handover']))
								,'date_submission'		=>	date('Y-m-d', strtotime($_POST['date_submission']))
								,'id_modify'			=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,'date_modify'			=>	date('Y-m-d G:i:s')
			);
			$sqllms	= $dblms->insert(EXAM_PAPER_CHECKER, $values);
			if ($sqllms) {
				$latestID = $_POST['id'];
				sendRemark(moduleName(false).' Updated. ID: "'.cleanvars($latestID).' detail', 2);
				sessionMsg("Success", "Record Successfully Updated.", "info");
				header("Location: ".moduleName().".php", true, 301);
				exit();
			}
		}else{
			sessionMsg("Warning", "There must be some paper quantity to assign.", "warning");
			header("Location: ".moduleName().".php", true, 301);
			exit();
		}
	}
}

// DELETE EXAM PAPER CHECKER
if(isset($_GET['deleteid'])){
	$latestID = $_GET['deleteid'];
	$values = array(
					  'is_deleted'		=> '1'
					, 'id_deleted'		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
					, 'ip_deleted'		=> cleanvars($ip)
					, 'date_deleted'	=> date('Y-m-d G:i:s')
				);
	$sqllms = $dblms->Update(EXAM_PAPER_CHECKER , $values, "WHERE id = '".cleanvars($latestID)."'");

	// REMARKS
	if($sqllms){
		sendRemark("Delete ".moduleName(false)." ID: ".$latestID." Detail", '3');
		sessionMsg("Success", "Record Successfully Deleted.", "success");
		header("Location: ".moduleName().".php", true, 301);
		exit();
	}
}
?>