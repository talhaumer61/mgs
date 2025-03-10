<?php 
// BULK FEE CHALLAN GENERATE
if(isset($_POST['challans_generate'])){
	$std_not_fount_flag = 0;
	$id_class	= cleanvars($_POST['id_class']);
	$id_month	= cleanvars($_POST['id_month']);
	$is_hostel	= cleanvars($_POST['is_hostel']);
	if($is_hostel == 2){
		$is_hostel = '0,2';
	}
	$srno = 0;
	$id_campus = (isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus']: $_SESSION['userlogininfo']['LOGINCAMPUS'];
	foreach($_POST['id_section'] as $key => $val):
		$srno++;
		$id_section = cleanvars($val);

		// NOTE
		$note = $_POST['note_'.$id_section][$id_section];
		// Dates
		$year = date('Y' , strtotime(cleanvars($_POST['due_date'][$key])));
		$challandate = date('Ym');
		$issue_date = date('Y-m-d' , strtotime(cleanvars($_POST['issue_date'][$key])));
		$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'][$key])));

		if(strlen($id_month) == 1){
			$yearmonth = $year.'-0'.$id_month;
		}else{
			$yearmonth = $year.'-'.$id_month;
		}

		$sqlStudent	= $dblms->querylms("SELECT s.std_id, s.std_name
										FROM ".STUDENTS." s
										WHERE s.id_campus	= '".$id_campus."'
										AND s.id_class		= '".cleanvars($id_class)."' 
										AND s.id_section	= '".cleanvars($id_section)."'
										AND s.std_status	= '1' 
										AND s.is_deleted	= '0' 
										AND s.is_hostel IN (".cleanvars($is_hostel).")
									");
		$no = 0;
		if(mysqli_num_rows($sqlStudent) != 0) {
			$std_not_fount_flag++;
			while($valStudent = mysqli_fetch_array($sqlStudent)) {
				
				$id_std = $valStudent['std_id'];
				$sqllmscheck  = $dblms->querylms("SELECT id_std
													FROM ".FEES." 
													WHERE id_std	=	'".cleanvars($id_std)."'
													AND	id_month	=	'".cleanvars($id_month)."'
													AND id_session	=	'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
													AND is_deleted	=	'0'
												");
				if(mysqli_num_rows($sqllmscheck) == 0){
					// SCHOLARSHIP, CONCESSION, FINE
					$sqlScholarship = $dblms->querylms("SELECT
														SUM(CASE WHEN id_type = '1' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN percent ELSE NULL END) as scholarship_percent,
														SUM(CASE WHEN id_type = '1' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN amount ELSE NULL END) as scholarship_amount,
														SUM(CASE WHEN id_type = '1' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN id_feecat ELSE NULL END) as scholarship_feecat,
														SUM(CASE WHEN id_type = '2' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN percent ELSE NULL END) as concession_percent,
														SUM(CASE WHEN id_type = '2' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN amount ELSE NULL END) as concession_amount,
														SUM(CASE WHEN id_type = '2' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN id_feecat ELSE NULL END) as concession_feecat,
														SUM(CASE WHEN id_type = '3' AND yearmonth = '".$yearmonth."' THEN amount ELSE NULL END) as fine_amount,
														SUM(CASE WHEN id_type = '3' AND yearmonth = '".$yearmonth."' THEN id_feecat ELSE NULL END) as fine_feecat
														FROM ".SCHOLARSHIP." 
														WHERE id_campus	= '".cleanvars($id_campus)."' 
														AND id_session  = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
														AND status		= '1' 
														AND is_deleted	= '0'
														AND id_std		= '".cleanvars($id_std)."'
													");
					$valScholarship = mysqli_fetch_array($sqlScholarship);
					$id_scholarship     = $valScholarship['scholarship_feecat'];
					$id_feehead         = $valScholarship['concession_feecat'];

					// CHECK IF IT'S FIRST CHALLAN OR NOT
					$sqlCheck = $dblms->querylms("SELECT id, remaining_amount
													FROM ".FEES."
													WHERE is_deleted    = '0'
													AND id_std  	    = '".cleanvars($id_std)."'
													AND id_class		= '".cleanvars($id_class)."'
													AND id_section	    = '".cleanvars($id_section)."'
													AND id_campus	    = '".cleanvars($id_campus)."'
													AND id_session	    = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
													ORDER BY id DESC LIMIT 1
												");
					if(mysqli_num_rows($sqlCheck) == 0){
						$valCheck = mysqli_fetch_array($sqlCheck);
						$rem_amount = $row_rem['remaining_amount'];
						// $duration = ",'Yearly'";
					}else{
						$rem_amount = 0;
						// $duration = "";
					}
					$amount = 0;
					$total_amount = 0;
					$scholarship = 0;
					$concession = 0;
					$fine = 0;
					$catDetails = array();

					foreach($_POST['amount_'.$id_section] as $keyAmt => $valAmt):
						$id_feecat = $_POST['id_cat_'.$id_section][$keyAmt];
						
						$amount = $_POST['amount_'.$id_section][$keyAmt];
						// SCHOLARSHIP
						if($valScholarship['scholarship_feecat'] != '0' && $valScholarship['scholarship_feecat'] == $id_feecat){
							if($valScholarship['scholarship_percent'] != '0'){
								$scholarship = ($amount * $valScholarship['scholarship_percent']) / 100;
							}
							elseif($valScholarship['scholarship_amount'] != '0'){
								$scholarship = $valScholarship['scholarship_amount'];
							}
							$scholarship .= ','.$id_scholarship;
						}

						// CONCESSION
						if(!empty($valScholarship['concession_feecat']) && $valScholarship['concession_feecat'] != '0' && $valScholarship['concession_feecat'] == $id_feecat){
							if($valScholarship['concession_percent'] != '0'){
								$concession = ($amount * $valScholarship['concession_percent']) / 100;
							}
							elseif($valScholarship['concession_concession'] != '0'){
								$concession = $valScholarship['concession_amount'];
							}
							$concession .= ','.$id_feehead;
						}
						// FINE
						if(!empty($valScholarship['fine_feecat']) && $valScholarship['fine_feecat'] != '0' && $valScholarship['fine_feecat'] == $id_feecat){
							if($valScholarship['fine_percent'] != '0'){
								$fine = $valScholarship['fine_amount'];
							}
						}

						$total_amount = $total_amount + $amount;						
						$catDetails[] = array($id_feecat, $amount);

					endforeach;
					// SCHOLARSHIP
					if($valScholarship['scholarship_feecat'] == '0'){
						if(!empty($valScholarship['scholarship_percent'])){
							$scholarship = ($total_amount * $valScholarship['scholarship_percent']) / 100;
						}
						elseif(!empty($valScholarship['scholarship_amount'])){
							$scholarship = $valScholarship['scholarship_amount'];
						}
					}

					// CONCESSION
					if($valScholarship['concession_feecat'] == '0'){
						if(!empty($valScholarship['concession_percent'])){
							$concession = ($total_amount * $valScholarship['concession_percent']) / 100;
						}
						elseif(!empty($valScholarship['concession_amount'])){
							$concession = $valScholarship['concession_amount'];
						}
					}   

					// FINE
					if($valScholarship['fine_feecat'] == '0'){
						if(!empty($valScholarship['fine_amount'])){
							$fine = $valScholarship['fine_amount'];
						}
					}

					// REMAINING AMOUNT, SCHOLARSHIP, CONCESSION, FINE
					if($rem_amount != 0){
						$total_amount = $total_amount + $rem_amount;
					}
					if(!empty($scholarship)){
						$total_amount = $total_amount - $scholarship;
					}
					if(!empty($concession)){
						$total_amount = $total_amount - $concession;
					}
					if(!empty($fine)){
						$total_amount = $total_amount + $fine;
					}

					// CHALLAN NUMBER					
					$sqllmschallan = $dblms->querylms("SELECT challan_no FROM ".FEES." 
														WHERE challan_no LIKE '".$challandate."%'  
														ORDER by challan_no DESC LIMIT 1 ");
					if(mysqli_num_rows($sqllmschallan) == 1){		
						$rowchallan = mysqli_fetch_array($sqllmschallan);
						$challano = ($rowchallan['challan_no'] +1);
					}else{
						$challano = $challandate.'00001';
					}
					
					// CREATE CHALLAN
					$values = array(
										 'status'					=> '2'
										,'id_type'					=> '2'
										,'challan_no'	  			=> cleanvars($challano)
										,'id_session' 				=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
										,'id_month' 				=> cleanvars($_POST['id_month'])
										,'id_class'  				=> cleanvars($id_class)
										,'id_section' 	  			=> cleanvars($id_section)
										,'id_std' 	 				=> cleanvars($id_std)
										,'issue_date'				=> cleanvars($issue_date)
										,'due_date'					=> cleanvars($due_date)
										,'scholarship'				=> cleanvars($scholarship)
										,'concession'				=> cleanvars($concession)
										,'fine' 	  				=> cleanvars($fine)
										,'total_amount'				=> cleanvars($total_amount)
										,'prev_remaining_amount'	=> cleanvars($rem_amount)
										,'note' 	  				=> cleanvars($note)
										,'id_campus' 	  			=> cleanvars($id_campus)
										,'id_added' 	  			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
										,'date_added' 	  			=> date('Y-m-d H:i:s')
									);
					$sqllms = $dblms->Insert(FEES , $values);

					if($sqllms){
						$idsetup = $dblms->lastestid();

						foreach($catDetails as $cats){
							$values = array(
												 'id_fee'	=> cleanvars($idsetup)
												,'id_cat'	=> cleanvars($cats[0])
												,'amount'	=> cleanvars($cats[1])
											);
							$sqllms = $dblms->Insert(FEE_PARTICULARS , $values);
						}
						// REMARKS
						sendRemark("Challan Created from Bulk Challans: ".cleanvars($challano)." detail", '1');
					}
				}
			}
			
		}
	endforeach;	   
	if ($std_not_fount_flag == 0){
		sessionMsg("Error", "No Students Found for Class.", "error");
		header("Location: fee_challans.php", true, 301);
		exit();
	} else {
		if($sqllms){
			sessionMsg("Successfully", "Record Successfully Added.", "success");
			header("Location: fee_challans.php", true, 301);
			exit();
		} else {
			sessionMsg("Error", "Class Challans Already Generated.", "error");
			header("Location: fee_challans.php?view=bulk", true, 301);
			exit();
		}
	}
}

// SINGLE CHALLAN GENERATE
if(isset($_POST['one_challan_generate'])){
	$id_campus = (isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus']: $_SESSION['userlogininfo']['LOGINCAMPUS'];

	// EXPLODE ARRAY
    $aray		= explode('|', $_POST['id_std']);
    $id_std     = $aray[0];
    $id_class 	= $aray[1];
    $id_section	= $aray[2];

	if(isset($_POST['remaining_amount'])){$rem_amount = $_POST['remaining_amount'];}else{$rem_amount = 0;}

	if(isset($_POST['scholarship'])) {
		$scholarship 			= $_POST['scholarship'].','.$_POST['id_scholarship'];
	} else {
		$scholarship 			= 0;
	}

	if(isset($_POST['concession'])) {
		$concession 			= $_POST['concession'].','.$_POST['id_feehead'];
	} else {
		$concession = 0;
	}
	
	if(isset($_POST['fine'])){$fine = $_POST['fine'];}else{$fine = 0;}
					   
	// REFORMAT DATE
	$challandate = date('Ym');
	$issue_date = date('Y-m-d' , strtotime(cleanvars($_POST['issue_date'])));
	$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));

	// CHALLAN NUMBER
	$sqllmschallan = $dblms->querylms("SELECT challan_no FROM ".FEES." 
										WHERE challan_no LIKE '".$challandate."%'  
										ORDER by challan_no DESC LIMIT 1 ");
	if(mysqli_num_rows($sqllmschallan) == 1){		
		$rowchallan = mysqli_fetch_array($sqllmschallan);
		$challano = ($rowchallan['challan_no'] +1);
	}else{
		$challano = $challandate.'00001';
	}

	// CHECK CHALLAN ALREADY EXIST
	$sqllmscheck  = $dblms->querylms("SELECT id_std
										FROM ".FEES." 
										WHERE id_std	=	'".cleanvars($id_std)."'
										AND	id_month	=	'".cleanvars($_POST['id_month'])."'
										AND id_session	=	'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
										AND is_deleted	=	'0'
									");
	if(mysqli_num_rows($sqllmscheck)) {		
		sessionMsg("Error", "Record Already Exists.", "error");
		header("Location: fee_challans.php", true, 301);
		exit();
	}else{
		$values = array(
							 'status'					=> '2'
							,'id_type'					=> '2'
							,'challan_no'	  			=> cleanvars($challano)
							,'id_session' 				=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
							,'id_month' 				=> cleanvars($_POST['id_month'])
							,'id_class'  				=> cleanvars($id_class)
							,'id_section' 	  			=> cleanvars($id_section)
							,'id_std' 	 				=> cleanvars($id_std)
							,'issue_date'				=> cleanvars($issue_date)
							,'due_date'					=> cleanvars($due_date)
							,'scholarship'				=> cleanvars($scholarship)
							,'concession'				=> cleanvars($concession)
							,'fine' 	  				=> cleanvars($fine)
							,'total_amount'				=> cleanvars($_POST['total_amount'])
							,'prev_remaining_amount'	=> cleanvars($rem_amount)
							,'note' 	  				=> cleanvars($_POST['note'])
							,'id_campus' 	  			=> cleanvars($id_campus)
							,'id_added' 	  			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,'date_added' 	  			=> date('Y-m-d H:i:s')
						);
		$sqllms = $dblms->Insert(FEES , $values);

		// LATEST ID
		$idsetup = $dblms->lastestid();

		// FEE PARTICULARS DETAIL
		if($sqllms){
			for($i=1; $i<= count($_POST['id_cat']); $i++){
				$values = array(
									 'id_fee'	=> cleanvars($idsetup)
									,'id_cat'	=> cleanvars($_POST['id_cat'][$i])
									,'amount'	=> cleanvars($_POST['amount'][$i])
								);
				$sqllms = $dblms->Insert(FEE_PARTICULARS , $values);
			}
			// REMARKS
			sendRemark("Single Fee Challans Genrated Challan: ".cleanvars($challano)." detail", '1');			
			sessionMsg("Successfully", "Record Successfully Added.", "success");
			header("Location: fee_challans.php", true, 301);
			exit();
		}
	}
}

// UPDATE SINGLE FEE CHALLAN
if(isset($_POST['changes_challan'])){
	$id_campus = (isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus']: $_SESSION['userlogininfo']['LOGINCAMPUS'];
	if($_POST['status'] == 1){
		$paidAmount = $_POST['payable'];
		if(!empty($_POST['paid_date'])){
			$paidDate = date('Y-m-d' , strtotime($_POST['paid_date']));
		}else{
			$paidDate = date('Y-m-d');
		}
	}else{
		$paidAmount = 0;
		$paidDate = "0000-00-00";
	}
	
	$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));

	if($_POST['status'] == 1){
		// UPDATE CHALLAN AS PAID
		$values = array (
							 "status"			=> cleanvars($_POST['status'])
							,"pay_mode"			=> cleanvars($_POST['pay_mode'])
							,"paid_date"		=> cleanvars($paidDate)
							,"paid_amount"		=> cleanvars($paidAmount)
							,"note"				=> cleanvars($_POST['note'])
							,"id_collector"		=> cleanvars($_POST['id_collector'])
							,"id_modify"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_modify"		=> date('Y-m-d h:i:s')
						);	
		$sqllms = $dblms->Update(FEES , $values , "WHERE id = '".cleanvars($_POST['id_fee'])."'");

		// LATEST ID
		$latestID = cleanvars($_POST['id_fee']);

		if($sqllms){
			$remarks = 'Fee Challan Paid.';
			// GET FEE HEAD FROM ACCOUNT HEADS
			$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
			$values_trans_head = mysqli_fetch_array($sqllms_head);

			// ADD INCOME
			$values = array (
								 "trans_status"		=> '1'
								,"trans_title"		=> cleanvars($_POST['challan_no'])
								,"trans_type"		=> cleanvars($_POST['pay_mode'])
								,"trans_amount"		=> cleanvars($paidAmount)
								,"voucher_no"		=> cleanvars($_POST['challan_no'])
								,"trans_method"		=> cleanvars($_POST['pay_mode'])
								,"trans_note"		=> cleanvars($_POST['note'])
								,"dated"			=> cleanvars($paidDate)
								,"id_head"			=> cleanvars($values_trans_head['head_id'])
								,"id_campus"		=> cleanvars($id_campus)
								,"id_added"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,"date_added"		=> date('Y-m-d h:i:s')
							);	
			$sqlIncome  = $dblms->insert(ACCOUNT_TRANS, $values);
			
			// MAKE ACCOUNT LOG
			$values = array (
								 "id_user"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
								,"action"		=> '1'
								,"challan_no"	=> cleanvars($_POST['challan_no'])
								,"dated"		=> date('Y-m-d h:i:s')
								,"ip"			=> cleanvars($ip)
								,"remarks"		=> cleanvars($remarks)
								,"id_campus"	=> cleanvars($id_campus)
							);
			$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);

			// REMARKS
			sendRemark("Fee Challan updated with ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Challan Successfully Paid.", "info");
			header("Location: fee_challans.php", true, 301);
			exit();
		}
	}else{
		// UPDATE CHALLAN
		$values = array (
							 "due_date"			=> cleanvars($due_date)
							,"note"				=> cleanvars($_POST['note'])
							,"id_modify"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
							,"date_modify"		=> date('Y-m-d h:i:s')
						);		
		$sqllms = $dblms->Update(FEES , $values , "WHERE id = '".cleanvars($_POST['id_fee'])."'");
		
		// LATEST ID
		$latestID = cleanvars($_POST['id_fee']);
		// REMARKS
		if($sqllms){
			sendRemark("Fee Challan updated with ID: ".$latestID." Detail", '2');
			sessionMsg("Success", "Record Successfully Updated.", "info");
			header("Location: fee_challans.php", true, 301);
			exit();
		}
	}
}

// CHANGE DUE DATE ONLY
if(isset($_POST['changes_due_date'])){
	$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
	$values = array (
						 "due_date"		=> cleanvars($due_date)
						,"id_modify"	=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,"date_modify"	=> date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(FEES , $values , "WHERE id = '".cleanvars($_POST['id_fee'])."'");
	// LATEST ID
	$latestID = cleanvars($_POST['id_fee']);
	// REMARKS
	if($sqllms){
		sendRemark("Fee Challan Due Date Updated with ID: ".$latestID." Detail", '2');
		sessionMsg("Success", "Record Successfully Updated.", "info");
		header("Location: fee_challans.php", true, 301);
		exit();
	}
}

// ADD PAYMENT FEE CHALLAN
if(isset($_POST['save_and_print']) || isset($_POST['save_only'])){
	$id_campus = (isset($_POST['id_campus']) && !empty($_POST['id_campus'])) ? $_POST['id_campus']: $_SESSION['userlogininfo']['LOGINCAMPUS'];
	$hrefLac = '';
	$challan_no_Comma = '';
	$chl_array = array();

	if(!empty($_POST['totaltransamount'])){
		if(!empty($_POST['paid_date'])){
			$paidDate = date('Y-m-d' , strtotime($_POST['paid_date']));
		}else{
			$paidDate = date('Y-m-d');
		}
	}else{
		$paidDate = "0000-00-00";
	}
	
	$due_date = date('Y-m-d' , strtotime($_POST['due_date']));
	$grandTotal = $_POST['grandTotal'];

	// FULL PAYMENT
	if($_POST['totaltransamount'] >= $grandTotal){
		$totaltransamount = $_POST['totaltransamount'];
		
		// UPDATE PREVIOUS MONTH CHALLAN
		$sqlnar  = $dblms->querylms("SELECT f.id, f.challan_no, f.total_amount, f.paid_amount, f.due_date, f.scholarship, f.concession
										FROM ".FEES." f
										WHERE f.due_date   <= '".cleanvars($due_date)."'
										AND f.id_month	   != '".cleanvars($_POST['id_month'])."'
										AND f.id_std		= '".cleanvars($_POST['id_std'])."'
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
					if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 1) {
						$payable += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
					} else if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 2) {
						if ($due_date_after_five_day > date('Y-m-d')) {
							$payable += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
						} else if ($due_date_after_five_day < date('Y-m-d')) {
							$payable += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][2];
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
									,"pay_mode"			=> cleanvars($_POST['pay_mode'])
									,"paid_date"		=> cleanvars($paidDate)
									,"paid_amount"		=> cleanvars($final_paid)
									,"note"				=> cleanvars($_POST['note'])
									,"id_collector"		=> cleanvars($_POST['id_collector'])
									,"id_modify"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,"date_modify"		=> date('Y-m-d h:i:s')
								);	
				$sqllmsUpdatePrev  = $dblms->Update(FEES , $values , "WHERE challan_no = '".cleanvars($rownar['challan_no'])."'");

				if($sqllmsUpdatePrev){
					// UPDATE REMAINING BALANCE
					$totaltransamount = $totaltransamount - $payable;

					// GET FEE HEAD FROM ACCOUNT HEADS
					$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".$id_campus."' AND head_name LIKE '%fee%'");
					$values_trans_head = mysqli_fetch_array($sqllms_head);

					$remarks = 'Fee Challan Paid';

					// Add INCOME
					$values = array (
										 "trans_status"		=> '1'
										,"trans_title"		=> cleanvars($rownar['challan_no'])
										,"trans_type"		=> cleanvars($_POST['pay_mode'])
										,"trans_amount"		=> cleanvars($payable)
										,"voucher_no"		=> cleanvars($rownar['challan_no'])
										,"trans_method"		=> cleanvars($_POST['pay_mode'])
										,"trans_note"		=> cleanvars($_POST['note'])
										,"dated"			=> cleanvars($paidDate)
										,"id_head"			=> cleanvars($values_trans_head['head_id'])
										,"id_campus"		=> cleanvars($id_campus)
										,"id_added"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
										,"date_added"		=> date('Y-m-d h:i:s')
									);		
					$sqlIncome = $dblms->insert(ACCOUNT_TRANS, $values);
					
					// Make Account Log
					$values = array (
										 "id_user"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
										,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
										,"action"		=> '1'
										,"challan_no"	=> cleanvars($rownar['challan_no'])
										,"dated"		=> date('Y-m-d h:i:s')
										,"ip"			=> cleanvars($ip)
										,"remarks"		=> cleanvars($remarks)
										,"id_campus"	=> cleanvars($id_campus)
									);		
					$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);

					// PAY CHALLAN FEE CATEGORY WISE
					$sqlnarPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
														FROM ".FEE_PARTICULARS." fp
														INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
														LEFT JOIN ".FEE_PARTICULARSPAID." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
														WHERE fp.id_fee		= '".cleanvars($rownar['id'])."'
														AND fp.amount	   != '0'
														AND NOT EXISTS (
															SELECT fpp.*
															FROM ".FEE_PARTICULARSPAID." fpp
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
							$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID, $values);
						}
					}

					// LOG
					sendRemark("Fee Challan Paid with ID: ".$rownar['id']." Detail", '2');
					array_push($chl_array, cleanvars($rownar['challan_no']));
				}
			}
		}	
		
		// UPDATE CURRENT MONTH CHALLAN
		if($totaltransamount >= 0){
			$final_paid = $totaltransamount + $_POST['paid_amount'];

			// SCHOLARSHIP SEPERATE ID, AMOUNT
			$slrArray 			= explode(',',$_POST['scholarship']);
			$scholarship 		= $slrArray[0];
			$id_scholarship 	= $slrArray[1];
			// CONCESSION SEPERATE ID, AMOUNT
			$conArray 			= explode(',',$_POST['concession']);
			$concession 		= $conArray[0];
			$id_concession 		= $conArray[1];

			$values = array (
								 "status"			=> '1'
								,"pay_mode"			=> cleanvars($_POST['pay_mode'])
								,"paid_date"		=> cleanvars($paidDate)
								,"paid_amount"		=> cleanvars($final_paid)
								,"note"				=> cleanvars($_POST['note'])
								,"id_collector"		=> cleanvars($_POST['id_collector'])
								,"id_modify"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,"date_modify"		=> date('Y-m-d h:i:s')
							);	
			$sqllmsupdate  = $dblms->Update(FEES , $values , "WHERE challan_no = '".cleanvars($_POST['challan_no'])."'");

			if($sqllmsupdate){
				
				// GET FEE HEAD FROM ACCOUNT HEADS
				$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
				$values_trans_head = mysqli_fetch_array($sqllms_head);

				$remarks = 'Fee Challan Paid';

				// ADD INCOME
				$values = array (
									 "trans_status"		=> '1'
									,"trans_title"		=> cleanvars($_POST['challan_no'])
									,"trans_type"		=> cleanvars($_POST['pay_mode'])
									,"trans_amount"		=> cleanvars($totaltransamount)
									,"voucher_no"		=> cleanvars($_POST['challan_no'])
									,"trans_method"		=> cleanvars($_POST['pay_mode'])
									,"trans_note"		=> cleanvars($_POST['note'])
									,"dated"			=> cleanvars($paidDate)
									,"id_head"			=> cleanvars($values_trans_head['head_id'])
									,"id_campus"		=> cleanvars($id_campus)
									,"id_added"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,"date_added"		=> date('Y-m-d h:i:s')
								);		
				$sqlIncome  = $dblms->insert(ACCOUNT_TRANS, $values);
				
				// MAKE LOG				
				$values = array (
									 "id_user"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
									,"action"		=> '1'
									,"challan_no"	=> cleanvars($_POST['challan_no'])
									,"dated"		=> date('Y-m-d h:i:s')
									,"ip"			=> cleanvars($ip)
									,"remarks"		=> cleanvars($remarks)
									,"id_campus"	=> cleanvars($id_campus)
								);		
				$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);

				// PAY CHALLAN FEE CATEGORY WISE
				$sqlCrntPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
													FROM ".FEE_PARTICULARS." fp
													INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
													LEFT JOIN ".FEE_PARTICULARSPAID." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
													WHERE fp.id_fee		= '".cleanvars($_POST['id_fee'])."'
													AND fp.amount	   != '0'
													AND NOT EXISTS (
														SELECT fpp.*
														FROM ".FEE_PARTICULARSPAID." fpp
														WHERE fpp.id_cat	= fp.id_cat
														AND fpp.id_fee		= '".cleanvars($_POST['id_fee'])."'
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
											,"id_fee"			=> cleanvars($_POST['id_fee'])
											,"id_cat"			=> cleanvars($rowCrntPart['id_cat'])
											,"paid_amount"		=> cleanvars($head_paid_amount)
											,"paid_date"		=> date('Y-m-d')
										);		
						$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID, $values);
					}
				}

				// REMARKS
				sendRemark("Fee Challan Paid with ID: ".$_POST['id_fee']." Detail", '2');

				array_push($chl_array, cleanvars($_POST['challan_no']));
				$challan_no_Comma = implode(',',$chl_array);
				$hrefLac = (isset($_POST['save_and_print']))? 'feechallanprint.php?id='.cleanvars($challan_no_Comma).'&id_campus='.$id_campus.'': 'fee_challans.php';

				sessionMsg("Success", "Challan Successfully Paid.", "info");
				header("Location: ".$hrefLac."", true, 301);
				exit();
			}
		}
	}

	// PARTIAL PAYMENT
	elseif($_POST['totaltransamount'] > 0){
		$totaltransamount = $_POST['totaltransamount'];
		
		// Update Previous pending Challans as Paid or Partial Paid
		$sqlnar  = $dblms->querylms("SELECT f.id, f.challan_no, f.total_amount, f.paid_amount, f.due_date, f.scholarship, f.concession
										FROM ".FEES." f
										WHERE f.due_date   <= '".cleanvars($due_date)."'
										AND f.id_month	   != '".cleanvars($_POST['id_month'])."'
										AND f.id_std		= '".cleanvars($_POST['id_std'])."'
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
					if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 1) {
						$payable += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
					} else if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 2) {
						if ($due_date_after_five_day > date('Y-m-d')) {
							$payable += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
						} else if ($due_date_after_five_day < date('Y-m-d')) {
							$payable += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][2];
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
										,"pay_mode"			=> cleanvars($_POST['pay_mode'])
										,"paid_date"		=> cleanvars($paidDate)
										,"paid_amount"		=> cleanvars($final_paid)
										,"note"				=> cleanvars($_POST['note'])
										,"id_collector"		=> cleanvars($_POST['id_collector'])
										,"id_modify"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
										,"date_modify"		=> date('Y-m-d h:i:s')
									);	
					$sqllmsUpdatePrev  = $dblms->Update(FEES , $values , "WHERE challan_no = '".cleanvars($rownar['challan_no'])."'");
					
					if($sqllmsUpdatePrev){
						$totaltransamount = $totaltransamount - $payable;

						// GET FEE HEAD FROM ACCOUNT HEADS
						$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
						$values_trans_head = mysqli_fetch_array($sqllms_head);

						$remarks = 'Fee Challan Paid';

						// Add INCOME
						$values = array (
											 "trans_status"		=> '1'
											,"trans_title"		=> cleanvars($rownar['challan_no'])
											,"trans_type"		=> cleanvars($_POST['pay_mode'])
											,"trans_amount"		=> cleanvars($payable)
											,"voucher_no"		=> cleanvars($rownar['challan_no'])
											,"trans_method"		=> cleanvars($_POST['pay_mode'])
											,"trans_note"		=> cleanvars($_POST['note'])
											,"dated"			=> cleanvars($paidDate)
											,"id_head"			=> cleanvars($values_trans_head['head_id'])
											,"id_campus"		=> cleanvars($id_campus)
											,"id_added"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
											,"date_added"		=> date('Y-m-d h:i:s')
										);		
						$sqlIncome  = $dblms->insert(ACCOUNT_TRANS, $values);
						
						// MAKE LOG
						$values = array (
											 "id_user"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
											,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
											,"action"		=> '1'
											,"challan_no"	=> cleanvars($rownar['challan_no'])
											,"dated"		=> date('Y-m-d h:i:s')
											,"ip"			=> cleanvars($ip)
											,"remarks"		=> cleanvars($remarks)
											,"id_campus"	=> cleanvars($id_campus)
										);		
						$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);

						// PAY CHALLAN FEE CATEGORY WISE
						$sqlnarPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
															FROM ".FEE_PARTICULARS." fp
															INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
															LEFT JOIN ".FEE_PARTICULARSPAID." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
															WHERE fp.id_fee		= '".cleanvars($rownar['id'])."'
															AND fp.amount	   != '0'
															AND NOT EXISTS (
																SELECT fpp.*
																FROM ".FEE_PARTICULARSPAID." fpp
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
								$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID, $values);
							}
						}
						
						// LOG
						sendRemark("Fee Challan Paid with ID: ".$rownar['id']." Detail", '2');
						array_push($chl_array, cleanvars($rownar['challan_no']));
					}
				}

				// PARTIAL PAID
				elseif($totaltransamount>0){

					$payable = $totaltransamount;
					$final_paid = $payable + $rownar['paid_amount'];
					// Update pending as Partial Paid
					$values = array (
										 "status"			=> '4'
										,"pay_mode"			=> cleanvars($_POST['pay_mode'])
										,"paid_date"		=> cleanvars($paidDate)
										,"paid_amount"		=> cleanvars($final_paid)
										,"note"				=> cleanvars($_POST['note'])
										,"id_collector"		=> cleanvars($_POST['id_collector'])
										,"id_modify"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
										,"date_modify"		=> date('Y-m-d h:i:s')
									);
					$sqllmsUpdatePrev  = $dblms->Update(FEES , $values , "WHERE challan_no = '".cleanvars($rownar['challan_no'])."'");
					
					if($sqllmsUpdatePrev){
						$totaltransamount = $totaltransamount - $payable;

						// GET FEE HEAD FROM ACCOUNT HEADS
						$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
						$values_trans_head = mysqli_fetch_array($sqllms_head);

						$remarks = 'Fee Challan Paid';
						// ADD INCOME
						$values = array (
											 "trans_status"		=> '1'
											,"trans_title"		=> cleanvars($rownar['challan_no'])
											,"trans_type"		=> cleanvars($_POST['pay_mode'])
											,"trans_amount"		=> cleanvars($payable)
											,"voucher_no"		=> cleanvars($rownar['challan_no'])
											,"trans_method"		=> cleanvars($_POST['pay_mode'])
											,"trans_note"		=> cleanvars($_POST['note'])
											,"dated"			=> cleanvars($paidDate)
											,"id_head"			=> cleanvars($values_trans_head['head_id'])
											,"id_campus"		=> cleanvars($id_campus)
											,"id_added"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
											,"date_added"		=> date('Y-m-d h:i:s')
										);		
						$sqlIncome  = $dblms->insert(ACCOUNT_TRANS, $values);
						
						// MAKE LOG
						$values = array (
											 "id_user"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
											,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
											,"action"		=> '1'
											,"challan_no"	=> cleanvars($rownar['challan_no'])
											,"dated"		=> date('Y-m-d h:i:s')
											,"ip"			=> cleanvars($ip)
											,"remarks"		=> cleanvars($remarks)
											,"id_campus"	=> cleanvars($id_campus)
										);		
						$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);

						// PAY CHALLAN FEE CATEGORY WISE
						$sqlnarPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
															FROM ".FEE_PARTICULARS." fp
															INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
															LEFT JOIN ".FEE_PARTICULARSPAID." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
															WHERE fp.id_fee		= '".cleanvars($rownar['id'])."'
															AND fp.amount	   != '0'
															AND NOT EXISTS (
																SELECT fpp.*
																FROM ".FEE_PARTICULARSPAID." fpp
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
									$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID, $values);

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
									$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID, $values);
									
									$payable -= $head_partial_paid;
								}
							}
						}
						
						// LOG
						sendRemark("Fee Challan Partially Paid with ID: ".$rownar['id']." Detail", '2');
						array_push($chl_array, cleanvars($rownar['challan_no']));
					}
				}
			}
		}

		// Update Current pending Challans as Paid or Partial Paid
		$payable = $_POST['total_amount'] - $_POST['paid_amount'];

		if(date('Y-m-d') > $_POST['due_date']) {
			$due_date_after_five_day = date('Y-m-d', strtotime($_POST['due_date']. ' + 5 days'));
			if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 1) {
				$payable += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
			} else if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 2) {
				if ($due_date_after_five_day > date('Y-m-d')) {
					$payable += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
				} else if ($due_date_after_five_day < date('Y-m-d')) {
					$payable += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][2];
				} else {
					$payable += LATEFEE;	
				}
			} else {
				$payable += LATEFEE;
			} 
		}

		// SCHOLARSHIP SEPERATE ID, AMOUNT
		$slrArray 			= explode(',',$_POST['scholarship']);
		$scholarship 		= $slrArray[0];
		$id_scholarship 	= $slrArray[1];
		// CONCESSION SEPERATE ID, AMOUNT
		$conArray 			= explode(',',$_POST['concession']);
		$concession 		= $conArray[0];
		$id_concession 		= $conArray[1];

		// FULL PAID
		if($totaltransamount>=$payable){
			$payable = $totaltransamount;
			$final_paid = $payable + $_POST['paid_amount'];
			// Update Pending as Paid
			$values = array (
								 "status"			=> '1'
								,"pay_mode"			=> cleanvars($_POST['pay_mode'])
								,"paid_date"		=> cleanvars($paidDate)
								,"paid_amount"		=> cleanvars($final_paid)
								,"note"				=> cleanvars($_POST['note'])
								,"id_collector"		=> cleanvars($_POST['id_collector'])
								,"id_modify"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,"date_modify"		=> date('Y-m-d h:i:s')
							);	
			$sqllmsupdate  = $dblms->Update(FEES , $values , "WHERE challan_no = '".cleanvars($_POST['challan_no'])."'");
			
			if($sqllmsupdate){				
				// GET FEE HEAD FROM ACCOUNT HEADS
				$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
				$values_trans_head = mysqli_fetch_array($sqllms_head);
	
				$remarks = 'Fee Challan Paid';
				// ADD INCOME
				$values = array (
									 "trans_status"		=> '1'
									,"trans_title"		=> cleanvars($_POST['challan_no'])
									,"trans_type"		=> cleanvars($_POST['pay_mode'])
									,"trans_amount"		=> cleanvars($payable)
									,"voucher_no"		=> cleanvars($_POST['challan_no'])
									,"trans_method"		=> cleanvars($_POST['pay_mode'])
									,"trans_note"		=> cleanvars($_POST['note'])
									,"dated"			=> cleanvars($paidDate)
									,"id_head"			=> cleanvars($values_trans_head['head_id'])
									,"id_campus"		=> cleanvars($id_campus)
									,"id_added"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,"date_added"		=> date('Y-m-d h:i:s')
								);		
				$sqlIncome  = $dblms->insert(ACCOUNT_TRANS, $values);
				
				// MAKE LOG
				$values = array (
									 "id_user"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
									,"action"		=> '1'
									,"challan_no"	=> cleanvars($_POST['challan_no'])
									,"dated"		=> date('Y-m-d h:i:s')
									,"ip"			=> cleanvars($ip)
									,"remarks"		=> cleanvars($remarks)
									,"id_campus"	=> cleanvars($id_campus)
								);		
				$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);

				// PAY CHALLAN FEE CATEGORY WISE
				$sqlnarPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
													FROM ".FEE_PARTICULARS." fp
													INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
													LEFT JOIN ".FEE_PARTICULARSPAID." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
													WHERE fp.id_fee		= '".cleanvars($_POST['id_fee'])."'
													AND fp.amount	   != '0'
													AND NOT EXISTS (
														SELECT fpp.*
														FROM ".FEE_PARTICULARSPAID." fpp
														WHERE fpp.id_cat	= fp.id_cat
														AND fpp.id_fee		= '".cleanvars($_POST['id_fee'])."'
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
											,"id_fee"			=> cleanvars($_POST['id_fee'])
											,"id_cat"			=> cleanvars($rowCrntPart['id_cat'])
											,"paid_amount"		=> cleanvars($head_paid_amount)
											,"paid_date"		=> date('Y-m-d')
										);		
						$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID, $values);
					}
				}
				
				// REMARKS
				sendRemark("Fee Challan Fully Paid with ID: ".$_POST['id_fee']." Detail", '2');

				array_push($chl_array, cleanvars($_POST['challan_no']));
				$challan_no_Comma = implode(',',$chl_array);
				$hrefLac = (isset($_POST['save_and_print']))? 'feechallanprint.php?id='.cleanvars($challan_no_Comma).'&id_campus='.$id_campus.'': 'fee_challans.php';

				sessionMsg("Success", "Payment Successfully Added.", "info");
				header("Location: ".$hrefLac."", true, 301);
				exit();
			}
		}
		
		// PARTIAL PAID
		elseif($totaltransamount>0){
			$payable = $totaltransamount;
			$final_paid = $payable + $_POST['paid_amount'];
			// Update Pending as Partial Paid
			$values = array (
								 "status"			=> '4'
								,"pay_mode"			=> cleanvars($_POST['pay_mode'])
								,"paid_date"		=> cleanvars($paidDate)
								,"paid_amount"		=> cleanvars($final_paid)
								,"note"				=> cleanvars($_POST['note'])
								,"id_collector"		=> cleanvars($_POST['id_collector'])
								,"id_modify"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
								,"date_modify"		=> date('Y-m-d h:i:s')
							);	
			$sqllmsupdate  = $dblms->Update(FEES , $values , "WHERE challan_no = '".cleanvars($_POST['challan_no'])."'");
			
			if($sqllmsupdate){				
				// GET FEE HEAD FROM ACCOUNT HEADS
				$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
				$values_trans_head = mysqli_fetch_array($sqllms_head);
	
				$remarks = 'Fee Challan Paid';
				// ADD INCOME
				$values = array (
									 "trans_status"		=> '1'
									,"trans_title"		=> cleanvars($_POST['challan_no'])
									,"trans_type"		=> cleanvars($_POST['pay_mode'])
									,"trans_amount"		=> cleanvars($payable)
									,"voucher_no"		=> cleanvars($_POST['challan_no'])
									,"trans_method"		=> cleanvars($_POST['pay_mode'])
									,"trans_note"		=> cleanvars($_POST['note'])
									,"dated"			=> cleanvars($paidDate)
									,"id_head"			=> cleanvars($values_trans_head['head_id'])
									,"id_campus"		=> cleanvars($id_campus)
									,"id_added"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,"date_added"		=> date('Y-m-d h:i:s')
								);		
				$sqlIncome = $dblms->insert(ACCOUNT_TRANS, $values);
				
				// MAKE LOG
				$values = array (
									 "id_user"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
									,"action"		=> '1'
									,"challan_no"	=> cleanvars($_POST['challan_no'])
									,"dated"		=> date('Y-m-d h:i:s')
									,"ip"			=> cleanvars($ip)
									,"remarks"		=> cleanvars($remarks)
									,"id_campus"	=> cleanvars($id_campus)
								);		
				$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);

				// PAY CHALLAN FEE CATEGORY WISE
				$sqlCrntPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
													FROM ".FEE_PARTICULARS." fp
													INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
													LEFT JOIN ".FEE_PARTICULARSPAID." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
													WHERE fp.id_fee		= '".cleanvars($_POST['id_fee'])."'
													AND fp.amount	   != '0'
													AND NOT EXISTS (
														SELECT fpp.*
														FROM ".FEE_PARTICULARSPAID." fpp
														WHERE fpp.id_cat	= fp.id_cat
														AND fpp.id_fee		= '".cleanvars($_POST['id_fee'])."'
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
												,"id_fee"			=> cleanvars($_POST['id_fee'])
												,"id_cat"			=> cleanvars($rowCrntPart['id_cat'])
												,"paid_amount"		=> cleanvars($head_paid_amount)
												,"paid_date"		=> date('Y-m-d')
											);		
							$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID, $values);

							$payable -= $head_paid_amount;
						}

						elseif($payable > 0 && $payable < $head_paid_amount){
							$head_partial_paid = $payable;
							// ADD HEAD WISE PAYMENT LOG - PARTIAL PAID
							$values = array (
												 "status"			=> '4'
												,"id_fee"			=> cleanvars($_POST['id_fee'])
												,"id_cat"			=> cleanvars($rowCrntPart['id_cat'])
												,"paid_amount"		=> cleanvars($head_partial_paid)
												,"paid_date"		=> date('Y-m-d')
											);		
							$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID, $values);
							
							$payable -= $head_partial_paid;
						}						
					}
				}
				
				// REMARKS
				sendRemark("Fee Challan Partially Paid with ID: ".$_POST['id_fee']." Detail", '2');

				array_push($chl_array, cleanvars($_POST['challan_no']));
				$challan_no_Comma = implode(',',$chl_array);
				$hrefLac = (isset($_POST['save_and_print']))? 'feechallanprint.php?id='.cleanvars($challan_no_Comma).'&id_campus='.$id_campus.'': 'fee_challans.php';

				sessionMsg("Success", "Partial Payment Successfully Added.", "info");
				header("Location: ".$hrefLac."", true, 301);
				exit();
			}
		}

		// STILL PENDING
		else{
			// REMARKS
			sendRemark("Fee Challan Updated with ID: ".$_POST['id_fee']." Detail", '2');

			$challan_no_Comma = implode(',',$chl_array);
			$hrefLac = (isset($_POST['save_and_print']))? 'feechallanprint.php?id='.cleanvars($challan_no_Comma).'&id_campus='.$id_campus.'': 'fee_challans.php';

			sessionMsg("Success", "Partial Payment Successfully Added.", "info");
			header("Location: ".$hrefLac."", true, 301);
			exit();
		}
	}

	// STILL PENDING
	else{
		// REMARKS
		sendRemark("Fee Challan Updated with ID: ".$_POST['id_fee']." Detail", '2');
		sessionMsg("Success", "No Payment Added.", "info");
		header("Location: ".$hrefLac."", true, 301);
		exit();
	}
}

// ADD BY SIBLINGS PAYMENT FEE CHALLAN
if(isset($_POST['siblings_save_and_print']) || isset($_POST['siblings_save_only'])){
	$std_familyno 		= cleanvars($_POST['std_familyno']);
	$totaltransamount 	= $_POST['totaltransamount'];
	$grandTotal 		= $_POST['grandTotal'];
	$hrefLac 			= '';
	$challan_no_Comma 	= '';
	$chl_array 			= array();
	

	if(!empty($totaltransamount)){
		if(!empty($_POST['paid_date'])){
			$paidDate = date('Y-m-d' , strtotime($_POST['paid_date']));
		}else{
			$paidDate = date('Y-m-d');
		}
	}else{
		$paidDate = "0000-00-00";
	}

	$condition = array(
                         'select'       =>  'f.id, f.status, f.id_type, f.challan_no, f.issue_date, f.due_date, f.paid_date, f.scholarship, f.concession, f.fine, f.total_amount, f.paid_amount, f.remaining_amount, f.note, f.id_session, f.id_month, f.id_campus, s.std_id, s.std_name, s.std_phone, s.std_fathername, c.class_id,c.class_name'
                        ,'join'         => 'INNER JOIN	'.FEES.' AS f ON (s.std_id = f.id_std AND f.id_type IN (1,2) AND f.is_deleted = 0 AND f.id_campus '.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'IN ('.$_SESSION['userlogininfo']['LOGINCAMPUS'].','.$_SESSION['userlogininfo']['SUBCAMPUSES'].')' : ' = '.$id_campus).')
                                            INNER JOIN '.CLASSES.' c ON c.class_id = s.id_class'
                        ,'where'        =>  array(  
                                                     's.is_deleted'     => 0
                                                    ,'s.std_familyno'   => $std_familyno
                                            )
                        ,'search_by'    =>  ' AND f.status IN (2,4)'
                        ,'order_by'     =>  ' f.id ASC'
                        ,'return_type'  =>  'all'
    );
    $STUDENTS = $dblms->getRows(STUDENTS.' AS s',$condition,$sql);
	if ($STUDENTS) {
		foreach ($STUDENTS as $key => $value) {
			$id_campus = $_POST['id_campus'][$key];
			$payable = $value['total_amount'] - $value['paid_amount'];

			// SCHOLARSHIP SEPERATE ID, AMOUNT
			if (!empty($value['scholarship'])) {
				$slrArray 			= explode(',',$value['scholarship']);
				$scholarship 		= $slrArray[0];
				$id_scholarship 	= $slrArray[1];
			} else {
				$scholarship 		= 0;
				$id_scholarship 	= 0;
			}
			// CONCESSION SEPERATE ID, AMOUNT
			if (!empty($value['concession'])) {
				$conArray 			= explode(',',$value['concession']);
				$concession 		= $conArray[0];
				$id_concession 		= $conArray[1];
			} else {
				$concession 		= 0;
				$id_concession 		= 0;
			}

			if(date('Y-m-d') > $value['due_date']) {
				$due_date_after_five_day = date('Y-m-d', strtotime($value['due_date']. ' + 5 days'));
				if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 1) {
					$payable += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
				} else if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 2) {
					if ($due_date_after_five_day > date('Y-m-d')) {
						$payable += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
					} else if ($due_date_after_five_day < date('Y-m-d')) {
						$payable += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][2];
					} else {
						$payable += LATEFEE;	
					}
				} else {
					$payable += LATEFEE;
				} 
			}

			// FULL PAID
			if($totaltransamount >= $payable){
				$final_paid = $payable + $value['paid_amount'];
				//Update pending as Paid					
				$values = array (
										"status"			=> '1'
									,"pay_mode"			=> cleanvars($_POST['pay_mode'])
									,"paid_date"		=> cleanvars($paidDate)
									,"paid_amount"		=> cleanvars($final_paid)
									,"id_collector"		=> cleanvars($_POST['id_collector'])
									,"id_modify"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,"date_modify"		=> date('Y-m-d h:i:s')
								);	
				$sqllmsUpdatePrev  = $dblms->Update(FEES , $values , "WHERE challan_no = '".cleanvars($value['challan_no'])."'");
				
				if($sqllmsUpdatePrev){
					$totaltransamount = $totaltransamount - $payable;

					// GET FEE HEAD FROM ACCOUNT HEADS
					$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
					$values_trans_head = mysqli_fetch_array($sqllms_head);

					$remarks = 'Fee Challan Paid';

					// Add INCOME
					$values = array (
											"trans_status"		=> '1'
										,"trans_title"		=> cleanvars($value['challan_no'])
										,"trans_type"		=> cleanvars($_POST['pay_mode'])
										,"trans_amount"		=> cleanvars($payable)
										,"voucher_no"		=> cleanvars($value['challan_no'])
										,"trans_method"		=> cleanvars($_POST['pay_mode'])
										,"dated"			=> cleanvars($paidDate)
										,"id_head"			=> cleanvars($values_trans_head['head_id'])
										,"id_campus"		=> cleanvars($id_campus)
										,"id_added"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
										,"date_added"		=> date('Y-m-d h:i:s')
									);		
					$sqlIncome  = $dblms->insert(ACCOUNT_TRANS, $values);
					
					// MAKE LOG
					$values = array (
											"id_user"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
										,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
										,"action"		=> '1'
										,"challan_no"	=> cleanvars($value['challan_no'])
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
														LEFT JOIN ".FEE_PARTICULARSPAID." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
														WHERE fp.id_fee		= '".cleanvars($value['id'])."'
														AND fp.amount	   != '0'
														AND NOT EXISTS (
															SELECT fpp.*
															FROM ".FEE_PARTICULARSPAID." fpp
															WHERE fpp.id_cat	= fp.id_cat
															AND fpp.id_fee		= '".cleanvars($value['id'])."'
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
												,"id_fee"			=> cleanvars($value['id'])
												,"id_cat"			=> cleanvars($rownarPart['id_cat'])
												,"paid_amount"		=> cleanvars($head_paid_amount)
												,"paid_date"		=> date('Y-m-d')
											);		
							$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID, $values);
						}
					}
					
					// LOG
					sendRemark("Fee Challan Paid with ID: ".$value['id']." Detail", '2');
					array_push($chl_array, cleanvars($value['challan_no']));
				}
			}

			// PARTIAL PAID
			elseif($totaltransamount>0){
				$payable = $totaltransamount;
				$final_paid = $payable + $value['paid_amount'];
				// Update pending as Partial Paid
				$values = array (
										"status"			=> '4'
									,"pay_mode"			=> cleanvars($_POST['pay_mode'])
									,"paid_date"		=> cleanvars($paidDate)
									,"paid_amount"		=> cleanvars($final_paid)
									,"note"				=> cleanvars($_POST['note'])
									,"id_collector"		=> cleanvars($_POST['id_collector'])
									,"id_modify"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
									,"date_modify"		=> date('Y-m-d h:i:s')
								);
				$sqllmsUpdatePrev  = $dblms->Update(FEES , $values , "WHERE challan_no = '".cleanvars($value['challan_no'])."'");
				
				if($sqllmsUpdatePrev){
					$totaltransamount = $totaltransamount - $payable;

					// GET FEE HEAD FROM ACCOUNT HEADS
					$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".cleanvars($id_campus)."' AND head_name LIKE '%fee%'");
					$values_trans_head = mysqli_fetch_array($sqllms_head);

					$remarks = 'Fee Challan Paid';
					// ADD INCOME
					$values = array (
											"trans_status"		=> '1'
										,"trans_title"		=> cleanvars($value['challan_no'])
										,"trans_type"		=> cleanvars($_POST['pay_mode'])
										,"trans_amount"		=> cleanvars($payable)
										,"voucher_no"		=> cleanvars($value['challan_no'])
										,"trans_method"		=> cleanvars($_POST['pay_mode'])
										,"trans_note"		=> cleanvars($_POST['note'])
										,"dated"			=> cleanvars($paidDate)
										,"id_head"			=> cleanvars($values_trans_head['head_id'])
										,"id_campus"		=> cleanvars($id_campus)
										,"id_added"			=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
										,"date_added"		=> date('Y-m-d h:i:s')
									);		
					$sqlIncome  = $dblms->insert(ACCOUNT_TRANS, $values);
					
					// MAKE LOG
					$values = array (
											"id_user"		=> cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
										,"filename"		=> strstr(basename($_SERVER['REQUEST_URI']), '.php', true)
										,"action"		=> '1'
										,"challan_no"	=> cleanvars($value['challan_no'])
										,"dated"		=> date('Y-m-d h:i:s')
										,"ip"			=> cleanvars($ip)
										,"remarks"		=> cleanvars($remarks)
										,"id_campus"	=> cleanvars($id_campus)
									);		
					$sqllmslog  = $dblms->insert(ACCOUNTS_LOGS, $values);

					// PAY CHALLAN FEE CATEGORY WISE
					$sqlnarPart  = $dblms->querylms("SELECT fp.*, SUM(fpp.paid_amount) paid_amount
														FROM ".FEE_PARTICULARS." fp
														INNER JOIN ".FEE_CATEGORY." fc ON fc.cat_id = fp.id_cat
														LEFT JOIN ".FEE_PARTICULARSPAID." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
														WHERE fp.id_fee		= '".cleanvars($value['id'])."'
														AND fp.amount	   != '0'
														AND NOT EXISTS (
															SELECT fpp.*
															FROM ".FEE_PARTICULARSPAID." fpp
															WHERE fpp.id_cat	= fp.id_cat
															AND fpp.id_fee		= '".cleanvars($value['id'])."'
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
													,"id_fee"			=> cleanvars($value['id'])
													,"id_cat"			=> cleanvars($rownarPart['id_cat'])
													,"paid_amount"		=> cleanvars($head_paid_amount)
													,"paid_date"		=> date('Y-m-d')
												);		
								$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID, $values);

								$payable -= $head_paid_amount;
							}

							elseif($payable > 0 && $payable < $head_paid_amount){
								$head_partial_paid = $payable;
								// ADD HEAD WISE PAYMENT LOG - PARTIAL PAID
								$values = array (
														"status"			=> '4'
													,"id_fee"			=> cleanvars($value['id'])
													,"id_cat"			=> cleanvars($rownarPart['id_cat'])
													,"paid_amount"		=> cleanvars($head_partial_paid)
													,"paid_date"		=> date('Y-m-d')
												);		
								$sqlHeadPay = $dblms->insert(FEE_PARTICULARSPAID, $values);
								
								$payable -= $head_partial_paid;
							}
						}
					}
					
					// LOG
					sendRemark("Fee Challan Partially Paid with ID: ".$value['id']." Detail", '2');
					array_push($chl_array, cleanvars($value['challan_no']));
				}
			}
		}
		$challan_no_Comma = implode(',',$chl_array);
		if (isset($_POST['siblings_save_and_print'])) {
			$hrefLac = 'feechallanprint.php?id='.cleanvars($challan_no_Comma).'&id_campus='.$id_campus.'';
			header("Location: ".$hrefLac."", true, 301);
			exit();
		} else {
			$hrefLac = 'fee_challans.php';
			sessionMsg("Success", "Payment Successfully Added.", "info");
			header("Location: ".$hrefLac."", true, 301);
			exit();
		}
	}
}

// DELETE RECORD
if(isset($_GET['deleteid'])){
	$values = array (
						 'is_deleted'	=>	'1'
						,'id_deleted'	=>	cleanvars($_SESSION['userlogininfo']['LOGINIDA'])
						,'ip_deleted'	=>	cleanvars($ip)
						,'date_deleted'	=>	date('Y-m-d h:i:s')
					);		
	$sqllms = $dblms->Update(FEES , $values , "WHERE id = '".cleanvars($_GET['deleteid'])."'");
	if($sqllms) { 
		sendRemark("Fee Challan Deleted ID: ".cleanvars($_GET['deleteid'])." details", '3');
		sessionMsg("Success", "Record Deleted.", "success");
		header("Location: fee_challans.php", true, 301);
		exit();
	}
}
?>