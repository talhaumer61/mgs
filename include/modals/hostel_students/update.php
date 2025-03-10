<?php
require_once("../../dbsetting/lms_vars_config.php");
require_once("../../dbsetting/classdbconection.php");
require_once("../../functions/functions.php");
$dblms = new dblms();
require_once("../../functions/login_func.php");
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('87', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '87', 'edit' => '1'))) {
	$id_reg 		= cleanvars($_GET['id_reg']);
	$id_hostel 		= cleanvars($_GET['id_hostel']);
	$id_room 		= cleanvars($_GET['id_room']);
	$id_class 		= cleanvars($_GET['id_class']);
	$id_std 		= cleanvars($_GET['id_std']);
	$status 		= cleanvars($_GET['status']);
	$id_campus 		= cleanvars($_GET['id_campus']);

	if ($_GET['date_start'] != '1970-01-01') {
		$date_start 	= date("m/d/Y",strtotime(cleanvars($_GET['date_start'])));
	} else {
		$date_start 	= '';
	}
	if ($_GET['date_end'] != '1970-01-01') {
		$date_end 		= date("m/d/Y",strtotime(cleanvars($_GET['date_end'])));
	} else {
		$date_end 		= '';
	}
	echo'
	<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<section class="panel panel-featured panel-featured-primary">
		<form action="hostel_students.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" autocomplete="off" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i>  Edit Hostel Registration</h2>
				<input type="hidden" name="id" value="'.$id_reg.'" />
				<input type="hidden" name="id_std_edit" value="'.$id_std.'" />
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
									echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $id_campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
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
									echo'<option value="'.$val['hostel_id'].'" '.(($val['hostel_id'] == $id_hostel)?'selected':'').'>'.$val['hostel_name'].'</option>';
								endforeach;
							echo '
						</select>
					</div>
				</div>
				<div class="form-group '.((empty($id_hostel))?'display-n':'').'" id="room_hide">
					<label class="col-md-3 control-label">Room <span class="required">*</span></label>
					<div class="col-md-9">
						<select data-plugin-selectTwo data-width="100%" id="id_room" name="id_room" required title="Must Be Required" class="form-control populate">
							<option value="">Select</option>';
								$sqllmsRoom = array ( 
														'select' 	=> '
																			  room_id 
																			, room_name 
																			, room_beds 
																			, room_bedfee 
																		',
														'where' 	=> array( 
																				  'is_deleted'    	=> '0'
																				, 'room_status'   	=> '1'
																				, 'id_hostel'   	=> $id_hostel
																				, 'id_campus'   	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
																			),
														'order_by' 		=> 'room_id ASC',
														'return_type' 	=> 'all' 
													); 
								$rowsRoom  = $dblms->getRows(HOSTEL_ROOMS, $sqllmsRoom);	
								foreach ($rowsRoom as $key => $val):
									$sqllmsRoomFree = array ( 
																'select' 	=> '
																					  '.HOSTELS_REGISTRATION.'.id
																					, '.HOSTELS_REGISTRATION.'.id_user
																				',
																'where' 	=> array( 
																						  HOSTELS_REGISTRATION.'.is_deleted'    => '0'
																						, HOSTELS_REGISTRATION.'.status'        => '1'
																						, HOSTELS_REGISTRATION.'.id_campus'     => cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
																						, HOSTELS_REGISTRATION.'.id_hostel'     => cleanvars($id_hostel)
																						, HOSTELS_REGISTRATION.'.id_room'       => cleanvars($val['room_id'])
																					),
																'return_type' 	=> 'count' 
															); 
									$rowsQueryCheckRoomFree  	= $dblms->getRows(HOSTELS_REGISTRATION, $sqllmsRoomFree);	
									if ($val['room_beds'] > $rowsQueryCheckRoomFree || $val['room_id'] == $id_room) {
										echo'<option value="'.$val['room_id'].'|'.$val['room_beds'].'|'.$val['room_bedfee'].'" '.(($val['room_id'] == $id_room)?'selected':'').'>( '.abs($val['room_beds']-$rowsQueryCheckRoomFree).' ) Beds Free In Room ( '.$val['room_name'].' )</option>';
									}
								endforeach;
							echo '
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Class <span class="required">*</span></label>
					<div class="col-md-9">
						<select name="id_class" data-plugin-selectTwo data-width="100%" id="id_class" onchange="get_Students(this.value)" required title="Must Be Required" class="form-control" disabled>
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
								echo'<option value="'.$val['class_id'].'" '.(($val['class_id'] == $id_class)?'selected':'').'>'.$val['class_name'].'</option>';
							endforeach;
							echo '
						</select>
					</div>
				</div>
				<div class="form-group '.((empty($id_class))?'display-n':'').'" id="std_hide">
					<label class="col-md-3 control-label">Student <span class="required">*</span></label>
					<div class="col-md-9">
						<select name="id_user" data-plugin-selectTwo data-width="100%" id="id_user" required title="Must Be Required" class="form-control" disabled>
							<option value="">Select</option>';
								$sqllmsStd = array ( 
														'select' 	=> '
																			  std_id 
																			, std_name 
																		',
														'where' 	=> array( 
																				  'is_deleted'    	=> '0'
																				, 'std_status'   	=> '1'
																				, 'id_class'   		=> $id_class
																				, 'id_campus'   	=> cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
																			),
														'order_by' 		=> 'std_name ASC',
														'return_type' 	=> 'all' 
													); 
								$rowsStd  = $dblms->getRows(STUDENTS, $sqllmsStd);	
								foreach ($rowsStd as $key => $val):
									echo'<option value="'.$val['std_id'].'" '.(($val['std_id'] == $id_std)?'selected':'').'>'.$val['std_name'].'</option>';
								endforeach;
							echo '
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Joining Date <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" value="'.$date_start.'" name="date_start" data-plugin-datepicker required title="Must Be Required" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Leaving Date</label>
					<div class="col-md-9">
						<input type="text" class="form-control" value="'.$date_end.'" name="date_end" data-plugin-datepicker />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="reg_status" name="reg_status" '.(($status == 1)?'checked':'').' value="1">
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="reg_status" name="reg_status" '.(($status == 2)?'checked':'').' value="2">
							<label for="radioExample2">Inactive</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="reg_status" name="reg_status" '.(($status == 3)?'checked':'').' value="3">
							<label for="radioExample3">Left</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="reg_status" name="reg_status" '.(($status == 4)?'checked':'').' value="4">
							<label for="radioExample4">Suspend</label>
						</div>				
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="edit_hostel_registration" name="edit_hostel_registration">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>';
}
?>