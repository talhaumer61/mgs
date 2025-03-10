<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'add' => '1'))){ 
echo '
<!-- Add Campus Box -->
<div id="make_bio" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="campus-biography.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i> Make Campus Biography</h2>
			</header>
			<div class="panel-body">
				<div class="form-group">
					<div class="col-md-6">
						<label class="control-label">Campus <span class="required">*</span></label>
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_campus">
							<option value="">Select</option>';
								$sqllmsbrand = $dblms->querylms("SELECT campus_id, campus_name
													FROM ".CAMPUS."
													WHERE campus_id != '' AND campus_status = '1'
													AND is_deleted != '1'
													ORDER BY campus_name ASC");
								while($valuebrand = mysqli_fetch_array($sqllmsbrand)) {
									echo '<option value="'.$valuebrand['campus_id'].'">'.$valuebrand['campus_name'].'</option>';
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
									echo '<option value="'.$valuead['emply_id'].'">'.$valuead['emply_name'].'</option>';
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
									echo '<option value="'.$valuede['emply_id'].'">'.$valuede['emply_name'].'</option>';
								}
						echo '
						</select>
					</div>
					<div class="col-md-6">
						<label class="control-label">Building <span class="required">*</span></label>
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="building_type">
							<option value="">Select</option>';
								foreach($buildingtype as $building) {
									echo '<option value="'.$building['id'].'">'.$building['name'].'</option>';
								}
						echo '
						</select>
					</div>
				</div>	
				<div class="form-group">
					<div class="col-md-6">
						<label class="control-label"> Building Area <span class="required">*</span></label>
						<input type="text" class="form-control" name="building_area" id="building_area" required title="Must Be Required"/>
					</div>
					<div class="col-md-6">
						<label class="control-label"> Covered Area <span class="required">*</span></label>
						<input type="text" class="form-control" name="covered_area" id="covered_area" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4">
						<label class="control-label"> Total Rooms <span class="required">*</span></label>
						<input type="text" class="form-control" name="total_rooms" id="total_rooms" required title="Must Be Required"/>
					</div>
					<div class="col-md-4">
					<label class="control-label"> Playgrounds <span class="required">*</span></label>
						<input type="text" class="form-control" name="play_grounds" id="play_grounds" required title="Must Be Required"/>
					</div>
					<div class="col-md-4">
						<label class="control-label"> Washrooms <span class="required">*</span></label>
						<input type="text" class="form-control" name="washrooms" id="washrooms" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<label class="control-label"> Principal <span class="required">*</span></label>
						<input type="text" class="form-control" name="principal_name" id="principal_name" required title="Must Be Required"/>
					</div>
					<div class="col-md-6">
						<label class="control-label"> DOA <span class="required">*</span></label>
						<input type="text" class="form-control" data-plugin-datepicker required title="Must Be Required" name="principal_doa" id="principal_doa" autocomplete="off"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<label class="control-label"> First Phone <span class="required">*</span></label>
						<input type="text" class="form-control" name="principal_phone" id="principal_phone" required title="Must Be Required"/>
					</div>
					<div class="col-md-6">
						<label class="control-label"> Second Phone </label>
						<input type="text" class="form-control" name="second_phone" id="second_phone" />
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<label class="control-label"> Whatsapp <span class="required">*</span></label>
						<input type="text" class="form-control" name="principal_whastapp" id="principal_whastapp" required title="Must Be Required"/>
					</div>
					<div class="col-md-6">
						<label class="control-label"> Eamil <span class="required">*</span></label>
						<input type="text" class="form-control" name="principal_email" id="principal_email" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<label class="control-label"> Education <span class="required">*</span></label>
						<input type="text" class="form-control" name="principal_edu" id="principal_edu" required title="Must Be Required"/>
					</div>
					<div class="col-md-6">
						<label class="control-label"> Experience <span class="required">*</span></label>
						<input type="text" class="form-control" name="principal_experience" id="principal_experience" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<label class="control-label"> Bank-1 Name <span class="required">*</span></label>
						<input type="text" class="form-control" name="primary_bank" id="primary_bank" required title="Must Be Required"/>
					</div>
					<div class="col-md-6">
						<label class="control-label"> Bank-1 Account <span class="required">*</span></label>
						<input type="text" class="form-control" name="primary_account" id="primary_account" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<label class="control-label"> Bank-2 Name <span class="required">*</span></label>
						<input type="text" class="form-control" name="secondary_bank" id="secondary_bank" required title="Must Be Required"/>
					</div>
					<div class="col-md-6">
						<label class="control-label">  Bank-2 Account <span class="required">*</span></label>
						<input type="text" class="form-control" name="secondary_account" id="secondary_account" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mb-md">
					<div class="col-md-6">
						<label class="control-label"> MEC President Name <span class="required">*</span></label>
						<input type="text" class="form-control" name="mec_president" id="mec_president" required title="Must Be Required"/>
					</div>
					<div class="col-md-6">
						<label class="control-label">MEC President Number <span class="required">*</span></label>
						<input type="text" class="form-control" name="mec_president_no" id="mec_president_no" required title="Must Be Required"/>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_bio" name="submit_bio">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>