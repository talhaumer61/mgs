<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'add' => '1'))){ 
	$sqlOrder	= $dblms->querylms("SELECT cat_ordering FROM ".FEE_CATEGORY." WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' AND is_deleted = '0' ORDER BY cat_ordering DESC LIMIT 1");
	if(mysqli_num_rows($sqlOrder) > 0){
		$valOrder = mysqli_fetch_array($sqlOrder);
		$order = $valOrder['cat_ordering'] + 1;
	}else{
		$order = '1';
	}
	echo'
	<div id="make_feecat" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="fee-category.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Fee Category</h2>
				</header>
				<div class="panel-body">				
					<div class="form-group">
						<label class="col-md-3 control-label">Priority <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="number" class="form-control" name="cat_ordering" id="cat_ordering" value="'.$order.'" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Category Name <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="cat_name" id="cat_name" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Category Detail <span class="required">*</span></label>
						<div class="col-md-9">
							<textarea type="text" class="form-control" rows="7" name="cat_detail" id="cat_detail" required title="Must Be Required"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Applied To <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="cat_for" name="cat_for" value="1" checked>
								<label for="radioExample1">All</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="cat_for" name="cat_for" value="2">
								<label for="radioExample2">Hostel</label>
							</div>
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
							<button type="submit" class="btn btn-primary" id="submit_feecat" name="submit_feecat">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}
?>