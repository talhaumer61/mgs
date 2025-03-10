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
	$sqllms	= $dblms->querylms("SELECT c.campus_id, c.campus_status, c.campus_regno, c.campus_name, c.id_brand, c.id_city, c.campus_address, c.campus_email, c.campus_phone, c.campus_head, c.campus_fax, c.campus_website, c.campus_logo
								   FROM ".CAMPUS." c 
								   WHERE c.campus_id = '".cleanvars($_GET['campus_id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
//---------------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="#" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<input type="hidden" name="campus_id" id="campus_id" value="'.cleanvars($_GET['campus_id']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Campus </h2>
		</header>

		<div class="panel-body">

		
			<div class="form-group mt-xl">
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-6">
						<div class="fileinput fileinput-new" data-provides="fileinput">
							<div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">';
								if($rowsvalues['campus_logo']) { 
								echo '
									<img src="uploads/images/campus/'.$rowsvalues['campus_logo'].'" class="rounded img-responsive">' ;
								} else {
									echo '<img src="uploads/logo.png" class="rounded img-responsive">';
								}
									echo'
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
					<div class="col-md-2"></div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"> Registration # <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="campus_regno" id="campus_regno" value="'.$rowsvalues['campus_regno'].'" required title="Must Be Required" readonly />
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"> Name <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="campus_name" id="campus_name" value="'.$rowsvalues['campus_name'].'" required title="Must Be Required" />
				</div>
			</div>

			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Brand <span class="required">*</span></label>
				<div class="col-md-9">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="brand">
						<option value="">Select</option>';
							$sqllmsbrand = $dblms->querylms("SELECT brand_id, brand_code, brand_name
												FROM ".BRANDS."
												WHERE brand_id != '' AND brand_status = '1'
												AND is_deleted != '1'
												ORDER BY brand_ordering ASC");
							while($valuebrand = mysqli_fetch_array($sqllmsbrand)) {
								if($rowsvalues['id_brand'] == $valuebrand['brand_id']){
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

			<div class="form-group mb-md">
				<label class="col-md-3 control-label">City <span class="required">*</span></label>
				<div class="col-md-9">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="city">
						<option value="">Select</option>';
							$sqllmscity	= $dblms->querylms("SELECT city_id, city_name, city_code, id_dist, id_zone, id_prov  
												FROM ".TEHSIL_CITIES."
												WHERE city_id != '' AND city_status = '1'
												AND is_deleted != '1'
												ORDER BY city_ordering ASC");
							while($valuecity = mysqli_fetch_array($sqllmscity)) {
								if($rowsvalues['id_city'] == $valuecity['city_id']){
									echo'<option value="'.$valuecity['city_code'].'|'.$valuecity['city_id'].'|'.$valuecity['id_dist'].'|'.$valuecity['id_zone'].'|'.$valuecity['id_prov '].'" selected>'.$valuecity['city_name'].'</option>';
								}
								else{
									echo'<option value="'.$valuecity['city_code'].'|'.$valuecity['city_id'].'|'.$valuecity['id_dist'].'|'.$valuecity['id_zone'].'|'.$valuecity['id_prov '].'">'.$valuecity['city_name'].'</option>';
								}
							}
					echo '
					</select>
				</div>
			</div>	

			<div class="form-group">
				<label class="col-md-3 control-label"> Address <span class="required">*</span></label>
				<div class="col-md-9">
					<textarea class="form-control" rows="3" name= "campus_address" id="campus_address" required title="Must Be Required">'.$rowsvalues['campus_address'].'</textarea>
				</div>
			</div>

			<div class="form-group mt-sm">
				<label class="col-md-3 control-label"> Campus Head <span class="required">*</span></label>
			<div class="col-md-9">
				<input type="text" class="form-control" name="campus_head" id="campus_head"  value="'.$rowsvalues['campus_head'].'" required title="Must Be Required"/>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-3 control-label"> E-mail <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="campus_email" id="campus_email" value="'.$rowsvalues['campus_email'].'" required title="Must Be Required" />
				</div>
			</div>


			<div class="form-group">
				<label class="col-md-3 control-label"> phone <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="campus_phone" id="campus_phone" value="'.$rowsvalues['campus_phone'].'" required title="Must Be Required" />
				</div>
			</div>


			<div class="form-group">
				<label class="col-md-3 control-label"> Fax</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="campus_fax" id="campus_fax" value="'.$rowsvalues['campus_fax'].'"/>
				</div>
			</div>


			<div class="form-group">
				<label class="col-md-3 control-label"> Website</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="campus_website" id="campus_website" value="'.$rowsvalues['campus_website'].'"/>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label"> Status <span class="required">*</span></label>
				<div class="col-md-9">';
					if($rowsvalues['campus_status'] == 1) { 
						echo '
							<div class="radio-custom radio-inline">
								<input type="radio" id="campus_status" name="campus_status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>';
					} else { 
						echo '
							<div class="radio-custom radio-inline">
								<input type="radio" id="campus_status" name="campus_status" value="1">
								<label for="radioExample1">Active</label>
							</div>';
					}
					if($rowsvalues['campus_status'] == 2) { 
						echo '
							<div class="radio-custom radio-inline">
								<input type="radio" id="campus_status" name="campus_status" checked value="2">
								<label for="radioExample2">Inactive</label>
							</div>';
					} else { 
						echo '
							<div class="radio-custom radio-inline">
								<input type="radio" id="campus_status" name="campus_status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>';
					}
			echo '
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="changes_campus" name="changes_campus">Update</button>
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