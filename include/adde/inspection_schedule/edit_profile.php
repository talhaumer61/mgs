<?php
$sqllms	= $dblms->querylms("SELECT c.campus_id, c.campus_status, c.campus_regno, c.govt_regno, c.bise_affiliation, c.established_date, 
								c.campus_name, c.campus_code, c.id_brand, c.id_group, c.campus_for, c.id_level, c.id_ad, c.id_de, c.is_hifiz, c.is_transport, 
								c.is_hostel, c.is_eveningclasses, c.id_city, c.id_permissions, c.id_printcopy, c.id_type, c.parent_campus, c.campus_address, c.campus_email,  c.campus_phone,
								c.campus_head, c.is_tvi, c.campus_website, c.campus_logo
								FROM ".CAMPUS." c  
								WHERE c.campus_id = '".cleanvars($_GET['id'])."' LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);
if($rowsvalues['campus_logo']) { 
	$photo = "uploads/images/campus/".$rowsvalues['campus_logo']." ";
}else{
	$photo = "uploads/logo.png";
}
// PERMISSIONS
$idCommaPer = explode( ',', $rowsvalues['id_permissions']);
$idCommaPrint = explode( ',', $rowsvalues['id_printcopy']);
echo'
<div id="edit" class="tab-pane active">
<form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
 <input type="hidden" name="campus_id" id="campus_id" value="'.cleanvars($_GET['id']).'">
	<fieldset class="mt-lg">
		<div class="form-group">
			<label class="col-sm-3 control-label">Photo</label>
			<div class="col-md-8">
				<div class="fileinput fileinput-new" data-provides="fileinput">
					<div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">
							<img src="'.$photo.'" class="rounded img-responsive">
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
		<div class="form-group">
			<label class="col-sm-3 control-label">Type <span class="required">*</span></label>
			<div class="col-md-8">
				<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_type_edit" name="id_type">
					<option>Select</option>';
					foreach (get_campus_type() as $key => $value) {									
						echo'<option value="'.$key.'" '.($rowsvalues['id_type'] == $key ? 'selected' : '').'>'.$value.'</option>';
					}
					echo '
				</select>
			</div>
		</div>
		<div id="parent_campuses">';
			if($rowsvalues['id_type'] == '2'){
				echo'
				<div class="form-group">
					<label class="col-sm-3 control-label">Parent Campus <span class="required">*</span></label>
					<div class="col-md-8">
						<select class="form-control" data-plugin-selectTwo data-width="100%" id="parent_campus" name="parent_campus" required>';
							$sqlParentCampus = $dblms->querylms("SELECT campus_id, campus_name, campus_code
																	FROM ".CAMPUS."
																	WHERE id_type       = '1'
																	AND campus_status   = '1'
																	AND is_deleted      = '0'
																	AND parent_campus   = '0'
																	AND campus_id      != '".cleanvars($_POST['campus_id'])."'
																	ORDER BY campus_id ASC");
							if(mysqli_num_rows($sqlParentCampus) > 0){
								echo'<option value="">Select</option>';
								while($valParentCampus = mysqli_fetch_array($sqlParentCampus)) {
									echo '<option value="'.$valParentCampus['campus_id'].'" '.($rowsvalues['parent_campus'] == $valParentCampus['campus_id'] ? 'selected' : '').'>'.$valParentCampus['campus_name'].' - '.$valParentCampus['campus_code'].'</option>';
								}
							}else{
								echo '<option value="">No Record Found</option>';
							}
							echo'
						</select>
					</div>
				</div>';
			}
			echo'
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Institution Code <span class="required">*</span></label>
			<div class="col-md-8">
				<input type="text" class="form-control" required name="campus_code" id="campus_code" value="'.$rowsvalues['campus_code'].'"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Name <span class="required">*</span></label>
			<div class="col-md-8">
				<input type="text" class="form-control" required name="campus_name" id="campus_name" value="'.$rowsvalues['campus_name'].'"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">Brand <span class="required">*</span></label>
			<div class="col-md-8">
				<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="brand">
					<option value="">Select</option>';
						$sqllmsbrand = $dblms->querylms("SELECT brand_id, brand_code, brand_name
											FROM ".BRANDS."
											WHERE brand_id != '' AND brand_status = '1'
											AND is_deleted != '1'
											ORDER BY brand_ordering ASC");
						while($valuebrand = mysqli_fetch_array($sqllmsbrand)) {
							if($valuebrand['brand_id'] == $rowsvalues['id_brand']){
								echo '<option value="'.$valuebrand['brand_code'].'|'.$valuebrand['brand_id'].'" selected>'.$valuebrand['brand_name'].'</option>';
							}
							else{
								echo '<option value="'.$valuebrand['brand_code'].'|'.$valuebrand['brand_id'].'">'.$valuebrand['brand_name'].'</option>';
							}
						}
				echo '
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">Group <span class="required">*</span></label>
			<div class="col-md-8">
				<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_group">
					<option value="">Select</option>';
						$sqllmscity	= $dblms->querylms("SELECT group_id, group_code, group_name
											FROM ".CAMPUS_GROUPS."
											WHERE group_id != '' AND group_status = '1'
											AND is_deleted != '1'
											ORDER BY group_ordering ASC");
						while($valuecity = mysqli_fetch_array($sqllmscity)) {
							if($valuecity['group_id'] == $rowsvalues['id_group']){
								echo'<option value="'.$valuecity['group_id'].'" selected>'.$valuecity['group_name'].'</option>';
							}else{
								echo'<option value="'.$valuecity['group_id'].'">'.$valuecity['group_name'].'</option>';
							}
						}
				echo '
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">Campus Level <span class="required">*</span></label>
			<div class="col-md-8">
				<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_level">
					<option value="">Select</option>';
						$sqllmslevel	= $dblms->querylms("SELECT level_id, level_name, level_code 
											FROM ".CAMPUS_LEVELS."
											WHERE level_id != '' AND level_status = '1'
											AND is_deleted != '1'
											ORDER BY level_ordering ASC");
						while($valuelevel = mysqli_fetch_array($sqllmslevel)) {
							if($valuelevel['level_id'] == $rowsvalues['id_level']){
								echo'<option value="'.$valuelevel['level_id'].'" selected>'.$valuelevel['level_name'].'</option>';
							}else{
								echo'<option value="'.$valuelevel['level_id'].'">'.$valuelevel['level_name'].'</option>';
							}
						}
				echo '
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">IMS Regno# <span class="required">*</span></label>
			<div class="col-md-8">
				<input type="text" class="form-control" required name="campus_regno" id="campus_regno" value="'.$rowsvalues['campus_regno'].'" readonly/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Govt. Regno# </label>
			<div class="col-md-8">
				<input type="text" class="form-control" required name="govt_regno" id="govt_regno" value="'.$rowsvalues['govt_regno'].'"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">BISE Affiliation <span class="required">*</span></label>
			<div class="col-md-8">
				<input type="text" class="form-control" required name="bise_affiliation" id="bise_affiliation" value="'.$rowsvalues['bise_affiliation'].'"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Etablished Date <span class="required">*</span></label> 
			<div class="col-md-8">
				<input type="text" class="form-control" name="established_date" value="'.date('m/d/Y', strtotime($rowsvalues['established_date'])).'" data-plugin-datepicker />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Eamil <span class="required">*</span></label>
			<div class="col-md-8">
				<input type="text" class="form-control" required name="campus_email" id="campus_email" value="'.$rowsvalues['campus_email'].'"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Phone <span class="required">*</span></label>
			<div class="col-md-8">
				<input type="text" class="form-control" required name="campus_phone" id="campus_phone" value="'.$rowsvalues['campus_phone'].'"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Principal <span class="required">*</span></label>
			<div class="col-md-8">
				<input type="text" class="form-control" required name="campus_head" id="campus_head" value="'.$rowsvalues['campus_head'].'"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Campus For <span class="required">*</span></label>
			<div class="col-md-8">
				<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="campus_for">
					<option value="">Select</option>';
					foreach($campusfor as $for) {
							echo '<option value="'.$for['id'].'"'; if($rowsvalues['campus_for'] == $for['id']){echo'selected';} echo'>'.$for['name'].'</option>';
						}
					echo '
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Hifiz <span class="required">*</span></label>
			<div class="col-md-8">
				<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="is_hifiz">
					<option value="">Select</option>';
					foreach($statusyesno as $stat) {
							echo '<option value="'.$stat['id'].'"'; if($rowsvalues['is_hifiz'] == $stat['id']){echo'selected';} echo'>'.$stat['name'].'</option>';
						}
					echo '
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Transport <span class="required">*</span></label>
			<div class="col-md-8">
				<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="is_transport">
					<option value="">Select</option>';
					foreach($statusyesno as $stat) {
							echo '<option value="'.$stat['id'].'"'; if($rowsvalues['is_transport'] == $stat['id']){echo'selected';} echo'>'.$stat['name'].'</option>';
						}
					echo '
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Hostel <span class="required">*</span></label>
			<div class="col-md-8">
				<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="is_hostel">
					<option value="">Select</option>';
					foreach($statusyesno as $stat) {
							echo '<option value="'.$stat['id'].'"'; if($rowsvalues['is_hostel'] == $stat['id']){echo'selected';} echo'>'.$stat['name'].'</option>';
						}
					echo '
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Evening Classes <span class="required">*</span></label>
			<div class="col-md-8">
				<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="is_eveningclasses">
					<option value="">Select</option>';
					foreach($statusyesno as $stat) {
							echo '<option value="'.$stat['id'].'"'; if($rowsvalues['is_eveningclasses'] == $stat['id']){echo'selected';} echo'>'.$stat['name'].'</option>';
						}
					echo '
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">TVI <span class="required">*</span></label>
			<div class="col-md-8">
				<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="tvi">
					<option value="">Select</option>';
					foreach($statusyesno as $stat) {
							echo '<option value="'.$stat['id'].'"'; if($rowsvalues['is_tvi'] == $stat['id']){echo'selected';} echo'>'.$stat['name'].'</option>';
						}
					echo '
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Website </label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="campus_website" id="campus_website" value="'.$rowsvalues['campus_website'].'"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label">Area </label>
			<div class="col-md-8">
				<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" name="city">
					<option value="">Select</option>';
						$sqllmscity	= $dblms->querylms("SELECT city_id, city_name, city_code, id_dist, id_zone, id_prov  
											FROM ".TEHSIL_CITIES."
											WHERE city_id != '' AND city_status = '1'
											AND is_deleted != '1'
											ORDER BY city_ordering ASC");
						while($valuecity = mysqli_fetch_array($sqllmscity)) {
							if($valuecity['city_id'] == $rowsvalues['id_city']){
								echo'<option value="'.$valuecity['city_code'].'|'.$valuecity['city_id'].'|'.$valuecity['id_dist'].'|'.$valuecity['id_zone'].'|'.$valuecity['id_prov'].'" selected>'.$valuecity['city_name'].'</option>';
							}else{
								echo'<option value="'.$valuecity['city_code'].'|'.$valuecity['city_id'].'|'.$valuecity['id_dist'].'|'.$valuecity['id_zone'].'|'.$valuecity['id_prov'].'">'.$valuecity['city_name'].'</option>';
							}
						}
				echo '
				</select>
			</div>
		</div>
		<div class="form-group mt-sm">
			<label class="col-md-3 control-label">Permissions <span class="required">*</span></label>
			<div class="col-md-8">
				<select class="form-control" data-plugin-selectTwo data-width="100%" multiple id="id_permissions" name="id_permissions[]">
					<option value="all">All</option>';
					$sql	= $dblms->querylms("SELECT role_id, role_name
												FROM ".ROLES."
												WHERE role_status = '1' 
												AND id_type = '2' OR id_type = '3' 
												ORDER BY role_name ASC");
					while($val = mysqli_fetch_array($sql)) {
						echo'<option class="opt_permission" value="'.$val['role_id'].'" '.(in_array($val['role_id'], $idCommaPer) ? 'selected': '').'>'.$val['role_name'].'</option>';
					}
					echo'
				</select>
			</div>
		</div>
		<div class="form-group mt-sm">
			<label class="col-md-3 control-label">Print Copy <span class="required">*</span></label>
			<div class="col-md-8">
				<select class="form-control" data-plugin-selectTwo required data-width="100%" multiple name="id_printcopy[]" id="id_printcopy">';
					foreach(get_PrintType() as $key => $val):
						echo '<option value="'.$key.'" '.((in_array($key,$idCommaPrint))? 'selected': '').'>'.$val.'</option>';
					endforeach;
					echo '
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Address <span class="required">*</span></label>
			<div class="col-md-8">
				<textarea name="campus_address" rows="2" class="form-control" value="" aria-required="true">'.$rowsvalues['campus_address'].'</textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
			<div class="col-md-9">
				<div class="radio-custom radio-inline">
					<input type="radio" id="campus_status" name="campus_status" value="1"'; if($rowsvalues['campus_status'] == 1) {echo'checked';} echo'>
					<label for="radioExample1">Active</label>
				</div>
				<div class="radio-custom radio-inline">
					<input type="radio" id="campus_status" name="campus_status" value="2"'; if($rowsvalues['campus_status'] == 2) {echo'checked';} echo'>
					<label for="radioExample2">Inactive</label>
				</div>		
			</div>
		</div>
	</fieldset>
	<div class="panel-footer">
		<div class="row text-center">
			<div class="col-sm-12">
				<button type="submit" name="changes_campus" id="changes_campus" class="btn btn-primary">Update Campus</button>
			</div>
		</div>
	</div>
</form>
</div>';
