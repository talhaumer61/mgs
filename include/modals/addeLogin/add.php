<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '45', 'add' => '1'))){ 
echo'
<div id="make_parentlogin" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="addeLogin.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i> Make AD / DE Login</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Login For <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_type" id="id_type">
							<option value="">Select</option>
							<option value="1">AD</option>
							<option value="2">DE</option>
						</select>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Employee <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_employee" name="id_employee">
							<option value="">Select</option>
						</select>
					</div>
				</div>
				<div id="getemployeedetail">
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label"> Full Name <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="adm_fullname" name="adm_fullname" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label"> Email </label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="adm_email" name="adm_email"/>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label"> Phone </label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="adm_phone" name="adm_phone"/>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label"> Username <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="adm_username" name="adm_username" required title="Must Be Required"/>
						</div>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> Password <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="password" class="form-control" id="adm_userpass" name="adm_userpass" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="adm_status" name="adm_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="adm_status" name="adm_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="makeLogin" name="makeLogin">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>