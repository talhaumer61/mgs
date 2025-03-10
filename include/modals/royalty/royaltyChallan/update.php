<?php 
//---------------------------------------------------------
	include "../../../dbsetting/lms_vars_config.php";
	include "../../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../../functions/login_func.php";
	include "../../../functions/functions.php";
	checkCpanelLMSALogin();
//---------------------------------------------------------
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))){ 
//---------------------------------------------------------
$sqllms = $dblms->querylms("SELECT r.id, r.status, r.challan_no, r.issue_date, r.due_date, r.total_amount, r.paid_amount, r.remaining_amount, r.note,
								c.campus_id, c.campus_name
								FROM ".FEES." r				   						 						 
								INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus
								WHERE r.id = '".cleanvars($_GET['id'])."'  LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);

//---------- Count Challans for Edit Balance --------------
//if there is one challan then previous balnc editable
$sqllms_blnc = $dblms->querylms("SELECT id
										FROM ".FEES."		
										WHERE id_campus = '".$rowsvalues['campus_id']."'
										AND id_type = '3' AND is_deleted != '1'");
if(mysqli_num_rows($sqllms_blnc) == 1 && $rowsvalues['status'] == 2)
{
	$readonly = "";
}else{
	$readonly = "readonly";
}

$remainingAmount = $rowsvalues['total_amount'] - $rowsvalues['paid_amount'];
//------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="royaltyChallans.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
		<input type="hidden" name="id_campus" id="id_campus" value="'.cleanvars($rowsvalues['campus_id']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Royalty Challan </h2>
		</header>
		<div class="panel-body">
			<div class="form-group">
				<div class="col-md-6">
					<label class=control-label">Campus <span class="required">*</span></label>
					<input type="text" class="form-control" required title="Must Be Required" value="'.$rowsvalues['campus_name'].'" readonly/>
				</div>
				<div class="col-md-6">
					<label class=control-label">Challan No <span class="required">*</span></label>
					<input type="text" class="form-control" required title="Must Be Required" name="challan_no" id="challan_no" value="'.$rowsvalues['challan_no'].'" readonly/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-6">
					<label class=control-label">Issue Date <span class="required">*</span></label>
					<input type="text" class="form-control" required title="Must Be Required" value="'.date('m/d/Y' , strtotime(cleanvars($rowsvalues['issue_date']))).'" readonly/>
				</div>
				<div class="col-md-6">
					<label class=control-label">Due Date <span class="required">*</span></label>
					<input type="text" id="due_date" name="due_date" class="form-control" data-plugin-datepicker required title="Must Be Required" value="'.date('m/d/Y' , strtotime(cleanvars($rowsvalues['due_date']))).'"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">';
					//--------------------------------------
					// $total_amount = 0;
					// //------------------------------------------------
					// $sqllmsDetails = $dblms->querylms("SELECT p.part_id, p.part_name, d.amount 
					// 									FROM ".ROYALTY_DETAIL." d
					// 									INNER JOIN ".ROYALTY_PARTICULARS." p ON p.part_id = d.id_particular
					// 									WHERE id_royalty  = '".$_GET['id']."' ");
					// if(mysqli_num_rows($sqllmsDetails)>0) { 
					// 	while($valueDetail = mysqli_fetch_array($sqllmsDetails)){
					// 		echo'
					// 		<div class="col-md-4">
					// 			<label class=control-label">'.$valueDetail['part_name'].' <span class="required">*</span></label>
					// 			<input type="hidden" name="part_id[]" value="'.$valueDetail['part_id'].'">
					// 			<input type="number" id="amount" name="amount[]" class="form-control cats" required title="Must Be Required" value="'.$valueDetail['amount'].'"'; if($rowsvalues['roy_status'] == 1) {echo'readonly';}echo'/>
					// 		</div>';
					// 		$total_amount = $total_amount + $valueDetail['amount'];
					// 	}
					// } else { 
					// 	echo'<h5 class="center text-danger">No Detail Availabel</h5>';
					// }
					echo'
					<input type="hidden" name="total_amount" value="'.$total_amount.'">

					<label class="control-label">Arrears </label>
					<input type="number" id="remaining_amount" name="remaining_amount" class="form-control remaining" value="'.$rowsvalues['remaining_amount'].'" '.$readonly.'/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-4">
					<label class="control-label">Payable <span class="required">*</span></label>
					<input type="number" id="payable" name="payable" class="form-control total" required title="Must Be Required" value="'.$rowsvalues['total_amount'].'" readonly/>
				</div>
				<div class="col-md-4">
					<label class="control-label">Paid Amount <span class="required">*</span></label>
					<input type="number" id="paid_amount" name="paid_amount" class="form-control" required title="Must Be Required" value="'.$rowsvalues['paid_amount'].'" readonly/>
				</div>
				<div class="col-md-4">
					<label class="control-label">Pay Now</label>
					<input type="number" id="pay_now" name="pay_now" class="form-control payNow"/>
				</div>
				<div class="col-md-12">
					<label class="control-label">Remaining To Pay <span class="required">*</span></label>
					<input type="number" id="rem" name="" class="form-control rem" required title="Must Be Required" value="'.$remainingAmount.'" readonly/>
				</div>
			</div>			
			<div class="form-group">
				<div class="col-md-4">
					<label class=control-label">Paid Date </label>
					<input type="text" id="paid_date" name="paid_date" class="form-control" data-plugin-datepicker title="Must Be Required"/>
				</div>
				<div class="col-md-4">
					<label class=control-label">Receipt No </label>
					<input type="text" id="receipt_no" name="receipt_no" class="form-control" title="Must Be Required"/>
				</div>
				<div class="col-md-4">
					<label class=control-label">Receipt </label>
					<input type="file" accept="image/*" id="receipt_image" name="receipt_image" class="form-control" title="Must Be Required"/>
				</div>
			</div> 
			<div class="form-group">
				<div class="col-md-12">
					<label class="control-label">Note </label>
					<textarea class="form-control" rows="2" name="note" id="note">'.$rowsvalues['note'].'</textarea>
				</div>
			</div>
			<!-- <div class="form-group">
				<label class="col-md-2 control-label">Status <span class="required">*</span></label>
				<div class="col-md-10">
					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" value="1"'; if($rowsvalues['status'] == 1) {echo' checked';}echo'>
						<label for="radioExample1">Paid</label>
					</div>'; 
					if($rowsvalues['status'] != 1) {echo'
					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" value="2"'; if($rowsvalues['status'] == 2) {echo' checked';}echo'>
						<label for="radioExample2">Pending</label>
					</div>

					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" value="3"'; if($rowsvalues['status'] == 3) {echo' checked';}echo'>
						<label for="radioExample2">Unpaid</label>
					</div>';
					}
					echo'
				</div>
			</div> -->
					
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="changes_royalty" name="changes_royalty">Update</button>
					<button class="btn btn-default modal-dismiss">Cancel </button>
				</div>
			</div>
		</footer>
	</form>
</section>
</div>
</div>';
}
?>

<script type="text/javascript">

	$(document).on("change", ".remaining", function() {
		var remaining =  document.getElementById("remaining_amount").value;
		var payable =  document.getElementById("payable").value;
		var paid 	= document.getElementById("paid_amount").value;
		var payNow 	=  document.getElementById("pay_now").value;
		var total  = (Number(remaining) + Number(payable));
		var rem  = (Number(total) - (Number(paid) + Number(payNow)));
		$(".total").val(total);
		$(".rem").val(rem);
	});

	// Enter Pay Now
	$(document).on("input", ".payNow", function() {
		var payable =  document.getElementById("payable").value;
		var paid 	= document.getElementById("paid_amount").value;
		var payNow 	=  document.getElementById("pay_now").value;
		var rem  = payable - (Number(paid) + Number(payNow));
		$(".rem").val(rem);
	});
</script>