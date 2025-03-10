<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '82', 'added' => '1'))){
	echo'
	<div id="add" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="exam_datesheet.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Instructions</h2>
				</header>
				<div class="panel-body">
					<div class="form-group">
						<div class="col-md-6">
							<label class="control-label">Exam Type <span class="required">*</span></label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_examtype" name="id_examtype" required title="Must Be Required">
								<option value="">Select</option>';
								$sqllmsexam	= $dblms->querylms("SELECT DISTINCT t.type_id, t.type_status, t.type_name 
																FROM ".EXAM_TYPES." t												 
																WHERE t.is_deleted	= '0'
																AND t.type_status	= '1'
																AND t.id_campus 	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
															");
								while($valueexam = mysqli_fetch_array($sqllmsexam)) {
									echo '<option value="'.$valueexam['type_id'].'">'.$valueexam['type_name'].'</option>';
								}
								echo'
							</select>
						</div>
						<div class="col-md-6">
							<label class="control-label">Class <span class="required">*</span></label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" required title="Must Be Required" >
								<option value="">Select</option>';
								$sqllmscls = $dblms->querylms("SELECT class_id, class_status, class_name 
																FROM ".CLASSES."
																WHERE class_status	= '1'
																AND is_deleted		= '0'
																ORDER BY class_id ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
								}
								echo'
							</select>
						</div>
						<div class="col-md-12">
							<label class="control-label">Instructions <span class="required">*</span></label>
							<textarea data-plugin-summernote class="summernote summernoteEx" rows="5" name="instructions" id="instructions"></textarea>
						</div>
						<div class="col-md-12">
							<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
							<div class="col-md-9">
								<div class="radio-custom radio-inline">
									<input type="radio" id="status" name="status" value="1" checked>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="status" name="status" value="2">
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit_inst" name="submit_inst">Save</button>
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