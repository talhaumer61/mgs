<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'add' => '1'))){ 
echo '
<!-- Add Campus Box -->
<div id="make_campus" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="campuses.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i> Make Campus </h2>
			</header>

			<div class="panel-body">

				<div class="form-group mt-xl">
					<div class="row">
						<div class="col-md-4"></div>
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
						<div class="col-md-2"></div>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> Name <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="campus_name" id="campus_name" required title="Must Be Required"/>
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
									echo '<option value="'.$valuebrand['brand_code'].'|'.$valuebrand['brand_id'].'">'.$valuebrand['brand_name'].'</option>';
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
							echo'<option value="'.$valuecity['city_code'].'|'.$valuecity['city_id'].'|'.$valuecity['id_dist'].'|'.$valuecity['id_zone'].'|'.$valuecity['id_prov '].'">'.$valuecity['city_name'].'</option>';
							}
						echo '
						</select>
					</div>
				</div>	

				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> Address <span class="required">*</span></label>
					<div class="col-md-9">
						<textarea class="form-control" rows="3" name= "campus_address" id="campus_address"></textarea>
					</div>
				</div>

				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> Campus Head <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="campus_head" id="campus_head" required title="Must Be Required"/>
					</div>
				</div>

				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> E-mail <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="campus_email" id="campus_email" required title="Must Be Required"/>
					</div>
				</div>

				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> Phone <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="campus_phone" id="campus_phone" required title="Must Be Required"/>
					</div>
				</div>

				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> Fax </label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="campus_fax" id="campus_fax"/>
					</div>
				</div>

				<div class="form-group mt-sm">
					<label class="col-md-3 control-label"> Website </label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="campus_website" id="campus_website"/>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label"> Status <span class="required">*</span></label>
					<div class="col-md-9">
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
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>