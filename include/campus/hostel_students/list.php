<?php 
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('87', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '87', 'view' => '1'))) {
	$id_campus = (!empty($_GET['id_campus']) ? cleanvars($_GET['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));
	
	if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
		echo'
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
			</header>
			<form action="#" id="form" enctype="multipart/form-data" method="get" accept-charset="utf-8">
				<div class="panel-body">
					<div class="row">';
						if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
							echo'
							<div class="col-md-6 col-md-offset-3">
								<div class="form-group mb-md">
									<label class="control-label">Sub Campus</label>
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
					</div>
					<center>
						<button type="submit" class="btn btn-primary"><i class="fa fa fa-search"></i> Search</button>
					</center>
				</div>
			</form>
		</section>';
	}
	
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('87', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '87', 'add' => '1'))) {
				echo'<a href="#make_notification" class="modal-with-move-anim btn btn-primary btn-xs pull-right">';
			}
			echo'
			<i class="fa fa-plus-square"></i> Make Registration</a>
			<h2 class="panel-title"><i class="fa fa-list"></i>  Hsotel Students List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th width="40" class="center">Sr.</th>
						<th width="180">Student</th>
						<th width="150">Class</th>
						<th>Hostel</th>
						<th width="50" class="center">Room</th>
						<th width="50" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllmscheck 	= array ( 
												'select' 	=> '
																	'.HOSTELS_REGISTRATION.'.id
																	, '.HOSTELS_REGISTRATION.'.status
																	, '.HOSTELS_REGISTRATION.'.date_start 
																	, '.HOSTELS_REGISTRATION.'.date_end 
																	, '.HOSTELS_REGISTRATION.'.id_campus 
																	, '.HOSTELS.'.hostel_id
																	, '.HOSTELS.'.hostel_name
																	, '.HOSTEL_ROOMS.'.room_id
																	, '.HOSTEL_ROOMS.'.room_name
																	, '.CLASSES.'.class_id
																	, '.CLASSES.'.class_name
																	, '.STUDENTS.'.std_id
																	, '.STUDENTS.'.std_name
																',
												'join' 		=> '
																	INNER JOIN '.HOSTELS.' 			ON '.HOSTELS.'.hostel_id 		= '.HOSTELS_REGISTRATION.'.id_hostel
																	INNER JOIN '.HOSTEL_ROOMS.' 	ON '.HOSTEL_ROOMS.'.room_id 	= '.HOSTELS_REGISTRATION.'.id_room
																	INNER JOIN '.STUDENTS.' 		ON '.STUDENTS.'.std_id 			= '.HOSTELS_REGISTRATION.'.id_user
																	INNER JOIN '.CLASSES.' 			ON '.CLASSES.'.class_id 		= '.HOSTELS_REGISTRATION.'.id_class
																',
												'where' 	=> array( 
																		HOSTELS_REGISTRATION.'.is_deleted'  	=> '0'
																		, HOSTELS_REGISTRATION.'.id_campus'   	=> cleanvars($id_campus)
																	),
												'return_type' 	=> 'all' 
											);
					$rowsQueryCheck  	= $dblms->getRows(HOSTELS_REGISTRATION, $sqllmscheck);	
					$srno = 0;
					foreach($rowsQueryCheck as $key => $val):
						$srno++;
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$val['std_name'].'</td>
							<td>'.$val['class_name'].'</td>
							<td>'.$val['hostel_name'].'</td>
							<td class="center">'.$val['room_name'].'</td>
							<td class="center">'.get_HostelStatus($val['status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('87', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '87', 'edit' => '1'))) {
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/hostel_students/update.php?id_reg='.$val['id'].'&id_hostel='.$val['hostel_id'].'&id_room='.$val['room_id'].'&id_class='.$val['class_id'].'&id_std='.$val['std_id'].'&date_start='.$val['date_start'].'&date_end='.$val['date_end'].'&status='.$val['status'].'&fee='.$val['fee'].'&id_campus='.$val['id_campus'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('87', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '87', 'delete' => '1'))) {
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'hostel_students.php?deleteid='.$val['id'].'&id_std='.$val['std_id'].'\');"><i class="el el-trash"></i></a>';
								}
								echo'
							</td>
						</tr>';
					endforeach;
					echo'
				</tbody>
			</table>
		</div>
	</section>';
} else {
	header("Location: dashboard.php");
}
?>