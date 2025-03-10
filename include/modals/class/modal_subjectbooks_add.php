<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('5', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '5', 'add' => '1'))) {
	echo'
	<div id="add_book" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="classsubjects.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Add Subject Book</h2>
				</header>
				<div class="panel-body">				
					<div class="form-group mb-md">
						<label class="col-md-4 control-label">Class Name <span class="required">*</span></label>
						<div class="col-md-8">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_class" name="id_class" required onchange="get_subject(this.value)" title="Must Be Required">
								<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
																FROM ".CLASSES." 
																WHERE class_status = '1'
																ORDER BY class_id ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo'<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-4 control-label">Subject <span class="required">*</span></label>
						<div class="col-md-8">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_subject" name="id_subject" required title="Must Be Required">
								<option value="">Select</option>
							</select>
						</div>
					</div>
					<!--
					<div class="form-group mb-md">
						<label class="col-md-4 control-label">Subject Total Marks <span class="required">*</span></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name = "subject_totalmarks" id="subject_totalmarks" required title="Must Be Required">
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-4 control-label">Subject Pass Marks <span class="required">*</span></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name = "subject_passmarks" id="subject_passmarks" required title="Must Be Required">
						</div>
					</div>
					-->
					<div class="form-group mb-md">
						<label class="col-md-4 control-label">Subject Type <span class="required">*</span></label>
						<div class="col-md-8">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="type" name="type" required title="Must Be Required">
								<option value="">Select</option>';
								foreach ($subjecttype as $type) {
									echo'<option value="'.$type['id'].'">'.$type['name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-4 control-label">Book Name <span class="required">*</span></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="name" id="name" required title="Must Be Required">
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-4 control-label">Book Edition <span class="required">*</span></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="edition" id="edition" required title="Must Be Required">
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-4 control-label">Book Publisher <span class="required">*</span></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="publisher" id="publisher" required title="Must Be Required">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Status <span class="required">*</span></label>
						<div class="col-md-8">
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
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit_book" name="submit_book">Save</button>
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