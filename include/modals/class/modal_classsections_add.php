<?php 

if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('6', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '6', 'add' => '1'))) {
echo '
<!-- Add Modal Box -->
<div id="make_section" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="classsections.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Add Section</h2>
			</header>
			<div class="panel-body">
				<div class="form-group mt-sm">
					<label class="col-md-3 control-label">Section Name <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="section_name" id="section_name" required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Section Strength</label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="section_strength" id="section_strength">
					</div>
				</div>';
				if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
                    echo'
                    <div class="form-group mb-md">
                        <label class="col-md-3 control-label">Sub Campus</label>
						<div class="col-md-9">
							<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required" onchange="get_class(this.value)">
								<option value="">Select</option>';
								$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																FROM ".CAMPUS." 
																WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																AND campus_status	= '1'
																AND is_deleted		= '0'
																ORDER BY campus_id ASC");
								while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
									echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
								}
								echo'
							</select>
						</div>
                    </div>';
                }
				echo'				
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Class Name <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_class" name="id_class">
							<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
													FROM ".CLASSES."
													WHERE class_id != '' AND class_status = '1'
													AND class_id IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
													ORDER BY class_id ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
							echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
							}
						echo '
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="section_status" name="section_status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="section_status" name="section_status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_section" name="submit_section">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>