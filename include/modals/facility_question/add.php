<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '83', 'add' => '1'))){
echo'
<div id="make_facility_question" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="facility_question.php" class="form-horizontal" id="form" autocomplete="off" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Inspection Statement</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Title <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="question_name" id="question_name" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Ordering <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="number" class="form-control" name="question_ordering" id="question_ordering" required title="Must Be Required">
					</div>
				</div>	
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Facility Category <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_cat">
							<option value="">Select</option>';
								$sqllmszone	= $dblms->querylms("SELECT  cat_id, cat_name
													FROM ".FACILITY_CATS."
													WHERE cat_id != '' AND cat_status = '1'
													AND is_deleted != '1'
													ORDER BY cat_ordering ASC");
								while($valuezone = mysqli_fetch_array($sqllmszone)) {
							echo '<option value="'.$valuezone['cat_id'].'">'.$valuezone['cat_name'].'</option>';
							}
						echo '
						</select>
					</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="question_status" name="question_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="question_status" name="question_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_question" name="submit_question">Save</button>
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