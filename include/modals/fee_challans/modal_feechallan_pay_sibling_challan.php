<?php
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))) { 
	echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<section class="panel panel-featured panel-featured-primary">
		<form action="fee_challans.php" class="form-horizontal" id="SiblingsForm" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
			<header class="panel-heading">
				<h2 class="panel-title"><img src="../../../assets/images/partial_payment.png" height="15" width="auto"> Pay Siblings Fee Challan </h2>
			</header>				
				<div class="panel-body">
					<div class="form-group">
						<div class="col-md-12">
							<label class="control-label" for="std_familyno">Family No <span class="required" aria-required="true">*</span></label>
							<input type="text" id="std_familyno" name="std_familyno" onkeyup="get_Siblings(this.value);" class="std_familyno form-control" title="Must Be Required" required="" />
						</div>
					</div>
					<div id="siblings_challan"></div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" onClick=\'return confirmAddPayment()\' class="sibling_pay_btn btn btn-primary mr-xs" id="siblings_save_and_print" name="siblings_save_and_print">Save & Print</button>
							<button type="submit" onClick=\'return confirmAddPayment()\' class="sibling_pay_btn btn btn-primary" id="siblings_save_only" name="siblings_save_only">Save</button>
							<button class="btn btn-default modal-dismiss" data-dismiss="modal">Cancel </button>
						</div>
					</div>
				</footer>';
			echo'
		</form>
	</section>';
}
?>
<!--Input Mask-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
	$(document).ready(function(){
		$(".std_familyno").mask("00000-0000000-0");
	});
	function confirmAddPayment() {
		var agree=confirm("Are you sure you want to Add Payment?");
		if (agree)
		return true ;
		else
		return false ;
	}
	$(".sibling_pay_btn").hide();
	function get_Siblings(std_familyno) {
		if (std_familyno.trim() != '') {
			// $('#SiblingsForm').preventDefault();
			$(".sibling_pay_btn").hide();
			$.ajax({
				 url	: 'include/ajax/get_Siblings_Challans.php'
				,type	: 'POST'
				,data	: { std_familyno : std_familyno }
				,success: function(response) {
					if (response == 'no_found') {
						$('#siblings_challan').html(`<div class="form-group"><div class="row"><div class="col-md-12"><center><h2><span class="text-danger">No Pending Challan Of This Family!</span></h2></center></div></div></div>`);
					} else {
						$('#siblings_challan').html(response);
						$(".sibling_pay_btn").show();
					}
				}
			});
		}
	}
</script>