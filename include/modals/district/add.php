<?php 
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'added' => '1'))){
	echo'
	<div id="make_district" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="district.php" class="form-horizontal" id="form" autocomplete="off" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make District</h2>
				</header>
				<div class="panel-body">
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Name <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="dist_name" id="dist_name" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Code <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="dist_code" id="dist_code" required title="Must Be Required"></textarea>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="number" class="form-control" name="dist_ordering" id="dist_ordering" required title="Must Be Required"></textarea>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Province <span class="required">*</span></label>
						<div class="col-md-9">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_prov">
								<option value="">Select</option>';
								$sqllmsprov	= $dblms->querylms("SELECT prov_id, prov_name
																FROM ".PROVINCES."
																WHERE prov_status	= '1'
																AND is_deleted		= '0'
																ORDER BY prov_ordering ASC");
								while($valueprov = mysqli_fetch_array($sqllmsprov)) {
									echo'<option value="'.$valueprov['prov_id'].'">'.$valueprov['prov_name'].'</option>';
								}
							echo '
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="dist_status" name="dist_status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="dist_status" name="dist_status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit_district" name="submit_district">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}
?>