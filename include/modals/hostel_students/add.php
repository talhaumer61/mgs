<?php
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('87', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '87', 'add' => '1'))) {
	echo '
	<style>
		.display-n {display: none;}
	</style>
	<div id="make_notification" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="hostel_students.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" autocomplete="off" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Hostel Registration</h2>
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
						<label class="col-md-3 control-label">Hostel <span class="required">*</span></label>
						<div class="col-md-9">
							<select name="id_hostel" data-plugin-selectTwo data-width="100%" id="id_hostel" onchange="get_Rooms(this.value)" required title="Must Be Required" class="form-control populate">
								<option value="">Select</option>';
									$sqllmsHostel = array ( 
															'select' 	=> '
																				hostel_id 
																				, hostel_name 
																			',
															'where' 	=> array( 
																					'is_deleted'    	=> '0'
																					, 'hostel_status'   => '1'
																					, 'id_campus'   	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
																				),
															'order_by' 		=> 'hostel_name ASC',
															'return_type' 	=> 'all' 
														); 
									$rowsHostel  = $dblms->getRows(HOSTELS, $sqllmsHostel);	
									foreach ($rowsHostel as $key => $val):
										echo'<option value="'.$val['hostel_id'].'">'.$val['hostel_name'].'</option>';
									endforeach;
								echo '
							</select>
						</div>
					</div>
					<div class="form-group display-n" id="room_hide">
						<label class="col-md-3 control-label">Room <span class="required">*</span></label>
						<div class="col-md-9">
							<select data-plugin-selectTwo data-width="100%" id="id_room" name="id_room" required title="Must Be Required" class="form-control populate">
								<option value="">Select</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Class <span class="required">*</span></label>
						<div class="col-md-9">
							<select name="id_class" data-plugin-selectTwo data-width="100%" id="id_class" onchange="get_section(this.value)" required title="Must Be Required" class="form-control populate">
								<option value="">Select</option>';
								$sqllmsClass = array ( 
															'select' 	=> '
																				class_id 
																				, class_name 
																			',
															'where' 	=> array( 
																					'is_deleted'    	=> '0'
																					, 'class_status'   	=> '1'
																				),
															'order_by' 		=> 'class_id ASC',
															'return_type' 	=> 'all' 
														); 
								$rowsClass  = $dblms->getRows(CLASSES, $sqllmsClass);	
								foreach ($rowsClass as $key => $val):
									echo'<option value="'.$val['class_id'].'">'.$val['class_name'].'</option>';
								endforeach;
								echo '
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Section <span class="required">*</span></label>
						<div class="col-md-9">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" onchange="get_sectionstudent(this.value)" id="id_section" name="id_section">
								<option value="">Select class first</option>
							</select>
						</div>
					</div>
					<div class="form-group display-n" id="std_hide">
						<label class="col-md-3 control-label">Student <span class="required">*</span></label>
						<div class="col-md-9">
							<select name="id_user" id="id_user" required title="Must Be Required" class="form-control selectTwo populate">
								<option value="">Select</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Joining Date <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="date_start" id="date_start" data-plugin-datepicker required title="Must Be Required" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Leaving Date</label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="date_end" id="date_end" data-plugin-datepicker />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="reg_status" name="reg_status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="reg_status" name="reg_status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="reg_status" name="reg_status" value="3">
								<label for="radioExample3">Left</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="reg_status" name="reg_status" value="4">
								<label for="radioExample4">Suspend</label>
							</div>				
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit_hostel_registration" name="submit_hostel_registration">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}
?>
<script>
	jQuery(document).ready(function($) {
		$(".selectTwo").select2({
			dropdownParent: $("#make_notification"),
			minimumResultsForSearch: 0,
			width: "100%"
		});
	});
</script>