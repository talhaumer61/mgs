<?php 
//---------------- Bulk Fee Challans Genrate ----------------------
if(isset($_POST['challans_generate'])) { 
					   
	//------------------------Reformat Date------------------------
	$challandate	= date('Ym');
	$issue_date = date('Y-m-d' , strtotime(cleanvars($_POST['issue_date'])));
	$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
	//------------------------------------------------	

	for($s=1; $s<= count($_POST['id_std']); $s++){
		//----------------------Challan Number-------------------------
		$sqllmschallan 	= $dblms->querylms("SELECT challan_no FROM ".FEES." 
										WHERE challan_no LIKE '".$challandate."%'  
										ORDER by challan_no DESC LIMIT 1 ");
		$rowchallan 	= mysqli_fetch_array($sqllmschallan);
		if(mysqli_num_rows($sqllmschallan) < 1) {
			$challano	= $challandate.'00001';
		} else  {
			$challano = ($rowchallan['challan_no'] +1);
		}

		//----------------- Remaining Amount ------------------
		$sqllms_rem = $dblms->querylms("SELECT remaining_amount FROM ".FEES." 
										WHERE id= (SELECT MAX(id) FROM ".FEES." WHERE  id_std = '".cleanvars($_POST['id_std'][$s])."')
										LIMIT 1");
		$row_rem = mysqli_fetch_array($sqllms_rem);
		$rem_amount = $row_rem['remaining_amount'];

		//----------------------Challan Number-------------------------
		$sqllms  = $dblms->querylms("INSERT INTO ".FEES."(
															status						, 
															challan_no					, 
															id_session					, 
															id_class					, 
															id_section					,
															id_std						,
															issue_date					,
															due_date					,
															total_amount				,
															remaining_amount			,
															note						, 
															id_campus 					,
															id_added					,
															date_added					,
															is_deleted
														)
													VALUES(
															'2'																,
															'".cleanvars($challano)."'										,
															'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	, 
															'".cleanvars($_POST['id_class'])."'								,
															'".cleanvars($_POST['id_section'])."'							,
															'".cleanvars($_POST['id_std'][$s])."'							,
															'".cleanvars($issue_date)."'									, 
															'".cleanvars($due_date)."'										,
															'".cleanvars($_POST['total_amount'])."'							,
															'".cleanvars($rem_amount)."'									,
															'".cleanvars($_POST['note'])."'									,
															'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
															'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
															Now()															,
															'0'
														)"
												);

		//-------------------------Fee Particulars Detail-----------------------
		if($sqllms) { 
			//-------------------------Get latest Id----------------------- 
			$idsetup = $dblms->lastestid();	
		//--------------------------------------
				for($i=1; $i<= count($_POST['id_cat']); $i++){
					$sqllms  = $dblms->querylms("INSERT INTO ".FEE_PARTICULARS."(
																	id_fee			,
																	id_cat			,
																	amount						
																)
															VALUES(
																	'".cleanvars($idsetup)."'						,
																	'".cleanvars($_POST['id_cat'][$i])."'			,
																	'".cleanvars($_POST['amount'][$i])."'			
																)
																");
	
				}
	
			$remarks = 'Add Bulk Fee Challans: "'.cleanvars($_POST['id_section']).'" detail';
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
	}

	if($sqllms) { 
	//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Successfully';
			$_SESSION['msg']['text'] 	= 'Record Successfully Added.';
			$_SESSION['msg']['type'] 	= 'success';
			header("Location: fee_challans.php", true, 301);
			exit();
	//--------------------------------------
	}
} 

//---------------- Single Fee Challans Genrate ----------------------
if(isset($_POST['one_challan_generate'])) { 
					   
	//------------------------Reformat Date------------------------
	$challandate	= date('Ym');
	$issue_date = date('Y-m-d' , strtotime(cleanvars($_POST['issue_date'])));
	$due_date = date('Y-m-d' , strtotime(cleanvars($_POST['due_date'])));
	//------------------------------------------------	

	//----------------------Challan Number-------------------------
	$sqllmschallan 	= $dblms->querylms("SELECT challan_no FROM ".FEES." 
									WHERE challan_no LIKE '".$challandate."%'  
									ORDER by challan_no DESC LIMIT 1 ");
	$rowchallan 	= mysqli_fetch_array($sqllmschallan);
	if(mysqli_num_rows($sqllmschallan) < 1) {
		$challano	= $challandate.'00001';
	} else  {
		$challano = ($rowchallan['challan_no'] +1);
	}
	//----------------- Remaining Amount ------------------
	$sqllms_rem = $dblms->querylms("SELECT remaining_amount FROM ".FEES." 
									WHERE id= (SELECT MAX(id) FROM ".FEES." WHERE  id_std = '".cleanvars($_POST['id_std'])."')
									LIMIT 1");
	$row_rem = mysqli_fetch_array($sqllms_rem);
	$rem_amount = $row_rem['remaining_amount'];
	//---------------------- Make -------------------------
	$sqllms  = $dblms->querylms("INSERT INTO ".FEES."(
														status						, 
														challan_no					, 
														id_session					, 
														id_class					, 
														id_section					,
														id_std						,
														issue_date					,
														due_date					,
														total_amount				,
														remaining_amount			,
														note						, 
														id_campus 					,
														id_added					,
														date_added
													)
												VALUES(
														'2'																,
														'".cleanvars($challano)."'										,
														'".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'	, 
														'".cleanvars($_POST['id_class'])."'								,
														'".cleanvars($_POST['id_section'])."'							,
														'".cleanvars($_POST['id_std'])."'								,
														'".cleanvars($issue_date)."'									, 
														'".cleanvars($due_date)."'										,
														'".cleanvars($_POST['total_amount'])."'							,
														'".cleanvars($rem_amount)."'									,
														'".cleanvars($_POST['note'])."'									,
														'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'		,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'			,
														Now()	
													)"
											);

	//-------------------------Fee Particulars Detail-----------------------
	if($sqllms) { 
		//-------------------------Get latest Id----------------------- 
		$idsetup = $dblms->lastestid();	
	//--------------------------------------
			for($i=1; $i<= count($_POST['id_cat']); $i++){
				$sqllms  = $dblms->querylms("INSERT INTO ".FEE_PARTICULARS."(
																id_fee			,
																id_cat			,
																amount						
															)
														VALUES(
																'".cleanvars($idsetup)."'						,
																'".cleanvars($_POST['id_cat'][$i])."'			,
																'".cleanvars($_POST['amount'][$i])."'			
															)
															");

			}

		$remarks = 'Single Fee Challans Genrated Challan: "'.cleanvars($challano).'" detail';
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
			header("Location: fee_challans.php", true, 301);
			exit();
	//--------------------------------------
	}
}

//----------------Update Single Fee Chalaln----------------------
if(isset($_POST['changes_challan'])) { 
		//------------------------------------
		$paidDate = date('Y-m-d');
		$paid_amount = $_POST['paid_amount'];
	if($_POST['paid_amount'] > 0){
		//----------------- Update Chllan as Paid ---------------------
		$sqllms  = $dblms->querylms("UPDATE ".FEES." SET 
												status				= '1'
											,	paid_date			= '".cleanvars($paidDate)."'
											,	paid_amount			= '".cleanvars($paid_amount)."'
											,	remaining_amount	= '".cleanvars($_POST['remaining_amount'])."'
											,	note				= '".cleanvars($_POST['note'])."'
											,	id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
											, 	date_modify			= NOW()
												WHERE id			= '".cleanvars($_POST['id_fee'])."'
											");
		//------------------------------------
		$remaining =  ($_POST['payable'] + $_POST['remaining_amount']) - $paid_amount;   
		//--------------------------------------
		if($sqllms) 
		{	
			//--------------------IF PAID THEN ADD IN EARNING-------------------------------
		
			//-------------------GET FEE HEAD FROM ACCOUNT HEADS------------------------
			$sqllms_head	= $dblms->querylms("SELECT head_id FROM ".ACCOUNT_HEADS." WHERE head_type = '1' AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' AND head_name LIKE '%fee%'");
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
																'".cleanvars($_POST['note'])."'								,				
																'".cleanvars($paidDate)."' 									,
																'".cleanvars($values_trans_head['head_id'])."'   			,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	,
																'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		,
																NOW()	
															)"
									);
			//--------------------------------------
												
			$remarks = 'Fee Challan Paid #: "'.cleanvars($_POST['challan_no']).'", ID: "'.cleanvars($_POST['id_fee']).'" ';
				
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
			header("Location: fee_challans.php", true, 301);
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
											,	note				= '".cleanvars($_POST['note'])."'
											,	id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' 
											, 	date_modify			= NOW()
												WHERE id			= '".cleanvars($_POST['id_fee'])."'
											");
		//------------------------------------
		$remaining =  ($_POST['payable'] + $_POST['remaining_amount']) - $paid_amount;

		if($sqllms) 
		{
			//----------------- Update Chllan info ---------------------
			$remarks = 'Fee Challan Paid #: "'.cleanvars($_POST['challan_no']).'", ID: "'.cleanvars($_POST['id_fee']).'" ';
				
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
			header("Location: fee_challans.php", true, 301);
			exit();
		}
	}

} 


//---------------- Delete reocrd----------------------
if(isset($_GET['deleteid'])) { 
	//------------------------------------------------
	$sqllms  = $dblms->querylms("UPDATE ".FEES." SET  
													is_deleted			= '1'
												, id_deleted			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
												, ip_deleted			= '".$ip."'
												, date_deleted			= NOW()
												WHERE id 			= '".cleanvars($_GET['deleteid'])."'");
	//--------------------------------------
		if($sqllms)
		{ 
			//--------------------------------------
			$remarks = 'Fee Challan Deleted ID: "'.cleanvars($_GET['deleteid']).'" details';
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
																'3'											, 
																NOW()										,
																'".cleanvars($ip)."'						,
																'".cleanvars($remarks)."'						,
																'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'			
															  )
										");
			//--------------------------------------
			$_SESSION['msg']['title'] 	= 'Warning';
			$_SESSION['msg']['text'] 	= 'Record Successfully Deleted.';
			$_SESSION['msg']['type'] 	= 'warning';
			header("Location: fee_challans.php", true, 301);
			exit();
			//--------------------------------------
		}
	//--------------------------------------
}

?>