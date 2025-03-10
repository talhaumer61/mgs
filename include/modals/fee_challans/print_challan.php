<?php  
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))) { 
	echo'
	<div id="print_challan" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="feechallanprint.php" target="_blank" class="form-horizontal" id="form" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i> Print Monthly Challan</h2>
				</header>
				<div class="panel-body">';
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
						echo'
						<div class="form-group mb-md">
							<label class="col-md-2 control-label">Campus</label>
							<div class="col-md-9">
								<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus"> 
									<option value="">Select</option>';
									$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																		FROM ".CAMPUS." 
																		WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																		AND campus_status	= '1'
																		AND is_deleted		= '0'
																		ORDER BY campus_id ASC");
									while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
										echo '<option value="'.$valSubCampus['campus_id'].'">'.$valSubCampus['campus_name'].'</option>';
									}
									echo'
								</select>
							</div>
						</div>';
					endif;
					echo'
					<div class="form-group mb-md">
						<label class="col-md-2 control-label">Month <span class="required">*</span></label>
						<div class="col-md-9">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_month">
								<option value="">Select</option>';
								foreach($monthtypes as $month) {
								echo '<option value="'.$month['id'].'">'.$month['name'].'</option>';
								}
							echo '
							</select>
						</div>
					</div>		
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="submit" name="submit">Print</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}
?>