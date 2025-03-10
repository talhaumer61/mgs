<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'view' => '1'))){
	if(isset($_POST['campus'])){$campus = $_POST['campus'];}else{$campus = '0';}	
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
		</header>
		<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<div class="panel-body">
				<div class="row mb-lg">
					<div class="col-md-offset-4 col-md-4">
						<div class="form-group">
							<label class="control-label">Campus <span class="required">*</span></label>
							<select data-plugin-selectTwo data-width="100%" name="campus" id="campus" required title="Must Be Required" class="form-control populate">
								<option value="0">Head Office</option>';
								$sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
																	FROM ".CAMPUS." c  
																	WHERE c.campus_id != '' AND campus_status = '1'
																	ORDER BY c.campus_name ASC");
								while($value_campus = mysqli_fetch_array($sqllmscampus)){
									if($campus == $value_campus['campus_id']){
										echo'<option value="'.$value_campus['campus_id'].'" selected>'.$value_campus['campus_name'].'</option>';
									}else{
										echo'<option value="'.$value_campus['campus_id'].'">'.$value_campus['campus_name'].'</option>';
									}
								}
								echo'
							</select>
						</div>
					</div>
				</div>
				<center>
					<button type="submit" name="view_students" id="view_students" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
				</center>
			</div>
		</form>
	</section>
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'add' => '1'))){ 
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
						<th width="40" class="center">Photo</th>
						<th>Employee Name</th>
						<th class="center">Type</th>
						<th>Regestration Number</th>
						<th>Department</th>
						<th>Designation</th>
						<th class="center">Phone</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT e.emply_id, e.emply_status, e.emply_regno, e.emply_name, e.id_dept, 
													e.id_designation, e.id_type, e.emply_gender, e.emply_dob, e.emply_joindate,
													e.emply_education, e.emply_experence, e.emply_religion, e.emply_bloodgroup,
													e.emply_address, e.emply_phone, e.emply_email, e.emply_photo,
													d.dept_name,
													dp.designation_name 
													FROM ".EMPLOYEES." e     
													INNER JOIN ".DEPARTMENTS." d ON d.dept_id = e.id_dept
													INNER JOIN ".DESIGNATIONS." dp ON dp.designation_id = e.id_designation
													WHERE e.emply_id != '' AND e.is_deleted != '1'
													AND e.id_campus = '".$campus."' 
													ORDER BY e.emply_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td>';
								if($rowsvalues['emply_photo']) { 
									echo'<img src="uploads/images/employees/'.$rowsvalues['emply_photo'].'" style="width:40px; height:40px;">' ;
								}else{
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
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'edit' => '1'))){ 
									echo'<a class="btn btn-success btn-xs" href="employee.php?id='.$rowsvalues['emply_id'].'"> <i class="fa fa-user-circle-o"></i></a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'delete' => '1'))){ 
									echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'employee.php?deleteid='.$rowsvalues['emply_id'].'\');"><i class="el el-trash"></i></a>';
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