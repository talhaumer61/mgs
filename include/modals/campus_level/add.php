<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'add' => '1'))){
echo'
<div id="make_level" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="campus_level.php" class="form-horizontal" id="form" autocomplete="off" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Campus Level</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Name <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="level_name" id="level_name" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Code <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="level_code" id="level_code" required title="Must Be Required">
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="level_ordering" id="level_ordering" required title="Must Be Required">
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Classes </label>
					<div class="col-md-9">';
					$sqllmscls = $dblms->querylms("SELECT class_id, class_name
													FROM ".CLASSES."
													WHERE class_id != '' AND class_status = '1'
													AND is_deleted != '1'
													ORDER BY class_id ASC");
					$i = 0;
					while($valuecls = mysqli_fetch_array($sqllmscls)) {
						echo'
						<div class="col-md-3">
							<div class="row">
								<div class="checkbox-custom checkbox-inline">
									<input type="checkbox" id="level_classes" name="level_classes[]" value="'.$valuecls['class_id'].'">
									<label for="checkboxExample1">'.$valuecls['class_name'].'</label>
								</div>
							</div>
						</div>';
					}
					echo'
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="level_status" name="level_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="level_status" name="level_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_level" name="submit_level">Save</button>
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