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
	$sqllms = $dblms->querylms("SELECT r.roy_id, r.roy_status, r.roy_detail, r.challan_no, r.issue_date, r.due_date, r.total_amount, r.paid_amount, r.remaining_amount,
									c.campus_id, c.campus_name
									FROM ".ROYALTY." r				   						 						 
									INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus
									WHERE r.roy_id = '".cleanvars($_GET['id'])."'  LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
//---------- Count Challans for Edit Balance --------------
	//if there is one challan then previous balnc editable
	$sqllms_blnc = $dblms->querylms("SELECT roy_id 
											FROM ".ROYALTY."		
											WHERE id_campus = '".$rowsvalues['campus_id']."' LIMIT 1");
	if(mysqli_num_rows($sqllms_blnc) == 1)
	{
		$blnc_edit = 1;
	}else{
		$blnc_edit = 0;
	}
//------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="royalty.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="id_roy" id="id_roy" value="'.cleanvars($_GET['id']).'">
		<input type="hidden" name="id_campus" id="id_campus" value="'.cleanvars($rowsvalues['campus_id']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Royalty Challan </h2>
		</header>
		<div class="panel-body">
			<div class="form-group mt-sm">
				<div class="col-md-12">
					<div class="row clearfix">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-md-12">
									<label class=control-label">Campus <span class="required">*</span></label>
									<input type="text" class="form-control" required title="Must Be Required" value="'.$rowsvalues['campus_name'].'" readonly/>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-md-12">
									<label class=control-label">Challan No <span class="required">*</span></label>
									<input type="text" class="form-control" required title="Must Be Required" name="challan_no" id="challan_no" value="'.$rowsvalues['challan_no'].'" readonly/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group mt-sm">
				<div class="col-md-12">
					<div class="row clearfix">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-md-12">
									<label class=control-label">Issue Date <span class="required">*</span></label>
									<input type="text" class="form-control" required title="Must Be Required" value="'.date('m/d/Y' , strtotime(cleanvars($rowsvalues['issue_date']))).'" readonly/>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-md-12">
									<label class=control-label">Due Date <span class="required">*</span></label>
									<input type="text" id="due_date" name="due_date" class="form-control" data-plugin-datepicker required title="Must Be Required" value="'.date('m/d/Y' , strtotime(cleanvars($rowsvalues['due_date']))).'"'; if($blnc_edit == 1) {echo' readonly';}echo'/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>			
			<div class="form-group mt-sm">
				<div class="col-md-12">
					<label class="control-label">All Fees</label>
					<div class="row clearfix">';
						//--------------------------------------
						$total_amount = 0;
						//------------------------------------------------
						$sqllmsDetails = $dblms->querylms("SELECT p.part_id, p.part_name, d.amount 
															FROM ".ROYALTY_DETAIL." d
															INNER JOIN ".ROYALTY_PARTICULARS." p ON p.part_id = d.id_particular
															WHERE id_royalty  = '".$_GET['id']."' ");
							if(mysqli_num_rows($sqllmsDetails)>0) { 
								while($valueDetail = mysqli_fetch_array($sqllmsDetails)){
									echo'
									<div class="col-md-4">
										<div class="form-group mt-sm">
											<div class="col-md-12">
												<label class=control-label">'.$valueDetail['part_name'].' <span class="required">*</span></label>
												<input type="hidden" name="part_id[]" value="'.$valueDetail['part_id'].'">
												<input type="text" id="amount" name="amount[]" class="form-control cats" required title="Must Be Required" value="'.$valueDetail['amount'].'"'; if($rowsvalues['roy_status'] == 1) {echo'readonly';}echo'/>
											</div>
										</div>
									</div>';
									$total_amount = $total_amount + $valueDetail['amount'];
								}
							}
								else { 
								echo'<h5 class=""center text-danger">No Record Found!</h5>';
							}
						//----------------------------------------------------------
							echo'
							<input type="hidden" name="total_amount" value="'.$total_amount.'">
					</div>
				</div>
			</div>';
			$payable = $total_amount + $rowsvalues['remaining_amount'];
			echo'
			<div class="form-group">
				<div class="col-md-4">
					<label class="control-label">Arrears <span class="required">*</span></label>
					<input type="number" id="remaining" name="remaining" class="form-control total" required title="Must Be Required" value="'.$rowsvalues['remaining_amount'].'"'; if($blnc_edit === 1) {echo'readonly';} echo'/>
				</div>
				<div class="col-md-4">
					<label class="control-label">Payable <span class="required">*</span></label>
					<input type="number" id="payable" name="payable" class="form-control total" required title="Must Be Required" value="'.$payable.'" readonly/>
				</div>
				<div class="col-md-4">
					<label class="control-label">Paid Amount <span class="required">*</span></label>
					<input type="number" id="paid_amount" name="paid_amount" class="form-control paid" required title="Must Be Required" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<label class="control-label">Rem. Amount <span class="required">*</span></label>
					<input type="number" id="remaining_amount" name="remaining_amount" class="form-control rem" required title="Must Be Required" readonly/>
				</div>
			</div> 
			<div class="form-group mb-md">
				<div class="col-md-12">
					<label class="control-label">Note </label>
					<textarea class="form-control" rows="2" name="roy_detail" id="roy_detail">'.$rowsvalues['roy_detail'].'</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label">Status <span class="required">*</span></label>
				<div class="col-md-10">
					<div class="radio-custom radio-inline">
						<input type="radio" id="roy_status" name="roy_status" value="1"'; if($rowsvalues['roy_status'] == 1) {echo' checked';}echo'>
						<label for="radioExample1">Paid</label>
					</div>'; 
					if($rowsvalues['roy_status'] != 1) {echo'
					<div class="radio-custom radio-inline">
						<input type="radio" id="roy_status" name="roy_status" value="2"'; if($rowsvalues['roy_status'] == 2) {echo' checked';}echo'>
						<label for="radioExample2">Pending</label>
					</div>

					<div class="radio-custom radio-inline">
						<input type="radio" id="roy_status" name="roy_status" value="3"'; if($rowsvalues['roy_status'] == 3) {echo' checked';}echo'>
						<label for="radioExample2">Unpaid</label>
					</div>';
					}
					echo'
				</div>
			</div>
					
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
$(document).on("change", ".paid", function() {
	var payable =  document.getElementById("payable").value;
	var paid = document.getElementById("paid_amount").value;
	var rem  = payable - paid;
	$(".rem").val(rem);
});
</script>