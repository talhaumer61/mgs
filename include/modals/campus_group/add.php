<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'add' => '1'))){
	$sqlOrder  = $dblms->querylms("SELECT group_ordering FROM ".CAMPUS_GROUPS." ORDER BY group_ordering DESC LIMIT 1");
	if(mysqli_num_rows($sqlOrder)>0){
		$valOrder = mysqli_fetch_array($sqlOrder);
		$ordering = $valOrder['group_ordering'] + 1;
	}else{
		$ordering = 1;
	}
	echo'
	<div id="make_group" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="campus_group.php" class="form-horizontal" id="form" autocomplete="off" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Campus Group</h2>
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
											<input type="file" name="group_logo" accept="image/*">
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
							<input type="text" class="form-control" name="group_ordering" id="group_ordering" value="'.$ordering.'" readonly required title="Must Be Required">
						</div>
					</div>
					<div class="form-group mt-md">
						<label class="col-md-3 control-label">Name <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="group_name" id="group_name" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Alpha Code <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="group_code" id="group_code" required title="Must Be Required">
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Numeric Code <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="number" class="form-control" name="group_code_numeric" id="group_code_numeric" required title="Must Be Required">
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Detail </label>
						<div class="col-md-9">
							<textarea type="text" class="form-control" name="group_detail" id="group_detail"></textarea>
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="group_status" name="group_status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="group_status" name="group_status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit_group" name="submit_group">Save</button>
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