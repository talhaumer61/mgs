<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'add' => '1'))){
echo'
<!-- Add Modal Box -->
<div id="make_particular" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="royaltyParticulars.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" autocomplete="off" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Particular</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mt-sm">
					<label class="col-md-2 control-label"> Title <span class="required">*</span></label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="part_name" id="part_name" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-2 control-label"> Type <span class="required">*</span></label>
					<div class="col-md-10">
						<select data-plugin-selectTwo data-width="100%" name="part_type" id="part_type" required title="Must Be Required" class="form-control populate" onchange="get_royalty_type(this.value)">
							<option value="">Select</option>';
							foreach($rolyaltyType as $for){
								echo'<option  value="'.$for['id'].'">'.$for['name'].'</option>';
							}
							echo'
						</select>	
					</div>
				</div>
				
				<div id="getroyaltytype"></div>
				
				<div class="form-group mt-sm">
					<label class="col-md-2 control-label"> Months <span class="required">*</span></label>
					<div class="col-md-10">
						<select data-plugin-selectTwo data-width="100%" name="part_months[]" id="part_months" required title="Must Be Required" class="form-control populate" multiple>
							<option value="">Select</option>';
							foreach($monthtypes as $month){
								echo'<option  value="'.$month['id'].'">'.$month['name'].'</option>';
							}
							echo'
						</select>	
					</div>
				</div>				
				<div class="form-group mt-sm">
					<label class="col-md-2 control-label">Details</label>
					<div class="col-md-10">
						<textarea class="form-control" rows="2" name ="part_detail" id="part_detail"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Status <span class="required">*</span></label>
					<div class="col-md-10">
						<div class="radio-custom radio-inline">
							<input type="radio" id="part_status" name="part_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="part_status" name="part_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_particular" name="submit_particular">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>