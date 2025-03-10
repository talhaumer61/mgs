<?php 
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '86', 'edit' => '1'))){ 

	$sqllms	= $dblms->querylms("SELECT d.quote_id, d.quote_type, d.quote_msg, d.date
								FROM ".DAILY_QUOTATION." d
								WHERE d.quote_id = '".cleanvars($_GET['id'])."'");
	$rowsvalues = mysqli_fetch_array($sqllms);
	echo '
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="daily_quotation.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<input type="hidden" name="quote_id" id="id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Quotation</h2>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Live Date <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control valid" name="date" value="'.date("m/d/Y",strtotime(cleanvars($rowsvalues['date']))).'" id="date" autocomplete="off" data-plugin-datepicker="" aria-invalid="false">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Quote Type <span class="required">*</span></label>
							<div class="col-md-9">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="quote_type" name="quote_type">
									<option value="">Select</option>';
										foreach(get_Quotation() as $key => $val):
											echo '<option value="'.$key.'" '.($key == $rowsvalues['quote_type']? 'selected': '').'>'.$val.'</option>';
										endforeach;
										echo '
								</select>
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Note</label>
							<div class="col-md-9">
								<textarea class="form-control" rows="6" name="quote_msg" id="quote_msg">'.$rowsvalues['quote_msg'].'</textarea>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="update_quote" name="update_quote">Update</button>
								<button class="btn btn-default modal-dismiss">Cancel</button>
							</div>
						</div>
					</footer>
				</form>
			</section>
		</div>
	</div>';
}
?>