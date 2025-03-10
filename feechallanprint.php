<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();
	
$id_campus	= ((isset($_GET['id_campus']) && !empty($_GET['id_campus'])))? cleanvars($_GET['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);

$sqllmscampus  = $dblms->querylms("SELECT c.campus_name, b.primary_bank, b.primary_account
									FROM ".CAMPUS." c
									LEFT JOIN ".CAMPUS_BIOGRAPHY." b ON b.id_campus = c.campus_id
									WHERE c.campus_status = '1' AND c.campus_id = '".cleanvars($id_campus)."' 
									ORDER BY b.bio_id DESC LIMIT 1");
$value_campus = mysqli_fetch_array($sqllmscampus);
$primary_bank = $value_campus['primary_bank'];
$primary_account = $value_campus['primary_account'];

if($value_campus['primary_account'] == ''){
	$sqllmscampus  = $dblms->querylms("SELECT c.campus_name, b.primary_bank, b.primary_account
										FROM ".CAMPUS." c
										LEFT JOIN ".CAMPUS_BIOGRAPHY." b ON b.id_campus = c.campus_id
										WHERE c.campus_status = '1' AND c.campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										ORDER BY b.bio_id DESC LIMIT 1");
	$val_campus = mysqli_fetch_array($sqllmscampus);
	$primary_bank = $val_campus['primary_bank'];
	$primary_account = $val_campus['primary_account'];
}
echo '
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Fee Challan Form</title>
		<style id="print-styles">
			body {overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";  }
			@media all { .page-break { display: none; } }
			@media print { .page-break	{ display: block; page-break-before: always; } @page { size: A4 '.((count($_SESSION['userlogininfo']['PRINTCOPY']) == 3)? 'landscape': 'portrait').'; margin: 4mm 4mm 4mm 4mm; } }
			h1 { text-align:left; margin:0; margin-top:0; margin-bottom:0px; font-size:26px; font-weight:700; text-transform:uppercase; }
			.spanh1 { font-size:14px; font-weight:normal; text-transform:none; text-align:right; float:right; }
			h2 { text-align:left; margin:0; margin-top:0; margin-bottom:1px; font-size:24px; font-weight:700; text-transform:uppercase; }
			.spanh2 { font-size:20px; font-weight:700; text-transform:none; }
			h3 { text-align:center; margin:0; margin-top:0; margin-bottom:1px; font-size:18px; font-weight:700; text-transform:uppercase; }
			h4 { text-align:center; margin:0; margin-bottom:1px; font-weight:normal; font-size:13px; font-weight:700; word-spacing:0.1em; }
			td { padding-bottom:3px; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; }
			.line1 { border:1px solid #333; width:100%; margin-top:2px; margin-bottom:5px; }
			.payable { border:2px solid #000; padding:2px; text-align:center; font-size:14px; }
		</style>
		<link rel="shortcut icon" href="images/favicon/favicon.ico">
		<script src="assets/javascripts/qrcode.min.js"></script>
	</head>
	<body>';
	//Single Challan Print
	if(isset($_GET['id'])) {
		$sqllms  = $dblms->querylms("SELECT f.id, f.status, f.id_type, f.id_month, f.challan_no, f.id_session, f.id_class, f.id_section, f.inquiry_formno, f.id_std, f.issue_date, f.due_date, f.total_amount, f.paid_amount, f.scholarship, f.concession, f.fine, f.remaining_amount, f.note,  c.class_id, c.class_name, f.paid_date, cs.section_id, cs.section_name, st.std_id, st.std_name, st.std_fathername, st.std_regno, st.std_rollno, se.session_id, se.session_name, adm.adm_fullname
									FROM ".FEES." f
									INNER JOIN ".SESSIONS." se ON se.session_id = f.id_session
									LEFT  JOIN ".STUDENTS." st ON st.std_id = f.id_std
									INNER JOIN ".CLASSES." c ON c.class_id = f.id_class
									LEFT  JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section	
									LEFT  JOIN ".ADMINS." adm ON adm.adm_id = f.id_collector
									WHERE f.id_campus	= '".cleanvars($id_campus)."'
									AND f.challan_no IN (".cleanvars($_GET['id']).")
									AND f.is_deleted	= '0'");
		$iBreal = 0;
		echo '
			<style>
				div.relative {
					position: absolute;
					color: green;
					opacity: 0.8;
					transform: rotate(-5deg);
					text-align: center;
				}
				div span.text-size {
					font-size: 20px; 
				}
			</style>';
		while ($feercord = mysqli_fetch_array($sqllms)){
			// scholarship seprate them
			$slrArray 			= explode(',',$feercord['scholarship']);
			$scholarship 		= $slrArray[0];
			$id_scholarship 	= $slrArray[1];
			// concession seprate them
			$conArray 			= explode(',',$feercord['concession']);
			$concession 		= $conArray[0];
			$id_concession 		= $conArray[1];

			$iBreal++;
			if ($iBreal > 1) {
				if (count($_SESSION['userlogininfo']['PRINTCOPY']) == 3) {
					echo '<div class="page-break"></div>';	
				} else if (($iBreal % 2) == 1) {
					echo '<div class="page-break"></div>';	
				}
			}
			echo '
			<table class="page" id="myTable_'.$iBreal.'" width="99%" border="0" cellpadding="10" cellspacing="15" align="center" style="border-collapse:collapse; margin-top:0px;">
				<tr>';
					$clspaid 	= ($feercord['status'] == 1)?' paid PaidDate'	:'';
					$clspaidAm 	= ($feercord['status'] == 1)?' PaidAmount'		:'';
					for($ifee = 1; $ifee<=3; $ifee++):
						if (in_array($ifee, $_SESSION['userlogininfo']['PRINTCOPY'])):
							$rightborder 	= ($ifee<3)?'style="border-right:1px dashed #333;"':'';
							$copyfor 		= ($ifee==1)?'Bank':(($ifee==2)?'Account':(($ifee==3)?'Student': ''));
							$stdname = preg_replace('/\s+/', ' ', $feercord['std_name']);
							$shortarray = explode(' ',trim($stdname));
							$firstname 	= $shortarray[0];
							$displayname =  $feercord['std_name'];
							$fathername =  $feercord['std_fathername'];
							echo'
							<td width="341" valign="top" '.$rightborder.' id="'.(($ifee==1)?'hBank':(($ifee == 2)?'hAccounts':'hStudents')).'">
								<div class="row">
									<table style="border-collapse:collapse;" width="100%" border="0">
										<tr>
											<td>
												<img src="'.$_SESSION['userlogininfo']['LOGINCAMPUSLOGO'].'" class="img-fluid" style="width: 50px; height: 50px;">
											</td>
											<td>
												<h4>'.$value_campus['campus_name'].'</h4>
												<h4>'.$primary_bank.' Collection Account #'.$primary_account.'</h4>
											</td>
											<td>
												<h6 style="margin-right: 0px;writing-mode: vertical-lr; text-orientation: mixed; border: 1px dashed black; border-radius: 12px; padding: 5px 3px;"> <span class="spanh1">'.$copyfor.'</span></h6>
											</td>
										</tr>
									</table>
								</div>
								<div class="line1"></div>
								<div style="font-size:13px; margin-top:5px;">
									<table style="border-collapse:collapse; line-height: 10px;" width="100%" border="0">
										<tr>
											<td style="text-align:left; width:75px;">Challan #:</td>
											<td style= text-align:left; width:150px;"><span style="width:90px;display:inline-block; overflow:hidden; border-bottom:1px solid;">'.$feercord['challan_no'].'</span></td>
											<td style="text-align:left;width:70px;">Issue Date:</td>
											<td style="text-align:left; text-decoration:underline;">'.$feercord['issue_date'].'</td>
										</tr>
										<tr>
											<td style="text-align:left;">Reg #:</td>
											<td style="text-align:left;"><span style="font-size:10px;"><u>'.$feercord['std_regno'].'</u></span></td>
											<td style="text-align:left;">Due Date:</td>
											<td style=" text-align:left; text-decoration:underline;">'.$feercord['due_date'].'</td>	
										</tr>
										<tr>
											<td style="text-align:left;">Name:</td>
											<td style=" text-decoration:underline;"><span style="font-size:12px;">'.$displayname.'</span></td>
											<td style="text-align:left;">so/do:</td>
											<td style=" text-decoration:underline;"><span style="font-size:12px;">'.$fathername.'</span></td>
										</tr>
										<tr>
											<td style="text-align:left;">Class:</td>
											<td style="text-align:left; text-decoration:underline;">'.$feercord['class_name'].'</td>
											<td style="text-align:left;">Section:</td>
											<td style="text-align:left; text-decoration:underline;">'.$feercord['section_name'].'</td>
										</tr>
										<tr>';
										if($feercord['id_month']){
											echo'
											<td style="text-align:left;">Month</td>
											<td style=" text-align:left;  text-decoration:underline;">'.get_monthtypes($feercord['id_month']).'-'.date('Y' , strtotime(cleanvars($feercord['due_date']))).'</td>';
										}
										echo'
											<td style="text-align:left;">Session</td>
											<td style=" text-align:left;  text-decoration:underline;">'.$feercord['session_name'].'</td>
										</tr>
									</table>
								</div>
								<div style="font-size:10px; margin-top:5px;">
									<table style="border-collapse:collapse; border:1px solid #666;" cellpadding="2" cellspacing="2" border="1" width="100%">
										<tr>
											<td style="text-align:center; font-size:12px; font-weight:bold;"> Descriptions </td>
											<td style="text-align:right; font-size:12px; font-weight:bold; width:50px;">Rs.</td>
										</tr>';
										if($feercord['id_type']){
											$sqllmscats  = $dblms->querylms("SELECT cat_id, cat_name  
																				FROM ".FEE_CATEGORY."
																				WHERE cat_status = '1' 
																				ORDER BY cat_id ASC");
											$countcats 	= mysqli_num_rows($sqllmscats);
											if($countcats > 0){
												$src = 0;
												$grandTotal = 0;
												while($rowdoc 	= mysqli_fetch_array($sqllmscats)) {
													$src++;
													$sqllmsfeeprt  = $dblms->querylms("SELECT id_cat, amount FROM ".FEE_PARTICULARS." 
																						WHERE id_cat = '".$rowdoc['cat_id']."' AND id_fee  = '".$feercord['id']."' 
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
															echo'
															<tr>
																<td>'.$rowdoc['cat_name'].$remarks.'</td>
																<td style="text-align:right; width:45%;">'.number_format($valuefeeprt['amount']).'</td>
															</tr>';
														}
														$grandTotal += $valuefeeprt['amount'];
													}
												}
												if (!empty($feercord['fine']) || $feercord['fine'] != 0 || $feercord['fine']) {
													echo'
													<tr>
														<td>Fine</td>
														<td style="text-align:right; width:45%;">'.number_format($feercord['fine']).'</td>
													</tr>';
													$grandTotal += $feercord['fine'];
												}
											}
											if($feercord['status'] == '2'){
												$sqlnarration  = $dblms->querylms("SELECT f.id, f.id_month, f.challan_no, f.id_std,
																					f.issue_date, f.due_date, f.total_amount, f.paid_amount, f.scholarship, f.concession, f.fine, f.remaining_amount
																					FROM ".FEES." f
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
														$amount = $valnarration['total_amount'] - $valnarration['paid_amount'];

														if(date('Y-m-d') > $valnarration['due_date']) {
															$due_date_after_five_day = date('Y-m-d', strtotime($valnarration['due_date']. ' + 5 days'));
															if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 1) {
																$amount += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
															} else if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 2) {
																if ($due_date_after_five_day > date('Y-m-d')) {
																	$amount += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
																} else if ($due_date_after_five_day < date('Y-m-d')) {
																	$amount += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][2];
																} else {
																	$amount += LATEFEE;	
																}
															} else {
																$amount += LATEFEE;
															} 
														}
													
														echo'
														<tr>
															<td>'.get_monthtypes($valnarration['id_month']).' '.$year.' <small>('.$valnarration['challan_no'].')</small></td>
															<td style="text-align:right;">'.number_format($amount).'</td>
														</tr>';
														$grandTotal = $grandTotal + $amount;
													}
												}
												if(date('Y-m-d') > $feercord['due_date']) {
													$late_fee = 0;
													$due_date_after_five_day = date('Y-m-d', strtotime($feercord['due_date']. ' + 5 days'));
													if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 1) {
														$late_fee += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
													} else if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 2) {
														if ($due_date_after_five_day > date('Y-m-d')) {
															$late_fee += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
														} else if ($due_date_after_five_day < date('Y-m-d')) {
															$late_fee += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][2];
														} else {
															$late_fee += LATEFEE;	
														}
													} else {
														$late_fee += LATEFEE;
													} 
													echo '
													<tr>
														<td>Late Fee</td>
														<td style="text-align:right; width:45%;">'.number_format($late_fee).'</td>
													</tr>';
													$grandTotal += $late_fee;
												}
											}
											echo'
											<tr>
												<td style="text-align:right; font-size:12px; font-weight:bold; border:2px solid #333;">Total Amount Payable</td>
												<td style="text-align:right; font-size:12px; font-weight:bold;  border:2px solid #333;">'.number_format($grandTotal).'</td>
											</tr>';
											if($feercord['status'] == '4'){
												echo '
												<tr>
													<td style="text-align:right; font-size:12px; font-weight:bold; border:2px solid #333;">Partial Paid Amount</td>
													<td style="text-align:right; font-size:12px; font-weight:bold;  border:2px solid #333;">'.number_format($feercord['paid_amount']).'</td>
												</tr>
												<tr>
													<td style="text-align:right; font-size:12px; font-weight:bold; border:2px solid #333;">Remaining Amount Payable</td>
													<td style="text-align:right; font-size:12px; font-weight:bold;  border:2px solid #333;">'.number_format($grandTotal - $feercord['paid_amount']).'</td>
												</tr>';
											}
											echo '
											<!--
											<tr>
												<td style="text-align:right; font-size:12px; font-weight:bold; border:2px solid #333;">Total Amount After Duedate</td>
												<td style="text-align:right; font-size:12px; font-weight:bold;  border:2px solid #333;">';
													$late_fee = 0;
													if(date('Y-m-d') > $feercord['due_date']) {
														$due_date_after_five_day = date('Y-m-d', strtotime($feercord['due_date']. ' + 5 days'));
														if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 1) {
															$late_fee += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
														} else if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 2) {
															if ($due_date_after_five_day > date('Y-m-d')) {
																$late_fee += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
															} else if ($due_date_after_five_day < date('Y-m-d')) {
																$late_fee += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][2];
															} else {
																$late_fee += LATEFEE;	
															}
														} else {
															$late_fee += LATEFEE;
														} 
													}
												echo'
												'.number_format($grandTotal+$late_fee).'
												</td>
											</tr>
											-->';
										}
										echo'
									</table>';
									if($_SESSION['userlogininfo']['LOGINAFOR'] != 3) { 
										echo '<span style="font-size:9px;">Issue By: '.html_entity_decode(cleanvars($_SESSION['userlogininfo']['LOGINNAME'])).'</span>';
									}
									echo'
									<span style="font-size:9px; float:right; margin-top:3px;">Print Date: '.date("m/d/Y").'</span>
								</div>
								<div style="clear:both;"></div>
								<div style="font-size:13px; color:#000; margin-top:20px;">
									<table width="100%" border="0" style="border-collapse:collapse;" cellpadding="0" cellspacing="5">
										<tr>
											<td style="font-weight:normal; font-style:italic; text-align:left; font-size:11px; width:80%;">Rupees in word: <span style="text-decoration:underline; font-size:9px; color:#000;">'.convert_number_to_words($feercord['total_amount']).' only</span></td>
											<td style="font-weight:normal; font-style:italic; text-align:right;">Cashier</td>
										</tr>
										<tr>
											<td style="font-weight:normal; font-style:italic; color: #777777; text-align:left; font-size:9px;">';
												$sqllmsChallanDes = array ( 
																		'select' 	=> ' 
																							chl_desc 
																						',
																		'where' 	=> array( 
																								  'is_deleted'    		=> '0'
																								, 'chl_desc_status'    	=> '1'
																								, 'id_campus'			=> cleanvars($id_campus)
																							),
																		'return_type' 	=> 'single' 
																	); 
												$rowsChallanDes  = $dblms->getRows(CHALLAN_DESCRIPTION, $sqllmsChallanDes);
												echo html_entity_decode(html_entity_decode($rowsChallanDes['chl_desc']));
												echo'
											</td>
											<td>';
												// GENERATE QR CODE
												/*
												$dataQR = array(
													'challan_no'	=> $feercord['challan_no'],
													'id_std'		=> $feercord['id_std'],
													'id_class'		=> $feercord['id_class'],
												);
												$dataJSON = json_encode($dataQR);
												*/
												$dataJSON = $feercord['id'].','.cleanvars($id_campus);
												echo'
												<div id="qrcode_'.$feercord['challan_no'].'_'.$ifee.'"></div>
												
												<script>
													const qrcode_'.$feercord['challan_no'].'_'.$ifee.' = new QRCode(document.getElementById(\'qrcode_'.$feercord['challan_no'].'_'.$ifee.'\'), {
														text: \''.$dataJSON.'\',
														width: 60,
														height: 60,
														colorDark: "#000",
														colorLight: "#fff",
														correctLevel: QRCode.CorrectLevel.H
													});
												</script>';
												echo '
											</td>
										</tr>
									</table>
								</div>
							</td>';
						endif;
					endfor;
					echo'
				</tr>
			</table>';
			if ($feercord['status'] == '1' || $feercord['status'] == '4') { 
				echo '
				<div class="relative">
					<div id="myDiv_'.$iBreal.'" style="border: 3px solid yellow; background-color: #fff;">
						<span class="text-size">DATE : '.$feercord['paid_date'].'</span><br>
						'.($feercord['status'] == '1' ? '<span style="font-size: 100px;">PAID</span><br>' : '<span style="font-size: 50px;">PARTIAL PAID</span><br>').'
						<span class="text-size">TOTAL : Rs '.number_format($feercord['paid_amount']).'.00</span><br>
						<span class="text-size">COLLECTOR : '.$feercord['adm_fullname'].'</span>
					</div>
				</div>';
			}
			echo '
			<script>
				window.addEventListener(\'DOMContentLoaded\', function() {
					var table 				= document.getElementById(\'myTable_'.$iBreal.'\');
					var div 				= document.getElementById(\'myDiv_'.$iBreal.'\');
					var tableWidth 			= table.clientWidth;
					var tableHeight 		= table.clientHeight;
					console.log(div.offsetHeight);
					div.style.marginTop		= -(tableHeight / 1.5) + \'px\';
					div.style.marginLeft	= (tableWidth / 7) + \'px\';
				});
			</script>
			<script>window.print();</script>';
		}
	}
	//End Single Challan Print

	//Monthly Challan Print
	if(isset($_POST['id_month'])){
		$id_campus	= ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])))? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);

		//----------------------------------------
		if($_POST['id_month'] <= 9){
			$challanIn = date('Y').'0'.$_POST['id_month'];
		} else{
			$challanIn = date('Y').$_POST['id_month'];
		}
		$sqllms  = $dblms->querylms("SELECT f.id, f.status, f.challan_no, f.id_month, f.issue_date, f.due_date, f.total_amount, f.scholarship, f.concession, f.fine, c.class_name, cs.section_name, s.session_name, st.std_name, st.std_regno, st.std_rollno      
										FROM ".FEES." f									
										INNER JOIN ".CLASSES." c ON c.class_id = f.id_class
										INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section
										INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session	
										INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std	
										WHERE f.status !='1' AND f.id_campus = '".cleanvars($id_campus)."' 
										AND f.id_month  = '".$_POST['id_month']."' ORDER BY f.id ASC");
		$iForBreak = 0;
		while($feercord = mysqli_fetch_array($sqllms)){
			// scholarship seprate them
			$slrArray 			= explode(',',$feercord['scholarship']);
			$scholarship 		= $slrArray[0];
			$id_scholarship 	= $slrArray[1];
			// concession seprate them
			$conArray 			= explode(',',$feercord['concession']);
			$concession 		= $conArray[0];
			$id_concession 		= $conArray[1];

			$iForBreak++;
			echo'
			<table width="99%" border="0" class="page " cellpadding="10" cellspacing="15" align="center" style="border-collapse:collapse; margin-top:0px;">
				<tr>';
				//----------------------------------------
				if($feercord['status'] == 1) { 
					$clspaid = " paid";
				} else { 
					$clspaid = "";
				}
				//----------------------------------------
				$cpi = 0;
				//------------------------------------------
				for($ifee = 1; $ifee<=3; $ifee++) { 
					if($ifee<3) { 
						$rightborder = 'style="border-right:1px dashed #333;"';
					}else{ 
						$rightborder = '';
					}
					$cpi++;
					if (in_array($cpi, $_SESSION['userlogininfo']['PRINTCOPY'])) {
					//------------------------------------------
					if($cpi==1) { 
						$copyfor = 'Bank';
					} else if($cpi==2) { 
						$copyfor = 'Account';
					}else if($cpi==3) { 
						$copyfor = "Student's";
					}

					$stdname = preg_replace('/\s+/', ' ', $feercord['std_name']);
					$shortarray = explode(' ',trim($stdname));
					$firstname 	= $shortarray[0];
					$displayname =  $feercord['std_name'];
					echo'
					<td width="341" valign="top" '.$rightborder.' class="'.$clspaid.'">
						<div class="row '.$clspaidAm.'">
							<table style="border-collapse:collapse;" width="100%" border="0">
								<tr>
									<td>
										<img src="'.$_SESSION['userlogininfo']['LOGINCAMPUSLOGO'].'" class="img-fluid" style="width: 50px; height: 50px;">
									</td>
									<td>
										<h4 style="font-size: 12px;">'.$value_campus['campus_name'].'</h4>
										<h4 style="font-size: 12px;">'.$primary_bank.' Collection Account #'.$primary_account.'</h4>
									</td>
									<td>
										<h6 style="margin-right: 0px;writing-mode: vertical-lr; text-orientation: mixed; border: 1px dashed black; border-radius: 12px; padding: 5px 3px;"> <span class="spanh1">'.$copyfor.'</span></h6>
									</td>
								</tr>
							</table>
						</div>
						<div class="line1"></div>
						<div style="font-size:10px; line-height: 10px; margin-top:5px;">
							<table style="border-collapse:collapse;" width="100%" border="0">
								<tr>
									<td style="text-align:left; width:75px;">Challan #:</td>
									<td style= text-align:left; width:150px;"><span style="width:90px;display:inline-block; overflow:hidden; border-bottom:1px solid;">'.$feercord['challan_no'].'</span></td>
									<td style="text-align:left;width:70px;">Issue Date:</td>
									<td style="text-align:left; text-decoration:underline;">'.$feercord['issue_date'].'</td>
								</tr>
								<tr>
									<td style="text-align:left;">Reg #:</td>
									<td style="text-align:left;"><span style="font-size:10px;"><u>'.$feercord['std_regno'].'</u></span></td>
									<td style="text-align:left;">Due Date:</td>
									<td style=" text-align:left; text-decoration:underline;">'.$feercord['due_date'].'</td>	
								</tr>
								<tr>
									<td style="text-align:left;">Name:</td>
									<td  colspan="3" style=" text-decoration:underline;"><span style="font-size:12px;">'.$displayname.'</span></td>
								</tr>
								<tr>
									<td style="text-align:left;">Class:</td>
									<td style="text-align:left; text-decoration:underline;">'.$feercord['class_name'].'</td>
									<td style="text-align:left;">Section:</td>
									<td style="text-align:left; text-decoration:underline;">'.$feercord['section_name'].'</td>
								</tr>
								<tr>
									<td style="text-align:left;">Month:</td>
									<td style=" text-align:left; text-decoration:underline;">'.get_monthtypes($feercord['id_month']).'</td>
									<td style="text-align:left;">Session</td>
									<td style=" text-align:left;  text-decoration:underline;">'.$feercord['session_name'].'</td>
								</tr>
							</table>
						</div>
						<div style="font-size:12px; margin-top:5px;">
							<table style="border-collapse:collapse; border:1px solid #666;" cellpadding="2" cellspacing="2" border="1" width="100%">
								<tr>
									<td style="text-align:center; font-size:12px; font-weight:bold;"></td>
									<td style="text-align:right; font-size:12px; font-weight:bold;">Rs.</td>
								</tr>';
								//------------------------------------------------
								$sqllmscats  = $dblms->querylms("SELECT cat_id, cat_name  
																	FROM ".FEE_CATEGORY."
																	WHERE cat_status = '1' 
																	ORDER BY cat_id ASC");
								$countcats 	= mysqli_num_rows($sqllmscats);
								if($countcats >0) {
									$src = 0;
									while($rowdoc 	= mysqli_fetch_array($sqllmscats)) {
										$src++;
										$sqllmsfeeprt  = $dblms->querylms("SELECT id_cat, amount FROM ".FEE_PARTICULARS." 
																			WHERE id_cat = '".$rowdoc['cat_id']."' AND id_fee  = '".$feercord['id']."' 
																			LIMIT 1");
										if(mysqli_num_rows($sqllmsfeeprt)>0) { 
											$valuefeeprt = mysqli_fetch_array($sqllmsfeeprt);
											$remarks = '';
											if (!empty($valuefeeprt['amount']) || $valuefeeprt['amount'] != 0 || $valuefeeprt['amount']) {
												if ($rowdoc['cat_id'] == $id_scholarship) {
													$valuefeeprt['amount'] -= $scholarship;
												}
												if ($rowdoc['cat_id'] == $id_concession) {
													$valuefeeprt['amount'] -= $concession;
												}
												echo '
												<tr>
													<td style="font-size: 10px;">'.$rowdoc['cat_name'].$remarks.'</td>
													<td style="font-size: 10px; text-align:right; width:45%;">'.number_format($valuefeeprt['amount']).'</td>
												</tr>';
											}
										}	
									}
									if (!empty($feercord['fine']) || $feercord['fine'] != 0 || $feercord['fine']) {
										echo'
										<tr>
											<td>Fine</td>
											<td style="text-align:right; width:45%;">'.number_format($feercord['fine']).'</td>
										</tr>';
										$grandTotal += $feercord['fine'];
									}
								}
								echo'
								<tr>
									<td style="text-align:left; font-size:12px; font-weight:bold; border:2px solid #333;">Grand Total</td>
									<td style="text-align:right; font-size:12px; font-weight:bold;  border:2px solid #333;">'.number_format($feercord['total_amount']).'</td>
								</tr>
							</table>';
							if($_SESSION['userlogininfo']['LOGINAFOR'] != 3) { 
								echo '<span style="font-size:9px;">Issue By: '.cleanvars($_SESSION['userlogininfo']['LOGINNAME']).'</span>';
							}
							echo'
							<span style="font-size:9px; float:right; margin-top:3px;">Print Date: '.date("m/d/Y").'</span>
						</div>
						<div style="clear:both;"></div>
						<div style="font-size:13px; color:#000; margin-top:20px;">
							<table width="100%" border="0" style="border-collapse:collapse;" cellpadding="0" cellspacing="5">
								<tr>
									<td style="font-weight:normal; font-style:italic; text-align:left; font-size:11px; width:80%;">Rupees in word: <span style="text-decoration:underline; font-size:9px; color:#000;">'.convert_number_to_words($feercord['total_amount']).' only</span></td>
									<td style="font-weight:normal; font-style:italic; text-align:right;">Cashier</td>
								</tr>
								<tr>
									<td style="font-weight:normal; font-style:italic; color: #777777; text-align:left; font-size:9px;">';
										$sqllmsChallanDes = array ( 
																'select' 	=> ' 
																					chl_desc 
																				',
																'where' 	=> array( 
																						'is_deleted'    		=> '0'
																						, 'chl_desc_status'    	=> '1'
																						, 'id_campus'			=> cleanvars($id_campus)
																					),
																'return_type' 	=> 'single' 
															); 
										$rowsChallanDes  = $dblms->getRows(CHALLAN_DESCRIPTION, $sqllmsChallanDes);
										echo html_entity_decode(html_entity_decode($rowsChallanDes['chl_desc']));
										echo '
									</td>
									<td>';
										// GENERATE QR CODE
										/*
										$dataQR = array(
											'challan_no'	=> $feercord['challan_no'],
											'id_std'		=> $feercord['id_std'],
											'id_class'		=> $feercord['id_class'],
										);
										$dataJSON = json_encode($dataQR);
										*/
										$dataJSON = $feercord['id'].','.cleanvars($id_campus);
										echo'
										<div id="qrcode_'.$feercord['challan_no'].'_'.$ifee.'"></div>
										
										<script>
											const qrcode_'.$feercord['challan_no'].'_'.$ifee.' = new QRCode(document.getElementById(\'qrcode_'.$feercord['challan_no'].'_'.$ifee.'\'), {
												text: \''.$dataJSON.'\',
												width: 60,
												height: 60,
												colorDark: "#000",
												colorLight: "#fff",
												correctLevel: QRCode.CorrectLevel.H
											});
										</script>';
										echo '
									</td>
								</tr>
							</table>
						</div>
					</td>';
					}
				}
				echo'
				</tr>
			</table>';
			if (count($_SESSION['userlogininfo']['PRINTCOPY']) == 3) {
				if ($iForBreak % 1 == 0) {
					echo '<div class="page-break"></div>';
				}
			} else {
				if ($iForBreak % 2 == 0) {
					echo '<div class="page-break"></div>';
				}
			}
			echo '
			<script>
				window.print();
			</script>';
		}
	}
	echo'
	</body>
</html>';
?>