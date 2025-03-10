 <?php 
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'add' => '1'))) {
	echo'
	<div id="make_teacherlogin" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="teacherlogin.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Teacher Login</h2>
				</header>
				<div class="panel-body">';
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
						echo'
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Campus <span class="required">*</span></label>
							<div class="col-md-9">
								<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required" onchange="get_dept(this.value)">
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
					<div class="form-group mb-md" id="hideDept">
							<label class="col-md-3 control-label">Department <span class="required">*</span></label>
							<div class="col-md-9">
								<div id="getCampusDept">
									<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_dept" id="id_dept" onchange="get_deptemployee(this.value)">
										<option value="">Select</option>';
										$sqllmsdept	= $dblms->querylms("SELECT dept_id, dept_name 
														FROM ".DEPARTMENTS." 
														WHERE dept_status = '1' AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
														ORDER BY dept_name ASC");
										while($value_dept 	= mysqli_fetch_array($sqllmsdept)) {
										echo '<option value="'.$value_dept['dept_id'].'">'.$value_dept['dept_name'].'</option>';
										}
										echo '
									</select>
								</div>
							</div>
						</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Employee <span class="required">*</span></label>
						<div class="col-md-9">
							<div id="getdeptemployee">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_employe" id="id_employe" onchange="get_employeedetail(this.value)">
									<option value="">Select</option>';
										$sqllmsdept	= $dblms->querylms("SELECT emply_id, emply_name 
														FROM ".EMPLOYEES." 
														WHERE emply_status = '1' AND id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
														ORDER BY emply_name ASC");
										while($value_dept 	= mysqli_fetch_array($sqllmsdept)) {
										echo '<option value="'.$value_dept['emply_id'].'">'.$value_dept['emply_name'].'</option>';
										}
										echo '
								</select>
							</div>
						</div>
					</div>
					<div id="getemployeedetail">
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label"> Full Name <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="adm_fullname" name="adm_fullname" required title="Must Be Required"/>
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label"> Email <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="adm_email" name="adm_email" required title="Must Be Required"/>
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label"> Phone </label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="adm_phone" name="adm_phone"/>
							</div>
						</div>
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label"> Username <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="adm_username" name="adm_username" required title="Must Be Required"/>
							</div>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label"> Password <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="adm_userpass" name="adm_userpass" required title="Must Be Required"/>
						</div>
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
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit_teacher" name="submit_teacher">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}
?>