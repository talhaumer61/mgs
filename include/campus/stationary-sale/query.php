<?php
//----------------STATIONARY SALE ADD----------------------
if(isset($_POST['make_sale'])) { 
	$sqllmscheck  = $dblms->querylms("SELECT receipt_no
										FROM ".INVENTORY_SALE." 
										WHERE receipt_no = '".cleanvars($_POST['receipt_no'])."' 
										AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck) > 0) {
//--------------------------------------
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: stationary_sale.php", true, 301);
		exit();
//--------------------------------------
	} else { 
//--------------------------------------
/*
sal_paid_amount
id_customer
*/
$dated = date("Y-m-d");
//--------------------------------------
	$sqllms  = $dblms->querylms("INSERT INTO ".INVENTORY_SALE."(
														sal_status						, 
														sal_pay_status					, 
														receipt_no						, 
														sal_total_amount				, 
														dated							,
														note							,
														id_campus 						,
														id_added						,
														date_added	
													  )
	   											VALUES(
														'1'															, 
														'2'															,
														'".cleanvars($_POST['receipt_no'])."'						,
														'".cleanvars($_POST['sal_total_amount'])."'					,
														'".cleanvars($dated)."'										,
														'".cleanvars($_POST['note'])."'								,
														'".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'	,
														'".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'		,
														NOW()
													  )"
							);
//--------------------------------------
$id_setup = $dblms->lastestid();	
//-------------------usee foreah like headoffice-------------------
// for($j=0; $j<= count(!empty($_POST['id_item'])); $j++){

// 	if($_POST['qty'][$j] != 0){

// 	$sqllmsdeduction  = $dblms->querylms("INSERT INTO ".INVENTORY_PUR_DETAIL." (
// 																		id_setup				, 
// 																		id_item				    , 
// 																		qty						,
// 																		unit_price				
// 																	)

// 																VALUES(
// 																		'".cleanvars($id_setup)."' 		   			    ,
// 																		'".cleanvars($_POST['id_item'][$j])."'			,
// 																		'".cleanvars($_POST['qty'][$j])."'				,
// 																		'".cleanvars($_POST['unit_price'][$j])."'			
// 																	)
// 															");
// 	}
// }


//--------------------------------------
	if($sqllms) { 
	//------------ Sale Detail ----------------
		$id_item 		= $_POST['id_item']; 
		$qty 			= $_POST['qty']; 
		$unit_price 	= $_POST['unit_price']; 

		// for($j=0; $j <= count(!empty($_POST['id_item'])); $j++){

		foreach($id_item as $key => $item){
			// if($qty[$key] != 0){

			$sqllmsdeduction  = $dblms->querylms("INSERT INTO ".INVENTORY_SALE_DETAIL." (
																				id_setup				, 
																				id_item				    , 
																				qty						,
																				unit_price				
																			)
																		VALUES(
																				'".cleanvars($id_setup)."' 				,
																				'".cleanvars($item)."'					,
																				'".cleanvars($qty[$key])."'				,
																				'".cleanvars($unit_price[$key])."'			
																			)
																	");
			// }
		}
//--------------------------------------
	$remarks = 'Add Campus Sale, Recipiet no: "'.cleanvars($_POST['receipt_no']).'" detail';
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
		header("Location: stationary_sale.php", true, 301);
		exit();
//--------------------------------------
	}
//--------------------------------------
	} // end checker
//--------------------------------------
} 



//----------------STATIONARY PURCHASE UPDATE----------------------
if(isset($_POST['changes_purchase'])) { 
	//------------------------------------------------
	$sqllms  = $dblms->querylms("UPDATE ".INVENTORY_PURCHASE." SET 
														sal_status			= '1'
													  , pur_total_amount	= '".cleanvars($_POST['pur_total_amount'])."' 
													  , pur_note			= '".cleanvars($_POST['pur_note'])."'
													  , id_modify			= '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."'
													  , date_modify			= NOW()
													 WHERE pur_id 			= '".cleanvars($_POST['pur_id'])."'");
	//--------------------------------------
	//-----------DELETE DETAILS---------------------------
	 $sqllms  = $dblms->querylms("DELETE FROM ".INVENTORY_PUR_DETAIL." where id_setup = '".cleanvars($_POST['pur_id'])."' ");
	 
	//-----------INSER DETAILS---------------------------
	for($j=0; $j<= count($_POST['id_item']); $j++){
	
		if($_POST['qty'][$j] != 0){
	
		$sqllmsdeduction  = $dblms->querylms("INSERT INTO ".INVENTORY_PUR_DETAIL." (
																			id_setup				, 
																			id_item				    , 
																			qty						,
																			unit_price				
																		)
	
																	VALUES(
																			'".cleanvars(cleanvars($_POST['pur_id']))."' 	,
																			'".cleanvars($_POST['id_item'][$j])."'			,
																			'".cleanvars($_POST['qty'][$j])."'				,
																			'".cleanvars($_POST['unit_price'][$j])."'			
																		)
																");
		}
	}	
	
	if($sqllms) { 
	//--------------------------------------
		$remarks = 'Update Campus Purchase, Recipiet no: "'.cleanvars($_POST['pur_receipt_no']).'" details';
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
				header("Location: stationary_purchase.php", true, 301);
				exit();
	//--------------------------------------
		}
	//--------------------------------------
	}



//----------------STATIONARY PURCHASE UPDATE----------------------
if(isset($_POST['upload_receipt'])) { 
		
	//-----------------UPLOAD RECEPIET IN PUCHASE---------------------
	if(!empty($_FILES['pur_pay_invoice']['name'])) { 
		//--------------------------------------
			$path_parts 	= pathinfo($_FILES["pur_pay_invoice"]["name"]);
			$extension 		= strtolower($path_parts['extension']);
			$img_dir 	= 'uploads/images/purchases/campus/';
		//--------------------------------------
			$originalImage	= $img_dir.to_seo_url(cleanvars($_POST['pur_receipt_no'])).'_'.$_POST['pur_id'].".".($extension);
			$img_fileName	= to_seo_url(cleanvars($_POST['pur_receipt_no'])).'_'.$_POST['pur_id'].".".($extension);
		//--------------------------------------
			if(in_array($extension , array('jpg','jpeg', 'gif', 'png', 'pdf'))) { 
		//--------------------------------------
				$sqllmsupload  = $dblms->querylms("UPDATE ".INVENTORY_PURCHASE."
																SET pur_pay_invoice = '".$img_fileName."'	
															  WHERE pur_id 		    = '".cleanvars($_POST['pur_id'])."'");
				unset($sqllmsupload);
				$mode = '0644'; 
		//--------------------------------------	
				move_uploaded_file($_FILES['pur_pay_invoice']['tmp_name'],$originalImage);
				chmod ($originalImage, octdec($mode));
		//--------------------------------------
			}
		}
		
	if($sqllmsupload) { 
		//--------------------------------------
			$remarks = 'payment Receipt Upload: "'.cleanvars($_POST['pur_receipt_no']).'" details';
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
					header("Location: stationary_purchase.php", true, 301);
					exit();
		//--------------------------------------
	}
	//--------------------------------------
}
	
	?>