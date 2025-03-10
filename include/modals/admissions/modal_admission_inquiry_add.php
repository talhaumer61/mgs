<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('49', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '49', 'add' => '1'))) {	
	$sqllmscampus  = $dblms->querylms("SELECT campus_code
										FROM ".CAMPUS." 
										WHERE campus_status = '1' AND campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
	$value_campus = mysqli_fetch_array($sqllmscampus);

	$sqllms	= $dblms->querylms("SELECT MAX(id) as form
									FROM ".ADMISSIONS_INQUIRY." 
									WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' ");
	$rowsvalues = mysqli_fetch_array($sqllms);
	$form_no = $rowsvalues['form'] + 1;
	echo'
	<div id="admission_inquiry" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="admission_inquiry.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Admission Inquiry</h2>
				</header>
				<div class="panel-body">
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Admission ID <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="form_no" id="form_no" value="'.$value_campus['campus_code'].'-'.$form_no.'" required title="Must Be Required" autocomplete="off">
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Name <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="name" id="name" required title="Must Be Required" autocomplete="off"/>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Father Name <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="fathername" id="fathername" required title="Must Be Required" autocomplete="off"/>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Gender <span class="required">*</span></label>
						<div class="col-md-9">
							<select id="gender" name="gender" data-plugin-selectTwo data-minimum-results-for-search="Infinity" data-width="100%" class="form-control populate" required title="Must Be Required">
								<option value="">Select</option>
								<option value="Male" >Male</option>
								<option value="Female" >Female</option>
							</select>
						</div>
					</div>								
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Cell No. <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="cell_no" id="cell_no" required title="Must Be Required" autocomplete="off"/>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Address <span class="required">*</span></label>
						<div class="col-md-9">
							<textarea type="text" class="form-control" name="address" id="address" required title="Must Be Required"></textarea>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Previous Class </label>
						<div class="col-md-9">
							<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_previousclass">
								<option value="">Select</option>';
									$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
																		FROM ".CLASSES."
																		WHERE class_status = '1' AND is_deleted != '1'
																		AND class_id IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
																		ORDER BY class_id ASC");
									while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
								}
							echo '
							</select>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Previous School </label>
						<div class="col-md-9">
							<input type="text" class="form-control" name="school" id="school" autocomplete="off"/>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Inquiry for Class <span class="required">*</span></label>
						<div class="col-md-9">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class">
								<option value="">Select</option>';
									$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
																		FROM ".CLASSES."
																		WHERE class_status = '1' AND is_deleted != '1'
																		AND class_id IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
																		ORDER BY class_id ASC");
									while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
								}
							echo '
							</select>
						</div>
					</div>';
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
						echo'
						<div class="form-group mt-sm">
							<label class="col-md-3 control-label">Sub Campus</label>
							<div class="col-md-9">
								<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required" onchange="get_class(this.value)">
									<option value="">Select</option>';
									$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																	FROM ".CAMPUS." 
																	WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																	AND campus_status	= '1'
																	AND is_deleted		= '0'
																	ORDER BY campus_id ASC");
									while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
										echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $_GET['id_campus'] ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>';
					}
					echo'
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Source <span class="required">*</span></label>
						<div class="col-md-9">
							<select id="source" name="source" data-plugin-selectTwo data-minimum-results-for-search="Infinity" data-width="100%" class="form-control populate" required title="Must Be Required">
								<option value="">Select</option>';
								foreach($inquirysrc as $source){
								echo '<option value="'.$source['id'].'">'.$source['name'].'</option>';
								}
								echo '
							</select>
						</div>
					</div>
					<div class="form-group mt-sm">
						<label class="col-md-3 control-label">Note </label>
						<div class="col-md-9">
							<textarea type="text" class="form-control" name="note" id="note" autocomplete="off"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="status" name="status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="status" name="status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit_inquiry" name="submit_inquiry">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}
?>
