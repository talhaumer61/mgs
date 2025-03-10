<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'add' => '1'))){ 
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form action="campuses.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
			<div class="panel-heading">
				<h4 class="panel-title"><i class="fa fa-plus-square"></i> Add Campus</h4>
			</div>
			<div class="panel-body">
				<label class="control-label">Photo</label>
				<div class="row">
					<div class="col-md-6">
						<div class="fileinput fileinput-new" data-provides="fileinput">
							<div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">
								<img src="uploads/logo.png" alt="...">
							</div>
							<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 130px"></div>
							<div>
								<span class="btn btn-xs btn-default btn-file">
									<span class="fileinput-new">Select image</span>
									<span class="fileinput-exists">Change</span>
									<input type="file" name="campus_logo" accept="image/*">
								</span>
								<a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
							</div>
						</div>
					</div>
				</div>
				<div class="row mt-sm">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Type <span class="required">*</span></label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_type" name="id_type" required>
								<option>Select</option>';
								foreach (get_campus_type() as $key => $value) {									
									echo'<option value="'.$key.'">'.$value.'</option>';
								}
								echo '
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group" id="parent_campuses">
						</div>
					</div>
				</div>
				<div class="row mt-sm">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Campus Name <span class="required">*</span></label>
							<input type="text" class="form-control" name="campus_name" id="campus_name" required title="Must Be Required"/>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Institution Code <span class="required">*</span></label>
							<input type="text" class="form-control" name="campus_code" id="campus_code" value="" required title="Must Be Required"/>
						</div>
					</div>
					<div class="col-md-4">
						<label class="control-label">Print Copy <span class="required">*</span></label>
						<select class="form-control" data-plugin-selectTwo required data-width="100%" multiple name="id_printcopy[]" id="id_printcopy">';
							foreach(get_PrintType() as $key => $val):
								echo '<option value="'.$key.'" selected>'.$val.'</option>';
							endforeach;
							echo '
						</select>
					</div>
				</div>
				<div class="row mt-sm">
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">Principle <span class="required">*</span></label>
							<input type="text" class="form-control" name="campus_head" id="campus_head" required title="Must Be Required"/>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label"> E-mail <span class="required">*</span></label>
							<input type="text" class="form-control" name="campus_email" id="campus_email" required title="Must Be Required"/>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">Phone <span class="required">*</span></label>
							<input type="text" class="form-control" name="campus_phone" id="campus_phone" required title="Must Be Required"/>
						</div>
					</div>
				</div>
				<div class="row mt-sm">
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label"> Govt Reg No# </label>
							<input type="text" class="form-control" name="govt_regno" id="govt_regno"/>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">BISE Affiliation <span class="required">*</span></label>
							<input type="text" class="form-control" name="bise_affiliation" id="bise_affiliation" required title="Must Be Required"/>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">Etablished Date <span class="required">*</span></label>
							<input type="text" class="form-control" data-plugin-datepicker required title="Must Be Required" name="established_date" id="established_date"/>
						</div>
					</div>
				</div>
				<div class="row mt-sm">
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Brand <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="brand">
								<option value="">Select</option>';
									$sqllmsbrand = $dblms->querylms("SELECT brand_id, brand_code, brand_name
														FROM ".BRANDS."
														WHERE brand_id != '' AND brand_status = '1'
														AND is_deleted != '1'
														ORDER BY brand_ordering ASC");
									while($valuebrand = mysqli_fetch_array($sqllmsbrand)) {
										echo '<option value="'.$valuebrand['brand_code'].'|'.$valuebrand['brand_id'].'">'.$valuebrand['brand_name'].'</option>';
									}
							echo '
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Group <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_group">
								<option value="">Select</option>';
									$sqllmsgroup = $dblms->querylms("SELECT group_id, group_code, group_name
														FROM ".CAMPUS_GROUPS."
														WHERE group_id != '' AND group_status = '1'
														AND is_deleted != '1'
														ORDER BY group_ordering ASC");
									while($valuegroup = mysqli_fetch_array($sqllmsgroup)) {
										echo '<option value="'.$valuegroup['group_id'].'">'.$valuegroup['group_name'].'</option>';
									}
							echo '
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Campus Level <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_level">
								<option value="">Select</option>';
									$sqllmslevel = $dblms->querylms("SELECT level_id, level_code, level_name
														FROM ".CAMPUS_LEVELS."
														WHERE level_id != '' AND level_status = '1'
														AND is_deleted != '1'
														ORDER BY level_ordering ASC");
									while($valuelevel = mysqli_fetch_array($sqllmslevel)) {
										echo '<option value="'.$valuelevel['level_id'].'">'.$valuelevel['level_name'].'</option>';
									}
							echo '
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Campus For <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="campus_for">
								<option value="">Select</option>';
									foreach($campusfor as $for) {
										echo '<option value="'.$for['id'].'">'.$for['name'].'</option>';
									}
								echo '
							</select>
						</div>
					</div>
				</div>
				<div class="row mt-sm">
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Hifiz <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="is_hifiz">
								<option value="">Select</option>';
								foreach($statusyesno as $stat) {
										echo '<option value="'.$stat['id'].'">'.$stat['name'].'</option>';
									}
								echo '
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Transport <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="is_transport">
								<option value="">Select</option>';
								foreach($statusyesno as $stat) {
										echo '<option value="'.$stat['id'].'">'.$stat['name'].'</option>';
									}
								echo '
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Hostel <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="is_hostel">
								<option value="">Select</option>';
								foreach($statusyesno as $stat) {
										echo '<option value="'.$stat['id'].'">'.$stat['name'].'</option>';
									}
								echo '
							</select>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Evening Classes <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="is_eveningclasses">
								<option value="">Select</option>';
								foreach($statusyesno as $stat) {
										echo '<option value="'.$stat['id'].'">'.$stat['name'].'</option>';
									}
								echo '
							</select>
						</div>
					</div>
				</div>
				<div class="row mt-sm">
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">TVI <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="tvi">
								<option value="">Select</option>';
								foreach($statusyesno as $stat) {
									echo '<option value="'.$stat['id'].'">'.$stat['name'].'</option>';
								}
								echo '
							</select>
					</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">Website </label>
							<input type="text" class="form-control" name="campus_website" placeholder="www.domain.com" id="campus_website"/>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label">Area <span class="required">*</span></label>
							<select class="form-control" name="city" title="Must Be Required" data-plugin-selectTwo data-width="100%" required>
								<option value="">Select</option>';
									$sqllmscity	= $dblms->querylms("SELECT city_id, city_name, city_code, id_dist, id_zone, id_prov  
														FROM ".TEHSIL_CITIES."
														WHERE city_id != '' AND city_status = '1'
														AND is_deleted != '1'
														ORDER BY city_ordering ASC");
									while($valuecity = mysqli_fetch_array($sqllmscity)) {
								echo'<option value="'.$valuecity['city_code'].'|'.$valuecity['city_id'].'|'.$valuecity['id_dist'].'|'.$valuecity['id_zone'].'|'.$valuecity['id_prov'].'">'.$valuecity['city_name'].'</option>';
								}
							echo '
							</select>
						</div>
					</div>
				</div>				
				<div class="row mt-sm">
					<div class="col-sm-12">
						<label class="control-label">Permissions <span class="required">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" multiple id="id_permissions" name="id_permissions[]">
							<option value="all">All</option>';
							$sql = $dblms->querylms("SELECT role_id, role_name
														FROM ".ROLES."
														WHERE role_status = '1' 
														AND id_type = '2' OR id_type = '3' 
														ORDER BY role_name ASC");
							while($val = mysqli_fetch_array($sql)) {
								echo'<option class="opt_permission" value="'.$val['role_id'].'">'.$val['role_name'].'</option>';
							}
							echo '
						</select>
					</div>
				</div>
				<div class="row mt-sm">
					<div class="col-sm-12">
						<label class="control-label">Address <span class="required">*</span></label>
						<textarea class="form-control" rows="3" name="campus_address" id="campus_address"></textarea>
					</div>
				</div>
				<div class="form-group mt-md mb-sm">
					<label class="col-sm-2 control-label">Status <span class="required">*</span></label>
					<div class="col-md-10">
						<div class="radio-custom radio-inline">
							<input type="radio" id="campus_status" name="campus_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="campus_status" name="campus_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_campus" name="submit_campus">Save</button>
						<button type="reset" class="btn btn-default">Reset</button>
					</div>
				</div>
			</footer>
		</form>
	</section>';
}else{
	header("Location: campuses.php");
}
?>