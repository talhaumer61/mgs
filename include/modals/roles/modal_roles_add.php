<?php 
echo '
<!-- Add Modal Box -->
<div id="make_hostel" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="roles.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Add Role</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Role Name <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="role_name" id="role_name" required title="Must Be Required"/>
					</div>
				</div>

				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Role Type <span class="required">*</span></label>
					<div class="col-md-9">
						<select data-plugin-selectTwo data-width="100%" name="role_type" id="role_type" required title="Must Be Required" class="form-control populate">
							<option value="">Select</option>';
							foreach($roletypes as $role){
								echo'<option value="'.$role['id'].'">'.$role['name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
			
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Role For <span class="required">*</span></label>
					<div class="col-md-9">
						<select data-plugin-selectTwo data-width="100%" class="form-control" name="id_type" id="id_type" required title="Must Be Required">
						<option value="">Select</option>';
							foreach($rolefor as $for){
								echo'<option value="'.$for['id'].'">'.$for['name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="role_status" name="role_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="role_status" name="role_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_roles" name="submit_roles">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';