<?php
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('42', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '42', 'add' => '1'))) {
	echo'
	<div id="make_event" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="#" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Event</h2>
				</header>
				<div class="panel-body">';
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
						echo'
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Sub Campus</label>
							<div class="col-md-9">
								<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required">
									<option value="">Select</option>';
									$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																		FROM ".CAMPUS." 
																		WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																		AND campus_status	= '1'
																		AND is_deleted		= '0'
																		ORDER BY campus_id ASC");
									while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
										echo '<option value="'.$valSubCampus['campus_id'].'">'.$valSubCampus['campus_name'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>';
					}
					echo'
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Subject <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="subject" id="subject" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Date From <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="input-daterange input-group" data-plugin-datepicker>
								<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
								<input type="text" class="form-control valid" name="date_from" id="date_from" required="" title="Must Be Required" aria-required="true" aria-invalid="false">
								<span class="input-group-addon">to</span>
								<input type="text" class="form-control" name = "date_to" id="date_to" required="" title="Must Be Required"  aria-required="true">
							</div>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Event Title <span class="required">*</span></label>
						<div class="col-md-9">
							<input class="form-control" rows="2" name="event_to" id="event_to">
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Details <span class="required">*</span></label>
						<div class="col-md-9">
							<textarea class="form-control" rows="2" name="detail" id="detail"></textarea>
						</div>
					</div>
					<!-- <div class="form-group mt-sm">
						<label class="col-md-3 control-label">Added By <span class="required">*</span></label>
						<div class="col-md-9">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="alert_by">
								<option value="">Select</option>';
									$sqllmscls	= $dblms->querylms("SELECT emply_id, emply_name 
														FROM ".EMPLOYEES."
														WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
														ORDER BY emply_name ASC");
									while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['emply_id'].'">'.$valuecls['emply_name'].'</option>';
								}
							echo '
							</select>
						</div>
					</div> -->
					<div class="form-group">
						<label class="col-md-3 control-label">Recipient <span class="required">*</span></label>
						<div class="col-md-9">';
						if($_SESSION['userlogininfo']['LOGINAFOR'] == 1){
							echo'
							<div class="checkbox-custom checkbox-inline">
								<input type="checkbox" id="to_campus" name="to_campus" value="1">
								<label for=checkboxExample">Campus </label>
							</div>';
						}
						echo'
							<div class="checkbox-custom checkbox-inline">
								<input type="checkbox" id="to_staff" name="to_staff" value="1">
								<label for=checkboxExample">Staff</label>
							</div>
							<div class="checkbox-custom checkbox-inline">
								<input type="checkbox" id="to_parent" name="to_parent" value="1">
								<label for=checkboxExample">Parent</label>
							</div>
							<div class="checkbox-custom checkbox-inline">
								<input type="checkbox" id="to_student" name="to_student" value="1">
								<label for=checkboxExample2">Student</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Type <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="id_type" name="id_type" value="1" checked>
								<label for="radioExample1">Popup</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="id_type" name="id_type" value="2">
								<label for="radioExample2">Navbar</label>
							</div>
						</div>
					</div>
					<div class="form-group">
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
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit_event" name="submit_event">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}
?>