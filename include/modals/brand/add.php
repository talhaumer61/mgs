<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'add' => '1'))){
	$sqlOrder  = $dblms->querylms("SELECT brand_ordering FROM ".BRANDS." ORDER BY brand_ordering DESC LIMIT 1");
	if(mysqli_num_rows($sqlOrder)>0){
		$valOrder = mysqli_fetch_array($sqlOrder);
		$ordering = $valOrder['brand_ordering'] + 1;
	}else{
		$ordering = 1;
	}
	echo'
	<div id="make_brand" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="brand.php" class="form-horizontal" id="form" autocomplete="off" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Brand</h2>
				</header>
				<div class="panel-body">
					<div class="form-group mt-xl">
						<div class="row">
							<label class="col-md-3 control-label">Logo </label>
							<div class="col-md-9">
								<div class="fileinput fileinput-new" data-provides="fileinput">
									<div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">
										<img src="uploads/logo.png" alt="...">
									</div>
									<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 130px"></div>
									<div>
										<span class="btn btn-xs btn-default btn-file">
											<span class="fileinput-new">Select image</span>
											<span class="fileinput-exists">Change</span>
											<input type="file" name="brand_logo" accept="image/*">
										</span>
										<a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
									</div>
								</div>
							</div>
						</div>
					</div>					
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="number" class="form-control" name="brand_ordering" id="brand_ordering" value="'.$ordering.'" readonly required title="Must Be Required">
						</div>
					</div>
					<div class="form-group mt-md">
						<label class="col-md-3 control-label">Name <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="brand_name" id="brand_name" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Alpha Code <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="brand_code" id="brand_code" required title="Must Be Required">
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Numeric Code <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="number" class="form-control" name="brand_code_numeric" id="brand_code_numeric" required title="Must Be Required">
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Detail </label>
						<div class="col-md-9">
							<textarea type="text" class="form-control" name="brand_detail" id="brand_detail"></textarea>
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="brand_status" name="brand_status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="brand_status" name="brand_status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit_brand" name="submit_brand">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}else{
	header("Location: dashboard.php");
}
?>