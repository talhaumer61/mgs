<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
//---------------------------------------------------------
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))){ 
//---------------------------------------------------------
	$sqllms	= $dblms->querylms("SELECT  f.id, f.status, f.challan_no, f.id_session, f.id_class, f.id_section, f.id_std,
								   f.issue_date, f.due_date, f.total_amount, f.paid_amount, f.remaining_amount, f.note, 
								   c.class_id, c.class_name,
								   cs.section_id, cs.section_name,
								   s.session_id, s.session_name,
								   st.std_id, st.std_name, st.std_regno
								   FROM ".FEES." f				   
								   INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
								   INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section							 
								   INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session							 
								   INNER JOIN ".STUDENTS." st ON st.std_id 	 = f.id_std
								   WHERE f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
								   AND f.id = '".cleanvars($_GET['id'])."'
								   ORDER BY f.challan_no DESC");
	$rowsvalues = mysqli_fetch_array($sqllms);
//---------------------------------------------------------
//------------------------TUITION FEE----------------------
/*	
	$sql_fees	= $dblms->querylms("SELECT c.cat_id, c.cat_name, p.id, p.id_cat, p.amount
									   FROM ".FEE_PARTICULARS." p
									   INNER JOIN ".FEE_CATEGORY." c ON c.cat_id = p.id_cat
									   WHERE p.id_fee = '".$rowsvalues['id']."' ");
									   */
//----------------------------------------------------------
//---------------------------Scholarship--------------------------
	$sql_scholarship	= $dblms->querylms("SELECT SUM(percent) as scholarship
									   FROM ".SCHOLARSHIP." 
									   WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
									   AND id_session = '".$rowsvalues['id_session']."'
									   AND   id_type = '1' AND status = '1' AND is_deleted != '1'
									   AND id_std = '".$rowsvalues['std_id']."' ");
	//-----------------------------------------------------
	$values_scholarship = mysqli_fetch_array($sql_scholarship);
	//----------------------------Fee Concession-------------------------
	$sql_concess	= $dblms->querylms("SELECT SUM(percent) as concession
									   FROM ".SCHOLARSHIP." 
									   WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
									   AND id_session = '".$rowsvalues['id_session']."'
									   AND   id_type = '2' AND status = '1' AND is_deleted != '1'
									   AND id_std = '".$rowsvalues['std_id']."' ");
	//-----------------------------------------------------
	$values_concess = mysqli_fetch_array($sql_concess);
	//----------------------------Fine-------------------------
	$chln_mnth  = date("n", strtotime($rowsvalues['due_date']));

	$sql_fine	= $dblms->querylms("SELECT SUM(amount) as fine
									   FROM ".SCHOLARSHIP." 
									    WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
										AND id_session = '".$rowsvalues['id_session']."'
										AND MONTH(date) = '".$chln_mnth."'
										AND id_type = '3' AND status = '1' AND is_deleted != '1'
										AND id_std = '".$rowsvalues['std_id']."' ");
	//-----------------------------------------------------
	$values_fine = mysqli_fetch_array($sql_fine);
	//------------------------TUITION FEE------------------------
	$sql_tuitionfee	= $dblms->querylms("SELECT amount
									   FROM ".FEESETUPDETAIL." d
									   INNER JOIN ".FEESETUP." f ON f.id = d.id_setup
									   WHERE f.id_class = '".$rowsvalues['class_id']."' AND f.id_section = '".$rowsvalues['section_id']."'
									   AND   d.id_cat = '1'");
	//-----------------------------------------------------
	$values_tuitionfee = mysqli_fetch_array($sql_tuitionfee);
	$tuition_fee = $values_tuitionfee['amount'];
	//-----------------------------------------------------
	//------------------------TUITION FEE------------------------
	$sqllms_his	= $dblms->querylms("SELECT SUM(remaining_amount) as rem
								   FROM ".FEES."
								   WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
								   AND id_std = '".$rowsvalues['id_std']."' ");
	//-----------------------------------------------------
	$values_his = mysqli_fetch_array($sqllms_his);
	//-----------------------------------------------------

//-----------payabel amount after Scholarship & Fine----------
	//--------------TOTAL PERCENTAGE DEDUCTION IN FEE--------------------
	$total_fee = $rowsvalues['total_amount'];
	$dis_per = $values_scholarship['scholarship'] + $values_concess['concession'];
	//-----------------------DISCOUNT IN TUTION FEE------------------------------
	$dis_amount = ($tuition_fee * $dis_per) / 100;
	//-----------------------DISCOUNT IN TUTION FEE------------------------------
	$after_dis = $total_fee - $dis_amount;
	//--------------------PAYABLE AFTER DISCOUNT AND FINE------------------------
	$payable = ($total_fee  + $values_fine['fine']) - $dis_amount;
	//-----------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="fee_challans.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" id="id" value="'.cleanvars($_GET['id']).'">
		<input type="hidden" name="std_id" id="std_id" value="'.cleanvars($rowsvalues['id_std']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Fee Challan </h2>
		</header>
		<div class="panel-body">
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Challan <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" required title="Must Be Required" name="challan_no" id="challan_no" value="'.$rowsvalues['challan_no'].'" readonly/>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Issue Date <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" required title="Must Be Required" value="'.date('m-d-Y' , strtotime(cleanvars($rowsvalues['issue_date']))).'" readonly/>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Due Date <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" id="due_date" name="due_date" class="form-control" data-plugin-datepicker required title="Must Be Required" value="'.date('m-d-Y' , strtotime(cleanvars($rowsvalues['due_date']))).'"'; if($rowsvalues['status'] == 1) {echo' readonly';}echo'/>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Student Name <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" required title="Must Be Required" value="'.$rowsvalues['std_name'].'" readonly/>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Class <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" required title="Must Be Required" value="'.$rowsvalues['class_name'].'" readonly/>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Section </label>
				<div class="col-md-9">
					<input type="text" class="form-control" value="'.$rowsvalues['section_name'].'" readonly/>
				</div>
			</div>
			<label class="col-md-3 control-label">All Fees</label>
			<div class="col-md-9" style="margin-bottom: 15px;">
				<div class="row clearfix">';
				//--------------------------------------
					$total_fee = 0;
//------------------------------------------------
	$sqllmscats  = $dblms->querylms("SELECT cat_id, cat_name  
										FROM ".FEE_CATEGORY."
										WHERE cat_status = '1' 
										ORDER BY cat_id ASC");
	$countcats 	= mysqli_num_rows($sqllmscats);
	//--------------------------------------
	if($countcats >0) {
	$src = 0;
		while($rowdoc 	= mysqli_fetch_array($sqllmscats)) {
		//------------------------------------
			$sqllmsfeeprt  = $dblms->querylms("SELECT id, id_cat, amount FROM ".FEE_PARTICULARS." 
												WHERE id_cat = '".$rowdoc['cat_id']."' AND id_fee  = '".$rowsvalues['id']."' 
												LIMIT 1");
			if(mysqli_num_rows($sqllmsfeeprt)>0) { 
                $valuefeeprt = mysqli_fetch_array($sqllmsfeeprt);
                echo'
                <div class="col-md-6">
					<div class="form-group mt-sm">
						<div class="col-md-12">
							<label class=control-label">'.$rowdoc['cat_name'].' <span class="required">*</span></label>
							<input type="hidden" name="id[]" value="'.$valuefeeprt['id'].'">
							<input type="text" id="amount" name="amount[]" class="form-control cats" required title="Must Be Required" value="'.$valuefeeprt['amount'].'"'; if($rowsvalues['status'] == 1 || $rowdoc['cat_id'] == '1') {echo'readonly';}echo'/>
						</div>
					</div>
				</div>
                ';
            } else { 
                echo'
                <div class="col-md-6">
					<div class="form-group mt-sm">
						<div class="col-md-12">
							<label class=control-label">'.$rowdoc['cat_name'].' <span class="required">*</span></label>
							<input type="hidden" name="id[]" value="'.$valuefeeprt['id'].'">
							<input type="text" id="amount" name="amount[]" class="form-control cats" required title="Must Be Required" value=""'; if($rowsvalues['status'] == 1 || $rowdoc['cat_id'] == '1') {echo'readonly';}echo'/>
						</div>
					</div>
				</div>
                ';
            }
            $total_fee = $total_fee + $valuefeeprt['amount'];
        }
	}
	/*
				//----------------------------------------------------------
				$total_fee = 0;
				while($value_fee = mysqli_fetch_array($sql_fees)){
				//----------------------------------------------------------
				echo'
				<div class="col-md-6">
					<div class="form-group mt-sm">
						<div class="col-md-12">
							<label class=control-label">'.$value_fee['cat_name'].' <span class="required">*</span></label>
							<input type="hidden" name="id[]" value="'.$value_fee['id'].'">
							<input type="text" id="amount" name="amount[]" class="form-control" required title="Must Be Required" value="'.$value_fee['amount'].'"'; if($rowsvalues['status'] == 1 || $value_fee['cat_id'] == '1') {echo'readonly';}echo'/>
						</div>
					</div>
				</div>
				';
				$total_fee = $total_fee + $value_fee['amount'];
				}
				//----------------------------------------------------------
				*/
				echo'
				<input type="hidden" name="total_fee" value="'.$total_fee.'">
				</div>
			</div>
			
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Others</label>
				<div class="col-md-9" style="margin-bottom: 15px;">
					<div class="row clearfix">
						<div class="col-md-4">
							<div class="form-group mt-sm">
								<div class="col-md-12">
									<label class=control-label">Discount <span class="required">*</span></label>
									<input type="text" id="" name="" class="form-control disc" required title="Must Be Required" value="'.$dis_amount.'" readonly/>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mt-sm">
								<div class="col-md-12">
									<label class=control-label">Fine <span class="required">*</span></label>
									<input type="text" id="" name="" class="form-control fine" required title="Must Be Required" value="'.$values_fine['fine'].'" readonly/>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group mt-sm">
								<div class="col-md-12">
									<label class=control-label">Balance <span class="required">*</span></label>
									<input type="text" id="remaining_amount" name="remaining_amount" class="form-control cats" required title="Must Be Required" value="'.$values_his['rem'].'" readonly/>
								</div>
							</div>
						</div>
					</div>';
					//$payable = ($total_fee + $values_fine['fine']) - $dis_amount;
					echo'
					<!-- <div class="row clearfix">
						<div class="col-md-6">
							<div class="form-group mt-sm">
								<div class="col-md-12">
									<label class=control-label">Payable <span class="required">*</span></label>
									<input type="text" id="payable" name="payable" class="form-control total" required title="Must Be Required" value="'.$payable.'" readonly/>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group mt-sm">
								<div class="col-md-12">
									<label class=control-label">Previous Balance <span class="required">*</span></label>
									<input type="text" id="remaining_amount" name="remaining_amount" class="form-control" required title="Must Be Required" value="'.$values_his['rem'].'" readonly/>
								</div>
							</div>
						</div>
					</div> -->
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Paid Amount <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" id="payable" name="payable" class="form-control total" required title="Must Be Required" value="'.$payable.'" readonly/>
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Paid Amount <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" id="paid_amount" name="paid_amount" class="form-control paid" required title="Must Be Required" />
				</div>
			</div>
			<div class="form-group mt-sm">
				<label class="col-md-3 control-label">Rem. Amount <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" id="remaining_amount" name="remaining_amount" class="form-control rem" required title="Must Be Required" readonly/>
				</div>
			</div> 
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Note </label>
				<div class="col-md-9">
					<textarea class="form-control" rows="2" name="note" id="note">'.$rowsvalues['note'].'</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
				<div class="col-md-9">
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
					echo '
				</div>
			</div>
					
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="changes_challan" name="changes_challan">Update</button>
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
$(document).on("change", ".cats", function() {
    var sum = 0;
    $(".cats").each(function(){
        sum += +$(this).val();
    });
	
	var concession = <?php echo $dis_amount; ?>;
    var fine = <?php echo $values_fine['fine'];?>;
	var payable = (fine + sum) - concession;
    $(".total").val(payable);
});


$(document).on("change", ".paid", function() {
	var payable =  document.getElementById("payable").value;
	var paid = document.getElementById("paid_amount").value;
	var rem  = payable - paid;
	$(".rem").val(rem);
});
</script>