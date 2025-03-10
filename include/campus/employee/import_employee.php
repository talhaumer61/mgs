<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'add' => '1'))){
	error_reporting(0);
	$id_campus 		= (!empty($_POST['id_campus']))		?$_POST['id_campus']	:$_SESSION['userlogininfo']['LOGINCAMPUS'];
	$id_type 		= (!empty($_POST['id_type']))		?$_POST['id_type']		:'';
	$emply_status 	= (!empty($_POST['emply_status']))	?$_POST['emply_status']	:'';
echo '
<div class="row">
	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-primary">
			<form action="employee.php?view=import_employee" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
				<div class="panel-heading">
					<a href="make_employee.xlsx" download="" class="btn btn-primary btn-xs pull-right mr-sm"><i class="fa fa-download"></i> Excel Formate</a>
					<h4 class="panel-title"><i class="fa fa-plus-square"></i> Import Employees</h4>
				</div>
				<div class="panel-body">
					<div class="row mt-sm">';
						if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
							echo'
							<div class="col-md-4">
								<label class="control-label">Sub Campus <span class="text-danger">*</span></label>
								<select class="form-control" title="Must Be Required" required="" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required">
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
							</div>';
						}
						echo'
						<div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-4' : 'col-md-6').'">
							<div class="form-group">
								<label class="control-label">Department <span class="required">*</span></label>
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_dept">
									<option value="">Select</option>';
									$sqllmscls	= $dblms->querylms("SELECT dept_id, dept_name 
																FROM ".DEPARTMENTS."
																WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
																ORDER BY dept_name ASC");
									while($valuecls = mysqli_fetch_array($sqllmscls)) {
										echo'<option value="'.$valuecls['dept_id'].'">'.$valuecls['dept_name'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-4' : 'col-md-6').'">
								<div class="form-group">
									<label class="control-label">Designation <span class="required">*</span></label>
									<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_designation">
										<option value="">Select</option>';
										$sqllmscls	= $dblms->querylms("SELECT designation_id, designation_name 
																	FROM ".DESIGNATIONS."
																	WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
																	ORDER BY designation_name ASC");
										while($valuecls = mysqli_fetch_array($sqllmscls)) {
											echo'<option value="'.$valuecls['designation_id'].'">'.$valuecls['designation_name'].'</option>';
										}
										echo'
									</select>
								</div>
							</div>
						<div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-4' : 'col-md-6').'">
							<div class="form-group">
								<label class="control-label">Employee Type <span class="required">*</span></label>
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_type">
									<option value="">Select</option>';
									foreach($emply_type as $emplyType){
										echo'<option value="'.$emplyType['id'].'" '.($emplyType['id'] == $id_type ? 'selected' : '').'>'.$emplyType['name'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>
						<div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-4' : 'col-md-6').'">
							<div class="form-group">
								<label class="control-label">Upload File <span class="text-danger">*</span></label>
								<input type="file" class="form-control" name="imported_file" id="imported_file">
							</div>
						</div>
						<div class="row mt-sm">
							<div class="col-sm-12">
								<div class="form-group mt-sm mb-sm">
									<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
									<div class="col-md-9">
										<div class="radio-custom radio-inline">
											<input type="radio" id="emply_status" name="emply_status" value="1" '.($emply_status == '1'?'checked':'').'>
											<label for="radioExample1">Active</label>
										</div>
										<div class="radio-custom radio-inline">
											<input type="radio" id="emply_status" name="emply_status" value="2" '.($emply_status == '2'?'checked':'').'>
											<label for="radioExample2">Inactive</label>
										</div>
									</div>
								</div>
							</div>
						</div>						
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-center">
							<button type="submit" id="view_submit" name="view_submit" class="mr-xs btn btn-primary">Preview</button>
						</div>
					</div>
				</footer>
			</form>
		</section>';
		if (isset($_POST['view_submit'])) {
			if (!empty($_FILES['imported_file']['name'])) {
				$ext	= pathinfo($_FILES['imported_file']['name'],PATHINFO_EXTENSION);
				if($ext === 'xlsx') {
					$tempFileName 		= $_FILES['imported_file']['tmp_name'];
					$targetPath 		= 'uploads/employee_excel_file/'.$_FILES['imported_file']['name'];
					move_uploaded_file($tempFileName, $targetPath);
					include 'importer.php';
						echo'
					<section class="panel panel-featured panel-featured-primary">
						<form action="employee.php?view=import_employee" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
							<input type="hidden" name="id_campus" value="'.cleanvars($id_campus).'">
							<input type="hidden" name="id_type" value="'.cleanvars($id_type).'">
							<input type="hidden" name="emply_status" value="'.cleanvars($emply_status).'">
							<div class="panel-heading">
								<h4 class="panel-title"><i class="fa fa-plus-square"></i> Preview Employee</h4>
							</div>
							<div class="panel-body">
								<div class="row mt-sm">
									<div class="col-md-12">
										<table class="table table-bordered table-striped table-condensed mb-none">
											<thead>
												<tr>
													<th class="center" width="50">Sr.</th>
													<th>Employee Name</th>
													<th>Email</th>
													<th width="100" class="center">Gender</th>
													<th width="150" class="center">Date Of Birth</th>
													<th width="150" class="center">CNIC</th>
													<th width="150" class="center">Phone</th>
													<th width="150" class="center">Whatsapp</th>
												</tr>
											</thead>
											<tbody>';
												$srno = 0;
												foreach($rows as $key => $valSheet):
													if ($key != 0) {
														$srno++;
														echo'
														<tr>
															<td class="center">'.$srno.'</td>
															<td><input type="hidden" name="employee_name[]" 				value="'.$valSheet[0].'">'.$valSheet[0].'</td>
															<td><input type="hidden" name="email[]" 						value="'.$valSheet[1].'">'.$valSheet[1].'</td>
															<td class="center"><input type="hidden" name="gender[]" 		value="'.$valSheet[2].'">'.$valSheet[2].'</td>
															<td class="center"><input type="hidden" name="date_of_birth[]" 	value="'.date('Y-m-d',strtotime($valSheet[3])).'">'.date('Y-m-d',strtotime($valSheet[3])).'</td>
															<td class="center"><input type="hidden" name="cnic[]" 			value="'.$valSheet[4].'">'.$valSheet[4].'</td>
															<td class="center"><input type="hidden" name="phone[]" 			value="'.$valSheet[5].'">'.$valSheet[5].'</td>
															<td class="center"><input type="hidden" name="whatsapp[]" 		value="'.$valSheet[6].'">'.$valSheet[6].'</td>
														</tr>';
													}
												endforeach;
												echo'
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 text-center">
										<button type="submit" id="import_submit" name="import_submit" class="mr-xs btn btn-primary">Import</button>
									</div>
								</div>
							</footer>
						</form>
					</section>';
				} else {

				}
			} else {

			}
		}
		echo'
	</div>
</div>';
} else {
	header("Location: students.php");
}
?>