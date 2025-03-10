<?php 
if($_SESSION['userlogininfo']['LOGINAFOR'] == 1){
echo'
<div id="add" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="campuslogin.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i> Make Campus Login</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Campus <span class="required">*</span></label>
					<div class="col-md-9">
						<select name="id_campus" id="id_campus" required class="form-control selectTwo" onchange="get_campusdetail(this.value)">
							<option value="">Select</option>';
							$sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
															FROM ".CAMPUS." c  
															WHERE c.campus_id != '' AND campus_status = '1'
															ORDER BY c.campus_name ASC");
							while($value_camp 	= mysqli_fetch_array($sqllmscampus)) {
								echo'<option value="'.$value_camp['campus_id'].'">'.$value_camp['campus_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div id="getcampusdetail">
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
						<button type="submit" class="btn btn-primary" id="submit_campuslogin" name="submit_campuslogin">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".selectTwo").select2({
			dropdownParent: $("#add"),
			minimumResultsForSearch: 0,
			width: "100%"
		});
	});
</script>