<?php
$sqllms	= $dblms->querylms("SELECT c.campus_id, c.campus_status, c.campus_regno, c.govt_regno, c.bise_affiliation, c.established_date, 
								   c.campus_name, c.id_brand, c.id_group, c.campus_for, c.id_level, c.id_ad, c.id_de, c.is_hifiz, c.is_transport, 
								   c.is_hostel, c.is_eveningclasses, c.id_city, c.campus_address, c.campus_email,  c.campus_phone,
								   c.campus_head, c.is_tvi, c.campus_website, c.campus_logo, c.controller_exam_sign
								   FROM ".CAMPUS." c  
								   WHERE c.campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);
if($rowsvalues['campus_logo']) { 
	$photo = "uploads/images/campus/".$rowsvalues['campus_logo']." ";
}else{
	$photo = $_SESSION['userlogininfo']['LOGINCAMPUSLOGO'];
}
echo'
<div id="edit" class="tab-pane active">
	<form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="campus_id" id="campus_id" value="'.cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']).'">
		<fieldset class="mt-lg">
			<div class="form-group col-sm-6">
				<label class="col-sm-6 control-label">Photo</label>
				<div class="col-md-6">
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
			<div class="form-group col-sm-6">
				<label class="col-sm-6 control-label">Controller Exam Sign</label>
				<div class="col-md-6">
					<div class="fileinput fileinput-new" data-provides="fileinput">
						<div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">
							<img src="uploads/images/controller_exam_sign/'.$rowsvalues['controller_exam_sign'].'" class="rounded img-responsive">
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 130px"></div>
						<div>
							<span class="btn btn-xs btn-default btn-file">
								<span class="fileinput-new">Select image</span>
								<span class="fileinput-exists">Change</span>
								<input type="file" name="controller_exam_sign" accept="image/*">
							</span>
							<a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Name <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="campus_name" id="campus_name" value="'.$rowsvalues['campus_name'].'" disabled/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Brand <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="brand" disabled>
						<option value="">Select</option>';
						$sqllmsbrand = $dblms->querylms("SELECT brand_id, brand_code, brand_name
															FROM ".BRANDS."
															WHERE brand_id != '' AND brand_status = '1'
															AND is_deleted != '1'
															ORDER BY brand_ordering ASC");
						while($valuebrand = mysqli_fetch_array($sqllmsbrand)){							
							echo '<option value="'.$valuebrand['brand_code'].'|'.$valuebrand['brand_id'].'" '.($valuebrand['brand_id'] == $rowsvalues['id_brand'] ? 'selected' : '').'>'.$valuebrand['brand_name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Group <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_group" disabled>
						<option value="">Select</option>';
							$sqllmscity	= $dblms->querylms("SELECT group_id, group_code, group_name
															FROM ".CAMPUS_GROUPS."
															WHERE group_id != '' AND group_status = '1'
															AND is_deleted != '1'
															ORDER BY group_ordering ASC");
							while($valuecity = mysqli_fetch_array($sqllmscity)) {							
								echo'<option value="'.$valuecity['group_id'].'" '.($valuecity['group_id'] == $rowsvalues['id_group'] ? 'selected' : '').'>'.$valuecity['group_name'].'</option>';
							}
					echo '
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Campus Level <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_level" disabled>
						<option value="">Select</option>';
						$sqllmslevel = $dblms->querylms("SELECT level_id, level_name, level_code 
															FROM ".CAMPUS_LEVELS."
															WHERE level_id != '' AND level_status = '1'
															AND is_deleted != '1'
															ORDER BY level_ordering ASC");
						while($valuelevel = mysqli_fetch_array($sqllmslevel)){
							echo'<option value="'.$valuelevel['level_id'].'" '.($valuelevel['level_id'] == $rowsvalues['id_level'] ? 'selected' : '').'>'.$valuelevel['level_name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">MES Regno# <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="campus_regno" id="campus_regno" value="'.$rowsvalues['campus_regno'].'" readonly/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Govt. Regno# </label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="govt_regno" id="govt_regno" value="'.$rowsvalues['govt_regno'].'" disabled/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">BISE Affiliation <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="bise_affiliation" id="bise_affiliation" value="'.$rowsvalues['bise_affiliation'].'" disabled/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Etablished Date <span class="required">*</span></label> 
				<div class="col-md-8">
					<input type="text" class="form-control" name="established_date" value="'.date('m/d/Y', strtotime($rowsvalues['established_date'])).'" data-plugin-datepicker disabled/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Eamil <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="campus_email" id="campus_email" value="'.$rowsvalues['campus_email'].'" disabled/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Phone <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="campus_phone" id="campus_phone" value="'.$rowsvalues['campus_phone'].'" disabled/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Principal <span class="required">*</span></label>
				<div class="col-md-8">
					<input type="text" class="form-control" required name="campus_head" id="campus_head" value="'.$rowsvalues['campus_head'].'" disabled/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Campus For <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="campus_for" disabled>
						<option value="">Select</option>';
						foreach($campusfor as $for) {
							echo '<option value="'.$for['id'].'" '.($rowsvalues['campus_for'] == $for['id'] ? 'selected' : '').'>'.$for['name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Hifiz <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="is_hifiz" disabled>
						<option value="">Select</option>';
						foreach($statusyesno as $stat) {
							echo '<option value="'.$stat['id'].'" '.($rowsvalues['is_hifiz'] == $stat['id'] ? 'selected' : '').'>'.$stat['name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Transport <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="is_transport" disabled>
						<option value="">Select</option>';
						foreach($statusyesno as $stat) {
							echo'<option value="'.$stat['id'].'" '.($rowsvalues['is_transport'] == $stat['id'] ? 'selected' : '').'>'.$stat['name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Hostel <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="is_hostel" disabled>
						<option value="">Select</option>';
						foreach($statusyesno as $stat) {
							echo'<option value="'.$stat['id'].'" '.($rowsvalues['is_hostel'] == $stat['id'] ? 'selected' : '').'>'.$stat['name'].'</option>';
						}
						echo '
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Evening Classes <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="is_eveningclasses" disabled>
						<option value="">Select</option>';
						foreach($statusyesno as $stat) {
							echo'<option value="'.$stat['id'].'" '.($rowsvalues['is_eveningclasses'] == $stat['id'] ?'selected' : '').'>'.$stat['name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">TVI <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="tvi" disabled>
						<option value="">Select</option>';
						foreach($statusyesno as $stat) {
							echo'<option value="'.$stat['id'].'" '.($rowsvalues['is_tvi'] == $stat['id'] ? 'selected' : '').'>'.$stat['name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Website </label>
				<div class="col-md-8">
					<input type="text" class="form-control" name="campus_website" id="campus_website" value="'.$rowsvalues['campus_website'].'" disabled/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">City <span class="required">*</span></label>
				<div class="col-md-8">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="city" disabled>
						<option value="">Select</option>';
						$sqllmscity	= $dblms->querylms("SELECT city_id, city_name, city_code, id_dist, id_zone, id_prov  
											FROM ".TEHSIL_CITIES."
											WHERE city_id != '' AND city_status = '1'
											AND is_deleted != '1'
											ORDER BY city_ordering ASC");
						while($valuecity = mysqli_fetch_array($sqllmscity)){
							echo'<option value="'.$valuecity['city_code'].'|'.$valuecity['city_id'].'|'.$valuecity['id_dist'].'|'.$valuecity['id_zone'].'|'.$valuecity['id_prov'].'" '.($valuecity['city_id'] == $rowsvalues['id_city'] ? 'selected' : '').'>'.$valuecity['city_name'].'</option>';
						}
						echo'
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Address <span class="required">*</span></label>
				<div class="col-md-8">
					<textarea name="campus_address" rows="2" class="form-control" value="" aria-required="true" disabled>'.$rowsvalues['campus_address'].'</textarea>
				</div>
			</div>
		</fieldset>
		<div class="panel-footer">
			<div class="row text-center">
				<div class="col-sm-12">
					<button type="submit" name="changes_campus" id="changes_campus" class="btn btn-primary"><i class="fa fa-refresh"></i> Update Campus</button>
				</div>
			</div>
		</div>
	</form>
</div>';
