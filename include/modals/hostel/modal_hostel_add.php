<?php 
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('31', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '31', 'add' => '1'))) {
	echo'
	<div id="make_hostel" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="hostels.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Hostel</h2>
				</header>
				<div class="panel-body">';
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
						echo'
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Sub Campus</label>
							<div class="col-md-9">
								<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus">
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
						<label class="col-md-3 control-label">Hostel Name <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="hostel_name" id="hostel_name" required title="Must Be Required"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Hostel Type <span class="required">*</span></label>
						<div class="col-md-9">
							<select class="form-control" required data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" required title="Must Be Required" name="id_type">
								<option value="">Select</option>';
								foreach($hostelype as $listtype) { 
									echo '<option value="'.$listtype['id'].'">'.$listtype['name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Hostel Address <span class="required">*</span></label>
						<div class="col-md-9">
							<textarea class="form-control" rows="3" name= "hostel_address" id="hostel_address"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Suprident Name <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="hostel_warden" id="hostel_warden" required title="Must Be Required" />
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Description</label>
						<div class="col-md-9">
							<textarea class="form-control" rows="2" name = "hostel_detail" id="hostel_detail"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="hostel_status" name="hostel_status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="hostel_status" name="hostel_status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit_hostel" name="submit_hostel">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}
?>