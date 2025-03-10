<?php
// INSERT RECORD
if(isset($_POST['genrate_challan'])){
	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".FEES." 
										WHERE id_type	= '3'
										AND id_month	= '".cleanvars($_POST['id_month'])."'
										AND id_campus	= '".cleanvars($_POST['id_campus'])."'
										AND id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck) > 0) {
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: royaltyChallans.php", true, 301);
		exit();
	}else{
		// REFORMAT DATE
		$challandate	= date('Ym');
		$issue_date		= date('Y-m-d');
		$due_date		= date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
		
		// GENERATE CHALLAN NUMBER
		$sqllmschallan 	= $dblms->querylms("SELECT challan_no FROM ".FEES." 
											WHERE challan_no LIKE '".$challandate."%'  
											ORDER by challan_no DESC LIMIT 1 ");
		$rowchallan 	= mysqli_fetch_array($sqllmschallan);
		if(mysqli_num_rows($sqllmschallan) < 1) {
			$challano	= $challandate.'00001';
		}else{
			$challano = ($rowchallan['challan_no'] +1);
		}

		// MAKE CHALLAN
		$sqllms  = $dblms->querylms("INSERT INTO ".FEES."(
															  status 
															, id_type
															, challan_no 
															, id_session 
															, id_month
															, issue_date
															, due_date
															, total_amount
															, id_campus
															, id_added
															, date_added
														)
													VALUES(
															  '2'
															, '3'
															, '".cleanvars($challano)."'
															, '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' 
															, '".cleanvars($_POST['id_month'])."'
															, '".cleanvars($issue_date)."' 
															, '".cleanvars($due_date)."'
															, '".cleanvars($_POST['grandTotal'])."'
															, '".cleanvars($_POST['id_campus'])."'
															, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
															, Now()	
														)
									");
		// LATEST ID
		$latest_id = $dblms->lastestid();	

		// CHALLAN DETAIL AND REMARKS
		if($sqllms){

			// CHALLAN DETAILS
			for($i=1; $i<=COUNT($_POST['id_particular']); $i++){
				// TYPE == IRREGULAR || FOR LUMP SUM AMOUNT
				if($_POST['part_type'][$i] == '2' || $_POST['part_for'][$i] == '3'){
					if($_POST['totalAmount'][$i] > 0){
						$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_CHALLAN_DET."(
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
							$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_CHALLAN_DET."(
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
			$remarks = "Single Royalty Challan Genrted";
			$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
																			  id_user 
																			, filename 
																			, action
																			, challan_no
																			, dated
																			, ip
																			, remarks 
																			, id_campus				
																		)
																	VALUES(
																			  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																			, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 
																			, '1'
																			, '".cleanvars($challano)."'
																			, NOW()
																			, '".cleanvars($ip)."'
																			, '".cleanvars($remarks)."'
																			, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
																		)
										");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: royaltyChallans.php", true, 301);
			exit();
		}
	}
}

// Update Challan
if(isset($_POST['update_challan'])) {
	
	// Update Parent Royalty Table
	$sqllmsRoyalty  = $dblms->querylms("UPDATE ".FEES." SET  
											  total_amount	= '".cleanvars($_POST['grandTotal'])."'
											, id_modify		= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
											, date_modify	= Now()
										WHERE id			= '".$_POST['id_challan']."'");

	if($sqllmsRoyalty) {

		for($i=1; $i<=COUNT($_POST['id_particular']); $i++){

			// echo  '<br>'.$_POST['part_for'][$i].'----'.$_POST['totalAmount'][$i] .'<br>';
			if($_POST['part_for'][$i] == 1) {

				// Check Record Exist
				$sqllmsRoyDetCheck = $dblms->querylms("SELECT detail_id 
														FROM ".ROYALTY_SETTING_DET." 
														WHERE id_setup  = '".cleanvars($_POST['id_challan'])."'
														AND   id_particular = '".cleanvars($_POST['id_particular'][$i])."' LIMIT 1");

				if(mysqli_num_rows($sqllmsRoyDetCheck) > 0){

					//If Exist Then Update 
					$sqllmsRoyDetUpdate  = $dblms->querylms("UPDATE ".ROYALTY_SETTING_DET." SET  
															id_class				= '0'
														,	no_of_std				= '0'
														,	amount_per_std			= '0'
														,	tuitionfee_percentage	= '0'
														,	total_amount			= '".cleanvars($_POST['totalAmount'][$i])."'
														WHERE id_particular			= '".cleanvars($_POST['id_particular'][$i])."'
														AND id_setup 				= '".cleanvars($_POST['id_challan'])."'");

				} else {

					// If Not Exist Then Add
					if($_POST['totalAmount'][$i] > 0) {
						$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING_DET."(
																id_setup				,
																id_particular			,
																id_class				,
																no_of_std				,
																amount_per_std			,
																tuitionfee_percentage	,	
																total_amount								
															) VALUES (
																'".cleanvars($_POST['id_challan'])."'					,
																'".cleanvars($_POST['id_particular'][$i])."'			,
																'0'														,
																'0'														,
																'0'														,
																'0'														,
																'".cleanvars($_POST['totalClassAmount'][$i])."'				
																)");
					}

				}

			} else if ($_POST['part_for'][$i] == 2) {

				
				// echo '<br> cls'.json_encode($_POST['id_class']);
				// echo '<br> stds: '.json_encode($_POST['stds']);
				// echo '<br> amt: '.json_encode($_POST['amount']);
				// echo '<br> per std: '.json_encode($_POST['tuitionfee_percentage']);
				// echo '<br> Total: '.json_encode($_POST['totalClassAmount']);
				
				for($cls=1; $cls<=COUNT($_POST['id_class'][$i]); $cls++){
					
					// POST Vars
					$id_class = 0;
					$no_of_std = 0;
					$amount_per_std = 0;
					$tuitionfee_percentage = 0;
					$totalClassAmount = 0;

					if(!empty($_POST['id_class'][$i][$cls])){
						$id_class = cleanvars($_POST['id_class'][$i][$cls]);
					}  
					if(!empty($_POST['stds'][$i][$cls])) { 
						$no_of_std = cleanvars($_POST['stds'][$i][$cls]);
					} 
					if(!empty($_POST['amount'][$i][$cls])) {

						$amount_per_std = cleanvars($_POST['amount'][$i][$cls]);
					}
					if(!empty($_POST['tuitionfee_percentage'][$i][$cls])) {

						$tuitionfee_percentage = cleanvars($_POST['tuitionfee_percentage'][$i][$cls]);
					}
					if(!empty($_POST['totalClassAmount'][$i][$cls])) {

						$totalClassAmount = cleanvars($_POST['totalClassAmount'][$i][$cls]);
					}
						
					// Check Record Exist
					$sqllmsRoyDetCheck	= $dblms->querylms("SELECT detail_id 
															FROM ".ROYALTY_SETTING_DET." 
															WHERE id_setup  = '".cleanvars($_POST['id_challan'])."'
															AND   id_particular = '".cleanvars($_POST['id_particular'][$i])."' 
															AND   id_class = '".cleanvars($id_class)."'");

					if(mysqli_num_rows($sqllmsRoyDetCheck) > 0) {

						$sqllmsRoyDetUpdate  = $dblms->querylms("UPDATE ".ROYALTY_SETTING_DET." SET  
																no_of_std				= '".cleanvars($no_of_std)."'
															, amount_per_std		= '".cleanvars($amount_per_std)."'
															, tuitionfee_percentage	= '".cleanvars($tuitionfee_percentage)."'
															, total_amount			= '".cleanvars($totalClassAmount)."'
														WHERE id_particular			= '".cleanvars($_POST['id_particular'][$i])."'
														AND id_class 				= '".cleanvars($id_class)."'
														AND id_setup 				= '".cleanvars($_POST['id_challan'])."'");

					} else {

						if($totalClassAmount > 0) {

							$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_SETTING_DET."(
																		id_setup				,
																		id_particular			,
																		id_class				,
																		no_of_std				,
																		amount_per_std			,
																		tuitionfee_percentage	,	
																		total_amount								
																	)
																VALUES(
																		'".cleanvars($_POST['id_challan'])."'					,
																		'".cleanvars($_POST['id_particular'][$i])."'			,
																		'".cleanvars($id_class)."'								,
																		'".cleanvars($no_of_std)."'								,
																		'".cleanvars($amount_per_std)."'						,
																		'".cleanvars($tuitionfee_percentage)."'					,
																		'".cleanvars($totalClassAmount)."'				
																	)");
						}

					}
				}

			}
			
		}

		// for($i=0; $i<COUNT($_POST['id_particular']); $i++){

		// 	// POST Vars
		// 	$id_class = 0;
		// 	$no_of_std = 0;
		// 	$amount_per_std = 0;
		// 	$tuitionfee_percentage = 0;

		// 	if(!empty($_POST['id_class'][$i])){
		// 		$id_class = cleanvars($_POST['id_class'][$i]);
		// 	}  
		// 	if(!empty($_POST['stds'][$i])) { 
		// 		$no_of_std = cleanvars($_POST['stds'][$i]);
		// 	} 
		// 	if(!empty($_POST['amount'][$i])) {

		// 		$amount_per_std = cleanvars($_POST['amount'][$i]);
		// 	}
		// 	if(!empty($_POST['tuitionfee_percentage'][$i])) {

		// 		$tuitionfee_percentage = cleanvars($_POST['tuitionfee_percentage'][$i]);
		// 	}

		// 	//Check If Record Exist
		// 	$sqllmsRoyDetCheck	= $dblms->querylms("SELECT detail_id 
		// 												FROM ".ROYALTY_CHALLAN_DET." 
		// 												WHERE id_setup  = '".$_POST['id_challan']."'
		// 												AND   id_particular = '".cleanvars($_POST['id_particular'][$i])."'
		// 												AND   id_class = '".cleanvars($id_class)."'");
			
		// 	if((mysqli_num_rows($sqllmsRoyDetCheck) == 1) && ($_POST['totalAmount'][$i] > 0)){

		// 		//If Exist Then Update 
		// 		$sqllmsRoyDetUpdate  = $dblms->querylms("UPDATE ".ROYALTY_CHALLAN_DET." SET  
		// 													no_of_std				= '".cleanvars($no_of_std)."'
		// 												, amount_per_std		= '".cleanvars($amount_per_std)."'
		// 												, tuitionfee_percentage	= '".cleanvars($tuitionfee_percentage)."'
		// 												, total_amount			= '".cleanvars($_POST['totalAmount'][$i])."'
		// 											WHERE id_setup				= '".$_POST['id_challan']."'
		// 											AND id_particular			= '".cleanvars($_POST['id_particular'][$i])."'
		// 											AND id_class 				= '".cleanvars($id_class)."'
		// 											");

		// 	} else if((mysqli_num_rows($sqllmsRoyDetCheck) == 0) && ($_POST['totalAmount'][$i] > 0)){

		// 		//If Not Exist & Amount Then Add
		// 		if($_POST['totalAmount'][$i] > 0) {

		// 			$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_CHALLAN_DET."(
		// 													id_setup				,
		// 													id_particular			,
		// 													id_class				,
		// 													no_of_std				,
		// 													amount_per_std			,
		// 													tuitionfee_percentage	,	
		// 													total_amount								
		// 												)
		// 											VALUES(
		// 													'".$_POST['id_challan']."'									,
		// 													'".cleanvars($_POST['id_particular'][$i])."'			,
		// 													'".cleanvars($id_class)."'								,
		// 													'".cleanvars($no_of_std)."'								,
		// 													'".cleanvars($amount_per_std)."'						,
		// 													'".cleanvars($tuitionfee_percentage)."'					,
		// 													'".cleanvars($_POST['totalAmount'][$i])."'				
		// 												)");
		// 		}
		// 	} else {
		// 		//If Amount !> 0 Then Delete
		// 		$sqllmsRoyDel  = $dblms->querylms("DELETE FROM ".ROYALTY_CHALLAN_DET."
		// 											WHERE id_setup				= '".$_POST['id_challan']."'
		// 											AND id_particular			= '".cleanvars($_POST['id_particular'][$i])."'
		// 											AND id_class 				= '".cleanvars($id_class)."'
		// 											");
		// 	}
		// }

		// Make Log
		$remarks = "Royalty Challan Heads Update";
		$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
													id_user 				, 
													filename				, 
													action					,
													challan_no 				,
													dated					,
													ip						,
													remarks					, 
													id_campus				
												)

											VALUES(
													'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'				,
													'".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 		, 
													'1'																	, 
													'".cleanvars($_POST['challan_no'])."'								,
													NOW()																,
													'".cleanvars($ip)."'												,
													'".cleanvars($remarks)."'											,
													'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
												)");
																			
		//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
		$_SESSION['msg']['type'] 	= 'info';
		header("Location: royaltyChallans.php", true, 301);
		exit();
		//--------------------------------------
	}
}

// Update Single Royalty Challan
if(isset($_POST['changes_royalty'])){
	// Vars
	if($_POST['paid_date']){
		$paid_date = date('Y-m-d' , strtotime(cleanvars($_POST['paid_date'])));
	}else{
		$paid_date = date('Y-m-d');
	}
	$totalPaid = cleanvars($_POST['paid_amount']) + cleanvars($_POST['pay_now']);

	// TOTAL PAYABLE <= CURRENT AMOUNT 
	if(($_POST['payable'] <= $totalPaid) && ($_POST['pay_now'] > 0)){
		// Update Chllan as Paid
		$sqllms  = $dblms->querylms("UPDATE ".FEES." SET 
													  status			= '1'
													, paid_date			= '".cleanvars($paid_date)."'
													, paid_amount		= '".cleanvars($totalPaid)."'
													, remaining_amount	= '".cleanvars($_POST['remaining_amount'])."'
													, note				= '".cleanvars($_POST['note'])."'
													, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
													, date_modify		= NOW()
													  WHERE id			= '".cleanvars($_POST['id'])."' ");
		if($sqllms){
			// IF PAID THEN ADD IN EARNING
		
			// GET HEAD FROM ACCOUNT HEADS 
			$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' AND head_name LIKE '%royalty%'");
			$values_trans_head = mysqli_fetch_array($sqllms_head);

			// Add INCOME
			$sqllmsIncome  = $dblms->querylms("INSERT INTO ".ACCOUNT_TRANS."(
																			  trans_status 
																			, trans_title
																			, trans_type
																			, trans_amount
																			, voucher_no
																			, trans_method
																			, receipt_no
																			, trans_note
																			, dated
																			, id_head
																			, id_campus  
																			, id_added  
																			, date_added 	
																		)VALUES(
																			  '1'	 
																			, '".cleanvars($_POST['challan_no'])."'
																			, '1'
																			, '".cleanvars($_POST['pay_now'])."'
																			, '".cleanvars($_POST['challan_no'])."'
																			, '1'
																			, '".cleanvars($_POST['receipt_no'])."'	
																			, '".cleanvars($_POST['note'])."'				
																			, '".cleanvars($paid_date)."'
																			, '".cleanvars($values_trans_head['head_id'])."'
																			, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
																			, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																			, NOW()	
																		)
											");
			// LATEST ACC ID
			$latest_acc_id = $dblms->lastestid();
			// IMAGE UPLOAD
			if(!empty($_FILES['receipt_image']['name'])) { 
				$path_parts 	= pathinfo($_FILES["receipt_image"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir		= 'uploads/receipt_images/';
				$originalImage	= $img_dir.to_seo_url($_POST['challan_no'].'-'.$_POST['receipt_no'].'-'.$latest_acc_id).".".($extension);
				$img_fileName	= to_seo_url($_POST['challan_no'].'-'.$_POST['receipt_no'].'-'.$latest_acc_id).".".($extension);
				
				if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))){
					$sqllmsupload  = $dblms->querylms("UPDATE ".ACCOUNT_TRANS."
																SET receipt_image	= '".$img_fileName."'
																WHERE trans_id		= '".cleanvars($latest_acc_id)."'
														");
					unset($sqllmsupload);
					$mode = '0644'; 	
					move_uploaded_file($_FILES['receipt_image']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// Make Log
			$remarks = 'Royalty Challan Paid';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
																		  id_user 
																		, filename 
																		, action
																		, challan_no
																		, dated
																		, ip
																		, remarks 
																		, id_campus				
																	)VALUES(
																		  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																		, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 
																		, '3' 
																		, '".cleanvars($_POST['challan_no'])."'
																		, NOW()
																		, '".cleanvars($ip)."'
																		, '".cleanvars($remarks)."'
																		, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
																	)
											");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: royaltyChallans.php", true, 301);
			exit();
		}
	}	
	// TOTAL PAYABLE > CURRENT AMOUNT > 0
	elseif(($_POST['payable'] > $totalPaid) && ($_POST['pay_now'] > 0)){
		// Update Challan As Partial Paid
		$sqllms  = $dblms->querylms("UPDATE ".FEES." SET 
													  status			= '4'
													, paid_date			= '".cleanvars($paid_date)."'
													, paid_amount		= '".cleanvars($totalPaid)."'
													, remaining_amount	= '".cleanvars($_POST['remaining_amount'])."'
													, note				= '".cleanvars($_POST['note'])."'
													, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
													, date_modify		= NOW()
													  WHERE id			= '".cleanvars($_POST['id'])."' ");
		if($sqllms){
			// IF PAID THEN ADD IN EARNING
		
			// GET HEAD FROM ACCOUNT HEADS
			$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' AND head_name LIKE '%royalty%'");
			$values_trans_head = mysqli_fetch_array($sqllms_head);

			// Add INCOME
			$sqllmsIncome  = $dblms->querylms("INSERT INTO ".ACCOUNT_TRANS."(
																  trans_status 
																, trans_title
																, trans_type
																, trans_amount
																, voucher_no
																, trans_method
																, receipt_no
																, trans_note
																, dated
																, id_head
																, id_campus  
																, id_added  
																, date_added 	
															)
														VALUES(
																  '4'	 
																, '".cleanvars($_POST['challan_no'])."'
																, '1'
																, '".cleanvars($_POST['pay_now'])."'
																, '".cleanvars($_POST['challan_no'])."'
																, '1'
																, '".cleanvars($_POST['receipt_no'])."'
																, '".cleanvars($_POST['note'])."'				
																, '".cleanvars($paid_date)."'
																, '".cleanvars($values_trans_head['head_id'])."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, NOW()	
															)
											");
			// LATEST ACC ID
			$latest_acc_id = $dblms->lastestid();
			// IMAGE UPLOAD
			if(!empty($_FILES['receipt_image']['name'])) { 
				$path_parts 	= pathinfo($_FILES["receipt_image"]["name"]);
				$extension 		= strtolower($path_parts['extension']);
				$img_dir		= 'uploads/receipt_images/';
				$originalImage	= $img_dir.to_seo_url($_POST['challan_no'].'-'.$_POST['receipt_no'].'-'.$latest_acc_id).".".($extension);
				$img_fileName	= to_seo_url($_POST['challan_no'].'-'.$_POST['receipt_no'].'-'.$latest_acc_id).".".($extension);
				
				if(in_array($extension , array('jpg','jpeg', 'gif', 'png'))){
					$sqllmsupload  = $dblms->querylms("UPDATE ".ACCOUNT_TRANS."
																SET receipt_image	= '".$img_fileName."'
																WHERE trans_id		= '".cleanvars($latest_acc_id)."'
														");
					unset($sqllmsupload);
					$mode = '0644'; 	
					move_uploaded_file($_FILES['receipt_image']['tmp_name'],$originalImage);
					chmod ($originalImage, octdec($mode));
				}
			}

			// Make Log
			$remarks = 'Royalty Challan Partial Paymnet, pay now:'.cleanvars($_POST['pay_now']).' ';
			$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
																  id_user 
																, filename 
																, action
																, challan_no
																, dated
																, ip
																, remarks 
																, id_campus				
															)
														VALUES(
																  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 
																, '3'
																, '".cleanvars($_POST['challan_no'])."'
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
											");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: royaltyChallans.php", true, 301);
			exit();
		}
	}
	// NO AMOUNT PAID
	else{
		$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
		//----------------- Update Chllan ---------------------
		$sqllms  = $dblms->querylms("UPDATE ".FEES." SET 
													  due_date			= '".cleanvars($due_date)."'
													, remaining_amount	= '".cleanvars($_POST['remaining_amount'])."'
													, total_amount		= '".cleanvars($_POST['payable'])."'
													, note				= '".cleanvars($_POST['note'])."'
													, id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
													, date_modify		= NOW()
													  WHERE id			= '".cleanvars($_POST['id'])."'
											");
		if($sqllms){
			//----------------- Make Log ---------------------
			if($_POST['remaining_amount'] > 0) {
				$remarks = 'Royalty Challan Update, with Remaining Amount: '.cleanvars($_POST['remaining_amount']).'';
			}else{
				$remarks = 'Royalty Challan Update';
			}
			$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
																  id_user 
																, filename 
																, action
																, challan_no
																, dated
																, ip
																, remarks 
																, id_campus				
															)
														VALUES(
																  '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 
																, '3'
																, '".cleanvars($_POST['challan_no'])."'
																, NOW()
																, '".cleanvars($ip)."'
																, '".cleanvars($remarks)."'
																, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															)
											");
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: royaltyChallans.php", true, 301);
			exit();
		}
	}
}

// GENERATE BULK CHALLAN
if(isset($_POST['generate_bulk'])){
	// REFORMAT DATE
	$challandate	= date('Ym');
	$issue_date		= date('Y-m-d');
	$due_date		= date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
	
	if(isset($_POST['id_campus'])){
		// EXPLODE ARRAY
		$aray = explode(',', $_POST['id_campus']);
		
		foreach($aray as $id_campus){
			// CHECK CHALLAN EXIST
			$sqllmscheck  = $dblms->querylms("SELECT id
												FROM ".FEES." 
												WHERE id_type	= '3'
												AND id_month	= '".cleanvars($_POST['id_month'])."'
												AND id_campus 	= '".cleanvars($id_campus)."'
												AND id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
											");
			if(mysqli_num_rows($sqllmscheck)==0){
				$sqlRoyalty	= $dblms->querylms("SELECT id, royalty_type, grand_total
													FROM ".ROYALTY_SETTING." 
													WHERE is_deleted	= '0'
													AND id_campus		= '".$id_campus."'
													ORDER BY id DESC LIMIT 1");
				if(mysqli_num_rows($sqlRoyalty) > 0){	
					$valRoyalty = mysqli_fetch_array($sqlRoyalty);

					// GENERATE CHALLAN NUMBER
					$sqllmschallan = $dblms->querylms("SELECT challan_no 
														FROM ".FEES." 
														WHERE challan_no LIKE '".$challandate."%'  
														ORDER by challan_no DESC LIMIT 1 ");
					$rowchallan = mysqli_fetch_array($sqllmschallan);
					if(mysqli_num_rows($sqllmschallan) < 1) {
						$challano	= $challandate.'00001';
					}else{
						$challano = ($rowchallan['challan_no'] +1);
					}

					// MAKE CHALLAN
					$sqllms  = $dblms->querylms("INSERT INTO ".FEES."(
																			status 
																		, id_type
																		, challan_no 
																		, id_session 
																		, id_month
																		, issue_date
																		, due_date
																		, total_amount
																		, id_campus
																		, id_added
																		, date_added
																	)
																VALUES(
																			'2'
																		, '3'
																		, '".cleanvars($challano)."'
																		, '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' 
																		, '".cleanvars($_POST['id_month'])."'
																		, '".cleanvars($issue_date)."' 
																		, '".cleanvars($due_date)."'
																		, '".cleanvars($valRoyalty['grand_total'])."'
																		, '".cleanvars($id_campus)."'
																		, '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																		, Now()	
																	)
												");													
					// LATEST ID
					$latest_id = $dblms->lastestid();
					// CHALLAN DETAIL AND REMARKS
					if($sqllms){
						// ROYALTY DETAIL
						$sqlRoyaltyDet = $dblms->querylms("SELECT rd.*
															FROM ".ROYALTY_SETTING_DET." rd
															WHERE rd.id_setup = '".$valRoyaltyCheck['id']."'
														");
						while($valRoyaltyDet = mysqli_fetch_array($sqlRoyaltyDet)){
							// CHALLAN DETAILS
							$sqllmsRoyDetAdd  = $dblms->querylms("INSERT INTO ".ROYALTY_CHALLAN_DET."(
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
																										, '".cleanvars($valRoyaltyDet['id_particular'])."'
																										, '".cleanvars($valRoyaltyDet['id_class'])."'
																										, '".cleanvars($valRoyaltyDet['no_of_std'])."'
																										, '".cleanvars($valRoyaltyDet['amount_for_cat'])."'
																										, '".cleanvars($valRoyaltyDet['amount_per_std'])."'
																										, '".cleanvars($valRoyaltyDet['tuitionfee_percentage'])."'
																										, '".cleanvars($valRoyaltyDet['total_amount'])."'
																									)
																");
						}
					}						
					// REMARKS
					$remarks = "Royalty Challan Generated in Bulk Challan";
					$sqllmslog  = $dblms->querylms("INSERT INTO ".ACCOUNTS_LOGS." (
																						id_user 
																					, filename 
																					, action
																					, challan_no
																					, dated
																					, ip
																					, remarks 
																					, id_campus				
																				)
																			VALUES(
																						'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
																					, '".strstr(basename($_SERVER['REQUEST_URI']), '.php', true)."' 
																					, '1'
																					, '".cleanvars($challano)."'
																					, NOW()
																					, '".cleanvars($ip)."'
																					, '".cleanvars($remarks)."'
																					, '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
																				)
													");
				}
			}else{
				continue;
			}
		}
	}
	// REDIRECTION
	$_SESSION['msg']['title'] 	= 'Successfully';
	$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
	$_SESSION['msg']['type'] 	= 'success';
	header("Location: royaltyChallans.php", true, 301);
	exit();
}
?>