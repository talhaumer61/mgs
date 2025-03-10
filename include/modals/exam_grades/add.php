<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '14', 'added' => '1'))){ 
	echo'
	<div id="make_hostel" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="exam_grades.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i> Add Grade</h2>
				</header>
				<div class="panel-body">
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Name <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="grade_name" id="grade_name" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Percent From <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="grade_lowermark" id="grade_lowermark" required>
						</div>
					</div>					
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Percent Upto <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="grade_uppermark" id="grade_uppermark" required>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Comment  <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="grade_comment" id="grade_comment" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="grade_status" name="grade_status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="grade_status" name="grade_status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit_grade" name="submit_grade">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}
?>