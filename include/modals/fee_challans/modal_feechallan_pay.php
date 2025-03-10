<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))) { 
	if($_SESSION['userlogininfo']['LOGINCAMPUS'] == $_GET['id_campus'] || in_array($_GET['id_campus'], explode(',',$_SESSION['userlogininfo']['SUBCAMPUSES']))){
		// Query get data
		$sqllms	= $dblms->querylms("SELECT  f.id, f.status, f.id_type, f.id_month, f.challan_no, f.id_session, f.id_class, f.id_section, f.id_std, f.pay_mode, f.id_collector,
									f.issue_date, f.due_date, f.total_amount, f.paid_amount, f.prev_remaining_amount, f.scholarship, f.concession, f.fine, f.remaining_amount, f.note, 
									c.class_id, c.class_name,
									cs.section_id, cs.section_name,
									s.session_id, s.session_name,
									st.std_id, st.std_name, st.std_regno, st.std_phone
									FROM ".FEES." f				   
									INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
									LEFT JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section							 
									INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session							 
									INNER JOIN ".STUDENTS." st ON st.std_id 	 = f.id_std
									WHERE f.id_campus = '".cleanvars($_GET['id_campus'])."'
									AND f.id = '".cleanvars($_GET['id'])."'
									ORDER BY f.challan_no DESC");
		$rowsvalues = mysqli_fetch_array($sqllms);
		if(date('Y-m-d') > $rowsvalues['due_date']) {
			$granTotal = $rowsvalues['total_amount'];
			$due_date_after_five_day = date('Y-m-d', strtotime($rowsvalues['due_date']. ' + 5 days'));
			if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 1) {
				$granTotal += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
			} else if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 2) {
				if ($due_date_after_five_day > date('Y-m-d')) {
					$granTotal += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
				} else if ($due_date_after_five_day < date('Y-m-d')) {
					$granTotal += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][2];
				} else {
					$granTotal += LATEFEE;	
				}
			} else {
				$granTotal += LATEFEE;
			} 
		}else{
			$granTotal = $rowsvalues['total_amount'];
		}
		echo'
		<script src="assets/javascripts/user_config/forms_validation.js"></script>
		<script src="assets/javascripts/theme.init.js"></script>
		<section class="panel panel-featured panel-featured-primary">
			<form action="fee_challans.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Or Pay Fee Challan </h2>
				</header>';
				if ($rowsvalues['status'] == 1) {
					echo'
					<div class="panel-body">
						<center>
							<h3 class="p-5 text-success">Challan Already Paid!</h3>
						</center>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button class="btn btn-default modal-dismiss" data-dismiss="modal">Cancel </button>
							</div>
						</div>
					</footer>';
				} else {
					echo'				
					<div class="panel-body">
						<input type="hidden" name="id_fee" id="id_fee" value="'.cleanvars($_GET['id']).'">
						<input type="hidden" name="challan_no" id="challan_no" value="'.$rowsvalues['challan_no'].'">
						<input type="hidden" name="std_phone" id="std_phone" value="'.$rowsvalues['std_phone'].'">
						<input type="hidden" name="id_std" id="id_std" value="'.$rowsvalues['id_std'].'">
						<input type="hidden" name="id_month" id="id_month" value="'.$rowsvalues['id_month'].'">
						<input type="hidden" name="due_date" id="due_date" value="'.$rowsvalues['due_date'].'">
						<input type="hidden" name="scholarship" id="scholarship" value="'.$rowsvalues['scholarship'].'">
						<input type="hidden" name="concession" id="concession" value="'.$rowsvalues['concession'].'">
						<input type="hidden" name="id_campus" value="'.cleanvars($_GET['id_campus']).'">';
						$sqllmscheck = $dblms->querylms("SELECT f.id, f.challan_no
															FROM ".FEES." f						 
															INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std
															WHERE f.status IN (2,4)
															AND f.is_deleted	= '0'
															AND f.id_std		= '".cleanvars($rowsvalues['std_id'])."'
															AND st.is_deleted	= '0'
															AND f.id_campus		= '".cleanvars($_GET['id_campus'])."'
															ORDER BY f.id DESC LIMIT 1");
						$valuesqllmscheck = mysqli_fetch_array($sqllmscheck);

						if($valuesqllmscheck['challan_no'] == $rowsvalues['challan_no']){
							echo'
							<div class="table-responsive">
								<table class="table table-bordered table-striped mb-none">
									<thead>
										<tr>
											<th class="center">Month</th>
											<th class="center">Challan</th>
											<th class="center">Due Date</th>
											<th class="center">Payable</th>
										</tr>
									</thead>
									<tbody>';
									$grandTotal = 0;
									foreach ($monthtypes as $month):
										// CURRENT MONTH
										if($rowsvalues['id_month']==$month['id']){

											$year = date("Y", strtotime($rowsvalues['due_date']));
											$narChallan = $rowsvalues['challan_no'];
											$amount = $rowsvalues['total_amount'] - $rowsvalues['paid_amount'];
											
											$due_date = $rowsvalues['due_date'];

											if($due_date < date('Y-m-d')) {
												$due_date_after_five_day = date('Y-m-d', strtotime($due_date. ' + 5 days'));
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
												<td>'.$month['name'].' '.$year.'</td>
												<td style="text-align:center;">'.$narChallan.'</td>
												<td style="text-align:center;">'.$rowsvalues['due_date'].'</td>
												<td style="text-align:right;">'.number_format($amount).'</td>
											</tr>';
										}
										
										// PREVIOUS MONTHS
										else{
											$sqlnarration  = $dblms->querylms("SELECT f.id, f.id_month, f.challan_no, f.id_std,
																				f.issue_date, f.due_date, f.total_amount, f.paid_amount, f.scholarship, f.concession, f.fine, f.remaining_amount
																				FROM ".FEES." f
																				WHERE f.id_campus	= '".cleanvars($_GET['id_campus'])."'
																				AND f.id_month		= '".cleanvars($month['id'])."'
																				AND f.id_std		= '".cleanvars($rowsvalues['id_std'])."'
																				AND f.status IN (2,4)
																				AND f.id_type IN (1,2)
																				AND f.is_deleted	= '0'
																				LIMIT 1");
											if(mysqli_num_rows($sqlnarration)>0){
												$valnarration = mysqli_fetch_array($sqlnarration);

												$year = date("Y", strtotime($valnarration['due_date']));
												$narChallan = $valnarration['challan_no'];
												$amount = $valnarration['total_amount'] - $valnarration['paid_amount'];

												$due_date = $valnarration['due_date'];

												if($due_date < date('Y-m-d')) {
													$due_date_after_five_day = date('Y-m-d', strtotime($due_date. ' + 5 days'));
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
											}else{
												$narChallan = '';
												$amount = 0;
											}
											if($amount>0){
												echo'
												<tr>
													<td>'.$month['name'].' '.$year.'</td>
													<td style="text-align:center;">'.$narChallan.'</td>
													<td style="text-align:center;">'.$valnarration['due_date'].'</td>
													<td style="text-align:right;">'.number_format($amount).'</td>
												</tr>';
											}
										}
										$grandTotal = $grandTotal + $amount;
									endforeach;
									echo'
										<tr>
											<td colspan="3" style="text-align:right;"><b>Grand Total</b></td>
											<td style="text-align:right;">'.number_format($grandTotal).'</td>
										</tr>
									</tbody>
								</table>
							</div>';
						}
						echo'
						<div class="form-group">';
							$onlineTotalPaid = 0;
							if($rowsvalues['paid_amount'] > 0 ){
								$onlineTotalPaid = $rowsvalues['paid_amount'];
								$totalRemAmount = $granTotal - ($onlineTotalPaid);
								echo'
								<div class="col-md-4">
									<label class="control-label">Total Amount </label>
									<input type="text" id="payable" name="payable" value="'.$granTotal.'" class="form-control payable" readonly/>
									<input type="hidden" id="payable" name="total_amount" class="totalPayable total_amount" required title="Must Be Required" value="'.$rowsvalues['total_amount'].'" readonly/>
								</div>
								<div class="col-md-4">
									<label class="control-label">Partial Paid </label>
									<input type="text" id="paid_amount" name="paid_amount" value="'.$onlineTotalPaid.'" class="form-control paid_amount" readonly/>
								</div>
								<div class="col-md-4">
									<label class="control-label">Remaining Amount <span class="required">*</span></label>
									<input type="text" class="form-control rem_amount" id="rem_amount" required title="Must Be Required" value="'.$totalRemAmount.'" readonly/>
								</div>

								<input type="hidden" id="totAmount" name="totAmount" required title="Must Be Required" value="'.$totalRemAmount.'" readonly/>';
							} else if ($valuesqllmscheck['challan_no'] != $rowsvalues['challan_no'] && $rowsvalues['paid_amount'] == 0) {
								echo '
								<div class="col-md-12">
									<label class="control-label">Total Amount </label>
									<input type="text" id="payable" name="payable" value="'.$granTotal.'" class="form-control paid_amount" readonly/>
									<input type="hidden" id="payable" name="total_amount" class="totalPayable total_amount" required title="Must Be Required" value="'.$rowsvalues['total_amount'].'" readonly/>
								</div>';
							}
							echo'
						</div>
						
						<div class="form-group">';
							if($valuesqllmscheck['challan_no'] == $rowsvalues['challan_no']){
								$col46 = "col-md-3";
								echo'
								<div class="'.$col46.'">
									<label class="control-label">Received Amount</label>
									<input type="number" id="totaltransamount" name="totaltransamount" value="'.$grandTotal.'" class="form-control totaltransamount paid"/>
									<input type="hidden" id="grandTotal" name="grandTotal" value="'.$grandTotal.'" class="total_amount"/>
									<input type="hidden" id="payable" name="total_amount" class="totalPayable total_amount" required title="Must Be Required" value="'.$rowsvalues['total_amount'].'" readonly/>
								</div>';
							}
							else{ $col46 = "col-md-4"; }
							echo'
							<div class="'.$col46.'">
								<label class="control-label" id="label_id_collector">Collector </label>
								<input type="hidden" id="id_collector" name="id_collector" value="'.$_SESSION['userlogininfo']['LOGINIDA'].'" />
								<input type="text" class="form-control" value="'.$_SESSION['userlogininfo']['LOGINNAME'].'" disabled/>
							</div>
							<div class="'.$col46.'">
								<label class="control-label" id="label_pay_mode">Pay Mode </label>
								<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="pay_mode" name="pay_mode">
									<option value="">Select</option>';
									foreach($paymethod as $method){
										echo'<option value="'.$method['id'].'" '.(($rowsvalues['pay_mode']==$method['id'] || $method['id'] == 1) ? 'selected' : '').'>'.$method['name'].'</option>';
									}
									echo'
								</select>
							</div>
							<div class="'.$col46.'">
								<label class="control-label" id="label_paid_date">Paid Date </label>
								<input type="text" id="paid_date" name="paid_date" class="form-control" value="'.date('m/d/Y' , strtotime(date('Y-m-d'))).'" data-plugin-datepicker title="Must Be Required" />
							</div>
						</div>
						<!--
						<div class="col-md-6">
							<label class="control-label">Paid Amount </label>
							<input type="text" id="paid_amount" name="paid_amount" class="form-control paid"/>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="control-label">Rem. Amount </label>
								<input type="text" id="remaining_amount" name="remaining_amount" class="form-control" readonly/>
							</div>
						</div> -->
						<div class="form-group">
							<div class="col-md-12">
								<label class="control-label">Note </label>
								<textarea class="form-control" rows="2" name="note" id="note">'.$rowsvalues['note'].'</textarea>
							</div>
						</div>';
						if($valuesqllmscheck['challan_no'] != $rowsvalues['challan_no']){
							echo'
							<div class="form-group">
								<label class="col-md-2 control-label">Status <span class="required">*</span></label>
								<div class="col-md-10">
									<div class="radio-custom radio-inline">
										<input type="radio" id="status1" name="status" value="1"'; if($rowsvalues['status'] == 1) {echo' checked';}echo'>
										<label for="radioExample1">Paid</label>
									</div>'; 
									if($rowsvalues['status'] != 4 ) {echo' 
									<div class="radio-custom radio-inline">
										<input type="radio" id="status2" name="status" value="2"'; if($rowsvalues['status'] == 2) {echo' checked';}echo'>
										<label for="radioExample2">Pending</label>
									</div>';
									}
									echo '
								</div>
							</div>
							';
						}
						echo'
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">';
								if($valuesqllmscheck['challan_no'] == $rowsvalues['challan_no']){
									echo'<button type="submit" onClick=\'return confirmAddPayment()\' class="btn btn-primary mr-xs" id="save_and_print" name="save_and_print">Save & Print</button>';
									echo'<button type="submit" onClick=\'return confirmAddPayment()\' class="btn btn-primary" id="save_only" name="save_only">Save</button>';
								}else{
									echo'<button type="submit" onClick=\'return confirmUpdate()\' class="btn btn-primary" id="changes_challan" name="changes_challan">Save</button>';
								}
								echo'
								<button class="btn btn-default modal-dismiss" data-dismiss="modal">Cancel </button>
							</div>
						</div>
					</footer>';
				}
				echo'
			</form>
		</section>';
	}
}
?>

<script type="text/javascript">
	function confirmUpdate() {
		var agree=confirm("Are you sure you want to Update Challan?");
		if (agree)
		return true ;
		else
		return false ;
	}
	function confirmAddPayment() {
		var agree=confirm("Are you sure you want to Add Payment?");
		if (agree)
		return true ;
		else
		return false ;
	}
    $(document).on("keyup", ".sum", function() {
        var sum = 0;
        $(".sum").each(function(){
            sum += +$(this).val();
        });

        var sub = 0;
        $(".sub").each(function(){
            sub += +$(this).val();
        });

        var total_amount = sum - sub
        $(".total_amount").val(total_amount);

		var paid_amount = $("#paid_amount").val();
		var rem_amount = total_amount - paid_amount;
        $(".rem_amount").val(rem_amount);
    });

	$(".totaltransamount").on("keyup", function() {
		var valTotal = $("#totaltransamount");
		if (valTotal.val() < 0) {
			valTotal.val(0);
		}
	});
	
    $(document).on("keyup", ".sub", function() {
        var sum = 0;
        $(".sum").each(function(){
            sum += +$(this).val();
        });
        
        var sub = 0;
        $(".sub").each(function(){
            sub += +$(this).val();
        });

        var total_amount = sum - sub
        $(".total_amount").val(total_amount);

		var paid_amount = $("#paid_amount").val();
		var rem_amount = total_amount - paid_amount;
        $(".rem_amount").val(rem_amount);
    });
	
    $(document).ready(function(){
        var sts1 = $('#status1'); 
        var sts2 = $('#status2'); 
        var paidDate = $('#paid_date'); 
        var label = $('#label_paid_date'); 
        var idCollector = $('#id_collector'); 
        var label_collector = $('#label_id_collector'); 
        var payMode = $('#pay_mode'); 
        var label_mode = $('#label_pay_mode'); 
        $(sts1).click(function(){
            paidDate.attr('required', '');
            label.append('<span class="required">*</span>');
            idCollector.attr('required', '');
            label_collector.append('<span class="required">*</span>');
            payMode.attr('required', '');
            label_mode.append('<span class="required">*</span>');
        });
        $(sts2).click(function(){
            paidDate.removeAttr('required');
            label.html('Paid Date ');
            idCollector.removeAttr('required');
            label_collector.html('Collector ');
            payMode.removeAttr('required');
            label_mode.html('Pay Mode ');
        });
    });
</script>