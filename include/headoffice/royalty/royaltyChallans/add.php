<?php
$campus = '';
$month_id = '';
$due_date = '';
if(isset($_POST['campus'])){$campus = $_POST['campus'];}	
if(isset($_POST['month'])){$month_id = $_POST['month'];}	
if(isset($_POST['due_date'])){$due_date = $_POST['due_date'];}
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
	</header>
	<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
		<div class="panel-body">
			<div class="row mb-lg">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Campus <span class="required">*</span></label>
						<select data-plugin-selectTwo data-width="100%" name="campus" id="campus" required title="Must Be Required" class="form-control populate">
							<option value="">Select</option>';
							$sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
																	FROM ".CAMPUS." c  
																	WHERE c.campus_id != '' AND campus_status = '1'
																	ORDER BY c.campus_name ASC");
							while($value_campus = mysqli_fetch_array($sqllmscampus)){
								if($value_campus['campus_id'] == $campus){
									echo'<option value="'.$value_campus['campus_id'].'" selected>'.$value_campus['campus_name'].'</option>';
								}else{
									echo'<option value="'.$value_campus['campus_id'].'">'.$value_campus['campus_name'].'</option>';
								}
							}
							echo'
						</select>
					</div>
				</div>
				<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Month <span class="required">*</span></label>
					<select data-plugin-selectTwo data-width="100%" name="month" id="month" required title="Must Be Required" class="form-control populate">
						<option value="">Select</option>';
						foreach($monthtypes as $month){
							if($month['id'] == $month_id){
								echo'<option value="'.$month['id'].'" selected>'.$month['name'].'</option>';
							} else {
								echo'<option value="'.$month['id'].'">'.$month['name'].'</option>';
							}
						}
						echo'
						</select>
				</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Due Date <span class="required">*</span></label>
						<input type="text" class="form-control" name="due_date" id="due_date" value="'.$_POST['due_date'].'" data-plugin-datepicker required title="Must Be Required"/>
					</div>
				</div>
			</div>
			<center>
				<button type="submit" name="view_detail" id="view_detail" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
			</center>
		</div>
	</form>
</section>';

// SEARCH RESULTS
if(isset($_POST['view_detail'])){
	$sqllmscheck  = $dblms->querylms("SELECT id
										FROM ".FEES." 
										WHERE id_type	= '3'
										AND id_month	= '".cleanvars($month_id)."'
										AND id_campus	= '".cleanvars($campus)."'
										AND id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' LIMIT 1");
	if(mysqli_num_rows($sqllmscheck)){
		$_SESSION['msg']['title'] 	= 'Error';
		$_SESSION['msg']['text'] 	= 'Record Already Exists';
		$_SESSION['msg']['type'] 	= 'error';
		header("Location: royaltyChallans.php?view=add", true, 301);
		exit();
	}else{
		$sqllmsRoyaltyCheck	= $dblms->querylms("SELECT id, royalty_type, grand_total
												FROM ".ROYALTY_SETTING." 
												WHERE is_deleted	= '0'
												AND id_campus		= '".$campus."'
												ORDER BY id DESC LIMIT 1");
		if(mysqli_num_rows($sqllmsRoyaltyCheck) > 0){	
			$valRoyaltyCheck = mysqli_fetch_array($sqllmsRoyaltyCheck);
			$grandTotal = $valRoyaltyCheck['grand_total'];								
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-list"></i>  Royalty Detail</h2>
				</header>
				<div class="panel-body">
					<form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<input type="hidden" name="id_campus" id="id_campus" value="'.$campus.'">
						<input type="hidden" name="id_month" id="id_month" value="'.$month_id.'">
						<input type="hidden" name="due_date" id="due_date" value="'.$due_date.'">
						<fieldset>
							<div class="panel-body">';	
								// ROYALTY PARTICULARS
								$sqllmsParticulars = $dblms->querylms("SELECT part_id, part_name, part_type, part_for, part_amount_type, part_months
																			FROM ".ROYALTY_PARTICULARS."
																			WHERE part_status	= '1'
																			AND is_deleted		= '0'
																			AND part_type	   != '0'
																			ORDER BY part_name ASC ");
								if(mysqli_num_rows($sqllmsParticulars) > 0){
									// ROYALTY SETTING
									$sqllmsRoyalty	= $dblms->querylms("SELECT id, grand_total
																			FROM ".ROYALTY_SETTING." r
																			WHERE id_campus	= '".$campus."'
																			AND is_deleted	= '0'
																			ORDER BY id DESC LIMIT 1");
									$valRoyaltyCheck = mysqli_fetch_array($sqllmsRoyalty);
									echo'
									<div class="table-responsive">
										<table class="table table-bordered table-condensed table-striped mb-none">
											<thead>
												<tr>
													<th width="40" class="center">Sr.</th>
													<th>Title</th>
													<th width="600" class="center">Amount</th>
												</tr>
											</thead>
											<tbody>';
												while($valPart = mysqli_fetch_array($sqllmsParticulars)){
													$totalAmount = '';
					
													// IF ROYALITY ALREADY ADDED
													if(isset($valRoyaltyCheck['id'])){
														// Royalty Setting Detail					
														$sqllmsDetail = $dblms->querylms("SELECT total_amount
																							FROM ".ROYALTY_SETTING_DET."
																							WHERE id_setup		= '".$valRoyaltyCheck['id']."'
																							AND id_particular	= '".$valPart['part_id']."' LIMIT 1");
														if(mysqli_num_rows($sqllmsDetail) > 0){
															$valDetail = mysqli_fetch_array($sqllmsDetail);
															$totalAmount = $valDetail['total_amount'];
														}
													}
													$srno++;
													echo'
													<tr>
														<th class="center">'.$srno.'</th>
														<th>
															<input type="hidden" name="id_particular['.$srno.']" id="id_particular" value="'.$valPart['part_id'].'">
															<input type="hidden" name="part_type['.$srno.']" id="part_type" value="'.$valPart['part_type'].'">
															'.$valPart['part_name'].'
														</th>
														<td class="center">';
															// TYPE == REGULAR
															if($valPart['part_type'] == '1'){
																// FOR STUDENTS
																if($valPart['part_for'] == '1'){
																	echo'<input type="hidden" name="part_for['.$srno.']" id="part_for" value="'.$valPart['part_for'].'">';
																	// FIXED AMOUNT
																	if($valPart['part_amount_type'] == '1'){
																		echo'
																		<input type="hidden" name="totalAmount['.$srno.']" id="totalAmount" value="0"/>
																		<table class="table table-bordered table-condensed table-striped mb-none">
																			<thead>
																				<tr>
																					<th width="40" class="center">Sr.</th>
																					<th>Class</th>
																					<th>Students</th>
																					<th>Amount</th>
																					<th class="center">Total</th>
																				</tr>
																			</thead>
																			<tbody>';
																				$stdSrno = 0;
																				// QUERY CLASSES AND STUDENTS
																				$sqllmsClasses = $dblms->querylms("SELECT c.class_id, c.class_name, COUNT(s.std_id) as students
																													FROM ".CLASSES." c
																													LEFT JOIN ".STUDENTS." s ON (
																														s.id_class = c.class_id
																														AND s.id_campus		= '".$campus."'
																														AND s.std_status	= '1'
																														AND s.is_deleted	= '0'
																														)
																													WHERE c.class_status	= '1'
																													AND c.is_deleted		= '0'
																													GROUP BY c.class_id
																												");
																				while($valClass = mysqli_fetch_array($sqllmsClasses)){
																					// ROYALTY SETTING DETAIL
																					if(isset($valRoyaltyCheck['id'])){
																						$sqllmsDetail = $dblms->querylms("SELECT id_class, no_of_std, amount_for_cat, amount_per_std, tuitionfee_percentage, total_amount
																															FROM ".ROYALTY_SETTING_DET."
																															WHERE id_setup		= '".$valRoyaltyCheck['id']."'
																															AND id_particular	= '".$valPart['part_id']."'
																															AND id_class		= '".$valClass['class_id']."' LIMIT 1
																														");
																						$valDetail = mysqli_fetch_array($sqllmsDetail);
																					}

																					// VARIABLES
																					$amount_per_std = '';
																					$totalClassAmount = '';
																					$no_of_std = $valClass['students'];
																					
																					if(!empty($valDetail['no_of_std'])){
																						$no_of_std = $valDetail['no_of_std'];
																					}
																					if(!empty($valDetail['amount_per_std'])){
																						$amount_per_std = $valDetail['amount_per_std'];
																					}
																					if(!empty($valDetail['total_amount'])){
																						$totalClassAmount = $valDetail['total_amount'];
																					}															
																					$stdSrno ++;

																					echo'
																					<tr>
																						<td class="center">'.$stdSrno.'</td>
																						<th>
																							'.$valClass['class_name'].'
																							<input type="hidden" name="id_class['.$srno.']['.$stdSrno.']" id="id_class'.$srno.''.$stdSrno.'" value="'.$valClass['class_id'].'">
																						</th>
																						<td width="100" class="center">
																							<input type="number" class="form-control stds" name="stds['.$srno.']['.$stdSrno.']" id="stdsA'.$srno.''.$stdSrno.'" value="'.$no_of_std.'"/>
																						</td>
																						<td width="100" class="center"> 
																							<input type="number" class="form-control amount" name="amount['.$srno.']['.$stdSrno.']" id="amountA'.$srno.''.$stdSrno.'"  placeholder="Amount" value="'.$amount_per_std.'"/>
																						</td>
																						<td width="100" class="center">
																							<input type="number" class="form-control totalAmount" name="totalClassAmount['.$srno.']['.$stdSrno.']" id="totalAmountA'.$srno.''.$stdSrno.'" value="'.$totalClassAmount.'" readonly/>
																						</td>
																					</tr>
																					
																					<script type="text/javascript">															
																						//Calculate Total Amount
																						$(document).on("input", "#amountA'.$srno.''.$stdSrno.'", function() {
																							var stds = document.getElementById("stdsA'.$srno.''.$stdSrno.'").value;
																							var amount = document.getElementById("amountA'.$srno.''.$stdSrno.'").value;
																							totalAmount = stds *  amount;
																							$("#totalAmountA'.$srno.''.$stdSrno.'").val(totalAmount);
																			
																							//Grand Total
																							var grandTotal = 0;
																							$(".totalAmount").each(function(){
																								grandTotal += +$(this).val();
																							});
																							$("#grandTotal").val(grandTotal);
																						});
																						
																						//Calculate Total Amount
																						$(document).on("input", "#stdsA'.$srno.''.$stdSrno.'", function() {
																							var stds = document.getElementById("stdsA'.$srno.''.$stdSrno.'").value;
																							var amount = document.getElementById("amountA'.$srno.''.$stdSrno.'").value;
																							totalAmount = stds *  amount;
																							$("#totalAmountA'.$srno.''.$stdSrno.'").val(totalAmount);
																			
																							//Grand Total
																							var grandTotal = 0;
																							$(".totalAmount").each(function(){
																								grandTotal += +$(this).val();
																							});
																							$("#grandTotal").val(grandTotal);
																						});
																					</script>';
																				}
																				echo'
																			</tbody>
																		</table>';
																	}
																	// PERCENTAGE AMOUNT
																	elseif($valPart['part_amount_type'] == '2'){
																		echo'
																		<input type="hidden" class="totalAmount" name="totalAmount[]" id="totalAmount" value="0"/>
																		<table class="table table-bordered table-condensed table-striped mb-none">
																			<thead>
																				<tr>
																					<th width="40" class="center">Sr.</th>
																					<th>Class</th>
																					<th width="70" class="center">Students</th>
																					<th width="80" class="center">Percentage</th>
																					<th class="center">Fee Type</th>
																					<th class="center" width="50">Total</th>
																				</tr>
																			</thead>
																			<tbody>';
																				$stdSrno = 0;
																				$sqllmsClasses = $dblms->querylms("SELECT c.class_id, c.class_name, COUNT(s.std_id) as students
																													FROM ".CLASSES." c
																													LEFT JOIN ".STUDENTS." s ON (
																														s.id_class = c.class_id
																														AND s.id_campus		= '".$campus."'
																														AND s.std_status	= '1'
																														AND s.is_deleted	= '0'
																														)
																													WHERE c.class_status	= '1'
																													AND c.is_deleted		= '0'
																													GROUP BY c.class_id
																												");
																				while($valClass = mysqli_fetch_array($sqllmsClasses)) {				
																					// Royalty Setting Detail																
																					if(isset($valRoyaltyCheck['id'])) {						
																						$sqllmsDetail = $dblms->querylms("SELECT id_class, no_of_std, amount_for_cat, amount_per_std, tuitionfee_percentage, total_amount
																																FROM ".ROYALTY_SETTING_DET."
																																WHERE id_setup = '".$valRoyaltyCheck['id']."'
																																AND id_particular = '".$valPart['part_id']."'
																																AND id_class = '".$valClass['class_id']."' LIMIT 1");
																						$valDetail = mysqli_fetch_array($sqllmsDetail);
																					}
																					$stdSrno ++;																					
																					// Vars
																					$tuitionfee_percentage = '';
																					$amount_for_cat= '';
																					$amount_per_std = '';
																					$totalClassAmount = '';
																					$no_of_std = $valClass['students'];

																					if(!empty($valDetail['no_of_std'])){
																						$no_of_std = $valDetail['no_of_std'];
																					}
																					if(!empty($valDetail['tuitionfee_percentage'])){
																						$tuitionfee_percentage = $valDetail['tuitionfee_percentage'];
																					}
																					if(isset($valDetail['amount_for_cat'])) {
																						$amount_for_cat = $valDetail['amount_for_cat'];
																					}
																					if(!empty($valDetail['amount_per_std'])) {
																						$amount_per_std = $valDetail['amount_per_std'];
																					}
																					if(!empty($valDetail['total_amount'])) {
																						$totalClassAmount = $valDetail['total_amount'];
																					}

																					echo'
																					<tr>
																						<td class="center" width="50">'.$stdSrno.'</td>
																						<th>
																							'.$valClass['class_name'].'
																							<input type="hidden" name="id_class['.$srno.']['.$stdSrno.']" id="id_class'.$srno.''.$stdSrno.'" value="'.$valClass['class_id'].'">
																							</th>
																						<td width="100" class="center">
																							<input type="number" class="form-control stds" name="stds['.$srno.']['.$stdSrno.']" id="stds'.$srno.''.$stdSrno.'" value="'.$no_of_std.'"/>
																						</td>
																						<td width="100" class="center"> 
																							<input type="number" class="form-control percentage" name="tuitionfee_percentage['.$srno.']['.$stdSrno.']" id="tuitionfee_percentage'.$srno.''.$stdSrno.'" min="0" max="100" placeholder="%" value="'.$tuitionfee_percentage.'"/>
																						</td>																
																						<td class="center">
																							<select data-plugin-selectTwo data-width="100%" id="id_cat'.$srno.''.$stdSrno.'" name="id_cat['.$srno.']['.$stdSrno.']" title="Must Be Required" class="form-control populate">
																								<option value="">Select</option>
																								<option value="all" '.($amount_for_cat == '0' ? 'selected' : '').'>All</option>';
																								$sqlfeecat	= $dblms->querylms("SELECT c.cat_id, c.cat_name
																															FROM ".FEE_CATEGORY." c  
																															WHERE c.cat_status	= '1' 
																															AND c.is_deleted	= '0'
																															ORDER BY c.cat_name ASC");
																								while($valfeecat = mysqli_fetch_array($sqlfeecat)){
																									echo'<option value="'.$valfeecat['cat_id'].'" '.($amount_for_cat==$valfeecat['cat_id'] ? 'selected' : '').'>'.$valfeecat['cat_name'].'</option>';
																								}
																								echo'
																							</select>
																						</td>
																						<td width="100" class="center">
																							<div id="get_value'.$srno.''.$stdSrno.'">
																								<input type="hidden" class="form-control amount" name="amount['.$srno.']['.$stdSrno.']" id="amount'.$srno.''.$stdSrno.'"  placeholder="Amount" value="'.$amount_per_std.'" readonly/>
																							</div>
																							<input type="number" class="form-control totalAmount" name="totalClassAmount['.$srno.']['.$stdSrno.']" id="totalAmount'.$srno.''.$stdSrno.'" value="'.$totalClassAmount.'" readonly/>
																						</td>
																					</tr>

																					<script type="text/javascript">
																						// RETURN TOTAL FEE WITH ID_CAT
																						$(document).on("change", "#id_cat'.$srno.''.$stdSrno.'", function(){
																							var id_cat = $(this).val();
																							var tuitionfee_percentage = document.getElementById("tuitionfee_percentage'.$srno.''.$stdSrno.'").value;
																							$.ajax({
																								url: "include/ajax/get_tuitionfee.php",
																								type: "POST",
																								data: { 
																										percentage	: tuitionfee_percentage
																										, id_cat		: id_cat
																										, camp			: '.$campus.'
																										, cls			: '.$valClass['class_id'].'
																										, srno			: '.$srno.'
																										, stdsrno		: '.$stdSrno.'
																									},
																								success: function(msg){
																									$("#get_value'.$srno.''.$stdSrno.'").html(msg); 
																									$("#loading").html(""); 
																									
																									//Calculate Total Amount
																									var stds = document.getElementById("stds'.$srno.''.$stdSrno.'").value;
																									var amount = document.getElementById("amount'.$srno.''.$stdSrno.'").value;
																									totalAmount = stds *  amount;
																									$("#totalAmount'.$srno.''.$stdSrno.'").val(totalAmount);
																					
																									//Grand Total
																									var grandTotal = 0;
																									$(".totalAmount").each(function(){
																										grandTotal += +$(this).val();
																									});
																									$("#grandTotal").val(grandTotal);
																								}
																							});
																						});

																						// RETURN TOTAL FEE WITH PERCENTAGE
																						$(document).on("input", "#tuitionfee_percentage'.$srno.''.$stdSrno.'", function(){
																							var tuitionfee_percentage = $(this).val();
																							var id_cat = document.getElementById("id_cat'.$srno.''.$stdSrno.'").value;
																							$.ajax({
																								url: "include/ajax/get_tuitionfee.php",
																								type: "POST",
																								data: { 
																										percentage	: tuitionfee_percentage
																										, id_cat		: id_cat
																										, camp			: '.$campus.'
																										, cls			: '.$valClass['class_id'].'
																										, srno			: '.$srno.'
																										, stdsrno		: '.$stdSrno.'
																									},
																								success: function(msg){
																									$("#get_value'.$srno.''.$stdSrno.'").html(msg); 
																									$("#loading").html(""); 
																									
																									//Calculate Total Amount
																									var stds = document.getElementById("stds'.$srno.''.$stdSrno.'").value;
																									var amount = document.getElementById("amount'.$srno.''.$stdSrno.'").value;
																									totalAmount = stds *  amount;
																									$("#totalAmount'.$srno.''.$stdSrno.'").val(totalAmount);
																					
																									//Grand Total
																									var grandTotal = 0;
																									$(".totalAmount").each(function(){
																										grandTotal += +$(this).val();
																									});
																									$("#grandTotal").val(grandTotal);
																								}
																							});
																						});
																						
																						// CALCULATE TOTAL AMOUNT
																						$(document).on("load", "#amount'.$srno.''.$stdSrno.'", function() {
																							var stds = document.getElementById("stds'.$srno.''.$stdSrno.'").value;
																							var amount = document.getElementById("amount'.$srno.''.$stdSrno.'").value;
																							totalAmount = stds *  amount;
																							$("#totalAmount'.$srno.''.$stdSrno.'").val(totalAmount);
																			
																							//Grand Total
																							var grandTotal = 0;
																							$(".totalAmount").each(function(){
																								grandTotal += +$(this).val();
																							});
																							$("#grandTotal").val(grandTotal);
																						}); 																		
																						
																						// CALCULATE TOTAL AMOUNT
																						$(document).on("input", "#stds'.$srno.''.$stdSrno.'", function() {
																							var stds = document.getElementById("stds'.$srno.''.$stdSrno.'").value;
																							var amount = document.getElementById("amount'.$srno.''.$stdSrno.'").value;
																							totalAmount = stds *  amount;
																							$("#totalAmount'.$srno.''.$stdSrno.'").val(totalAmount);
																			
																							//Grand Total
																							var grandTotal = 0;
																							$(".totalAmount").each(function(){
																								grandTotal += +$(this).val();
																							});
																							$("#grandTotal").val(grandTotal);
																						});
																					</script>';
																				}
																				echo'
																			</tbody>
																		</table>';
																	}
																}
																// FOR CLASS
																elseif($valPart['part_for'] == '2'){
																	echo'
																	<input type="hidden" name="part_for['.$srno.']" id="part_for" value="'.$valPart['part_for'].'">
																	<input type="hidden" name="totalAmount['.$srno.']" id="totalAmount" value="0"/>
																	<table class="table table-bordered table-condensed table-striped mb-none">
																		<thead>
																			<tr>
																				<th width="40" class="center">Sr.</th>
																				<th>Class</th>
																				<th>Students</th>
																				<th>Amount</th>
																				<th class="center">Total</th>
																			</tr>
																		</thead>
																		<tbody>';
																			$stdSrno = 0;
																			// QUERY CLASSES
																			$sqllmsClasses = $dblms->querylms("SELECT c.class_id, c.class_name, COUNT(s.std_id) as students
																													FROM ".CLASSES." c
																													LEFT JOIN ".STUDENTS." s ON (
																														s.id_class = c.class_id
																														AND s.id_campus		= '".$campus."'
																														AND s.std_status	= '1'
																														AND s.is_deleted	= '0'
																														)
																													WHERE c.class_status	= '1'
																													AND c.is_deleted		= '0'
																													GROUP BY c.class_id
																												");
																			while($valClass = mysqli_fetch_array($sqllmsClasses)){

																				// ROYALTY SETTING DETAIL	
																				if(isset($valRoyaltyCheck['id'])){		
																					$sqllmsDetail = $dblms->querylms("SELECT id_class, no_of_std, amount_per_std, tuitionfee_percentage, total_amount
																														FROM ".ROYALTY_SETTING_DET."
																														WHERE id_setup		= '".$valRoyaltyCheck['id']."'
																														AND id_particular	= '".$valPart['part_id']."'
																														AND id_class		= '".$valClass['class_id']."' LIMIT 1
																													");
																					$valDetail = mysqli_fetch_array($sqllmsDetail);
																				}

																				// VARIABLES
																				$no_of_std = $valClass['students'];
																				$amount_per_std = '';
																				$totalClassAmount = '';
																				
																				if(!empty($valDetail['no_of_std'])){
																					$no_of_std = $valDetail['no_of_std'];
																				}
																				if(!empty($valDetail['amount_per_std'])){
																					$amount_per_std = $valDetail['amount_per_std'];
																				}
																				if(!empty($valDetail['total_amount'])){
																					$totalClassAmount = $valDetail['total_amount'];
																				}															
																				$stdSrno ++;

																				echo'
																				<tr>
																					<td class="center">'.$stdSrno.'</td>
																					<th>
																						'.$valClass['class_name'].'
																						<input type="hidden" name="id_class['.$srno.']['.$stdSrno.']" id="id_class'.$srno.''.$stdSrno.'" value="'.$valClass['class_id'].'">
																					</th>
																					<td width="100" class="center">
																						<input type="number" class="form-control stds" name="stds['.$srno.']['.$stdSrno.']" id="stdsA'.$srno.''.$stdSrno.'" value="'.$no_of_std.'"/>
																					</td>
																					<td width="100" class="center"> 
																						<input type="number" class="form-control amount" name="amount['.$srno.']['.$stdSrno.']" id="amountA'.$srno.''.$stdSrno.'"  placeholder="Amount" value="'.$amount_per_std.'"/>
																					</td>
																					<td width="100" class="center">
																						<input type="number" class="form-control totalAmount" name="totalClassAmount['.$srno.']['.$stdSrno.']" id="totalAmountA'.$srno.''.$stdSrno.'" value="'.$totalClassAmount.'" readonly/>
																					</td>
																				</tr>
																				
																				<script type="text/javascript">															
																					//Calculate Total Amount
																					$(document).on("input", "#amountA'.$srno.''.$stdSrno.'", function() {
																						var stds = document.getElementById("stdsA'.$srno.''.$stdSrno.'").value;
																						var amount = document.getElementById("amountA'.$srno.''.$stdSrno.'").value;
																						totalAmount = stds *  amount;
																						$("#totalAmountA'.$srno.''.$stdSrno.'").val(totalAmount);
																		
																						//Grand Total
																						var grandTotal = 0;
																						$(".totalAmount").each(function(){
																							grandTotal += +$(this).val();
																						});
																						$("#grandTotal").val(grandTotal);
																					});
																					
																					//Calculate Total Amount
																					$(document).on("input", "#stdsA'.$srno.''.$stdSrno.'", function() {
																						var stds = document.getElementById("stdsA'.$srno.''.$stdSrno.'").value;
																						var amount = document.getElementById("amountA'.$srno.''.$stdSrno.'").value;
																						totalAmount = stds *  amount;
																						$("#totalAmountA'.$srno.''.$stdSrno.'").val(totalAmount);
																		
																						//Grand Total
																						var grandTotal = 0;
																						$(".totalAmount").each(function(){
																							grandTotal += +$(this).val();
																						});
																						$("#grandTotal").val(grandTotal);
																					});
																				</script>';
																			}
																			echo'
																		</tbody>
																	</table>';
																}
																// FOR LUMP SUM
																elseif($valPart['part_for'] == '3'){
																	echo'
																	<input type="hidden" name="part_for['.$srno.']" id="part_for" value="'.$valPart['part_for'].'">
																	<input type="number" class="form-control totalAmount" name="totalAmount['.$srno.']" id="totalAmount" value="'.$totalAmount.'"/>';
																}
															}
															// TYPE == IRREGULAR
															elseif($valPart['part_type'] == '2'){
																echo'<input type="number" class="form-control totalAmount" name="totalAmount['.$srno.']" id="totalAmount" value="'.$totalAmount.'"/>';
															}
															echo'
														</td>
													</tr>';
												}
												echo'
											</tbody>
										</table>
										<h5><b>Grand Total</b></h5>	
										<div class="center">
											<input class="form-control" type="number" class="form-control" name="grandTotal" id="grandTotal" value="'.$grandTotal.'" readonly/>
										</div>
									</div>';
								}else{
									echo'<h4 class="text text-danger center">No Royalty Particular Added!</h4>';
								}
							echo'
							</div>
						</fieldset>
						<div class="panel-footer row center" style="margin-bottom: -15px;">
							<button type="submit" name="genrate_challan" id="genrate_challan" class="btn btn-primary">Genarte Royalty Challan</button>
						</div>
					</form>
				</div>
			</section>';
		}else{
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<h2 class="panel-body text-danger text-center mt-none">Royalty Not Added.</h2>
			</section>';
		}
	}
}
?>

<script type="text/javascript">
	//Return Royalty Details
	function get_royalty_type(royalty_type) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_royalty_type.php",
			data: "royalty_type="+royalty_type,
			success: function(msg){  
				$(".getroyaltytype").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	//Grand Total Amount
	$(document).on("input", ".totalAmount", function() {
		var grandTotal = 0;
		$(".totalAmount").each(function(){
			grandTotal += +$(this).val();
		});
		$("#grandTotal").val(grandTotal);
	});
</script>