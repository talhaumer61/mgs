<?php
require_once("include/dbsetting/lms_vars_config.php");	
require_once("include/dbsetting/classdbconection.php");	
require_once ("include/functions/functions.php");	
$dblms = new dblms();	
require_once("include/functions/login_func.php");

// GET CHALLAN
$sqlRoyalty  = $dblms->querylms("SELECT f.id, f.challan_no, f.id_month, f.issue_date, f.due_date, f.remaining_amount, f.total_amount, c.campus_id, c.campus_regno, c.campus_name, c.id_ad, c.id_de, z.zone_name
									FROM ".FEES." f
									INNER JOIN ".CAMPUS." c ON c.campus_id = f.id_campus
									LEFT JOIN ".ZONES." z ON z.zone_id = c.id_zone
									WHERE f.id_type		= '3'
									AND f.is_deleted	= '0'
									AND f.challan_no	= '".cleanvars($_GET['id'])."' LIMIT 1");
$valRoyalty = mysqli_fetch_array($sqlRoyalty); 

// ROYALTY CHALLAN DETAIL
$sqllmsDetail  = $dblms->querylms("SELECT part_name, SUM(total_amount) AS amount 
									FROM ".ROYALTY_CHALLAN_DET." d							
									INNER JOIN ".ROYALTY_PARTICULARS." p ON p.part_id = d.id_particular
									WHERE d.id_setup = '".$valRoyalty['id']."'
									GROUP BY d.id_particular");
echo'
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<!--WEB ICON-->
		<link rel="shortcut icon" href="assets/images/favicon.png">

		<title>Royalty Challan Print</title>
		<style type="text/css">
			body { font-size:20px; overflow: -moz-scrollbars-vertical; margin:0; font-family: Times New Roman; }
			h1 { font-size:33px; font-weight:700; margin:0; margin-bottom:0; padding-bottom:0;  }
			h2 { font-size:27px; font-weight:normal; margin-top:0; }
			.admissionform { background-color:#000; color:#fff; width:400px; font-size:32px; font-weight:700; } 
			th { font-weight:600; font-size:18px; padding: 5px; }
			td { font-size:18px; padding: 4px;}

			ul li{
				padding: 5px;
			}

			@media all {
				.page-break	{ display: none; }
			}

			@media print {
				.page-break	{ display: block; page-break-before: always; }
				@page { 
					size: letter;
					size: portrait; 
				}
			}	
		</style>
	</head>
	<body id="borderimg2">
		<center>
			<div style="margin-top:10px; ">				
				<div class="content">	
					<table width="960" border="0" align="center">
						<tbody>
							<tr>
								<td style="text-align:center; ">
									<img src="uploads/campuses/cms.png" height="100"/> 
								</td>
								<td style="text-align:center;">
									<h1 style="font-size: 3rem; font-family:Arial, Helvetica, sans-serif;"> <b> <u>Minhaj Education Society</u>  </b> </h1>
									<b style="font-size: 1.1rem; font-family: Times New Roman; margin-top: 5px;"> Aghosh Complex, Shah-e-Jilani Road, Civic Center, Township, Lahore.</b>
									<h1 style="font-size: 1.4rem; font-family: Times New Roman; margin-top: 5px; color: red;"> <b>Meezan Bank Account: 0293-0104623590</b> </h1>
								</td>
							</tr>
						</tbody>
					</table>					
					<table width="960" border="0" align="center">
						<tbody>
							<tr>
								<td style="text-align:left;"> Institution:<b> '.$valRoyalty['campus_name'].'</b> </td>
								<td style="text-align:right;"> Month:<b> '.get_monthtypes($valRoyalty['id_month']).'</td>
							</tr>
						</tbody>
					</table>					
					<table width="960" border="0" align="center">
						<tbody>
							<tr>
								<td style="text-align:left;">MES Regno:<b> '.$valRoyalty['campus_regno'].'</b>  </td>
								<td style="text-align:right;"><b>Issue Date: '.date('d M Y', strtotime($valRoyalty['issue_date'])).'</b>  </td>
							</tr>
						</tbody>
					</table>					
					<table width="960" border="0" align="center">
						<tbody>
							<tr>
								<td style="text-align:left;"> Zone:<b> '.$valRoyalty['zone_name'].'</td>
								<td style="text-align:right;"><b>Due Date: </b><b style="color:red"> '.date('d M Y', strtotime($valRoyalty['due_date'])).'</b></td>
							</tr>
						</tbody>
					</table>
					<table width="960" border="0" align="center">
						<tbody>';
							if($valRoyalty['id_ad']!=0){
								$sqlADE  = $dblms->querylms("SELECT emply_name, emply_phone
																	FROM ".EMPLOYEES."
																	WHERE emply_id = '".$valRoyalty['id_ad']."' ");
								$valADE = mysqli_fetch_array($sqlADE);
								echo'
								<tr>
									<td style="text-align:left;"> ADE Name:<b> '.$valADE['emply_name'].'</td>
									<td style="text-align:right;">Contact No: <b> '.$valADE['emply_phone'].'</b></td>
								</tr>';
							}
							if($valRoyalty['id_de']!=0){
								$sqlDDE  = $dblms->querylms("SELECT emply_name, emply_phone
																	FROM ".EMPLOYEES."
																	WHERE emply_id = '".$valRoyalty['id_de']."' ");
								$valDDE = mysqli_fetch_array($sqlDDE);
								echo'
								<tr>
									<td style="text-align:left;"> DDE Name:<b> '.$valDDE['emply_name'].'</td>
									<td style="text-align:right;">Contact No: <b> '.$valDDE['emply_phone'].'</b></td>
								</tr>';
							}
							echo'
						</body>
					</table>
					<br>
					<table  style="border-collapse: collapse;" width="960" border="0" align="center">
						<tbody>
							<tr style=" border: 1px solid">
								<td style="text-align:center; color: #41C3F3; font-size: 20px; font-weight: bold;" colspan="3">  Monthly Dues</td>
							</tr>';
							$sr = 0;
							while($valDetail = mysqli_fetch_array($sqllmsDetail)) {
								$sr++;
								echo'
								<tr>
									<td style="border: 1px solid black; width:50px; text-align:center;">'.$sr.'</td>
									<td style=" border: 1px solid black;">'.$valDetail['part_name'].'</td>
									<td style=" border: 1px solid black; text-align: center;">'.$valDetail['amount'].'</td>
								</tr>';
							}
							echo'
							<tr>
								<td style="text-align:center;border: 1px solid black;" colspan="2"> <b>Total </b> </td>
								<td style=" border: 1px solid black; text-align: center;"> <b>'.$valRoyalty['total_amount'].'</b></td>
							</tr>
						</tbody>
					</table>';
					// echo'				
					// 	<br><br>						
					// 	<table style="border-collapse: collapse;" width="960" border="0" align="center">
					// 		<tbody>
					// 		<td width="890"></td>
					// 		<td width="130" align="left" style="border-bottom:1px solid #666666 !important; text-align:left;"></td>

					// 		</tbody>
					// 	</table>
					// 	<br/>
					// 	<table width="960" border="0" align="center">
					// 		<tbody>
					// 			<tr>
					// 				<td width="890"> </td>
					// 				<td width="130" style="text-align:right;"> Director &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
					// 			</tr>
					// 		</tbody>
					// 	</table>
					// 	<br/>
					// ';
					echo'
					<table width="960" border="0" align="center" style="margin-top:7rem;">
						<tbody>
							<tr>	
								<td width="600"> 
									<table style="border-collapse: collapse;"  border="0" align="center">
										<tbody>
											<tr>
												<td   width=""style="text-align:center; border: 1px solid black;  "> <b style="color:#41C3F3" >Instrucation </b></td>
											</tr> 
											<tr>
												<td style="text-align:left; border: 1px solid black;">
													<ul>
														<li> Please Pay Your Dues Before Due Date In Given Account</li>
														<li> Share Scanned Deposited Slip / Screen Short By WhatsApp To ADE/DDEs, DDI & Accounts Officer In Head Office</li>
														<li> If The Whole Dues Are Already Deposited, Then Discard This Challan Form And Share The Deposit Slip With Details To Head Office</li>	
														<li> In Case of Partial Deposit, Share The Deposited Amount Details And Deposit The Remaining Dues.</li>
														<li> <b>In Case Of Late Payment, Rs.500 Per Week Will Be Charged To The Campus As Late Payment Charges.</b></li>
														<li> In Ccase Of Any Query Feel Free To Contact Head Office</li>
														<li style="color:#41C3F3">	Head Office Can Review This Voucher When Needed</li>
													</ul>
												</td>
											</tr>
										</tbody>
									</table>
								</td> 

								<td width="250"> 
									<table style="border-collapse: collapse;"  border="0" align="center">
										<tbody>
											<tr>
												<td colspan="4" width="220" style="text-align:center; border: 1px solid black;"> <b style="color:#41C3F3">Total History </b> </td>
											</tr>
											<tr>
												<td  width="50"style="border: 1px solid black;text-align:center;  color:red; "> <b>Total  </b> </td>
												<td  width="50"style="border: 1px solid black;text-align:center;  color:red; "> <b>Paid  </b> </td>
												<td  width="50"style="border: 1px solid black;text-align:center;  color:red; "> <b>Balance  </b> </td>
											</tr>';
												$sqllmsRoyaltyHistory  = $dblms->querylms("SELECT id, id_month, SUM(total_amount) AS total, SUM(paid_amount) AS paid, SUM(remaining_amount) AS remaining
																								FROM ".FEES."
																								WHERE id_type = '3' AND is_deleted != '1' 
																								AND id_campus = '".$valRoyalty['campus_id']."'
																								ORDER BY id DESC");
								
												$valHistory = mysqli_fetch_array($sqllmsRoyaltyHistory);
												echo'
												<tr>
													<td width="50"style="border: 1px solid black;text-align:center; ">'.number_format($valHistory['total']).'</td>
													<td width="50"style="border: 1px solid black;text-align:center; ">'.number_format($valHistory['paid']).'</td>
													<td width="50"style="border: 1px solid black;text-align:center; ">'.number_format($valHistory['total'] - $valHistory['paid']).'</td>
												</tr>';
											echo'
										</tbody>
									</table>
									<table style="border-collapse: collapse; margin-top: 10px;"  border="0" align="center">
										<tbody>
											<tr>
												<td colspan="4" width="220" style="text-align:center; border: 1px solid black;"> <b style="color:#41C3F3">Last Six Months History </b> </td>
											</tr>
											<tr>
												<td width="50"style="border: 1px solid black;text-align:center; color:red;"> <b>Month</b> </td>
												<td width="50"style="border: 1px solid black;text-align:center;  color:red; "> <b>Total  </b> </td>
												<td width="50"style="border: 1px solid black;text-align:center;  color:red; "> <b>Paid  </b> </td>
												<td width="50"style="border: 1px solid black;text-align:center;  color:red; "> <b>Balance  </b> </td>
											</tr>';
												// AND id_month = '".$month['id']."' 
												$sqllmsRoyaltyHistory  = $dblms->querylms("SELECT id, id_month, total_amount, paid_amount, remaining_amount
																								FROM ".FEES."
																								WHERE id_type = '3' AND is_deleted != '1' 
																								AND id_campus = '".$valRoyalty['campus_id']."'
																								AND id != '".$valRoyalty['id']."' ORDER BY id DESC");
												if(mysqli_num_rows($sqllmsRoyaltyHistory)>0){													
													while($valHistory = mysqli_fetch_array($sqllmsRoyaltyHistory)){
														echo'
														<tr>
															<td width="50"style="border: 1px solid black;text-align:left;">'.get_monthtypes($valHistory['id_month']).'</td>
															<td width="50"style="border: 1px solid black;text-align:center; ">'.number_format($valHistory['total_amount']).'</td>
															<td width="50"style="border: 1px solid black;text-align:center; ">'.number_format($valHistory['paid_amount']).'</td>
															<td width="50"style="border: 1px solid black;text-align:center; ">'.number_format($valHistory['total_amount'] - $valHistory['paid_amount']).'</td>
														</tr>';
													}
												}else{
													echo'
													<tr>
														<td colspan="4" width="50"style="border: 1px solid black;text-align:center;">No History Found</td>
													</tr>';
												}
											echo'
										</tbody>
									</table>
								</td>
							</tr>	
						</tbody>
					</table>
					<br>					
					<table width="960" border="0" align="center">
						<tbody>
							<tr>
								<td style="text-align:center;" >
									<i>Controlled Document, Institutions Department</i><br>
									<b>Minhaj Education Society, Pakistan</b>
								</td>
							</tr>
						</tbody>
					</table>
					<br><br><br>
				</div>
			</div>
		</center>
		<!--<div class="page-break"></div>-->
	</body>
	<script type="text/javascript" language="javascript1.2">
		//Do print the page
		if (typeof(window.print) != "undefined") {
			window.print();
		}
	</script>
</html>';
?>