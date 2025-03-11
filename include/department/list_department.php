<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('10', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '10', 'view' => '1'))) {
	$id_campus 		= ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])))? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
	$campus_flag 	= ((!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])))? 'col-md-3': 'col-md-4';
	if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
		echo'
		<form action="#" method="POST">
			<div class="row mb-sm">
				<div class="col-md-5 col-md-offset-3">
					<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus">
						<option value="">Select Sub Campus</option>';
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
				<div class="col-md-1">
					<button type="submit" class="btn btn-primary btn-md pull-right"><i class="fa fa-search"></i></button>
				</div>
			</div>
		</form>';
	endif;
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('10', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '10', 'add' => '1'))) {
				echo'<a href="#make_hostel" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Add Department</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Department List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th width="40" class="center">No.</th>
						<th>Department Name</th>
						<th>Department Code</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT dep.dept_id, dep.dept_name, dep.dept_code, dep.dept_status  
													FROM ".DEPARTMENTS." dep 
													WHERE dep.id_campus IN (".$id_campus.") AND is_deleted = 0
													ORDER BY dep.dept_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo'
						<tr>
							<td class="center">'.$rowsvalues['dept_id'].'</td>
							<td>'.$rowsvalues['dept_name'].'</td>
							<td>'.$rowsvalues['dept_code'].'</td>
							<td class="center">'.get_status($rowsvalues['dept_status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('10', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '10', 'edit' => '1'))) { 
									echo '<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/department/modal_department_update.php?id='.$rowsvalues['dept_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('10', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '10', 'delete' => '1'))) { 
									echo '<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'department.php?deleteid='.$rowsvalues['dept_id'].'\');"><i class="el el-trash"></i></a>';
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
}
?>