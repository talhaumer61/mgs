<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))) { 
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
		<form action="#" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
			<input type="hidden" name="id_fee" id="id_fee" value="'.cleanvars($_GET['id']).'">
			<input type="hidden" name="challan_no" id="challan_no" value="'.$rowsvalues['challan_no'].'">
			<input type="hidden" name="std_phone" id="std_phone" value="'.$rowsvalues['std_phone'].'">
			<input type="hidden" name="id_std" id="id_std" value="'.$rowsvalues['id_std'].'">
			<input type="hidden" name="id_month" id="id_month" value="'.$rowsvalues['id_month'].'">
			<input type="hidden" name="dueDate" id="dueDate" value="'.$rowsvalues['due_date'].'">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Fee Challan </h2>
			</header>
			<div class="panel-body">
				<div class="form-group">
					<div class="col-md-4">
						<label class=control-label">Student <span class="required">*</span></label>
						<input type="text" class="form-control" required title="Must Be Required" value="'.$rowsvalues['std_name'].'" readonly/>
					</div>
					<div class="col-md-4">
						<label class=control-label">Class <span class="required">*</span></label>
						<input type="text" class="form-control" required title="Must Be Required" value="'.$rowsvalues['class_name'].'"'; if($rowsvalues['section_name']){echo'( '.$rowsvalues['section_name'].' )';} echo'" readonly/>
					</div>
					<div class="col-md-4">
						<label class=control-label">Challan No <span class="required">*</span></label>
						<input type="text" class="form-control" required title="Must Be Required" name="challan_no" id="challan_no" value="'.$rowsvalues['challan_no'].'" readonly/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="control-label">For Month <span class="required">*</span></label>
						<input type="text" class="form-control" required title="Must Be Required" value="'.get_monthtypes(cleanvars($rowsvalues['id_month'])).'" readonly/>
					</div>
					<div class="col-md-4">
						<label class=control-label">Issue Date <span class="required">*</span></label>
						<input type="text" class="form-control" required title="Must Be Required" value="'.date('m/d/Y' , strtotime(cleanvars($rowsvalues['issue_date']))).'" readonly/>
					</div>
					<div class="col-md-4">
						<label class=control-label">Due Date <span class="required">*</span></label>
						<input type="text" id="due_date" name="due_date" class="form-control" data-plugin-datepicker required title="Must Be Required" value="'.date('m/d/Y' , strtotime(cleanvars($rowsvalues['due_date']))).'"'; if($rowsvalues['status'] == 1) {echo' readonly';}echo'/>
					</div>
				</div>
				<div class="form-group">';
					$sqllmscats  = $dblms->querylms("SELECT cat_id, cat_name  
														FROM ".FEE_CATEGORY."
														WHERE cat_status = '1'
														ORDER BY cat_id ASC");
					$countcats = mysqli_num_rows($sqllmscats);
					
					if($countcats >0){
						$src = 0;
						$scholarshipConcession = 0;
						$fine = 0;
						while($rowdoc = mysqli_fetch_array($sqllmscats)){
							$sqllmsfeeprt  = $dblms->querylms("SELECT id, id_cat, amount 
																FROM ".FEE_PARTICULARS." 
																WHERE id_cat = '".$rowdoc['cat_id']."' AND id_fee  = '".$rowsvalues['id']."'
																LIMIT 1");
							if(mysqli_num_rows($sqllmsfeeprt) > 0){ 
								$valuefeeprt = mysqli_fetch_array($sqllmsfeeprt);
								echo'
								<div class="col-md-4">
									<label class=control-label">'.$rowdoc['cat_name'].' <span class="required">*</span></label>
									<input type="hidden" name="id[]" value="'.$valuefeeprt['id'].'">
									<input type="hidden" name="id_cat[]" value="'.$rowdoc['cat_id'].'">
									<input type="number" value="'.$valuefeeprt['amount'].'" id="amount" name="amount[]" class="form-control amount sum" required title="Must Be Required" readonly/>
								</div>';
							}
						}
					}
					if($rowsvalues['id_type']!='1'){
						echo'
						<div class="col-md-4">
							<label class=control-label">Previous Remainings <span class="required">*</span></label>
							<input type="number" value="'.$rowsvalues['prev_remaining_amount'].'" id="prev_remaining_amount" name="prev_remaining_amount" class="form-control sum" required title="Must Be Required" readonly/>
						</div>
						<div class="col-md-4">
							<label class=control-label">Fine <span class="required">*</span></label>
							<input type="number" value="'.$rowsvalues['fine'].'" id="fine" name="fine" class="form-control sum" required title="Must Be Required" readonly/>
						</div>
						<div class="col-md-4">
							<label class=control-label">Scholarship <span class="required">*</span></label>
							<input type="number" value="'.$rowsvalues['scholarship'].'" id="scholarship" name="scholarship" class="form-control sub" required title="Must Be Required" readonly/>
						</div>
						<div class="col-md-4">
							<label class=control-label">Concession <span class="required">*</span></label>
							<input type="number" value="'.$rowsvalues['concession'].'" id="concession" name="concession" class="form-control sub" required title="Must Be Required" readonly/>
						</div>';
					}
					echo'
				</div>
				<div class="form-group">
					<div class="'.($rowsvalues['paid_amount']==0 ? 'col-md-12' : 'col-md-4').'">
						<label class="control-label">Total Amount <span class="required">*</span></label>
						<input type="text" id="" name="payable" class="form-control totalPayable total_amount" required title="Must Be Required" value="'.$granTotal.'" readonly/>
						<input type="hidden" id="payable" name="total_amount" class="totalPayable total_amount" required title="Must Be Required" value="'.$rowsvalues['total_amount'].'" readonly/>
					</div>';
					$onlineTotalPaid = 0;
					if($rowsvalues['paid_amount'] > 0){
						$onlineTotalPaid = $rowsvalues['paid_amount'];
						$totalRemAmount = $granTotal - ($onlineTotalPaid);
						echo'
						<div class="col-md-4">
							<label class="control-label">Partial Paid </label>
							<input type="text" id="paid_amount" name="paid_amount" value="'.$onlineTotalPaid.'" class="form-control paid_amount" readonly/>
						</div>
						<div class="col-md-4">
							<label class="control-label">Remaining Amount <span class="required">*</span></label>
							<input type="text" class="form-control rem_amount" id="rem_amount" required title="Must Be Required" value="'.$totalRemAmount.'" readonly/>
						</div>

						<input type="hidden" id="paid_amount" name="paid_amount" title="Must Be Required" value="'.$rowsvalues['paid_amount'].'" readonly/>
						<input type="hidden" id="totAmount" name="totAmount" required title="Must Be Required" value="'.$totalRemAmount.'" readonly/>';
					}
					echo'
				</div>';
				echo'
				<div class="form-group">';
					if($valuesqllmscheck['challan_no'] == $rowsvalues['challan_no']){
						$col46 = "col-md-3";
						echo'
						<div class="'.$col46.'">
							<label class="control-label">Received Amount</label>
							<input type="number" id="totaltransamount" name="totaltransamount" class="form-control paid"/>
							<input type="hidden" id="grandTotal" name="grandTotal" value="'.$grandTotal.'" class="total_amount"/>
						</div>';
					}
					else{ $col46 = "col-md-4"; }
					echo'
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
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						Note: <span class="text-danger">(Only due date allow to change)</span>
						<button type="submit" class="btn btn-primary" onClick=\'return confirmUpdate()\' id="changes_due_date" name="changes_due_date">Update</button>
						<button class="btn btn-default modal-dismiss">Cancel </button>
					</div>
				</div>
			</footer>
		</form>
	</section>';
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