<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'edit' => '1'))){ 
//---------------------------------------------------------
	$sqllms	= $dblms->querylms("SELECT *  
								   FROM ".CAMPUS_BIOGRAPHY." 
								   WHERE bio_id = '".cleanvars($_GET['bio_id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
//---------------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="#" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<input type="hidden" name="bio_id" id="bio_id" value="'.cleanvars($_GET['bio_id']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Campus Biography Detail </h2>
		</header>
		<div class="panel-body">

			<div class="form-group">
				<div class="col-md-6">
					<label class="control-label">Campus <span class="required">*</span></label>
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_campus">
						<option value="">Select</option>';
							$sqllmscamp = $dblms->querylms("SELECT campus_id, campus_name
												FROM ".CAMPUS."
												WHERE campus_id != '' AND campus_status = '1'
												AND is_deleted != '1'
												ORDER BY campus_name ASC");
							while($valuecamp = mysqli_fetch_array($sqllmscamp)) {
								if($valuecamp['campus_id'] == $rowsvalues['id_campus']){
									echo '<option value="'.$valuecamp['campus_id'].'" selected>'.$valuecamp['campus_name'].'</option>';
								}
								else{
									echo '<option value="'.$valuecamp['campus_id'].'">'.$valuecamp['campus_name'].'</option>';
								}
							}
					echo '
					</select>
				</div>
				<div class="col-md-6">
					<label class="control-label">ADE <span class="required">*</span></label>
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_ad">
						<option value="">Select</option>';
							$sqllmsad = $dblms->querylms("SELECT emply_id, emply_name
												FROM ".EMPLOYEES."
												WHERE emply_id != '' AND emply_status = '1'
												AND is_deleted != '1' AND is_ad = '1'
												AND id_campus = '0'
												ORDER BY emply_name ASC");
							while($valuead = mysqli_fetch_array($sqllmsad)) {
								if($valuead['emply_id'] == $rowsvalues['id_ad']){
									echo '<option value="'.$valuead['emply_id'].'" selected>'.$valuead['emply_name'].'</option>';
								}
								else{
									echo '<option value="'.$valuead['emply_id'].'">'.$valuead['emply_name'].'</option>';
								}
							}
					echo '
					</select>
				</div>
			</div>	
			<div class="form-group">
				<div class="col-md-6">
					<label class="control-label">DDE <span class="required">*</span></label>
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_de">
						<option value="">Select</option>';
							$sqllmsde = $dblms->querylms("SELECT emply_id, emply_name
												FROM ".EMPLOYEES."
												WHERE emply_id != '' AND emply_status = '1'
												AND is_deleted != '1' AND is_de = '1'
												AND id_campus = '0'
												ORDER BY emply_name ASC");
							while($valuede = mysqli_fetch_array($sqllmsde)) {
								if($valuede['emply_id'] == $rowsvalues['id_de']){
									echo '<option value="'.$valuede['emply_id'].'" selected>'.$valuede['emply_name'].'</option>';
								}
								else{
									echo '<option value="'.$valuede['emply_id'].'">'.$valuede['emply_name'].'</option>';
								}
							}
					echo '
					</select>
				</div>
				<div class="col-md-6">
					<label class="control-label">Building <span class="required">*</span></label>
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="building_type">
						<option value="">Select</option>';
							foreach($buildingtype as $building) {
								if($rowsvalues['building_type'] == $building['id']){
									echo '<option value="'.$building['id'].'" selected>'.$building['name'].'</option>';
								}
								else{
									echo '<option value="'.$building['id'].'">'.$building['name'].'</option>';
								}
							}
					echo '
					</select>
				</div>
			</div>	
			<div class="form-group mt-sm">
				<div class="col-md-6">
					<label class="control-label"> Building Area <span class="required">*</span></label>
					<input type="text" class="form-control" name="building_area" id="building_area" value="'.$rowsvalues['building_area'].'" required title="Must Be Required"/>
				</div>
				<div class="col-md-6">
					<label class="control-label"> Covered Area <span class="required">*</span></label>
					<input type="text" class="form-control" name="covered_area" id="covered_area" value="'.$rowsvalues['covered_area'].'" required title="Must Be Required"/>
				</div>
			</div>
			<div class="form-group mt-sm">
				<div class="col-md-4">
					<label class="control-label"> Total Rooms <span class="required">*</span></label>
					<input type="text" class="form-control" name="total_rooms" id="total_rooms" value="'.$rowsvalues['total_rooms'].'" required title="Must Be Required"/>
				</div>
				<div class="col-md-4">
					<label class="control-label"> Playgrounds <span class="required">*</span></label>
					<input type="text" class="form-control" name="play_grounds" id="play_grounds" value="'.$rowsvalues['play_grounds'].'" required title="Must Be Required"/>
				</div>
				<div class="col-md-4">
					<label class="control-label"> Washrooms <span class="required">*</span></label>
					<input type="text" class="form-control" name="washrooms" id="washrooms" value="'.$rowsvalues['washrooms'].'" required title="Must Be Required"/>
				</div>
			</div>
			<div class="form-group mt-sm">
				<div class="col-md-6">
					<label class="control-label"> Principal <span class="required">*</span></label>
					<input type="text" class="form-control" name="principal_name" id="principal_name" value="'.$rowsvalues['principal_name'].'" required title="Must Be Required"/>
				</div>
				<div class="col-md-6">
					<label class="control-label"> DOA <span class="required">*</span></label>
					<input type="text" class="form-control" data-plugin-datepicker value="'.date('m/d/Y', strtotime($rowsvalues['principal_doa'])).'" required title="Must Be Required" name="principal_doa" id="principal_doa" autocomplete="off"/>
				</div>
			</div>
			<div class="form-group mt-sm">
				<div class="col-md-6">
					<label class="control-label"> First Phone <span class="required">*</span></label>
					<input type="text" class="form-control" name="principal_phone" id="principal_phone" value="'.$rowsvalues['principal_phone'].'" required title="Must Be Required"/>
				</div>
				<label class="control-label"> Second Phone </label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="second_phone" id="second_phone"  value="'.$rowsvalues['second_phone'].'" />
				</div>
			</div>
			<div class="form-group mt-sm">
				<div class="col-md-6">
					<label class="control-label"> Whatsapp <span class="required">*</span></label>
					<input type="text" class="form-control" name="principal_whastapp" id="principal_whastapp" value="'.$rowsvalues['principal_whastapp'].'"  required title="Must Be Required"/>
				</div>
				<div class="col-md-6">
					<label class="control-label"> Eamil <span class="required">*</span></label>
					<input type="text" class="form-control" name="principal_email" id="principal_email" value="'.$rowsvalues['principal_email'].'" required title="Must Be Required"/>
				</div>
			</div>
			<div class="form-group mt-sm">
				<div class="col-md-6">
					<label class="control-label"> Education <span class="required">*</span></label>
					<input type="text" class="form-control" name="principal_edu" id="principal_edu" value="'.$rowsvalues['principal_edu'].'" required title="Must Be Required"/>
				</div>
				<div class="col-md-6">
					<label class="control-label"> Experience <span class="required">*</span></label>
					<input type="text" class="form-control" name="principal_experience" id="principal_experience" value="'.$rowsvalues['principal_experience'].'" required title="Must Be Required"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-6">
					<label class="control-label"> Bank-1 Name <span class="required">*</span></label>
					<input type="text" class="form-control" name="primary_bank" id="primary_bank" value="'.$rowsvalues['primary_bank'].'" required title="Must Be Required"/>
				</div>
				<div class="col-md-6">
					<label class="control-label"> Bank-1 Account <span class="required">*</span></label>
					<input type="text" class="form-control" name="primary_account" id="primary_account" value="'.$rowsvalues['primary_account'].'" required title="Must Be Required"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-6">
					<label class="control-label"> Bank-2 Name <span class="required">*</span></label>
					<input type="text" class="form-control" name="secondary_bank" id="secondary_bank" value="'.$rowsvalues['secondary_bank'].'" required title="Must Be Required"/>
				</div>
				<div class="col-md-6">
					<label class="control-label">  Bank-2 Account <span class="required">*</span></label>
					<input type="text" class="form-control" name="secondary_account" id="secondary_account" value="'.$rowsvalues['secondary_account'].'" required title="Must Be Required"/>
				</div>
			</div>
			<div class="form-group mb-md">
				<div class="col-md-6">
					<label class="control-label"> MEC President Name <span class="required">*</span></label>
					<input type="text" class="form-control" name="mec_president" id="mec_president" value="'.$rowsvalues['mec_president'].'" required title="Must Be Required"/>
				</div>
				<div class="col-md-6">
					<label class="control-label">MEC President Number <span class="required">*</span></label>
					<input type="text" class="form-control" name="mec_president_no" id="mec_president_no" value="'.$rowsvalues['mec_president_no'].'" required title="Must Be Required"/>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<!-- <button type="submit" class="btn btn-primary" id="changes_campus" name="changes_campus">Update</button> -->
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</form>
</section>
</div>
</div>';
}
?>