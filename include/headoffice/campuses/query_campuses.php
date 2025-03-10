<?php 
// ADD RECORD
if(isset($_POST['submit_campus'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT campus_name  
										FROM ".CAMPUS." 
										WHERE campus_name = '".cleanvars($_POST['campus_name'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {		
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: campuses.php", true, 301);
		exit();
	}else{
		// ROLES PERMISSIONS		
		$idRoleFor 			= array();
		$idRoleForComma 	= "";
		foreach($_POST['id_permissions'] as $key => $val):
			if($val != 'all'){
				array_push($idRoleFor, $val);
				$idRoleForComma = implode(",", $idRoleFor);	
			}	
		endforeach;

		// CHALLAN PRINT PERMISSION
		$idPrintFor 		= array();
		$idPrintCopyComma	= '';
		foreach($_POST['id_printcopy'] as $key => $val):
			array_push($idPrintFor, $val);
			$idPrintCopyComma = implode(",", $idPrintFor);	
		endforeach;

		// CAMPUS TYPE
		if(isset($_POST['id_type']) && $_POST['id_type'] == '1'){
			$parent_campus = '0';
		}elseif(isset($_POST['id_type']) && $_POST['id_type'] == '2'){
			$parent_campus = $_POST['parent_campus'];
		}

		// SEPERATE THE VALUES
		$values_city = explode("|",$_POST['city']);
		$city_code   = $values_city[0];
		$city_id 	 = $values_city[1];
		$dist_id	 = $values_city[2];
		$zone_id 	 = $values_city[3];
		$prov_id	 = $values_city[4];

		$value_brand = explode("|",$_POST['brand']);
		$br_code = $value_brand[0];
		$br_id = $value_brand[1];

		// CAMPUS NO
		$sqllmscamp	= $dblms->querylms("SELECT campus_id FROM ".CAMPUS." ORDER by campus_id DESC LIMIT 1 ");
		$rowcamp 	= mysqli_fetch_array($sqllmscamp);
		$id 		= $rowcamp['campus_id'] + 1;
		if(mysqli_num_rows($sqllmscamp) < 1) {
			$camp_no	= str_pad(1,5,"0",STR_PAD_LEFT);
		}else{
			$camp_no	= str_pad($id,5,"0",STR_PAD_LEFT);
		}

		// CODE
		$campus_regno = "MES-".$br_code."-".$city_code."-".$camp_no;

		// Date Format
		$estab_date = date('Y-m-d', strtotime($_POST['established_date']));

		// insert query
		$values = array(
							 'campus_status'		=> cleanvars($_POST['campus_status']) 
							,'id_type'				=> cleanvars($_POST['id_type']) 
							,'campus_regno'			=> cleanvars($campus_regno) 
							,'govt_regno'			=> cleanvars($_POST['govt_regno']) 
							,'bise_affiliation'		=> cleanvars($_POST['bise_affiliation']) 
							,'established_date'		=> cleanvars($estab_date) 
							,'campus_name'			=> cleanvars($_POST['campus_name']) 
							,'campus_code'			=> cleanvars($_POST['campus_code']) 
							,'id_brand'				=> cleanvars($br_id) 
							,'id_group'				=> cleanvars($_POST['id_group'])
							,'id_level'				=> cleanvars($_POST['id_level'])
							,'campus_for'			=> cleanvars($_POST['campus_for']) 
							,'is_hifiz'				=> cleanvars($_POST['is_hifiz']) 
							,'is_transport'			=> cleanvars($_POST['is_transport']) 
							,'is_hostel'			=> cleanvars($_POST['is_hostel']) 
							,'is_eveningclasses'	=> cleanvars($_POST['is_eveningclasses']) 
							,'id_city'				=> cleanvars($city_id) 
							,'id_dist'				=> cleanvars($dist_id) 
							,'id_zone'				=> cleanvars($zone_id) 
							,'id_prov'				=> cleanvars($prov_id) 
							,'campus_address'		=> cleanvars($_POST['campus_address']) 
							,'campus_email'			=> cleanvars($_POST['campus_email']) 
							,'campus_phone'			=> cleanvars($_POST['campus_phone']) 
							,'campus_head'			=> cleanvars($_POST['campus_head']) 
							,'is_tvi'				=> cleanvars($_POST['tvi'])
							,'campus_website'		=> cleanvars($_POST['campus_website'])
							,'id_permissions'		=> cleanvars($idRoleForComma)
							,'id_printcopy'			=> cleanvars($idPrintCopyComma)
							,'parent_campus'		=> cleanvars($parent_campus)
							,'id_added'				=> cleanvars($_SESSION['userlogininfo']['LOGINIDA']) 
							,'date_added'			=> date('Y-m-d H:i:s')
						);
		$sqllms = $dblms->Insert(CAMPUS , $values);

		// LATEST ID
		$campus_id = $dblms->lastestid();

		// FILE UPLOAD
		if(!empty($_FILES['campus_logo']['name'])) {
			$path_parts	= pathinfo($_FILES["campus_logo"]["name"]);
			$extension	= strtolower($path_parts['extension']);
			$img_dir	= 'uploads/images/campus/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($campus_regno)).'_'.$campus_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($campus_regno)).'_'.$campus_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) {
				$values = array (
									'campus_logo'	=> cleanvars($img_fileName)
								);	
				$sqllmsupload = $dblms->Update(CAMPUS , $values , "WHERE campus_id = '".cleanvars($campus_id)."'");

				unset($sqllmsupload);
				$mode = '0644';
				move_uploaded_file($_FILES['campus_logo']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		// remarks insert
		if($sqllms){
			sendRemark("Add Campus: ".cleanvars($_POST['campus_name'])." detail", '1');			
			sessionMsg("Success", "Record Successfully Added.", "success");
			header("Location: campuses.php", true, 301);
			exit();
		}
	}
}

// UPDATE RECORD
if(isset($_POST['changes_campus'])) {
	$sqllmscheck  = $dblms->querylms("SELECT campus_name  
										FROM ".CAMPUS." 
										WHERE campus_name = '".cleanvars($_POST['campus_name'])."'
										AND campus_id	 != '".cleanvars($_POST['campus_id'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)) {		
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: campuses.php", true, 301);
		exit();
	}else{
		// ROLES PERMISSIONS		
		$idRoleFor 			= array();
		$idRoleForComma 	= "";
		foreach($_POST['id_permissions'] as $key => $val):
			if($val != 'all'){
				array_push($idRoleFor, $val);
				$idRoleForComma = implode(",", $idRoleFor);	
			}	
		endforeach;

		// CHALLAN PRINT PERMISSIONS
		$idPrintFor 		= array();
		$idPrintCopyComma	= '';
		foreach($_POST['id_printcopy'] as $key => $val):
			array_push($idPrintFor, $val);
			$idPrintCopyComma = implode(",", $idPrintFor);	
		endforeach;

		// CAMPUS TYPE
		if(isset($_POST['id_type']) && $_POST['id_type'] == '1'){
			$parent_campus = '0';
		}elseif(isset($_POST['id_type']) && $_POST['id_type'] == '2'){
			$parent_campus = $_POST['parent_campus'];
		}

		// Seprate The Values 
		$values_city = explode("|",$_POST['city']);
		$city_code   = $values_city[0];
		$city_id 	 = $values_city[1];
		$dist_id	 = $values_city[2];
		$zone_id 	 = $values_city[3];
		$prov_id	 = $values_city[4];

		$value_brand = explode("|",$_POST['brand']);
		$br_code = $value_brand[0];
		$br_id = $value_brand[1];

		// CAMPUS NO
		$sqllmscamp 	= $dblms->querylms("SELECT campus_id FROM ".CAMPUS." 
												WHERE campus_id = '".$_POST['campus_id']."'
												LIMIT 1 ");
		$rowcamp 	= mysqli_fetch_array($sqllmscamp);
		$id = $rowcamp['campus_id'];
		if(mysqli_num_rows($sqllmscamp) < 1) {
			$camp_no	= str_pad(1,5,"0",STR_PAD_LEFT);
		} else  {
			$camp_no	= str_pad($id,5,"0",STR_PAD_LEFT);
		}

		// CODE 
		$campus_regno = "MES-".$br_code."-".$city_code."-".$camp_no;

		// Date Format
		$estab_date = date('Y-m-d', strtotime($_POST['established_date']));
		
		// update Record 
		$values = array(
							 'campus_status'		=> cleanvars($_POST['campus_status']) 
							,'id_type'				=> cleanvars($_POST['id_type']) 
							,'campus_regno'			=> cleanvars($campus_regno) 
							,'govt_regno'			=> cleanvars($_POST['govt_regno']) 
							,'bise_affiliation'		=> cleanvars($_POST['bise_affiliation']) 
							,'established_date'		=> cleanvars($estab_date) 
							,'campus_name'			=> cleanvars($_POST['campus_name']) 
							,'campus_code'			=> cleanvars($_POST['campus_code']) 
							,'id_brand'				=> cleanvars($br_id) 
							,'id_group'				=> cleanvars($_POST['id_group'])
							,'id_level'				=> cleanvars($_POST['id_level'])
							,'campus_for'			=> cleanvars($_POST['campus_for']) 
							,'is_hifiz'				=> cleanvars($_POST['is_hifiz']) 
							,'is_transport'			=> cleanvars($_POST['is_transport']) 
							,'is_hostel'			=> cleanvars($_POST['is_hostel']) 
							,'is_eveningclasses'	=> cleanvars($_POST['is_eveningclasses']) 
							,'id_city'				=> cleanvars($city_id) 
							,'id_dist'				=> cleanvars($dist_id) 
							,'id_zone'				=> cleanvars($zone_id) 
							,'id_prov'				=> cleanvars($prov_id) 
							,'campus_address'		=> cleanvars($_POST['campus_address']) 
							,'campus_email'			=> cleanvars($_POST['campus_email']) 
							,'campus_phone'			=> cleanvars($_POST['campus_phone']) 
							,'campus_head'			=> cleanvars($_POST['campus_head']) 
							,'is_tvi'				=> cleanvars($_POST['tvi'])
							,'campus_website'		=> cleanvars($_POST['campus_website'])
							,'id_permissions'		=> cleanvars($idRoleForComma)
							,'id_printcopy'			=> cleanvars($idPrintCopyComma)
							,'parent_campus'		=> cleanvars($parent_campus)
							,'id_modify'			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA']) 
							,'date_modify'			=> date('Y-m-d H:i:s')
						);						
		$sqllms = $dblms->Update(CAMPUS , $values , "WHERE campus_id = '".cleanvars($_POST['campus_id'])."'");

		// LATEST ID
		$campus_id = cleanvars($_POST['campus_id']);
		// FILE UPLOAD
		if(!empty($_FILES['campus_logo']['name'])) { 
			$path_parts 	= pathinfo($_FILES["campus_logo"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 		= 'uploads/images/campus/';
			
			$originalImage	= $img_dir.to_seo_url(cleanvars($campus_regno)).'_'.$campus_id.".".($extension);
			$img_fileName	= to_seo_url(cleanvars($campus_regno)).'_'.$campus_id.".".($extension);
			
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))) {
				$values = array (
									'campus_logo'	=> cleanvars($img_fileName)
								);	
				$sqllmsupload = $dblms->Update(CAMPUS , $values , "WHERE campus_id = '".cleanvars($campus_id)."'");

				unset($sqllmsupload);
				$mode = '0644';				
				move_uploaded_file($_FILES['campus_logo']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
			}
		}

		// remarks insert
		if($sqllms) {			
			sendRemark("Update Campus: ".cleanvars($_POST['campus_name'])." detail", '2');			
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: campuses.php?id=".$_POST['campus_id']."", true, 301);
			exit();
		}
	}
}

// ADD UTILITIES
if(isset($_POST['add_utilities'])) {
	$doa = date('Y-m-d', strtotime($_POST['principal_doa']));
	$value = array (
			 "status"				=>	'1'
			,"library"				=>	$_POST['library']
			,"science_lab"			=>	$_POST['science_lab']
			,"computer_lab"			=>	$_POST['computer_lab']
			,"security_armaments"	=>	$_POST['security_armaments']
			,"fire_extinguisher"	=>	$_POST['fire_extinguisher']
			,"student_hostel"		=>	$_POST['student_hostel']
			,"power_backup"			=>	$_POST['power_backup']
			,"sound_system"			=>	$_POST['sound_system']
			,"water_filter_cooler"	=>	$_POST['water_filter_cooler']
			,"firstaid_box"			=>	$_POST['firstaid_box']
			,"printer_photocopier"	=>	$_POST['printer_photocopier']
			,"montessori_kit"		=>	$_POST['montessori_kit']
			,"internet_cctv"		=>	$_POST['internet_cctv']
			,"lcd_projector"		=>	$_POST['lcd_projector']
			,"sport_kits"			=>	$_POST['sport_kits']
			,"id_campus"			=>	$_POST['campus_id']
			,"id_added"				=>	$_SESSION['userlogininfo']['LOGINIDA']
			,"date_added"			=>	date('Y-m-d h:i:s')
	);
	$sqllms  = $dblms->insert(CAMPUS_UTILITIES, $value);
	
	$latest_id = $dblms->lastestid();

	if($sqllms) { 
		
	$remarks = 'Add Campus Utility ID: "'.cleanvars($latest_id).'" detail';
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
														, '1' 
														, NOW()
														, '".cleanvars($ip)."'
														, '".cleanvars($remarks)."'
														, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	
														)
								");
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: campuses.php?id=".$_POST['campus_id']."", true, 301);
		exit();
	} // end checker
}

// ADD BIOGRAPHY
if(isset($_POST['submit_bio'])){
	$doa = date('Y-m-d', strtotime($_POST['principal_doa']));
	$values = array (
						 "bio_status"			=> '1'
						,"id_ad"				=>	$_POST['id_ad']
						,"id_de"				=>	$_POST['id_de']
						,"building_type"		=>	$_POST['building_type']
						,"building_area"		=>	$_POST['building_area']
						,"covered_area"			=>	$_POST['covered_area']
						,"total_rooms"			=>	$_POST['total_rooms']
						,"play_grounds"			=>	$_POST['play_grounds']
						,"washrooms"			=>	$_POST['washrooms']
						,"principal_name"		=>	$_POST['principal_name']
						,"principal_doa"		=>	$doa
						,"principal_phone"		=>	$_POST['principal_phone']
						,"second_phone"			=>	$_POST['second_phone']
						,"principal_whastapp"	=>	$_POST['principal_whastapp']
						,"principal_email"		=>	$_POST['principal_email']
						,"principal_edu"		=>	$_POST['principal_edu']
						,"principal_experience"	=>	$_POST['principal_experience']
						,"primary_bank"			=>	$_POST['primary_bank']
						,"primary_account"		=>	$_POST['primary_account']
						,"secondary_bank"		=>	$_POST['secondary_bank']
						,"secondary_account"	=>	$_POST['secondary_account']
						,"mec_president"		=>	$_POST['mec_president']
						,"mec_president_no"		=>	$_POST['mec_president_no']
						,"id_campus"			=>	$_POST['campus_id']
					);

	$sqlBioCheck = $dblms->querylms("SELECT bio_id
										FROM ".CAMPUS_BIOGRAPHY."
										WHERE id_campus = '".cleanvars($_POST['campus_id'])."'
										AND is_deleted	= '0'
										ORDER BY bio_id DESC LIMIT 1 ");
	if(mysqli_num_rows($sqlBioCheck)>0){
		$valBio = mysqli_fetch_array($sqlBioCheck);

		$values['id_modify']	=	$_SESSION['userlogininfo']['LOGINIDA'];
		$values['date_modify']	=	date('Y-m-d h:i:s');

		$sqllms = $dblms->Update(CAMPUS_BIOGRAPHY , $values , "WHERE bio_id = '".cleanvars($valBio['bio_id'])."'");
		$latest_id = $valBio['bio_id'];

		$action = '2';
		$comment = 'Updated';
	}else{
		$values['id_added']		=	$_SESSION['userlogininfo']['LOGINIDA'];
		$values['date_added']	=	date('Y-m-d h:i:s');

		$sqllms  = $dblms->insert(CAMPUS_BIOGRAPHY, $values);
		$latest_id = $dblms->lastestid();

		$action = '1';
		$comment = 'Added';
	}


	if($sqllms){
		$values = array (
						 "id_ad"	=>	$_POST['id_ad']
						,"id_de"	=>	$_POST['id_de']
					);
		$sqlCampusUpdate = $dblms->Update(CAMPUS , $values , "WHERE campus_id = '".$_POST['campus_id']."'");

		$remarks = $comment.' Campus Biograpgy: '.cleanvars($latest_id).' detail';
		$values = array (
							 "id_user"		=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"filename"		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
							,"action"		=>	cleanvars($action)
							,"dated"		=>	date('Y-m-d h:i:s')
							,"ip"			=>	cleanvars($ip)
							,"remarks"		=>	cleanvars($remarks)
							,"id_campus"	=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
						);
		$sqllms  = $dblms->insert(LOGS, $values);
		
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully '.$comment.'.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: campuses.php?id=".$_POST['campus_id']."", true, 301);
		exit();
	}
}

// ADD CAMPUS ROYALTY SETTING
if(isset($_POST['submit_royalty'])){
	$id_campus = cleanvars($_POST['campus_id']);

	// CHECK IF ROYALTY ALREADY EXIST
	$sqllmsRoyalty = $dblms->querylms("SELECT id
										FROM ".ROYALTY_SETTING."
										WHERE id_campus = '".$id_campus."'
										AND is_deleted	= '0' ");
	// UPDATE IF RECORD FOUND
	if(mysqli_num_rows($sqllmsRoyalty) > 0){
		$valRoyalty = mysqli_fetch_array($sqllmsRoyalty);

		// UPDATE ROYALTY SETTING TABLE
		$sqllmsRoyalty  = $dblms->querylms("UPDATE ".ROYALTY_SETTING." SET  
																	  grand_total	= '".cleanvars($_POST['grandTotal'])."'
																	, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																	, date_modify	= Now()
																	  WHERE id		= '".$valRoyalty['id']."'");
		// LATEST ID
		$latest_id = cleanvars($valRoyalty['id']);

		// UPDATE DETAIL AND REMARKS
		if($sqllmsRoyalty){
			// DELETE OLD DATA
			$sqlDel = $dblms->querylms("DELETE FROM ".ROYALTY_SETTING_DET."  WHERE id_setup = '".cleanvars($latest_id)."' ");

			// INSERT NEW DETAILS
			for($i=1; $i<=COUNT($_POST['id_particular']); $i++){

				// TYPE == IRREGULAR || FOR LUMP SUM AMOUNT
				if($_POST['part_type'][$i] == '2' || $_POST['part_for'][$i] == '3'){
					if($_POST['totalAmount'][$i] > 0){
						$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING_DET."(
																									  id_setup
																									, id_particular
																									, id_class
																									, no_of_std
																									, amount_for_cat
																									, amount_per_std
																									, tuitionfee_percentage	
																									, total_amount								
																								)VALUES(
																									  '".$latest_id."'
																									, '".cleanvars($_POST['id_particular'][$i])."'
																									, '0'
																									, '0'
																									, '0'
																									, '0'
																									, '0'
																									, '".cleanvars($_POST['totalAmount'][$i])."'				
																								)
															");
					}
				}
				// (TYPE == REGULAR && FOR STUDENT) || (TYPE == REGULAR && FOR CLASS) 
				elseif(($_POST['part_type'][$i] == '1' && $_POST['part_for'][$i] == '1') || ($_POST['part_type'][$i] == '1' && $_POST['part_for'][$i] == '2')){
					for($cls=1; $cls<=COUNT($_POST['id_class'][$i]); $cls++){
						// POST Vars
						$id_class = 0;
						$no_of_std = 0;
						$amount_for_cat = 0;
						$amount_per_std = 0;
						$tuitionfee_percentage = 0;
						$totalClassAmount = 0;

						if(!empty($_POST['id_class'][$i][$cls])){
							$id_class = cleanvars($_POST['id_class'][$i][$cls]);
						}

						if(!empty($_POST['stds'][$i][$cls])){ 
							$no_of_std = cleanvars($_POST['stds'][$i][$cls]);
						}

						if(!empty($_POST['id_cat'][$i][$cls])){
							$amount_for_cat = cleanvars($_POST['id_cat'][$i][$cls]);
						}

						if(!empty($_POST['amount'][$i][$cls])){
							$amount_per_std = cleanvars($_POST['amount'][$i][$cls]);
						}

						if(!empty($_POST['tuitionfee_percentage'][$i][$cls])){
							$tuitionfee_percentage = cleanvars($_POST['tuitionfee_percentage'][$i][$cls]);
						}

						if(!empty($_POST['totalClassAmount'][$i][$cls])){
							$totalClassAmount = cleanvars($_POST['totalClassAmount'][$i][$cls]);
						}

						if($totalClassAmount > 0){
							$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING_DET."(
																										  id_setup
																										, id_particular
																										, id_class
																										, no_of_std
																										, amount_for_cat
																										, amount_per_std
																										, tuitionfee_percentage	
																										, total_amount								
																									)VALUES(
																										  '".$latest_id."'
																										, '".cleanvars($_POST['id_particular'][$i])."'
																										, '".cleanvars($id_class)."'
																										, '".cleanvars($no_of_std)."'
																										, '".cleanvars($amount_for_cat)."'
																										, '".cleanvars($amount_per_std)."'
																										, '".cleanvars($tuitionfee_percentage)."'
																										, '".cleanvars($totalClassAmount)."'				
																									)
																");
						}
					}
				}
				// ELSE CONTINEW
				else{
					continue;
				}				
			}

			// for($i=1; $i<=COUNT($_POST['id_particular']); $i++){

			// 	// echo  '<br>'.$_POST['part_for'][$i].'----'.$_POST['totalAmount'][$i] .'<br>';
			// 	if($_POST['part_for'][$i] == 1){

			// 		// Check Record Exist
			// 		$sqllmsRoyDetCheck = $dblms->querylms("SELECT detail_id 
			// 												FROM ".ROYALTY_SETTING_DET." 
			// 												WHERE id_setup  = '".$valRoyalty['id']."'
			// 												AND   id_particular = '".cleanvars($_POST['id_particular'][$i])."' LIMIT 1");

			// 		if(mysqli_num_rows($sqllmsRoyDetCheck) > 0){

			// 			//If Exist Then Update 
			// 			$sqllmsRoyDetUpdate  = $dblms->querylms("UPDATE ".ROYALTY_SETTING_DET." SET  
			// 												  id_class				=	'0'
			// 												, no_of_std				=	'0'
			// 												, amount_per_std		=	'0'
			// 												, tuitionfee_percentage	=	'0'
			// 												, total_amount			=	'".cleanvars($_POST['totalAmount'][$i])."'
			// 												WHERE id_particular		=	'".cleanvars($_POST['id_particular'][$i])."'
			// 												AND id_setup			=	'".$valRoyalty['id']."'");

			// 		} else {

			// 			// If Not Exist Then Add
			// 			if($_POST['totalAmount'][$i] > 0) {
			// 				$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING_DET."(
			// 														  id_setup
			// 														, id_particular
			// 														, id_class
			// 														, no_of_std
			// 														, amount_per_std
			// 														, tuitionfee_percentage	
			// 														, total_amount								
			// 													) VALUES (
			// 														  '".$valRoyalty['id']."'
			// 														, '".cleanvars($_POST['id_particular'][$i])."'
			// 														, '0'
			// 														, '0'
			// 														, '0'
			// 														, '0'
			// 														, '".cleanvars($_POST['totalClassAmount'][$i])."'				
			// 														)");
			// 			}
			// 		}
			// 	}elseif($_POST['part_for'][$i] == 2) {

					
			// 		// echo '<br> cls'.json_encode($_POST['id_class']);
			// 		// echo '<br> stds: '.json_encode($_POST['stds']);
			// 		// echo '<br> amt: '.json_encode($_POST['amount']);
			// 		// echo '<br> per std: '.json_encode($_POST['tuitionfee_percentage']);
			// 		// echo '<br> Total: '.json_encode($_POST['totalClassAmount']);
					
			// 		for($cls=1; $cls<=COUNT($_POST['id_class'][$i]); $cls++){
						
			// 			// POST Vars
			// 			$id_class = 0;
			// 			$no_of_std = 0;
			// 			$amount_per_std = 0;
			// 			$tuitionfee_percentage = 0;
			// 			$totalClassAmount = 0;

			// 			if(!empty($_POST['id_class'][$i][$cls])){
			// 				$id_class = cleanvars($_POST['id_class'][$i][$cls]);
			// 			}  
			// 			if(!empty($_POST['stds'][$i][$cls])) { 
			// 				$no_of_std = cleanvars($_POST['stds'][$i][$cls]);
			// 			} 
			// 			if(!empty($_POST['amount'][$i][$cls])) {

			// 				$amount_per_std = cleanvars($_POST['amount'][$i][$cls]);
			// 			}
			// 			if(!empty($_POST['tuitionfee_percentage'][$i][$cls])) {

			// 				$tuitionfee_percentage = cleanvars($_POST['tuitionfee_percentage'][$i][$cls]);
			// 			}
			// 			if(!empty($_POST['totalClassAmount'][$i][$cls])) {

			// 				$totalClassAmount = cleanvars($_POST['totalClassAmount'][$i][$cls]);
			// 			}
							
			// 			// Check Record Exist
			// 			$sqllmsRoyDetCheck	= $dblms->querylms("SELECT detail_id 
			// 													FROM ".ROYALTY_SETTING_DET." 
			// 													WHERE id_setup  = '".$valRoyalty['id']."'
			// 													AND   id_particular = '".cleanvars($_POST['id_particular'][$i])."' 
			// 													AND   id_class = '".cleanvars($id_class)."'");

			// 			if(mysqli_num_rows($sqllmsRoyDetCheck) > 0) {

			// 				$sqllmsRoyDetUpdate  = $dblms->querylms("UPDATE ".ROYALTY_SETTING_DET." SET  
			// 														no_of_std				= '".cleanvars($no_of_std)."'
			// 													, amount_per_std		= '".cleanvars($amount_per_std)."'
			// 													, tuitionfee_percentage	= '".cleanvars($tuitionfee_percentage)."'
			// 													, total_amount			= '".cleanvars($totalClassAmount)."'
			// 												WHERE id_particular			= '".cleanvars($_POST['id_particular'][$i])."'
			// 												AND id_class 				= '".cleanvars($id_class)."'
			// 												AND id_setup 				= '".cleanvars($valRoyalty['id'])."'");

			// 			} else {
			// 				if($totalClassAmount > 0) {
			// 					$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING_DET."(
			// 																id_setup				,
			// 																id_particular			,
			// 																id_class				,
			// 																no_of_std				,
			// 																amount_per_std			,
			// 																tuitionfee_percentage	,	
			// 																total_amount								
			// 															)
			// 														VALUES(
			// 																'".$valRoyalty['id']."'										,
			// 																'".cleanvars($_POST['id_particular'][$i])."'			,
			// 																'".cleanvars($id_class)."'								,
			// 																'".cleanvars($no_of_std)."'								,
			// 																'".cleanvars($amount_per_std)."'						,
			// 																'".cleanvars($tuitionfee_percentage)."'					,
			// 																'".cleanvars($totalClassAmount)."'				
			// 															)");
			// 				}

			// 			}
			// 		}

			// 	}
				
			// }

			// REMARKS
			$remarks = 'Royalty Updated, CampusID# "'.cleanvars($id_campus).'" details';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																  id_user 
																, filename 
																, action
																, dated
																, ip
																, remarks 
																, id_campus				
															)VALUES(
																  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'
																, '2'
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: campuses.php?id=$id_campus", true, 301);
			exit();
		}

	}
	// INSERT RECORD IF NOT FOUND
	else{
		// Insert Into Parent Royalty Table
		$sqllmsRoyality  = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING."(
																		  grand_total
																		, id_campus
																		, id_added
																		, date_added								
																	)VALUES(
																		  '".cleanvars($_POST['grandTotal'])."'
																		, '".cleanvars($id_campus)."'
																		, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																		, Now()	
																	)
											");
		// LATEST ID
		$latest_id = $dblms->lastestid();

		// INSERT DETAIL AND REMARKS
		if($sqllmsRoyality){
			for($i=1; $i<=COUNT($_POST['id_particular']); $i++){

				// TYPE == IRREGULAR || FOR LUMP SUM AMOUNT
				if($_POST['part_type'][$i] == '2' || $_POST['part_for'][$i] == '3'){
					if($_POST['totalAmount'][$i] > 0){
						$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING_DET."(
																									  id_setup
																									, id_particular
																									, id_class
																									, no_of_std
																									, amount_for_cat
																									, amount_per_std
																									, tuitionfee_percentage	
																									, total_amount								
																								) VALUES (
																									  '".$latest_id."'
																									, '".cleanvars($_POST['id_particular'][$i])."'
																									, '0'
																									, '0'
																									, '0'
																									, '0'
																									, '0'
																									, '".cleanvars($_POST['totalAmount'][$i])."'				
																								)
															");
					}
				}
				// (TYPE == REGULAR && FOR STUDENT) || (TYPE == REGULAR && FOR CLASS) 
				elseif(($_POST['part_type'][$i] == '1' && $_POST['part_for'][$i] == '1') || ($_POST['part_type'][$i] == '1' && $_POST['part_for'][$i] == '2')){
					for($cls=1; $cls<=COUNT($_POST['id_class'][$i]); $cls++){
						// POST Vars
						$id_class = 0;
						$no_of_std = 0;
						$amount_for_cat = 0;
						$amount_per_std = 0;
						$tuitionfee_percentage = 0;
						$totalClassAmount = 0;

						if(!empty($_POST['id_class'][$i][$cls])){
							$id_class = cleanvars($_POST['id_class'][$i][$cls]);
						}

						if(!empty($_POST['stds'][$i][$cls])){ 
							$no_of_std = cleanvars($_POST['stds'][$i][$cls]);
						}

						if(!empty($_POST['id_cat'][$i][$cls])){
							$amount_for_cat = cleanvars($_POST['id_cat'][$i][$cls]);
						}

						if(!empty($_POST['amount'][$i][$cls])){
							$amount_per_std = cleanvars($_POST['amount'][$i][$cls]);
						}

						if(!empty($_POST['tuitionfee_percentage'][$i][$cls])){
							$tuitionfee_percentage = cleanvars($_POST['tuitionfee_percentage'][$i][$cls]);
						}

						if(!empty($_POST['totalClassAmount'][$i][$cls])){
							$totalClassAmount = cleanvars($_POST['totalClassAmount'][$i][$cls]);
						}

						if($totalClassAmount > 0){
							$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING_DET."(
																										  id_setup
																										, id_particular
																										, id_class
																										, no_of_std
																										, amount_for_cat
																										, amount_per_std
																										, tuitionfee_percentage	
																										, total_amount								
																									)VALUES(
																										  '".$latest_id."'
																										, '".cleanvars($_POST['id_particular'][$i])."'
																										, '".cleanvars($id_class)."'
																										, '".cleanvars($no_of_std)."'
																										, '".cleanvars($amount_for_cat)."'
																										, '".cleanvars($amount_per_std)."'
																										, '".cleanvars($tuitionfee_percentage)."'
																										, '".cleanvars($totalClassAmount)."'				
																									)
																");
						}
					}
				}
				// ELSE CONTINEW
				else{
					continue;
				}				
			}

			// REMARKS
			$remarks = 'Royalty Add, CampusID# "'.cleanvars($id_campus).'" details';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".LOGS." (
																  id_user 
																, filename 
																, action
																, dated
																, ip
																, remarks 
																, id_campus				
															)VALUES(
																  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."'
																, '2'
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
															)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: campuses.php?id=$id_campus", true, 301);
			exit();
		}		
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$sqllms  = $dblms->querylms("UPDATE ".CAMPUS." SET  
												  is_deleted		=	'1'
												, id_deleted		=	'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted		=	'".$ip."'
												, date_deleted		=	NOW()
												  WHERE campus_id	=	'".cleanvars($_GET['deleteid'])."'");
	if($sqllms) {
		$remarks = 'Student Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
															, '3'
															, NOW()
															, '".cleanvars($ip)."'
															, '".cleanvars($remarks)."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
														)
									");
		$_SESSION['msg']['title'] 	= 'Warning';
		$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
		$_SESSION['msg']['type'] 	= 'warning';
		header("Location: campuses.php", true, 301);
		exit();
	}
}
?>