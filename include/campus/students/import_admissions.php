<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'add' => '1'))){
	error_reporting(0);
	$id_campus 		= (!empty($_POST['id_campus']))		?$_POST['id_campus']	:$_SESSION['userlogininfo']['LOGINCAMPUS'];
	$id_class 		= explode('|',$_POST['id_class']);
	$id_class 		= (!empty($id_class))				?$id_class[0]			:'';
	$id_section 	= (!empty($_POST['id_section']))	?$_POST['id_section']	:'';
	$is_hostel 		= (!empty($_POST['is_hostel']))		?$_POST['is_hostel']	:'';
	$std_status 	= (!empty($_POST['std_status']))	?$_POST['std_status']	:'';
echo '
<div class="row">
	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-primary">
			<form action="students.php?view=import_admissions" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
				<div class="panel-heading">
					<a href="make_admission_sample.xlsx" download="" class="btn btn-primary btn-xs pull-right mr-sm"><i class="fa fa-download"></i> Excel Formate</a>
					<h4 class="panel-title"><i class="fa fa-plus-square"></i> Import Students</h4>
				</div>
				<div class="panel-body">
					<div class="row mt-sm">';
						if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
							echo'
							<div class="col-md-4">
								<label class="control-label">Sub Campus <span class="text-danger">*</span></label>
								<select class="form-control" title="Must Be Required" required="" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required" onchange="get_class(this.value)">
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
						<div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-4' : 'col-md-3').'">
							<label class="control-label">Class <span class="text-danger">*</span></label>
							<select required="" class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value)" title="Must Be Required">
								<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
																FROM ".CLASSES." 
																WHERE class_status = '1'
																AND class_id IN (".$_SESSION['userlogininfo'] ['LOGINCAMPUSCLASSES'].")
																ORDER BY class_id ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['class_id'].'" '.($valuecls['class_id'] == $id_class ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
								}
								echo'
							</select>
						</div>
						<div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-4' : 'col-md-3').'">
							<label class="control-label">Section <span class="text-danger">*</span></label>
							<select required="" class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section" title="Must Be Required">';
								$sqlSection	= $dblms->querylms("SELECT section_id, section_name 
																FROM ".CLASS_SECTIONS."
																WHERE id_class      = '".$id_class."'
																AND section_status  = '1'
																AND is_deleted      = '0'
																AND id_campus IN (".$id_campus.")
																ORDER BY section_name ASC");
								if(mysqli_num_rows($sqlSection) > 0){
									echo'<option value="">Select <span class="text-danger">*</span></option>';
									while($valSection = mysqli_fetch_array($sqlSection)) {
										echo '<option value="'.$valSection['section_id'].'" '.($valSection['section_id'] == $id_section ? 'selected' : '').'>'.$valSection['section_name'].'</option>';
									}
								}else{
									echo '<option value="">No Record Found</option>';
								}
								echo'
							</select>
						</div>
						<div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-4' : 'col-md-2').'">
							<label class="control-label">Is Hostelize <span class="text-danger">*</span></label>
							<select required="" class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="is_hostel" name="is_hostel" title="Must Be Required">
								<option value="">Select</option>';
								foreach ($statusyesno as $hostel_status) {
									echo '<option value="'.$hostel_status['id'].'" '.($hostel_status['id'] == $is_hostel ? 'selected' : '').'>'.$hostel_status['name'].'</option>';
								}
								echo'
							</select>
						</div>
						<div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-4' : 'col-md-2').'">
							<label class="control-label">Status <span class="text-danger">*</span></label>
							<select required="" class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="status" name="std_status" title="Must Be Required">
								<option value="">Select</option>';
								foreach ($stdstatus as $status) {
									echo '<option value="'.$status['id'].'" '.($status['id'] == $std_status ? 'selected' : '').'>'.$status['name'].'</option>';
								}
								echo'
							</select>
						</div>
						<div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-4' : 'col-md-2').'">
							<div class="form-group">
								<label class="control-label">Upload File <span class="text-danger">*</span></label>
								<input type="file" class="form-control" name="imported_file" id="imported_file">
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
					$targetPath 		= 'uploads/students_excel_file/'.$_FILES['imported_file']['name'];
					move_uploaded_file($tempFileName, $targetPath);
					include 'importer.php';
					echo'
					<section class="panel panel-featured panel-featured-primary">
						<form action="students.php?view=import_admissions" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
							<input type="hidden" name="id_campus" value="'.cleanvars($id_campus).'">
							<input type="hidden" name="id_class" value="'.cleanvars($id_class).'">
							<input type="hidden" name="id_section" value="'.cleanvars($id_section).'">
							<input type="hidden" name="is_hostel" value="'.cleanvars($is_hostel).'">
							<input type="hidden" name="std_status" value="'.cleanvars($std_status).'">
							<div class="panel-heading">
								<h4 class="panel-title"><i class="fa fa-plus-square"></i> Preview Students</h4>
							</div>
							<div class="panel-body">
								<div class="row mt-sm">
									<div class="col-md-12">
										<table class="table table-bordered table-striped table-condensed mb-none">
											<thead>
												<tr>
													<th class="center" width="50">Sr.</th>
													<th class="center" width="100">Admission ID</th>
													<th>Student Name</th>
													<th>Father Name</th>
													<th width="180" class="center">Family No (Father CNIC)</th>
													<th width="100" class="center">Gender</th>
													<th width="150" class="center">Date Of Birth</th>
													<th width="150" class="center">Admission Date</th>
													<th width="150" class="center">CNIC/B-Form</th>
													<th width="100" class="center">Mobile 1</th>
													<th width="100" class="center">Mobile 2</th>
													<th width="150" class="center">Adress</th>
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
															<td><input type="hidden" name="admission_id[]" 	value="'.$valSheet[0].'">'.$valSheet[0].'</td>
															<td><input type="hidden" name="student_name[]" 	value="'.$valSheet[1].'">'.$valSheet[1].'</td>
															<td><input type="hidden" name="father_name[]" 	value="'.$valSheet[2].'">'.$valSheet[2].'</td>
															<td class="center"><input type="hidden" name="family_no[]" 		value="'.get_formatCNIC($valSheet[3]).'">'.get_formatCNIC($valSheet[3]).'</td>
															<td class="center"><input type="hidden" name="gender[]" 		value="'.$valSheet[4].'">'.$valSheet[4].'</td>
															<td class="center"><input type="hidden" name="date_of_birth[]" value="'.date('Y-m-d',strtotime($valSheet[5])).'">'.date('Y-m-d',strtotime($valSheet[5])).'</td>
															<td class="center"><input type="hidden" name="adm_date[]" value="'.date('Y-m-d',strtotime($valSheet[6])).'">'.date('Y-m-d',strtotime($valSheet[6])).'</td>
															<td class="center"><input type="hidden" name="cnic[]" 			value="'.get_formatCNIC($valSheet[7]).'">'.get_formatCNIC($valSheet[7]).'</td>
															<td class="center"><input type="hidden" name="mobile_1[]" 		value="'.$valSheet[8].'">'.$valSheet[8].'</td>
															<td class="center"><input type="hidden" name="mobile_2[]" 		value="'.$valSheet[9].'">'.$valSheet[9].'</td>
															<td class="center"><input type="hidden" name="adress[]" 		value="'.$valSheet[10].'">'.$valSheet[10].'</td>
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