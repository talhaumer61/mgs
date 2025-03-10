<?php 
//---------------- Bulk Royalty Challans Genrate ----------------------
if(isset($_POST['bulk_challans_generate'])) { 
					   
	//------------------------Reformat Date------------------------
	$challandate	= date('Ym');
	$issue_date = date('Y-m-d' , strtotime(cleanvars($_POST['issue_date'])));
	$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
	//------------------------------------------------	

	
	//--------- Get Campuses Have Defualt Royalty ---------	
	$sqllmsCampus	= $dblms->querylms("SELECT campus_id
											FROM ".CAMPUS."
											WHERE campus_status = '1' AND is_deleted != '1'
											AND is_var_royalty = '1'
											ORDER BY campus_id ASC ");
	if(mysqli_num_rows($sqllmsCampus) > 0) {
		while($valueCampus = mysqli_fetch_array($sqllmsCampus)) {

			//----------------------Challan Number-------------------------
			$sqllmschallan 	= $dblms->querylms("SELECT challan_no FROM ".ROYALTY." 
											WHERE challan_no LIKE '".$challandate."%'  
											ORDER by challan_no DESC LIMIT 1 ");
			$rowchallan 	= mysqli_fetch_array($sqllmschallan);
			if(mysqli_num_rows($sqllmschallan) < 1) {
				$challano	= $challandate.'00001';
			} else{
				$challano = ($rowchallan['challan_no'] +1);
			}

			//----------------- Remaining Amount ------------------
			$sqllms_rem = $dblms->querylms("SELECT remaining_amount FROM ".ROYALTY." 
											WHERE roy_id = (SELECT MAX(roy_id) FROM ".ROYALTY." WHERE id_campus = '".cleanvars($valueCampus['campus_id'])."')
											LIMIT 1");
			$row_rem = mysqli_fetch_array($sqllms_rem);
			$rem_amount = $row_rem['remaining_amount'];

			
			//-------- Get Particulars & Amount for Grand Total -------------
			//------------------- Get Particulars -------------------------
			$total_amount = 0;
			$sqllmsParticulars	= $dblms->querylms("SELECT part_id, part_name
												FROM ".ROYALTY_PARTICULARS."
												WHERE part_status = '1'  AND is_deleted != '1' ");
			if(mysqli_num_rows($sqllmsParticulars) > 0){
				while($valuePart = mysqli_fetch_array($sqllmsParticulars)) {

					//------------------- Get Amounts ----------------------
					$sqllmsRoyalty	= $dblms->querylms("SELECT r.id, d.id_particular, d.amount
												FROM ".CAMPUS_ROYALTY." r
												INNER JOIN ".CAMPUS_ROYALTY_DET." d ON d.id_setup = r.id
												WHERE r.id_campus = '".$valueCampus['campus_id']."'
												AND d.id_particular = '".$valuePart['part_id']."' 
												AND r.is_deleted != '1'  
												ORDER BY d.detail_id DESC LIMIT 1");
					if(mysqli_num_rows($sqllmsRoyalty) > 0){
						$valueRoyalty = mysqli_fetch_array($sqllmsRoyalty);
						$value = $valueRoyalty['amount'];
					}
					else{
						$value = 0;
					}

					$royalityDetail[] = $valueRoyalty;
					$total_amount = $total_amount + $value;
				}
			}
			//------------------ Challan Create -------------------
			$sqllmsRoyal  = $dblms->querylms("INSERT INTO ".ROYALTY."(
																roy_status					, 
																roy_detail					, 
																challan_no					, 
																issue_date					,
																due_date					,
																total_amount                ,
																remaining_amount			,
																id_month					,
																id_session					, 
																id_campus					,
																id_added					,
																date_added
															)
														VALUES(
																'2'																,
																'".cleanvars($_POST['roy_detail'])."'							,
																'".cleanvars($challano)."'										,
																'".cleanvars($issue_date)."'									, 
																'".cleanvars($due_date)."'										,
																'".cleanvars($total_amount)."'	        						,
																'".cleanvars($rem_amount)."'									,
																'".cleanvars($_POST['id_month'])."'								,
																'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	, 
																'".cleanvars($valueCampus['campus_id'])."'						,
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
																Now()	
															)"
													);

			//------------------------- Royalty Detail -----------------------
			if($sqllmsRoyal) { 
				//-------------------------Get latest Id----------------------- 
				$idRoyalty = $dblms->lastestid();	
				//-------------- Add Detail ------------------------
				foreach($royalityDetail as $detail){
					echo $detail['id_particular']."=".$detail['amount']."<br>";
					$sqllmsDet = $dblms->querylms("INSERT INTO ".ROYALTY_DETAIL."(
																	id_royalty		,
																	id_particular	,
																	amount						
																)
															VALUES(
																	'".cleanvars($idRoyalty)."'					,
																	'".cleanvars($detail['id_particular'])."'	,
																	'".cleanvars($detail['amount'])."'			
																)
														");
				}

				$remarks = 'Add Bulk Royality Challans of month: "'.cleanvars($_POST['id_month']).'" detail';
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
			}
			//--------------------------------------
			unset($royalityDetail);
		}
	}
	else{
		//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Warning';
		$_SESSION['msg']['text'] 	= 'No Campus Have Variable Royalty.';
		$_SESSION['msg']['type'] 	= 'warning';
		header("Location: royalty.php", true, 301);
		exit();
		//--------------------------------------
	}

	if($sqllmsRoyal) { 
		//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Successfully';
		$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
		$_SESSION['msg']['type'] 	= 'success';
		header("Location: royalty.php", true, 301);
		exit();
		//--------------------------------------
	}
} 

//---------------- Single Royalty Challans Genrate ----------------------
if(isset($_POST['single_challan_generate'])) { 
					   
	//------------------------Reformat Date------------------------
	$challandate	= date('Ym');
	$issue_date = date('Y-m-d' , strtotime(cleanvars($_POST['issue_date'])));
	$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
	//------------------------------------------------	

	//----------------------Challan Number-------------------------
	$sqllmschallan 	= $dblms->querylms("SELECT challan_no FROM ".ROYALTY." 
									WHERE challan_no LIKE '".$challandate."%'  
									ORDER by challan_no DESC LIMIT 1 ");
	$rowchallan 	= mysqli_fetch_array($sqllmschallan);
	if(mysqli_num_rows($sqllmschallan) < 1) {
		$challano	= $challandate.'00001';
	} else  {
		$challano = ($rowchallan['challan_no'] +1);
	}
	//----------------- Remaining Amount ------------------
	$sqllms_rem = $dblms->querylms("SELECT remaining_amount FROM ".ROYALTY." 
									WHERE roy_id = (SELECT MAX(roy_id) FROM ".ROYALTY." WHERE  id_campus = '".cleanvars($_POST['id_campus'])."')
									LIMIT 1");
	$row_rem = mysqli_fetch_array($sqllms_rem);
	$rem_amount = $row_rem['remaining_amount'];

	//----------------==- Total Amount --------------------
		$total_amount = 0;
		for($i=1; $i<= count($_POST['id_particular']); $i++){
			$total_amount = $total_amount + $_POST['amount'][$i];
		}
	//---------------------- Make -------------------------
	$sqllms  = $dblms->querylms("INSERT INTO ".ROYALTY."(
														roy_status					, 
														roy_detail					, 
														challan_no					, 
														issue_date					,
														due_date					,
														total_amount				,
														remaining_amount			,
														id_month					,
														id_session					, 
														id_campus					,
														id_added					,
														date_added
													)
												VALUES(
														'2'																,
														'".cleanvars($_POST['roy_detail'])."'							,
														'".cleanvars($challano)."'										,
														'".cleanvars($issue_date)."'									, 
														'".cleanvars($due_date)."'										,
														'".cleanvars($total_amount)."'									,
														'".cleanvars($rem_amount)."'									,
														'".cleanvars($_POST['id_month'])."'								,
														'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	, 
														'".cleanvars($_POST['id_campus'])."'							,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
														Now()	
													)"
											);

	//-------------------------Royalty Particulars Detail-----------------------
	if($sqllms) { 
		//-------------------------Get latest Id----------------------- 
		$idroyalty = $dblms->lastestid();	
		//-------------- Add Detail ------------------------
			for($i=1; $i<= count($_POST['id_particular']); $i++){
				$sqllms  = $dblms->querylms("INSERT INTO ".ROYALTY_DETAIL."(
																id_royalty		,
																id_particular	,
																amount						
															)
														VALUES(
																'".cleanvars($idroyalty)."'						,
																'".cleanvars($_POST['id_particular'][$i])."'	,
																'".cleanvars($_POST['amount'][$i])."'			
															)
															");

			}

		$remarks = 'Single Royalty Challans Genrated Challan: "'.cleanvars($challano).'", ID: "'.$idroyalty.'" detail';
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
	}
	//--------------------------------------

	if($sqllms) { 
	//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: royalty.php", true, 301);
			exit();
	//--------------------------------------
	}
}

//----------------Update Single Royalty Challan----------------------
if(isset($_POST['changes_royalty'])) { 
		//------------------------------------
		$paidDate = date('Y-m-d');
		$paid_amount = $_POST['paid_amount'];
	if($_POST['paid_amount'] > 0){
		//----------------- Update Chllan as Paid ---------------------
		$sqllms  = $dblms->querylms("UPDATE ".ROYALTY." SET 
												roy_status			= '1'
											,	paid_date			= '".cleanvars($paidDate)."'
											,	paid_amount			= '".cleanvars($paid_amount)."'
											,	remaining_amount	= '".cleanvars($_POST['remaining_amount'])."'
											,	roy_detail			= '".cleanvars($_POST['roy_detail'])."'
											,	id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
											, 	date_modify			= NOW()
										WHERE   roy_id				= '".cleanvars($_POST['id_roy'])."'
											");
		//------------------------------------
		$remaining =  ($_POST['payable'] + $_POST['remaining_amount']) - $paid_amount;   
		//--------------------------------------
		if($sqllms) 
		{	
			//--------------------IF PAID THEN ADD IN EARNING-------------------------------
		
			//-------------------GET HEAD FROM ACCOUNT HEADS------------------------
			$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' AND head_name LIKE '%royalty%'");
			$values_trans_head = mysqli_fetch_array($sqllms_head);

			//------------------- Add INCOME ----------------------
			$sqllms  = $dblms->querylms("INSERT INTO ".ACCOUNT_TRANS."(
																trans_status							, 
																trans_title							    ,
																trans_type							    ,
																trans_amount							,
																voucher_no							    ,
																trans_method							,
																trans_note							    ,
																dated							        ,
																id_head							        ,
																id_campus							    ,  
																id_added							    ,  
																date_added 	
															)
														VALUES(
																'1'		                                    				,	 
																'".cleanvars($_POST['challan_no'])."'						,
																'1'		                                    				,
																'".cleanvars($_POST['paid_amount'])."'						,
																'".cleanvars($_POST['challan_no'])."'						,
																'1'															,
																'".cleanvars($_POST['roy_detail'])."'						,				
																'".cleanvars($paidDate)."' 									,
																'".cleanvars($values_trans_head['head_id'])."'   			,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	,
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		,
																NOW()	
															)"
									);
			//--------------------------------------
												
			$remarks = 'Royalty Challan Paid #: "'.cleanvars($_POST['challan_no']).'", ID: "'.cleanvars($_POST['id_roy']).'" ';
				
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
																	'2'																	, 
																	NOW()																,
																	'".cleanvars($ip)."'												,
																	'".cleanvars($remarks)."'											,
																	'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
																)
											");

			//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: royalty.php", true, 301);
			exit();
			//--------------------------------------
		}
	}
	else{

		//----------------- Update Chllan as Paid ---------------------
		$sqllms  = $dblms->querylms("UPDATE ".FEES." SET 
												status				= '".cleanvars($_POST['status'])."'
											,	paid_date			= '".cleanvars($paidDate)."'
											,	paid_amount			= '".cleanvars($paid_amount)."'
											,	remaining_amount	= '".cleanvars($_POST['remaining_amount'])."'
											,	roy_detail			= '".cleanvars($_POST['roy_detail'])."'
											,	id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
											, 	date_modify			= NOW()
										  WHERE roy_id				= '".cleanvars($_POST['id_roy'])."'
											");
		//------------------------------------
		$remaining =  ($_POST['payable'] + $_POST['remaining_amount']) - $paid_amount;

		if($sqllms) 
		{
			//----------------- Update Chllan info ---------------------
			$remarks = 'Royality Challan Paid #: "'.cleanvars($_POST['challan_no']).'", ID: "'.cleanvars($_POST['id_roy']).'" ';
				
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
																	'2'																	, 
																	NOW()																,
																	'".cleanvars($ip)."'												,
																	'".cleanvars($remarks)."'											,
																	'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
																)
											");

			//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Updated.';
			$_SESSION['msg']['type'] 	= 'info';
			header("Location: royalty.php", true, 301);
			exit();
		}
	}

} 

?>