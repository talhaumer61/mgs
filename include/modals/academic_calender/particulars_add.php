<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1)  || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '67', 'add' => '1'))){ 
echo '
<!-- Add Modal Box -->
<div id="make_particular" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="academiccalender_particulars.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Particular</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Particular <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="cat_name" id="cat_name" required title="Must Be Required" />
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="cat_ordering" id="cat_ordering" required title="Must Be Required" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="cat_status" name="cat_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="cat_status" name="cat_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_particular" name="submit_particular">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>