<?php 
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('26', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '26', 'add' => '1'))) {
	echo'
	<div id="make_earning_head" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="earninghead.php" class="form-horizontal" id="form" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i> Add Income Head</h2>
				</header>
				<div class="panel-body">

					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Head Name <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="head_name" id="head_name" required title="Must Be Required"/>
						</div>
					</div>			
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="head_status" name="head_status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="head_status" name="head_status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit_earning_head" name="submit_earning_head">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}
?>