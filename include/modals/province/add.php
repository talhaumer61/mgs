<?php 
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'added' => '1'))){
echo'
<!-- Add Modal Box -->
<div id="make_province" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="province.php" class="form-horizontal" id="form" enctype="multipart/form-data" autocomplete="off" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Province</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Name <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="prov_name" id="prov_name" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Code <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="prov_code" id="prov_code" required title="Must Be Required"></textarea>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="number" class="form-control" name="prov_ordering" id="prov_ordering" required title="Must Be Required"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="prov_status" name="prov_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="prov_status" name="prov_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_province" name="submit_province">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}	
?>