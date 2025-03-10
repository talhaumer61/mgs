<?php
if($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1) { 
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form action="admins.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<div class="panel-heading">
				<h4 class="panel-title"><i class="fa fa-plus-square"></i> Add Admin</h4>
			</div>
			<div class="panel-body">
				<label class="control-label">Photo</label>
				<div class="row">
					<div class="col-md-6">
						<div class="fileinput fileinput-new" data-provides="fileinput">
							<div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">
								<img src="uploads/default-student.jpg" alt="...">
							</div>
							<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 130px"></div>
							<div>
								<span class="btn btn-xs btn-default btn-file">
									<span class="fileinput-new">Select image</span>
									<span class="fileinput-exists">Change</span>
									<input type="file" name="adm_photo" accept="image/*">
								</span>
								<a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<label class="control-label">Sub Campus <span class="required">*</span></label>
							<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_subcampus" name="id_subcampus[]" multiple title="Must Be Required">
								<option value="all">All</option>';
								$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																	FROM ".CAMPUS." 
																	WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																	AND campus_status	= '1'
																	AND is_deleted		= '0'
																	ORDER BY campus_id ASC");
								while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
									echo '<option class="opt_permission" value="'.$valSubCampus['campus_id'].'">'.$valSubCampus['campus_name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>					
					<div class="form-group">
						<div class="col-md-6">
							<label class="control-label">User <span class="required">*</span></label>
							<select class="form-control populate" data-plugin-selectTwo data-width="100%" id="id_emply" name="id_emply" required title="Must Be Required" onchange="get_employeedetail(this.value)">
								<option value="">Select</option>';
								$sqllmsdept	= $dblms->querylms("SELECT emply_id, emply_name 
																FROM ".EMPLOYEES."
																WHERE id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
																AND emply_status	= '1'
																AND is_deleted		= '0'
																AND id_type			= '2'
																ORDER BY emply_name ASC");
								while($valuedept = mysqli_fetch_array($sqllmsdept)) {
									echo'<option value="'.$valuedept['emply_id'].'">'.$valuedept['emply_name'].'</option>';
								}
								echo'
							</select>
						</div>
						<div class="col-md-6">
							<label class="control-label">Password <span class="required">*</span></label>
							<input type="password" class="form-control" name="adm_userpass" id="adm_userpass" required title="Must Be Required" value="" >
						</div>
					</div>
					<div id="getemployeedetail"></div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="adm_status" name="adm_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="adm_status" name="adm_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
				<div style="clear:both;"></div>
				<div id="checkboxes">';
					$sri = 0;
					$ji = 0;
					$kij = 0;
					$rolesarray = array();
					$brolesarray = array();
					// foreach looop
					foreach($roletypes as $itemroletypes) {
						$sqllmsroles  = $dblms->querylms("SELECT role_id, role_status, role_name, role_type 
															FROM ".ROLES." 
															WHERE role_type = '".$itemroletypes['id']."' 
															AND role_status = '1'
															AND is_deleted 	= '0'
															AND id_type IN (2, 3)
															ORDER BY role_name ASC");
						if (mysqli_num_rows($sqllmsroles) > 0) {
							$kij++;
							echo'
							<div style="clear:both;"></div>
							<div class="col-lg-12 heading-modal" style="background-color: #cb3f44; color: white; padding: 5px; border-radius: 5px;">
								<input id="role_'.$kij.'" type="checkbox" class="checkbox-inline"><label for="role_'.$kij.'"> <b> '.$itemroletypes['name'].'</b></label>
							</div>
							<div style="clear:both;"></div>';
							$rolesarray[] = ' $("#role_'.$kij.'").change(function () {
											$(".role_'.$kij.'").prop("checked", $(this).prop("checked"));
										});';
							while($rowroles = mysqli_fetch_array($sqllmsroles)) {
								$sri++;
								$ji++;
								echo'
								<div class="col-sm-41">
									<div class="form_sep" style="margin-top:10px;">
										<div class="col-sm-3" style="padding:10px;">
											<label><input id="roleb_'.$sri.'" type="checkbox" class="checkbox-inline role_'.$kij.'"><label for="roleb_'.$sri.'"> <b style="color: #cb3f44"> '.$rowroles['role_name'].'</b></label><br>
											<input id="add_'.$sri.'" name="added['.$sri.']" type="checkbox" value="1" class="checkbox-inline role_'.$kij.' roleb_'.$sri.'"> <label for="add_'.$sri.'"> Add </label> 
											<input id="edit_'.$sri.'" name="updated['.$sri.']" type="checkbox" value="1" class="checkbox-inline role_'.$kij.' roleb_'.$sri.'"> <label for="edit_'.$sri.'"> Edit</label>
											<input id="delete_'.$sri.'" name="deleted['.$sri.']" type="checkbox" value="1" class="checkbox-inline role_'.$kij.' roleb_'.$sri.'"> <label for="delete_'.$sri.'"> Delete</label>
											<input id="view_'.$sri.'"  name="view['.$sri.']" type="checkbox" value="1" class="checkbox-inline role_'.$kij.' roleb_'.$sri.'"> <label for="view_'.$sri.'"> View</label>
										</div>
									</div> 
								</div>
								<input type="hidden" name="right_name['.$sri.']" id="right_name['.$sri.']" value="'.$rowroles['role_id'].'">
								<input type="hidden" name="right_type['.$sri.']" id="right_type['.$sri.']" value="'.$itemroletypes['id'].'">';
								$brolesarray[] = ' $("#roleb_'.$sri.'").change(function () {
												$(".roleb_'.$sri.'").prop("checked", $(this).prop("checked"));
											});';
							}
							//echo '<input type="hidden" name="right_type['.$sri.']" id="right_type['.$sri.']" value="'.$itemroletypes['id'].'"> ';
						}
					}
					echo'
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" id="submit_admin" name="submit_admin" class="mr-xs btn btn-primary">Add Admin</button>
						<button type="reset" class="btn btn-default">Reset</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
	
	<script type="text/javascript">
		$(document).ready(function(){ ';
			foreach($rolesarray as $totalrole) { 
				echo $totalrole;
			}
			foreach($brolesarray as $totalbrole) { 
				echo $totalbrole;
			}
			echo'
		});
	</script>';
}else{
	header("Location: dashboard.php");
}
?>