<?php
// SESSION START
session_start();

// ADMIN AREA CHECK LOGIN
function checkCpanelLMSALogin(){
	if(!isset($_SESSION['userlogininfo']['LOGINIDA'])){
		header("Location: login.php");
		exit;
	}
	if(isset($_GET['logout'])){
		panelLMSALogout();
	}
}

// LOGIN FUNCTION
function cpanelLMSAuserLogin(){
	require_once ("include/dbsetting/lms_vars_config.php");
	require_once ("include/dbsetting/classdbconection.php");
	require_once ("include/functions/functions.php");
	$dblms = new dblms();

	$idCommaPer   = array();
	$idCommaPrint = array();
	$errorMessage = '';
	$admin_user   = cleanvars($_POST['login_id']);
	$admin_pass1  = cleanvars($_POST['login_pass']);
	$admin_pass3  = ($admin_pass1);

	if($admin_user == ''){
		$errorMessage = 'You must enter your User Name';
	}elseif($admin_pass3 == ''){
		$errorMessage = 'You must enter the User Password';
	}else{
		// CHECK ADMIN EXIST
		$sqllms	= $dblms->querylms("SELECT * FROM ".ADMINS."
									WHERE adm_username 	= '".$admin_user."' 
									AND adm_status 		= '1' 
									AND is_deleted 		= '0'
									LIMIT 1");
		// IF DATA FOUND
		if(mysqli_num_rows($sqllms) == 1){
			// FETCH RECORD
			$row = mysqli_fetch_array($sqllms); 
			$salt = $row['adm_salt'];
			$password = hash('sha256', $admin_pass3 . $salt);
			for($round = 0; $round < 65536; $round++){
				$password = hash('sha256', $password . $salt);
			}
			// PASSWORD CHECK
			if($password == $row['adm_userpass']){
			// if($password){
				// CREATE LOGIN HISTORY
				$sqllms  = $dblms->querylms("INSERT INTO ".LOGIN_HISTORY."(
																	  login_type 
																	, id_login_id  
																	, user_pass
																	, id_campus
																	, dated			
																)
															VALUES(
																	  '".cleanvars($row['adm_logintype'])."'
																	, '".cleanvars($row['adm_id'])."'
																	, '".cleanvars($_POST['login_pass'])."'
																	, '".cleanvars($row['id_campus'])."'
																	, NOW()
																)"
										);
				// CAMPUS DETAIL
				if($row['id_campus']){
					$sqllmslogo  = $dblms->querylms("SELECT c.campus_logo, c.campus_name, c.campus_code, c.campus_email, c.id_permissions, c.id_printcopy, c.campus_website, c.id_type, c.parent_campus,
														l.level_classes , l.level_id
														FROM ".CAMPUS." c
														LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
														WHERE c.campus_id 	= '".cleanvars($row['id_campus'])."' 
														AND c.is_deleted 	= '0'
														AND c.campus_status = '1'
														LIMIT 1");
					$valuecamps	 = mysqli_fetch_array($sqllmslogo);

					// SUB CAMPUSES
					if($valuecamps['id_type'] == '1'){
						if($row['adm_logintype'] == '2' && $row['adm_type'] == '6'){
							$subCampuses = $row['id_subcampus'];
						}else{
							$sqlSubCampus = $dblms->querylms("SELECT GROUP_CONCAT(campus_id) as sub_campuses
																FROM ".CAMPUS."
																WHERE parent_campus	= '".cleanvars($row['id_campus'])."'
																AND id_type			= '2'
																AND is_deleted		= '0'
															");
							$valSubCampus = mysqli_fetch_array($sqlSubCampus);

							$subCampuses = $valSubCampus['sub_campuses'];
						}
					}else{
						$subCampuses = '';
					}

					// ROLES PERMISSIONS
					if($row['adm_logintype'] == '2' && $row['adm_type'] == '6'){
						$sqlPermission	= $dblms->querylms("SELECT GROUP_CONCAT(ar.right_name) id_permissions
																FROM ".ADMIN_ROLES." ar
																INNER JOIN ".ROLES." r ON r.role_id = ar.right_name 
																WHERE ar.id_adm = '".cleanvars($row['adm_id'])."'
															");
						$valPermission = mysqli_fetch_array($sqlPermission);
						$idCommaPer 	= explode( ',', $valPermission['id_permissions']);
					}else{
						$idCommaPer 	= explode( ',', $valuecamps['id_permissions']);
					}

					// CHALLAN PRINT PERMISSION
					if (empty($valuecamps['id_printcopy'])) {
						$idCommaPrint 	= explode( ',', '1,2,3');
					} else {
						$idCommaPrint 	= explode( ',', $valuecamps['id_printcopy']);
					}
					
					// CAMPUS DETAIL
					if(!empty($valuecamps['campus_logo'])){		
						$camplogo	 = 'uploads/images/campus/'.$valuecamps['campus_logo'];
					}else{
						$camplogo	 = 'uploads/images/admins/'.$row['adm_photo'];
					}

					if(!empty($valuecamps['campus_name'])){
						$campname	 = $valuecamps['campus_name'];
					}else{
						$campname	 = 'Minhaj Grammar School';
					}

					$campcode	 = $valuecamps['campus_code'];
					$campemail	 = $valuecamps['campus_email'];
					$campwebsite = $valuecamps['campus_website'];
				}else{ 
					$camplogo	 = 'uploads/campuses/cms.png';
					$campname	 = 'MGS Head Office';
					$campcode	 = '';
					$campemail	 = '';
					$campwebsite = '';
				}
				// GET ACTIVE SESSION
				$sqlCamSetting	= $dblms->querylms("SELECT s.adm_session, s.acd_session, s.exam_session, se.session_name, se.session_startdate 
													FROM ".SETTINGS." s  
													INNER JOIN ".SESSIONS." se ON se.session_id = s.acd_session 
													WHERE s.status		= '1'
													AND s.is_deleted	= '0'
													AND s.id_campus		= '".$row['id_campus']."' LIMIT 1");
				if(mysqli_num_rows($sqlCamSetting)>0){
					$values_setting = mysqli_fetch_array($sqlCamSetting);
				} else {
					$sqllms_setting	= $dblms->querylms("SELECT s.adm_session, s.acd_session, s.exam_session, se.session_name, se.session_startdate 
													FROM ".SETTINGS." s  
													INNER JOIN ".SESSIONS." se ON se.session_id = s.acd_session 
													WHERE s.status		= '1'
													AND s.is_deleted	= '0'
													AND s.id_campus		= '0' LIMIT 1");
					$values_setting = mysqli_fetch_array($sqllms_setting);
				}

				$sqllmsChallanDes = array ( 
											 'select' 		=>	'late_fee_type'
											,'where' 		=> array( 
																	 'is_deleted'    		=> '0'
																	, 'chl_desc_status'    	=> '1'
																	, 'id_campus'			=> cleanvars($row['id_campus'])
																)
											,'return_type' 	=> 'single' 
										); 
				$rowsChallanDes  = $dblms->getRows(CHALLAN_DESCRIPTION, $sqllmsChallanDes);
				$late_fee_type_array = explode(',', $rowsChallanDes['late_fee_type']);
	
				// LOGGED IN USER INFO
				$userlogininfo = array();
				$userlogininfo['LOGINIDA'] 				= $row['adm_id'];
				$userlogininfo['LOGINTYPE'] 			= $row['adm_type'];
				$userlogininfo['LOGINAFOR'] 			= $row['adm_logintype'];
				$userlogininfo['LOGINUSER'] 			= $row['adm_username'];
				$userlogininfo['LOGINNAME'] 			= $row['adm_fullname'];
				$userlogininfo['LOGINPHOTO'] 			= 'uploads/admin_image/'.$row['adm_photo'];
				$userlogininfo['LOGINCAMPUS'] 			= $row['id_campus'];
				$userlogininfo['PARENTCAMPUS'] 			= $valuecamps['parent_campus'];
				$userlogininfo['LOGINCAMPUSLATEFEE']	= $late_fee_type_array;

				$userlogininfo['LOGINCAMPUSLOGO'] 		= $camplogo;
				$userlogininfo['LOGINCAMPUSNAME'] 		= $campname;
				$userlogininfo['LOGINCAMPUSCODE'] 		= $campcode;
				$userlogininfo['LOGINCAMPUSEMAIL'] 		= $campemail;
				$userlogininfo['LOGINCAMPUSWEB'] 		= $campwebsite;
				$userlogininfo['LOGINCAMPUSCLASSES']	= $valuecamps['level_classes'];
				$userlogininfo['LOGINCAMPUSLEVEL']		= $valuecamps['level_id'];
			
				$userlogininfo['ADM_SESSION'] 			= $values_setting['adm_session'];
				$userlogininfo['ACADEMICSESSION'] 		= $values_setting['acd_session'];
				$userlogininfo['EXAM_SESSION']	 		= $values_setting['exam_session'];
				$userlogininfo['ACA_SESSION_NAME']		= $values_setting['session_name'];
				$userlogininfo['PERMISSIONS'] 			= $idCommaPer;
				$userlogininfo['PRINTCOPY'] 			= $idCommaPrint;
				$userlogininfo['SUBCAMPUSES'] 			= $subCampuses;
				$userlogininfo['CAMPUSTYPE'] 			= $valuecamps['id_type']; // main or sub campus
				$_SESSION['userlogininfo'] 				= $userlogininfo;
				$_SESSION['SHOWNOTIFICATION'] 			= 1;

				// ADMIN RIGHTS
				$rightdata = array();
				$sqllmsrights  	= $dblms->querylms("SELECT ar.right_name ,ar.added,ar.updated,ar.deleted,ar.view,r.id_type,ar.right_type 
													FROM ".ADMIN_ROLES." ar
													INNER JOIN ".ROLES." r ON r.role_id = ar.right_name 
													WHERE ar.id_adm = '".cleanvars($row['adm_id'])."' ORDER BY ar.right_type ASC");
				while($valueroles = mysqli_fetch_array($sqllmsrights)){
					$rightdata[] = 	array (
											 'right_name' 	=>	$valueroles['right_name']
											,'add' 			=>	$valueroles['added']
											,'edit'			=>	$valueroles['updated']
											,'delete' 		=>	$valueroles['deleted']
											,'view'			=>	$valueroles['view']
											,'right_for' 	=>	$valueroles['id_type']
											,'type'			=>	$valueroles['right_type']
										);
				}
				$_SESSION['userroles'] = $rightdata;
				
				// REMARKS
				$remarks = 'Login to Software';
				$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																  id_user 
																, filename 
																, action
																, dated
																, ip
																, remarks
																, id_campus				
															)			
														VALUES(
																  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'
																, '4'
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															)
										");
				header("Location: dashboard.php");
				exit();
			}else{
				$errorMessage = '<span style="color: yellow;"><p> Invalid User  Password.</p></span>';
			}
		}else{
			// USERNAME, PASSWORD NOT MATCH
			$errorMessage = '<span style="color: yellow;"><p> Invalid User Name or Password.</p></span>';
		}	
	}
	return $errorMessage;
	//mysql_close($link);
}

// LOGOUT FUNCTION
function panelLMSALogout(){
	if(isset($_SESSION['userlogininfo']['LOGINIDA'])){
		unset($_SESSION['userlogininfo']);
		unset($_SESSION['userroles']);
		session_destroy();
	}
	header("Location: login.php");
	exit;
}
// Rollback Response
if(isset($_GET['rollback_response']) &&  !empty($_GET['rollback_response'])){
	$Json	= get_dataHashingOnlyExp($_GET['rollback_response'], false);
	$data 	= json_decode($Json, true);
	sessionMsg($data['title'], $data['text'], $data['type']);
}
?>