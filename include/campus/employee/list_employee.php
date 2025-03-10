<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'view' => '1'))) {
	$id_campus 		= (!empty($_GET['id_campus']) ? cleanvars($_GET['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));
	$campus_flag 	= (!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-3': 'col-md-4');
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
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'add' => '1'))) {
				echo'<a href="employee.php?view=add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Employee</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Employees List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
				<thead>
					<tr>
						<th width="40" class="center">Sr.</th>
						<th width="40">Photo</th>
						<th>Employee Name</th>
						<th>Type</th>
						<th>Regestration Number</th>
						<th>Department</th>
						<th>Designation</th>
						<th>Phone</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT e.emply_id, e.emply_status, e.emply_regno, e.emply_name, e.id_dept, e.id_designation, e.id_type, e.emply_gender, e.emply_dob, e.emply_joindate, e.emply_education, e.emply_experence, e.emply_religion, e.emply_bloodgroup, e.emply_address, e.emply_phone, e.emply_email, e.emply_photo, e.id_campus, d.dept_name, dp.designation_name 
												FROM ".EMPLOYEES." e
												LEFT JOIN ".DESIGNATIONS." dp ON dp.designation_id = e.id_designation
												LEFT JOIN ".DEPARTMENTS." d ON d.dept_id = e.id_dept
												WHERE e.emply_id != '' AND e.is_deleted != '1'
												AND e.id_campus IN (".$id_campus.")
												AND e.is_ad != '1' AND e.is_de != '1'
												ORDER BY e.emply_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td>';
								if($rowsvalues['emply_photo']) { 
									echo'<img src="uploads/images/employees/'.$rowsvalues['emply_photo'].'" width="40" height="40">' ;
								} else {
									echo'<img src="uploads/defualt.png" class="rounded img-responsive">';
								}
							echo'
							</td>
							<td>'.$rowsvalues['emply_name'].'</td>
							<td>'.get_emplytype($rowsvalues['id_type']).'</td>
							<td>'.$rowsvalues['emply_regno'].'</td>
							<td>'.$rowsvalues['dept_name'].'</td>
							<td>'.$rowsvalues['designation_name'].'</td>
							<td>'.$rowsvalues['emply_phone'].'</td>
							<td class="center">'.get_status($rowsvalues['emply_status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'edit' => '1'))) { 
									echo'<a class="btn btn-success btn-xs" href="employee.php?id='.$rowsvalues['emply_id'].'&id_campus='.$id_campus.'"> <i class="fa fa-user-circle-o"></i></a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'delete' => '1'))) { 
									echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'employee.php?deleteid='.$rowsvalues['emply_id'].'&id_campus='.$id_campus.'\');"><i class="el el-trash"></i></a>';
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
}else{
	header("location: dashboard.php");
}
?>