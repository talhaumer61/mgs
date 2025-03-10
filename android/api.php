<?php
include("../include/dbsetting/lms_vars_config.php");
include("../include/dbsetting/classdbconection.php");
include("../include/functions/functions.php");
$dblms = new dblms();

date_default_timezone_set("Asia/Karachi");

if(isset($_POST)){ 

	$data_arr = json_decode(file_get_contents('php://input'), true);
	$data_arr = $_POST;

	// USER LOGIN
	if($data_arr['method_name'] == "user_login") {
		$jsonObj	= array();
		
		$username		= cleanvars($data_arr['username']);
		$password		= cleanvars($data_arr['password']);		
		$admin_pass3	= ($password);

		$loginconditions = array ( 
									 'select' 			=> '*'
									,'where' 			=> array( 
																	 'adm_status'		=>	1
																	,'adm_username' 	=>	$username
																	,'is_deleted'		=>	0
																)
									,'return_type'		=> 'single' 
								); 
		$row = $dblms->getRows(ADMINS, $loginconditions);
		if (!empty($row)) {
			// PASSWORD DECRYPTION
			$salt 		= $row['adm_salt'];
			$password 	= hash('sha256', $admin_pass3 . $salt);
			
			for ($round = 0; $round < 65536; $round++) {
				$password = hash('sha256', $password . $salt);
			}

			if($password == $row['adm_userpass']) { 
				$dataLog = array(
									 'login_type'		=> cleanvars($row['adm_logintype'])
									,'id_login_id'		=> cleanvars($row['adm_id'])
									,'user_name'		=> cleanvars($username)
									,'user_pass'		=> cleanvars($password)
									,'email'			=> cleanvars($row['adm_email'])
									,'id_campus'		=> cleanvars($row['id_campus'])
									,'dated'			=> date("Y-m-d G:i:s")
								);
				$sqllmslog  = $dblms->Insert(LOGIN_HISTORY, $dataLog);

				// GET ACTIVE SESSION
				$sqlCamSetting = array ( 
											 'select' 			=>	's.adm_session, s.acd_session, s.exam_session, se.session_name, se.session_startdate'
											,'join'				=>	'INNER JOIN '.SESSIONS.' se ON se.session_id = s.acd_session'
											,'where' 			=>	array( 
																			 's.status'			=>	1
																			,'s.is_deleted' 	=>	0
																			,'s.id_campus'		=>	cleanvars($row['id_campus'])
																		)
											,'return_type'		=> 'count' 
										); 
				$setCount = $dblms->getRows(SETTINGS.' s', $sqlCamSetting);
				if($setCount){
					$sqlCamSetting['return_type'] = 'single';
					$values_setting = $dblms->getRows(SETTINGS.' s', $sqlCamSetting);
				}else{
					$sqlCamSetting['where']['s.id_campus'] = '0';
					$sqlCamSetting['return_type'] = 'single';
					$values_setting = $dblms->getRows(SETTINGS.' s', $sqlCamSetting);
				}

				// REMARKS
				$remarks = 'Login to Software From Mobile App';		
				$dataLogs = array(
									 'id_user'		=> cleanvars($row['adm_id'])
									,'filename'		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
									,'action'		=> '4'
									,'dated'		=> date("Y-m-d G:i:s")
									,'ip'			=> cleanvars($ip)
									,'remarks'		=> cleanvars($remarks)
									,'id_campus'	=> cleanvars($row['id_campus'])
								);
				$sqllmslogs  = $dblms->Insert(LOGS , $dataLogs);

				$data['success']		= true;
				$data['MSG']			= "Login successfully...";
				$data['user_id'] 		= $row['adm_id'];
				$data['user_type'] 		= $row['adm_type'];
				$data['user_name'] 		= $row['adm_username'];
				$data['user_fullname'] 	= $row['adm_fullname'];
				$data['id_campus']		= $row['id_campus'];
				$data['acdemicsess'] 	= $values_setting['acd_session'];
				$data['user_photo'] 	= BASE_URL.'/uploads/images/admins/'.$row['adm_photo'];

				array_push($jsonObj,$data);
				$set['SYSTEM_101'] = $jsonObj;
			}else { 
				$data['success'] = false;	
				$data['MSG'] 	 = "Password is invalid !";
			
				array_push($jsonObj,$data);
				$set['SYSTEM_101'] = $jsonObj;
					
			}
		} else { 
			$data['success'] = false;	
			$data['MSG'] 	 = "Email / Username is not found !";
			
			array_push($jsonObj,$data);
			$set['SYSTEM_101'] = $jsonObj;
					
		}
				
		header('Content-Type: application/json; charset=utf-8');
		echo $val= str_replace('\\/', '/', json_encode($data,JSON_UNESCAPED_UNICODE));
		die();
	}

	// CHALLAN INFO
	else if($data_arr['method_name'] == "get_challan_info") {
		
		$jsonObj = array();		
		
		if(!empty($data_arr['challan_no']) && !empty($data_arr['id_campus'])){
			$challan_no	=	cleanvars($data_arr['challan_no']);
			$id_campus	=	cleanvars($data_arr['id_campus']);

			// LATEFEE
			$sqllmsChallanDes = array ( 
										 'select' 		=>	'late_fee_type'
										,'where' 		=>	array( 
																	 'is_deleted'    		=>	0
																	,'chl_desc_status'    	=>	1
																	,'id_campus'			=>	cleanvars($id_campus)
																)
										,'return_type' 	=> 'single' 
									); 
			$rowsChallanDes  = $dblms->getRows(CHALLAN_DESCRIPTION, $sqllmsChallanDes);
			$late_fee_type_array = explode(',', $rowsChallanDes['late_fee_type']);

			// CHALLAN INFO
			$sqllms  = $dblms->querylms("SELECT f.id, f.status, f.id_type, f.id_month, f.challan_no, f.id_session, f.id_class, f.id_section, f.inquiry_formno, f.id_std, f.issue_date, f.due_date, f.total_amount, f.paid_amount, f.scholarship, f.concession, f.fine, f.remaining_amount, f.note,  c.class_id, c.class_name, f.paid_date, cs.section_id, cs.section_name, st.std_id, st.std_name, st.std_fathername, st.std_regno, st.std_rollno, se.session_id, se.session_name, adm.adm_fullname
											FROM ".FEES_LIVE." f
											INNER JOIN ".SESSIONS." se ON se.session_id = f.id_session
											LEFT  JOIN ".STUDENTS." st ON st.std_id = f.id_std
											INNER JOIN ".CLASSES." c ON c.class_id = f.id_class
											LEFT  JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section	
											LEFT  JOIN ".ADMINS." adm ON adm.adm_id = f.id_collector
											WHERE f.id_campus	= '".cleanvars($id_campus)."' 
											AND f.challan_no = '".cleanvars($challan_no)."'
											AND f.is_deleted	= '0'
										");
			if(mysqli_num_rows($sqllms) > 0){
				$feercord = mysqli_fetch_array($sqllms);
				if($feercord['status'] != '1'){
					// CHECK IF LATEST CHALLAN
					$sqllmscheck = $dblms->querylms("SELECT f.id, f.challan_no
														FROM ".FEES_LIVE." f						 
														INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std
														WHERE f.status		= '2'
														AND f.id_type IN (1,2)
														AND f.is_deleted	= '0'
														AND f.id_std		= '".cleanvars($feercord['id_std'])."'
														AND f.id_campus		= '".$id_campus."'
														ORDER BY f.id DESC LIMIT 1");
					$valuesqllmscheck = mysqli_fetch_array($sqllmscheck);
					if($valuesqllmscheck['challan_no'] == $feercord['challan_no']){

						// scholarship seprate them
						$slrArray 			= explode(',',$feercord['scholarship']);
						$scholarship 		= $slrArray[0];
						$id_scholarship 	= $slrArray[1];
						// concession seprate them
						$conArray 			= explode(',',$feercord['concession']);
						$concession 		= $conArray[0];
						$id_concession 		= $conArray[1];

						if($feercord['id_type']){
							$sqllmscats  = $dblms->querylms("SELECT cat_id, cat_name  
																FROM ".FEE_CATEGORY."
																WHERE cat_status = '1' 
																ORDER BY cat_id ASC");
							$countcats 	= mysqli_num_rows($sqllmscats);
							if($countcats > 0){
								$src = 0;
								$grandTotal = 0;
								$fee_description = [];
								while($rowdoc = mysqli_fetch_array($sqllmscats)) {
									$src++;
									$sqllmsfeeprt  = $dblms->querylms("SELECT id_cat, amount FROM ".FEE_PARTICULARS." 
																		WHERE id_cat = '".$rowdoc['cat_id']."'
																		AND id_fee  = '".$feercord['id']."' 
																		LIMIT 1");
									if(mysqli_num_rows($sqllmsfeeprt)>0){
										$valuefeeprt = mysqli_fetch_array($sqllmsfeeprt);
										$remarks = '';
										if ($valuefeeprt['amount']) {
											if ($id_scholarship == $rowdoc['cat_id'] || $id_scholarship == 0) {
												$valuefeeprt['amount'] -= $scholarship;
											}
											if ($id_concession == $rowdoc['cat_id'] || $id_concession == 0) {
												$valuefeeprt['amount'] -= $concession;
											}

											// Generate some sample data
											$desc_name = $rowdoc['cat_name'];
											$amount = $valuefeeprt['amount'];

											// Create an associative array with the data
											$feeObject = [
												"desc_name"	=> $desc_name,
												"amount"	=> strval($amount) // Convert amount to a string
											];

											// Push the associative array into the fee_description array
											$fee_description[] = $feeObject;
										}
										$grandTotal += $valuefeeprt['amount'];
									}
								}
								if (!empty($feercord['fine']) || $feercord['fine'] != 0 || $feercord['fine']) {
									$grandTotal += $feercord['fine'];
								}
							}
							
							if($feercord['status'] == '2'){
								$sqlnarration  = $dblms->querylms("SELECT f.id, f.id_month, f.challan_no, f.id_std,
																	f.issue_date, f.due_date, f.total_amount, f.paid_amount, f.scholarship, f.concession, f.fine, f.remaining_amount
																	FROM ".FEES_LIVE." f
																	WHERE f.id_campus	= '".cleanvars($id_campus)."'
																	AND f.id_std		= '".cleanvars($feercord['id_std'])."'
																	AND f.status IN (2,4)
																	AND f.id_type IN (1,2)
																	AND f.is_deleted	= '0'
																	AND f.challan_no   != '".cleanvars($feercord['challan_no'])."'
																");
								if(mysqli_num_rows($sqlnarration)>0){
									while ($valnarration = mysqli_fetch_array($sqlnarration)) {

										$year = date('Y' , strtotime(cleanvars($valnarration['due_date'])));
										$desc_name = get_monthtypes($valnarration['id_month']).' '.$year.' ('.$valnarration['challan_no'].')';
										$amount = $valnarration['total_amount'] - $valnarration['paid_amount'];

										if(date('Y-m-d') > $valnarration['due_date']) {
											$due_date_after_five_day = date('Y-m-d', strtotime($valnarration['due_date']. ' + 5 days'));
											if ($late_fee_type_array[0] == 1) {
												$amount += $late_fee_type_array[1];
											} else if ($late_fee_type_array[0] == 2) {
												if ($due_date_after_five_day > date('Y-m-d')) {
													$amount += $late_fee_type_array[1];
												} else if ($due_date_after_five_day < date('Y-m-d')) {
													$amount += $late_fee_type_array[2];
												} else {
													$amount += LATEFEE;	
												}
											} else {
												$amount += LATEFEE;
											} 
										}

										// Create an associative array with the data
										$feeObject = [
											"desc_name"		=> $desc_name,
											"amount"		=> strval($amount) // Convert amount to a string
										];

										// Push the associative array into the fee_description array
										$fee_description[] = $feeObject;
										
										// update grandtotal
										$grandTotal = $grandTotal + $amount;
									}
								}
								if(date('Y-m-d') > $feercord['due_date']) {
									$late_fee = 0;
									$due_date_after_five_day = date('Y-m-d', strtotime($feercord['due_date']. ' + 5 days'));
									if ($late_fee_type_array[0] == 1) {
										$late_fee += $late_fee_type_array[1];
									} else if ($late_fee_type_array[0] == 2) {
										if ($due_date_after_five_day > date('Y-m-d')) {
											$late_fee += $late_fee_type_array[1];
										} else if ($due_date_after_five_day < date('Y-m-d')) {
											$late_fee += $late_fee_type_array[2];
										} else {
											$late_fee += LATEFEE;	
										}
									} else {
										$late_fee += LATEFEE;
									}
									// update grand total
									$grandTotal += $late_fee;

									// Create an associative array with the data
									$feeObject = [
										"desc_name"	=> 'Late Fee',
										"amount"	=> strval($late_fee) // Convert amount to a string
									];

									// Push the associative array into the fee_description array
									$fee_description[] = $feeObject;
								}
							}
						}

						$data['success']					= true;
						$data['MSG']						= "Challan Information";
						$data['id_fee']						= $feercord['id'];
						$data['challan_no']					= $feercord['challan_no'];
						$data['id_std']						= $feercord['id_std'];
						$data['std_name']					= $feercord['std_name'];
						$data['std_fathername']				= $feercord['std_fathername'];
						$data['std_regno'] 	    			= $feercord['std_regno'];
						$data['class_name']					= $feercord['class_name'];
						$data['section_name']				= $feercord['section_name'];
						$data['id_month']					= $feercord['id_month'];
						$data['month']						= "".get_monthtypes($feercord['id_month']).'-'.date('Y' , strtotime(cleanvars($feercord['due_date'])))."";
						$data['session_name']				= $feercord['session_name'];
						$data['issue_date']					= $feercord['issue_date'];
						$data['due_date']					= $feercord['due_date'];
						$data['fee_description']			= $fee_description;
						$data['grand_total']				= "".$grandTotal."";
						$data['paid_amount']				= $feercord['paid_amount'];
						$data['scholarship']				= $feercord['scholarship'];
						$data['concession']					= $feercord['concession'];
						$data['total_amount']				= $feercord['total_amount'];
				
						array_push($jsonObj,$data);
						$set['SYSTEM_101'] = $jsonObj;
					} else {
						$data['success'] = false;	
						$data['MSG'] 	 = "Please Scan Latest Challan.";
						
						array_push($jsonObj,$data);
						$set['SYSTEM_101'] = $jsonObj;
					}
				}else {
					$data['success'] = false;
					$data['MSG'] 	 = "Challan Already Paid.";
					
					array_push($jsonObj,$data);
					$set['SYSTEM_101'] = $jsonObj;
				}
			} else {
				$data['success'] = false;
				$data['MSG'] 	 = "Please Scan valid Challan Code";
				
				array_push($jsonObj,$data);
				$set['SYSTEM_101'] = $jsonObj;
			}
		}else{
			$data['success'] = false;
			$data['MSG'] 	 = "Please Scan valid Challan Code";
			
			array_push($jsonObj,$data);
			$set['SYSTEM_101'] = $jsonObj;
		}

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($data,JSON_UNESCAPED_UNICODE));
		die();
	}

	// PAY CHALLAN
	else if($data_arr['method_name'] == "pay_challan") {
		
		$jsonObj	= array();
		
		if(!empty($data_arr['challan_no']) && !empty($data_arr['totaltransamount']) && !empty($data_arr['grandTotal']) && !empty($data_arr['due_date']) && !empty($data_arr['id_month']) && !empty($data_arr['id_campus']) && !empty($data_arr['id_std']) && !empty($data_arr['id_fee']) && !empty($data_arr['adm_id'])){
			
			$adm_id				= cleanvars($data_arr['adm_id']);
			$challan_no			= cleanvars($data_arr['challan_no']);
			$totaltransamount	= cleanvars($data_arr['totaltransamount']);
			$grandTotal			= cleanvars($data_arr['grandTotal']);
			$id_month			= cleanvars($data_arr['id_month']);
			$id_campus			= cleanvars($data_arr['id_campus']);
			$id_std				= cleanvars($data_arr['id_std']);
			$id_fee				= cleanvars($data_arr['id_fee']);
			$paid_amount		= cleanvars($data_arr['paid_amount']);
			$scholarship		= cleanvars($data_arr['scholarship']);
			$concession			= cleanvars($data_arr['concession']);
			$total_amount		= cleanvars($data_arr['total_amount']);
			$due_date			= date('Y-m-d' , strtotime($data_arr['due_date']));
			$paidDate			= date('Y-m-d');
			$pay_mode			= '3';
			$note				= 'Paid by mobile app.';

			// LATEFEE
			$sqllmsChallanDes = array ( 
										'select' 		=>	'late_fee_type'
										,'where' 		=>	array( 
																	 'is_deleted'    		=>	0
																	,'chl_desc_status'    	=>	1
																	,'id_campus'			=>	cleanvars($id_campus)
																)
										,'return_type' 	=> 'single' 
									); 
			$rowsChallanDes  = $dblms->getRows(CHALLAN_DESCRIPTION, $sqllmsChallanDes);
			$late_fee_type_array = explode(',', $rowsChallanDes['late_fee_type']);
		
			// FULL PAYMENT
			if($totaltransamount >= $grandTotal){
				
				// UPDATE PREVIOUS MONTH CHALLAN
				$sqlnar  = $dblms->querylms("SELECT f.id, f.challan_no, f.total_amount, f.paid_amount, f.due_date, f.scholarship, f.concession
												FROM ".FEES_LIVE." f
												WHERE f.due_date   <= '".cleanvars($due_date)."'
												AND f.id_month	   != '".cleanvars($id_month)."'
												AND f.id_std		= '".cleanvars($id_std)."'
												AND f.is_deleted	= '0'
												AND f.status IN (2,4)
												AND f.id_type IN (1,2)
												ORDER BY f.id ASC
											");
				if(mysqli_num_rows($sqlnar)>0){
					while($rownar = mysqli_fetch_array($sqlnar)){
		
						$payable = $rownar['total_amount'] - $rownar['paid_amount'];
		
						// SCHOLARSHIP SEPERATE ID, AMOUNT
						$slrArray 			= explode(',',$rownar['scholarship']);
						$scholarship 		= $slrArray[0];
						$id_scholarship 	= $slrArray[1];
						// CONCESSION SEPERATE ID, AMOUNT
						$conArray 			= explode(',',$rownar['concession']);
						$concession 		= $conArray[0];
						$id_concession 		= $conArray[1];
		
						if(date('Y-m-d') > $rownar['due_date']) {
							$due_date_after_five_day = date('Y-m-d', strtotime($rownar['due_date']. ' + 5 days'));
							if ($late_fee_type_array[0] == 1) {
								$payable += $late_fee_type_array[1];
							} else if ($late_fee_type_array[0] == 2) {
								if ($due_date_after_five_day > date('Y-m-d')) {
									$payable += $late_fee_type_array[1];
								} else if ($due_date_after_five_day < date('Y-m-d')) {
									$payable += $late_fee_type_array[2];
								} else {
									$payable += LATEFEE;	
								}
							} else {
								$payable += LATEFEE;
							} 
						}
						
						$final_paid = $payable + $rownar['paid_amount'];
		
						// Update Previous pending Challans as Paid
						$values = array (
											 "status"			=> '1'
											,"pay_mode"			=> cleanvars($pay_mode)
											,"paid_date"		=> cleanvars($paidDate)
											,"paid_amount"		=> cleanvars($final_paid)
											,"note"				=> cleanvars($note)
											,"id_collector"		=> cleanvars($adm_id)
											,"id_modify"		=> cleanvars($adm_id)
											,"date_modify"		=> date('Y-m-d G:i:s')
										);	
						$sqllmsUpdatePrev  = $dblms->Update(FEES_LIVE , $values , "WHERE challan_no = '".cleanvars($rownar['challan_no'])."'");
		
						if($sqllmsUpdatePrev){
							// UPDATE REMAINING BALANCE
							$totaltransamount = $totaltransamount - $payable;
		
							// GET FEE HEAD FROM ACCOUNT HEADS
							$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".$id_campus."' AND head_name LIKE '%fee%'");
							$values_trans_head = mysqli_fetch_array($sqllms_head);
		
							$remarks = 'Fee Challan Paid by mobile app';
		
							// Add INCOME
							$values = array (
												 "trans_status"		=> '1'
												,"trans_title"		=> cleanvars($rownar['challan_no'])
												,"trans_type"		=> cleanvars($pay_mode)
												,"trans_amount"		=> cleanvars($payable)
												,"voucher_no"		=> cleanvars($rownar['challan_no'])
												,"trans_method"		=> cleanvars($pay_mode)
												,"trans_note"		=> cleanvars($note)
												,"dated"			=> cleanvars($paidDate)
												,"id_head"			=> cleanvars($values_trans_head['head_id'])
												,"id_campus"		=> cleanvars($id_campus)
												,"id_added"			=> cleanvars($adm_id)
												,"date_added"		=> date('Y-m-d G:i:s')
											);
							$sqlIncome = $dblms->insert(ACCOUNT_TRANS, $values);
							
							// Make Account Log
							$values = array (
												 "id_user"		=> cleanvars($adm_id)
												,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
												,"action"		=> '1'
												,"challan_no"	=> cleanvars($rownar['challan_no'])
												,"dated"		=> date('Y-m-d G:i:s')
												,"ip"			=> cleanvars($ip)
												,"remarks"		=> cleanvars($remarks)
												,"id_campus"	=> cleanvars($id_campus)
											);		
							$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);
		
							// PAY CHALLAN FEE CATEGORY WISE
							$sqlnarPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
																FROM ".FEE_PARTICULARS." fp
																INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
																LEFT JOIN ".FEE_PARTICULARSPAID_LIVE." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
																WHERE fp.id_fee		= '".cleanvars($rownar['id'])."'
																AND fp.amount	   != '0'
																AND NOT EXISTS (
																	SELECT fpp.*
																	FROM ".FEE_PARTICULARSPAID_LIVE." fpp
																	WHERE fpp.id_cat	= fp.id_cat
																	AND fpp.id_fee		= '".cleanvars($rownar['id'])."'
																	AND fpp.status		= '1'
																)
																GROUP BY fc.cat_id
																ORDER BY fc.cat_ordering ASC
															");
							while($rownarPart = mysqli_fetch_array($sqlnarPart)){
								if ($rownarPart['amount']) {
									if ($id_scholarship == $rownarPart['id_cat'] || $id_scholarship == 0) {
										$rownarPart['amount'] -= $scholarship;
									}
									if ($id_concession == $rownarPart['id_cat'] || $id_concession == 0) {
										$rownarPart['amount'] -= $concession;
									}
		
									$head_paid_amount = $rownarPart['amount'] - $rownarPart['paid_amount'];
		
									// ADD HEAD WISE PAYMENT LOG
									$values = array (
														 "status"			=> '1'
														,"id_fee"			=> cleanvars($rownar['id'])
														,"id_cat"			=> cleanvars($rownarPart['id_cat'])
														,"paid_amount"		=> cleanvars($head_paid_amount)
														,"paid_date"		=> date('Y-m-d')
													);		
									$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID_LIVE, $values);
								}
							}
		
							// LOG
							$log_remarks = "Fee Challan Paid by mobile app with ID: ".$rownar['id']." Detail";
							$values = array (
												 'id_user'		=>	cleanvars($adm_id)
												,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
												,'action'		=>	'2'
												,'dated'		=>	date('Y-m-d G:i:s')
												,'ip'			=>	cleanvars($ip)
												,'remarks'		=>	cleanvars($remarks)
												,'id_campus'	=>	cleanvars($id_campus)
											);
							$sqlRemarks = $dblms->insert(LOGS, $values);
							array_push($chl_array, cleanvars($rownar['challan_no']));
						}
					}
				}	
				
				// UPDATE CURRENT MONTH CHALLAN
				if($totaltransamount > 0){
					$final_paid = $totaltransamount + $data_arr['paid_amount'];
		
					// SCHOLARSHIP SEPERATE ID, AMOUNT
					$slrArray 			= explode(',',$data_arr['scholarship']);
					$scholarship 		= $slrArray[0];
					$id_scholarship 	= $slrArray[1];
					// CONCESSION SEPERATE ID, AMOUNT
					$conArray 			= explode(',',$data_arr['concession']);
					$concession 		= $conArray[0];
					$id_concession 		= $conArray[1];
		
					$values = array (
										 "status"			=> '1'
										,"pay_mode"			=> cleanvars($pay_mode)
										,"paid_date"		=> cleanvars($paidDate)
										,"paid_amount"		=> cleanvars($final_paid)
										,"note"				=> cleanvars($note)
										,"id_collector"		=> cleanvars($adm_id)
										,"id_modify"		=> cleanvars($adm_id)
										,"date_modify"		=> date('Y-m-d G:i:s')
									);	
					$sqllmsupdate  = $dblms->Update(FEES_LIVE , $values , "WHERE challan_no = '".cleanvars($challan_no)."'");
		
					if($sqllmsupdate){
						
						// GET FEE HEAD FROM ACCOUNT HEADS
						$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
						$values_trans_head = mysqli_fetch_array($sqllms_head);
		
						$remarks = 'Fee Challan Paid by mobile app.';
		
						// ADD INCOME
						$values = array (
											 "trans_status"		=> '1'
											,"trans_title"		=> cleanvars($challan_no)
											,"trans_type"		=> cleanvars($pay_mode)
											,"trans_amount"		=> cleanvars($totaltransamount)
											,"voucher_no"		=> cleanvars($challan_no)
											,"trans_method"		=> cleanvars($pay_mode)
											,"trans_note"		=> cleanvars($note)
											,"dated"			=> cleanvars($paidDate)
											,"id_head"			=> cleanvars($values_trans_head['head_id'])
											,"id_campus"		=> cleanvars($id_campus)
											,"id_added"			=> cleanvars($adm_id)
											,"date_added"		=> date('Y-m-d G:i:s')
										);		
						$sqlIncome  = $dblms->insert(ACCOUNT_TRANS, $values);
						
						// MAKE LOG				
						$values = array (
											 "id_user"		=> cleanvars($adm_id)
											,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
											,"action"		=> '1'
											,"challan_no"	=> cleanvars($challan_no)
											,"dated"		=> date('Y-m-d G:i:s')
											,"ip"			=> cleanvars($ip)
											,"remarks"		=> cleanvars($remarks)
											,"id_campus"	=> cleanvars($id_campus)
										);		
						$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);
		
						// PAY CHALLAN FEE CATEGORY WISE
						$sqlCrntPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
															FROM ".FEE_PARTICULARS." fp
															INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
															LEFT JOIN ".FEE_PARTICULARSPAID_LIVE." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
															WHERE fp.id_fee		= '".cleanvars($data_arr['id_fee'])."'
															AND fp.amount	   != '0'
															AND NOT EXISTS (
																SELECT fpp.*
																FROM ".FEE_PARTICULARSPAID_LIVE." fpp
																WHERE fpp.id_cat	= fp.id_cat
																AND fpp.id_fee		= '".cleanvars($data_arr['id_fee'])."'
																AND fpp.status		= '1'
															)
															GROUP BY fc.cat_id
															ORDER BY fc.cat_ordering ASC
														");
						while($rowCrntPart = mysqli_fetch_array($sqlCrntPart)){
							if ($rowCrntPart['amount']) {
								if ($id_scholarship == $rowCrntPart['id_cat'] || $id_scholarship == 0) {
									$rowCrntPart['amount'] -= $scholarship;
								}
								if ($id_concession == $rowCrntPart['id_cat'] || $id_concession == 0) {
									$rowCrntPart['amount'] -= $concession;
								}
		
								$head_paid_amount = $rowCrntPart['amount'] - $rowCrntPart['paid_amount'];
		
								// ADD HEAD WISE PAYMENT LOG
								$values = array (
													 "status"			=> '1'
													,"id_fee"			=> cleanvars($data_arr['id_fee'])
													,"id_cat"			=> cleanvars($rowCrntPart['id_cat'])
													,"paid_amount"		=> cleanvars($head_paid_amount)
													,"paid_date"		=> date('Y-m-d')
												);		
								$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID_LIVE, $values);
							}
						}
		
						// REMARKS
						$log_remarks = "Fee Challan Paid with ID: ".$data_arr['id_fee']." Detail";
						$values = array (
											 'id_user'		=>	cleanvars($adm_id)
											,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
											,'action'		=>	'2'
											,'dated'		=>	date('Y-m-d G:i:s')
											,'ip'			=>	cleanvars($ip)
											,'remarks'		=>	cleanvars($log_remarks)
											,'id_campus'	=>	cleanvars($id_campus)
										);
						$sqlRemarks = $dblms->insert(LOGS, $values);
		
						array_push($chl_array, cleanvars($_POST['challan_no']));
						// $challan_no_Comma = implode(',',$chl_array);
						// $hrefLac = (isset($_POST['save_and_print']))? 'feechallanprint.php?id='.cleanvars($challan_no_Comma).'&id_campus='.$id_campus.'': 'fee_challans.php';
		
						// sessionMsg("Success", "Challan Successfully Paid.", "info");
						// header("Location: ".$hrefLac."", true, 301);
						// exit();
						
						$data['success'] = true;	
						$data['MSG'] 	 = "Fee Challan Paid Along with Arears.";
					
						array_push($jsonObj,$data);
						$set['SYSTEM_101'] = $jsonObj;
					}
				}
			}
		
			// PARTIAL PAYMENT
			elseif($totaltransamount > 0){
				
				// Update Previous pending Challans as Paid or Partial Paid
				$sqlnar  = $dblms->querylms("SELECT f.id, f.challan_no, f.total_amount, f.paid_amount, f.due_date, f.scholarship, f.concession
												FROM ".FEES_LIVE." f
												WHERE f.due_date   <= '".cleanvars($due_date)."'
												AND f.id_month	   != '".cleanvars($id_month)."'
												AND f.id_std		= '".cleanvars($id_std)."'
												AND f.is_deleted	= '0'
												AND f.status IN (2,4)
												AND f.id_type IN (1,2)
												ORDER BY f.id ASC
											");
				if(mysqli_num_rows($sqlnar)>0){
					while($rownar = mysqli_fetch_array($sqlnar)){
		
						$payable = $rownar['total_amount'] - $rownar['paid_amount'];
		
						// SCHOLARSHIP SEPERATE ID, AMOUNT
						$slrArray 			= explode(',',$rownar['scholarship']);
						$scholarship 		= $slrArray[0];
						$id_scholarship 	= $slrArray[1];
						// CONCESSION SEPERATE ID, AMOUNT
						$conArray 			= explode(',',$rownar['concession']);
						$concession 		= $conArray[0];
						$id_concession 		= $conArray[1];
		
						if(date('Y-m-d') > $rownar['due_date']) {
							$due_date_after_five_day = date('Y-m-d', strtotime($rownar['due_date']. ' + 5 days'));
							if ($late_fee_type_array[0] == 1) {
								$payable += $late_fee_type_array[1];
							} else if ($late_fee_type_array[0] == 2) {
								if ($due_date_after_five_day > date('Y-m-d')) {
									$payable += $late_fee_type_array[1];
								} else if ($due_date_after_five_day < date('Y-m-d')) {
									$payable += $late_fee_type_array[2];
								} else {
									$payable += LATEFEE;	
								}
							} else {
								$payable += LATEFEE;
							} 
						}
		
						// FULL PAID
						if($totaltransamount>=$payable){
		
							$final_paid = $payable + $rownar['paid_amount'];
							//Update pending as Paid					
							$values = array (
												 "status"			=> '1'
												,"pay_mode"			=> cleanvars($pay_mode)
												,"paid_date"		=> cleanvars($paidDate)
												,"paid_amount"		=> cleanvars($final_paid)
												,"note"				=> cleanvars($note)
												,"id_collector"		=> cleanvars($adm_id)
												,"id_modify"		=> cleanvars($adm_id)
												,"date_modify"		=> date('Y-m-d G:i:s')
											);	
							$sqllmsUpdatePrev  = $dblms->Update(FEES_LIVE , $values , "WHERE challan_no = '".cleanvars($rownar['challan_no'])."'");
							
							if($sqllmsUpdatePrev){
								$totaltransamount = $totaltransamount - $payable;
		
								// GET FEE HEAD FROM ACCOUNT HEADS
								$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
								$values_trans_head = mysqli_fetch_array($sqllms_head);
		
								$remarks = 'Fee Challan Paid by mobile app.';
		
								// Add INCOME
								$values = array (
													 "trans_status"		=> '1'
													,"trans_title"		=> cleanvars($rownar['challan_no'])
													,"trans_type"		=> cleanvars($pay_mode)
													,"trans_amount"		=> cleanvars($payable)
													,"voucher_no"		=> cleanvars($rownar['challan_no'])
													,"trans_method"		=> cleanvars($pay_mode)
													,"trans_note"		=> cleanvars($note)
													,"dated"			=> cleanvars($paidDate)
													,"id_head"			=> cleanvars($values_trans_head['head_id'])
													,"id_campus"		=> cleanvars($id_campus)
													,"id_added"			=> cleanvars($adm_id)
													,"date_added"		=> date('Y-m-d G:i:s')
												);		
								$sqlIncome  = $dblms->insert(ACCOUNT_TRANS, $values);
								
								// MAKE LOG
								$values = array (
													 "id_user"		=> cleanvars($adm_id)
													,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
													,"action"		=> '1'
													,"challan_no"	=> cleanvars($rownar['challan_no'])
													,"dated"		=> date('Y-m-d G:i:s')
													,"ip"			=> cleanvars($ip)
													,"remarks"		=> cleanvars($remarks)
													,"id_campus"	=> cleanvars($id_campus)
												);		
								$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);
		
								// PAY CHALLAN FEE CATEGORY WISE
								$sqlnarPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
																	FROM ".FEE_PARTICULARS." fp
																	INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
																	LEFT JOIN ".FEE_PARTICULARSPAID_LIVE." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
																	WHERE fp.id_fee		= '".cleanvars($rownar['id'])."'
																	AND fp.amount	   != '0'
																	AND NOT EXISTS (
																		SELECT fpp.*
																		FROM ".FEE_PARTICULARSPAID_LIVE." fpp
																		WHERE fpp.id_cat	= fp.id_cat
																		AND fpp.id_fee		= '".cleanvars($rownar['id'])."'
																		AND fpp.status		= '1'
																	)
																	GROUP BY fc.cat_id
																	ORDER BY fc.cat_ordering ASC
																");
								while($rownarPart = mysqli_fetch_array($sqlnarPart)){
									if ($rownarPart['amount']) {
										if ($id_scholarship == $rownarPart['id_cat'] || $id_scholarship == 0) {
											$rownarPart['amount'] -= $scholarship;
										}
										if ($id_concession == $rownarPart['id_cat'] || $id_concession == 0) {
											$rownarPart['amount'] -= $concession;
										}
		
										$head_paid_amount = $rownarPart['amount'] - $rownarPart['paid_amount'];
		
										// ADD HEAD WISE PAYMENT LOG
										$values = array (
															"status"			=> '1'
															,"id_fee"			=> cleanvars($rownar['id'])
															,"id_cat"			=> cleanvars($rownarPart['id_cat'])
															,"paid_amount"		=> cleanvars($head_paid_amount)
															,"paid_date"		=> date('Y-m-d')
														);		
										$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID_LIVE, $values);
									}
								}
								
								// LOG
								$log_remarks = "Fee Challan Paid with ID: ".$rownar['id']." Detail";
								$values = array (
													 'id_user'		=>	cleanvars($adm_id)
													,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
													,'action'		=>	'2'
													,'dated'		=>	date('Y-m-d G:i:s')
													,'ip'			=>	cleanvars($ip)
													,'remarks'		=>	cleanvars($log_remarks)
													,'id_campus'	=>	cleanvars($id_campus)
												);
								$sqlRemarks = $dblms->insert(LOGS, $values);
								// array_push($chl_array, cleanvars($rownar['challan_no']));
							}
						}
		
						// PARTIAL PAID
						elseif($totaltransamount > 0){
		
							$payable = $totaltransamount;
							$final_paid = $payable + $rownar['paid_amount'];
							// Update pending as Partial Paid
							$values = array (
												 "status"			=> '4'
												,"pay_mode"			=> cleanvars($pay_mode)
												,"paid_date"		=> cleanvars($paidDate)
												,"paid_amount"		=> cleanvars($final_paid)
												,"note"				=> cleanvars($note)
												,"id_collector"		=> cleanvars($adm_id)
												,"id_modify"		=> cleanvars($adm_id)
												,"date_modify"		=> date('Y-m-d G:i:s')
											);
							$sqllmsUpdatePrev  = $dblms->Update(FEES_LIVE , $values , "WHERE challan_no = '".cleanvars($rownar['challan_no'])."'");
							
							if($sqllmsUpdatePrev){
								$totaltransamount = $totaltransamount - $payable;
		
								// GET FEE HEAD FROM ACCOUNT HEADS
								$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
								$values_trans_head = mysqli_fetch_array($sqllms_head);
		
								$remarks = 'Fee Challan Paid by mobile app.';
								// ADD INCOME
								$values = array (
													 "trans_status"		=> '1'
													,"trans_title"		=> cleanvars($rownar['challan_no'])
													,"trans_type"		=> cleanvars($pay_mode)
													,"trans_amount"		=> cleanvars($payable)
													,"voucher_no"		=> cleanvars($rownar['challan_no'])
													,"trans_method"		=> cleanvars($pay_mode)
													,"trans_note"		=> cleanvars($note)
													,"dated"			=> cleanvars($paidDate)
													,"id_head"			=> cleanvars($values_trans_head['head_id'])
													,"id_campus"		=> cleanvars($id_campus)
													,"id_added"			=> cleanvars($adm_id)
													,"date_added"		=> date('Y-m-d G:i:s')
												);		
								$sqlIncome  = $dblms->insert(ACCOUNT_TRANS, $values);
								
								// MAKE LOG
								$values = array (
													 "id_user"		=> cleanvars($adm_id)
													,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
													,"action"		=> '1'
													,"challan_no"	=> cleanvars($rownar['challan_no'])
													,"dated"		=> date('Y-m-d G:i:s')
													,"ip"			=> cleanvars($ip)
													,"remarks"		=> cleanvars($remarks)
													,"id_campus"	=> cleanvars($id_campus)
												);		
								$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);
		
								// PAY CHALLAN FEE CATEGORY WISE
								$sqlnarPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
																	FROM ".FEE_PARTICULARS." fp
																	INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
																	LEFT JOIN ".FEE_PARTICULARSPAID_LIVE." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
																	WHERE fp.id_fee		= '".cleanvars($rownar['id'])."'
																	AND fp.amount	   != '0'
																	AND NOT EXISTS (
																		SELECT fpp.*
																		FROM ".FEE_PARTICULARSPAID_LIVE." fpp
																		WHERE fpp.id_cat	= fp.id_cat
																		AND fpp.id_fee		= '".cleanvars($rownar['id'])."'
																		AND fpp.status		= '1'
																	)
																	GROUP BY fc.cat_id
																	ORDER BY fc.cat_ordering ASC
																");
								while($rownarPart = mysqli_fetch_array($sqlnarPart)){
									if ($rownarPart['amount']) {
										if ($id_scholarship == $rownarPart['id_cat'] || $id_scholarship == 0) {
											$rownarPart['amount'] -= $scholarship;
										}
										if ($id_concession == $rownarPart['id_cat'] || $id_concession == 0) {
											$rownarPart['amount'] -= $concession;
										}
		
										$head_paid_amount = $rownarPart['amount'] - $rownarPart['paid_amount'];
		
										if($payable >= $head_paid_amount){
											// ADD HEAD WISE PAYMENT LOG - FULL PAID
											$values = array (
																 "status"			=> '1'
																,"id_fee"			=> cleanvars($rownar['id'])
																,"id_cat"			=> cleanvars($rownarPart['id_cat'])
																,"paid_amount"		=> cleanvars($head_paid_amount)
																,"paid_date"		=> date('Y-m-d')
															);		
											$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID_LIVE, $values);
		
											$payable -= $head_paid_amount;
										}
		
										elseif($payable > 0 && $payable < $head_paid_amount){
											$head_partial_paid = $payable;
											// ADD HEAD WISE PAYMENT LOG - PARTIAL PAID
											$values = array (
																 "status"			=> '4'
																,"id_fee"			=> cleanvars($rownar['id'])
																,"id_cat"			=> cleanvars($rownarPart['id_cat'])
																,"paid_amount"		=> cleanvars($head_partial_paid)
																,"paid_date"		=> date('Y-m-d')
															);		
											$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID_LIVE, $values);
											
											$payable -= $head_partial_paid;
										}
									}
								}
								
								// LOG
								$log_remarks = "Fee Challan Partially Paid by mobile app with ID: ".$rownar['id']." Detail";
								$values = array (
													 'id_user'		=>	cleanvars($adm_id)
													,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
													,'action'		=>	'2'
													,'dated'		=>	date('Y-m-d G:i:s')
													,'ip'			=>	cleanvars($ip)
													,'remarks'		=>	cleanvars($log_remarks)
													,'id_campus'	=>	cleanvars($id_campus)
												);
								$sqlRemarks = $dblms->insert(LOGS, $values);
							}
						}
					}
				}
		
				// Update Current pending Challans as Paid or Partial Paid
				$payable = $total_amount - $paid_amount;
		
				if(date('Y-m-d') > $due_date) {
					$due_date_after_five_day = date('Y-m-d', strtotime($due_date. ' + 5 days'));
					if ($late_fee_type_array[0] == 1) {
						$payable += $late_fee_type_array[1];
					} else if ($late_fee_type_array[0] == 2) {
						if ($due_date_after_five_day > date('Y-m-d')) {
							$payable += $late_fee_type_array[1];
						} else if ($due_date_after_five_day < date('Y-m-d')) {
							$payable += $late_fee_type_array[2];
						} else {
							$payable += LATEFEE;	
						}
					} else {
						$payable += LATEFEE;
					} 
				}
		
				// SCHOLARSHIP SEPERATE ID, AMOUNT
				$slrArray 			= explode(',',$scholarship);
				$scholarship 		= $slrArray[0];
				$id_scholarship 	= $slrArray[1];
				// CONCESSION SEPERATE ID, AMOUNT
				$conArray 			= explode(',',$concession);
				$concession 		= $conArray[0];
				$id_concession 		= $conArray[1];
		
				// FULL PAID
				if($totaltransamount >= $payable){
					$payable = $totaltransamount;
					$final_paid = $payable + $paid_amount;
					// Update Pending as Paid
					$values = array (
										 "status"			=> '1'
										,"pay_mode"			=> cleanvars($pay_mode)
										,"paid_date"		=> cleanvars($paidDate)
										,"paid_amount"		=> cleanvars($final_paid)
										,"note"				=> cleanvars($note)
										,"id_collector"		=> cleanvars($adm_id)
										,"id_modify"		=> cleanvars($adm_id)
										,"date_modify"		=> date('Y-m-d G:i:s')
									);	
					$sqllmsupdate  = $dblms->Update(FEES_LIVE , $values , "WHERE challan_no = '".cleanvars($challan_no)."'");
					
					if($sqllmsupdate){				
						// GET FEE HEAD FROM ACCOUNT HEADS
						$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
						$values_trans_head = mysqli_fetch_array($sqllms_head);
			
						$remarks = 'Fee Challan Paid';
						// ADD INCOME
						$values = array (
											 "trans_status"		=> '1'
											,"trans_title"		=> cleanvars($challan_no)
											,"trans_type"		=> cleanvars($pay_mode)
											,"trans_amount"		=> cleanvars($payable)
											,"voucher_no"		=> cleanvars($challan_no)
											,"trans_method"		=> cleanvars($pay_mode)
											,"trans_note"		=> cleanvars($note)
											,"dated"			=> cleanvars($paidDate)
											,"id_head"			=> cleanvars($values_trans_head['head_id'])
											,"id_campus"		=> cleanvars($id_campus)
											,"id_added"			=> cleanvars($adm_id)
											,"date_added"		=> date('Y-m-d G:i:s')
										);		
						$sqlIncome  = $dblms->insert(ACCOUNT_TRANS, $values);
						
						// MAKE LOG
						$values = array (
											 "id_user"		=> cleanvars($adm_id)
											,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
											,"action"		=> '1'
											,"challan_no"	=> cleanvars($challan_no)
											,"dated"		=> date('Y-m-d G:i:s')
											,"ip"			=> cleanvars($ip)
											,"remarks"		=> cleanvars($remarks)
											,"id_campus"	=> cleanvars($id_campus)
										);		
						$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);
		
						// PAY CHALLAN FEE CATEGORY WISE
						$sqlnarPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
															FROM ".FEE_PARTICULARS." fp
															INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
															LEFT JOIN ".FEE_PARTICULARSPAID_LIVE." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
															WHERE fp.id_fee		= '".cleanvars($id_fee)."'
															AND fp.amount	   != '0'
															AND NOT EXISTS (
																SELECT fpp.*
																FROM ".FEE_PARTICULARSPAID_LIVE." fpp
																WHERE fpp.id_cat	= fp.id_cat
																AND fpp.id_fee		= '".cleanvars($id_fee)."'
																AND fpp.status		= '1'
															)
															GROUP BY fc.cat_id
															ORDER BY fc.cat_ordering ASC
														");
						while($rowCrntPart = mysqli_fetch_array($sqlCrntPart)){
							if ($rowCrntPart['amount']) {
								if ($id_scholarship == $rowCrntPart['id_cat'] || $id_scholarship == 0) {
									$rowCrntPart['amount'] -= $scholarship;
								}
								if ($id_concession == $rowCrntPart['id_cat'] || $id_concession == 0) {
									$rowCrntPart['amount'] -= $concession;
								}
		
								$head_paid_amount = $rowCrntPart['amount'] - $rowCrntPart['paid_amount'];
		
								// ADD HEAD WISE PAYMENT LOG
								$values = array (
													 "status"			=> '1'
													,"id_fee"			=> cleanvars($id_fee)
													,"id_cat"			=> cleanvars($rowCrntPart['id_cat'])
													,"paid_amount"		=> cleanvars($head_paid_amount)
													,"paid_date"		=> date('Y-m-d')
												);		
								$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID_LIVE, $values);
							}
						}
						
						// REMARKS
						$log_remarks = "Fee Challan Fully Paid by mobile app with ID: ".$id_fee." Detail";
						$values = array (
											 'id_user'		=>	cleanvars($adm_id)
											,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
											,'action'		=>	'2'
											,'dated'		=>	date('Y-m-d G:i:s')
											,'ip'			=>	cleanvars($ip)
											,'remarks'		=>	cleanvars($log_remarks)
											,'id_campus'	=>	cleanvars($id_campus)
										);
						$sqlRemarks = $dblms->insert(LOGS, $values);
						
						$data['success']		= true;
						$data['MSG']			= "Challan Paid Successfully.";
				
						array_push($jsonObj,$data);
						$set['SYSTEM_101'] = $jsonObj;
		
						// array_push($chl_array, cleanvars($_POST['challan_no']));
						// $challan_no_Comma = implode(',',$chl_array);
						// $hrefLac = (isset($_POST['save_and_print']))? 'feechallanprint.php?id='.cleanvars($challan_no_Comma).'&id_campus='.$id_campus.'': 'fee_challans.php';
		
						// sessionMsg("Success", "Payment Successfully Added.", "info");
						// header("Location: ".$hrefLac."", true, 301);
						// exit();
					}
				}
				
				// PARTIAL PAID
				elseif($totaltransamount > 0){
					$payable = $totaltransamount;
					$final_paid = $payable + $paid_amount;
					// Update Pending as Partial Paid
					$values = array (
										 "status"			=> '4'
										,"pay_mode"			=> cleanvars($pay_mode)
										,"paid_date"		=> cleanvars($paidDate)
										,"paid_amount"		=> cleanvars($final_paid)
										,"note"				=> cleanvars($note)
										,"id_collector"		=> cleanvars($adm_id)
										,"id_modify"		=> cleanvars($adm_id)
										,"date_modify"		=> date('Y-m-d G:i:s')
									);	
					$sqllmsupdate  = $dblms->Update(FEES_LIVE , $values , "WHERE challan_no = '".cleanvars($challan_no)."'");
					
					if($sqllmsupdate){				
						// GET FEE HEAD FROM ACCOUNT HEADS
						$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
						$values_trans_head = mysqli_fetch_array($sqllms_head);
			
						$remarks = 'Fee Challan Paid by mobile app.';
						// ADD INCOME
						$values = array (
											 "trans_status"		=> '1'
											,"trans_title"		=> cleanvars($challan_no)
											,"trans_type"		=> cleanvars($pay_mode)
											,"trans_amount"		=> cleanvars($payable)
											,"voucher_no"		=> cleanvars($challan_no)
											,"trans_method"		=> cleanvars($pay_mode)
											,"trans_note"		=> cleanvars($note)
											,"dated"			=> cleanvars($paidDate)
											,"id_head"			=> cleanvars($values_trans_head['head_id'])
											,"id_campus"		=> cleanvars($id_campus)
											,"id_added"			=> cleanvars($adm_id)
											,"date_added"		=> date('Y-m-d G:i:s')
										);		
						$sqlIncome = $dblms->insert(ACCOUNT_TRANS, $values);
						
						// MAKE LOG
						$values = array (
											 "id_user"		=> cleanvars($adm_id)
											,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
											,"action"		=> '1'
											,"challan_no"	=> cleanvars($challan_no)
											,"dated"		=> date('Y-m-d G:i:s')
											,"ip"			=> cleanvars($ip)
											,"remarks"		=> cleanvars($remarks)
											,"id_campus"	=> cleanvars($id_campus)
										);		
						$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);
		
						// PAY CHALLAN FEE CATEGORY WISE
						$sqlCrntPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
															FROM ".FEE_PARTICULARS." fp
															INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
															LEFT JOIN ".FEE_PARTICULARSPAID_LIVE." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
															WHERE fp.id_fee		= '".cleanvars($id_fee)."'
															AND fp.amount	   != '0'
															AND NOT EXISTS (
																SELECT fpp.*
																FROM ".FEE_PARTICULARSPAID_LIVE." fpp
																WHERE fpp.id_cat	= fp.id_cat
																AND fpp.id_fee		= '".cleanvars($id_fee)."'
																AND fpp.status		= '1'
															)
															GROUP BY fc.cat_id
															ORDER BY fc.cat_ordering ASC
														");
						while($rowCrntPart = mysqli_fetch_array($sqlCrntPart)){
							if ($rowCrntPart['amount']) {
								if ($id_scholarship == $rowCrntPart['id_cat'] || $id_scholarship == 0) {
									$rowCrntPart['amount'] -= $scholarship;
								}
								if ($id_concession == $rowCrntPart['id_cat'] || $id_concession == 0) {
									$rowCrntPart['amount'] -= $concession;
								}
		
								$head_paid_amount = $rowCrntPart['amount'] - $rowCrntPart['paid_amount'];
		
								if($payable >= $head_paid_amount){
									// ADD HEAD WISE PAYMENT LOG - FULL PAID
									$values = array (
														 "status"			=> '1'
														,"id_fee"			=> cleanvars($id_fee)
														,"id_cat"			=> cleanvars($rowCrntPart['id_cat'])
														,"paid_amount"		=> cleanvars($head_paid_amount)
														,"paid_date"		=> date('Y-m-d')
													);		
									$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID_LIVE, $values);
		
									$payable -= $head_paid_amount;
								}
		
								elseif($payable > 0 && $payable < $head_paid_amount){
									$head_partial_paid = $payable;
									// ADD HEAD WISE PAYMENT LOG - PARTIAL PAID
									$values = array (
														 "status"			=> '4'
														,"id_fee"			=> cleanvars($id_fee)
														,"id_cat"			=> cleanvars($rowCrntPart['id_cat'])
														,"paid_amount"		=> cleanvars($head_partial_paid)
														,"paid_date"		=> date('Y-m-d')
													);		
									$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID_LIVE, $values);
									
									$payable -= $head_partial_paid;
								}						
							}
						}
						
						// REMARKS
						$log_remarks = "Fee Challan Partially Paid by mobile app with ID: ".$id_fee." Detail";
						$values = array (
												'id_user'		=>	cleanvars($adm_id)
											,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
											,'action'		=>	'2'
											,'dated'		=>	date('Y-m-d G:i:s')
											,'ip'			=>	cleanvars($ip)
											,'remarks'		=>	cleanvars($log_remarks)
											,'id_campus'	=>	cleanvars($id_campus)
										);
						$sqlRemarks = $dblms->insert(LOGS, $values);
						
						$data['success']		= true;
						$data['MSG']			= "Challan Partially Paid! Scan again to pay again.";
				
						array_push($jsonObj,$data);
						$set['SYSTEM_101'] = $jsonObj;
		
						// array_push($chl_array, cleanvars($_POST['challan_no']));
						// $challan_no_Comma = implode(',',$chl_array);
						// $hrefLac = (isset($_POST['save_and_print']))? 'feechallanprint.php?id='.cleanvars($challan_no_Comma).'&id_campus='.$id_campus.'': 'fee_challans.php';
		
						// sessionMsg("Success", "Partial Payment Successfully Added.", "info");
						// header("Location: ".$hrefLac."", true, 301);
						// exit();
						
					}
				}
		
				// STILL PENDING
				else{
					// REMARKS
					$log_remarks = "Arears Paid! Fee Challan Updated with ID: ".$id_fee." Detail";
					$values = array (
										 'id_user'		=>	cleanvars($adm_id)
										,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
										,'action'		=>	'2'
										,'dated'		=>	date('Y-m-d G:i:s')
										,'ip'			=>	cleanvars($ip)
										,'remarks'		=>	cleanvars($log_remarks)
										,'id_campus'	=>	cleanvars($id_campus)
									);
					$sqlRemarks = $dblms->insert(LOGS, $values);
					
					$data['success']		= true;
					$data['MSG']			= "Arears Paid! Latest Challan still pending.";
			
					array_push($jsonObj,$data);
					$set['SYSTEM_101'] = $jsonObj;
		
					// $challan_no_Comma = implode(',',$chl_array);
					// $hrefLac = (isset($_POST['save_and_print']))? 'feechallanprint.php?id='.cleanvars($challan_no_Comma).'&id_campus='.$id_campus.'': 'fee_challans.php';
		
					// sessionMsg("Success", "Partial Payment Successfully Added.", "info");
					// header("Location: ".$hrefLac."", true, 301);
					// exit();
				}
			}
		
			// STILL PENDING
			else{
				// REMARKS
				$log_remarks = "Fee Challan Updated with ID: ".$id_fee." Detail";
				$values = array (
									 'id_user'		=>	cleanvars($adm_id)
									,'filename'		=>	strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
									,'action'		=>	'2'
									,'dated'		=>	date('Y-m-d G:i:s')
									,'ip'			=>	cleanvars($ip)
									,'remarks'		=>	cleanvars($log_remarks)
									,'id_campus'	=>	cleanvars($id_campus)
								);
				$sqlRemarks = $dblms->insert(LOGS, $values);
				
				$data['success']		= false;
				$data['MSG']			= "No Payment Added!";
		
				array_push($jsonObj,$data);
				$set['SYSTEM_101'] = $jsonObj;

				// sessionMsg("Success", "No Payment Added.", "info");
				// header("Location: ".$hrefLac."", true, 301);
				// exit();
			}
		}else{
			$data['success'] = false;	
			$data['MSG'] 	 = "No Payment Added! Please Check All Parameters.";
			
			array_push($jsonObj,$data);
			$set['SYSTEM_101'] = $jsonObj;
		}

		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($data,JSON_UNESCAPED_UNICODE));
		die();


	}

	// ELSE
	else{
		$jsonObj	= array();
		$data['success'] = false;	
		$data['MSG'] 	 = "Invalid Method!";
			
		array_push($jsonObj,$data);
		$set['SYSTEM_101'] = $jsonObj;
		
		header( 'Content-Type: application/json; charset=utf-8' );
		echo $val= str_replace('\\/', '/', json_encode($data,JSON_UNESCAPED_UNICODE));
		die();
	}
}
?>