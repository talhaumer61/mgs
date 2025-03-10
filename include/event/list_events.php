<?php
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('42', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '42', 'view' => '1'))) {
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
			if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('42', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '42', 'add' => '1'))) {
				echo'<a href="#make_event" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make event</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Event List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center">#</th>
						<th>Subject </th>
						<th>Date From</th>
						<th>Date To</th>
						<th>Event Title</th>
						<th>Event Of</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					if($_SESSION['userlogininfo']['LOGINCAMPUS'] == 0){
						$condition = "";
					}else{
						$condition = " WHERE e.id_campus IN (0, '".$_SESSION['userlogininfo']['LOGINCAMPUS']."')   ";
					}
					$sqllms	= $dblms->querylms("SELECT e.id, e.status, e.subject, e.detail, e.date_from, e.date_to, e.event_to,
													c.campus_name
													FROM ".EVENTS." e  
													LEFT JOIN ".CAMPUS." c ON c.campus_id = e.id_campus
													WHERE e.is_deleted = '0'
													AND e.id_campus IN (0, ".$id_campus.")
													ORDER BY e.date_from ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {	
						$srno++;
						if($rowsvalues['campus_name']){
							$campus = $rowsvalues['campus_name'];
						}else{
							$campus = "Head Office";
						}
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['subject'].'</td>
							<td>'.$rowsvalues['date_from'].'</td>
							<td>'.$rowsvalues['date_to'].'</td>
							<td>'.$rowsvalues['event_to'].'</td>
							<td>'.$campus.'</td>
							<td class="center">'.get_status($rowsvalues['status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('42', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '42', 'edit' => '1'))) {
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/event/modal_event_update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('42', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '42', 'delete' => '1'))) {
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'event.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
								}
								echo'
							</td>
						</tr>';
					}
					echo'
				</tbody>
			</table>
		</div>
	</section>';
} else {
	header("location: dashboard.php");
}
?>