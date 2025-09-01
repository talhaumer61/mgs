<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('70', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '70', 'add' => '1'))) {
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h4 class="panel-title"><i class="fa fa-plus-square"></i> Make Class Fee Structure</h4>
		</header>
		<form action="feesetup.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" >
			<div class="panel-body">';
				if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
					echo'					
					<div class="form-group">
						<label class="col-md-2 control-label">Sub Campus</label>
						<div class="col-md-9">
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" onchange="get_class(this.value)"> 
								<option value="">Select</option>';
								$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																	FROM ".CAMPUS." 
																	WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																	AND campus_status	= '1'
																	AND is_deleted		= '0'
																	ORDER BY campus_id ASC");
								while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
									echo '<option value="'.$valSubCampus['campus_id'].'" '.(($valSubCampus['campus_id'] == $id_campus) ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>';
				endif;
				echo'
				<div class="form-group">
					<label class="col-md-2 control-label">Session <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_session" id="id_session">
							<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT session_id, session_name 
														FROM ".SESSIONS."
														WHERE session_id != '' AND is_deleted != '1'
														ORDER BY session_id DESC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
							echo '<option value="'.$valuecls['session_id'].'">'.$valuecls['session_name'].'</option>';
							}
						echo '
						</select>
					</div>
				</div>
				<div class="form-group mt-sm">
					<label class="col-md-2 control-label">Dated <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" name="dated" id="dated" autocomplete="off" data-plugin-datepicker required title="Must Be Required"/>
					</div>
				</div>
				<div class="form-group">
				<label class="col-md-2 control-label">Class <span class="required">*</span></label>
					<div class="col-md-9">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" onchange="get_section(this.value)">
						<option value="">Select</option>';
						$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
													FROM ".CLASSES."
													WHERE class_status = '1' AND is_deleted != '1'
													AND class_id IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
													ORDER BY class_id ASC");
						while($valuecls = mysqli_fetch_array($sqllmscls)) {
							echo'<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
						}
						echo'
					</select>
				</div>
				</div>
				<div id="getfeestruclasssection">
				<div class="form-group">
					<label class="col-md-2 control-label">Section <span class="required">*</span></label>
						<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_section" id="id_section">
							<option value="">Select Class First</option>
						</select>
					</div>
				</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Status <span class="required">*</span></label>
					<div class="col-md-9">
						<div class="radio-custom radio-inline">
							<input type="radio" id="status" name="status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="status" name="status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>				
				<br>
				<table class="table table-hover table-striped table-condensed mb-none">
					<thead>
						<tr>
							<th class="text-center">Category</th>
							<!--<th class="text-center">Duration</th>-->
							<th class="text-center">Amount</th>
							<th class="text-center">Type</th>
						</tr>
					</thead>
					<tbody>';
						$sqllms	= $dblms->querylms("SELECT c.cat_id, c.cat_name
															FROM ".FEE_CATEGORY." c												 
															WHERE c.cat_status = '1' AND is_deleted != '1'
															ORDER BY c.cat_name ASC");
						$srno = 0;
						while($rowsvalues = mysqli_fetch_array($sqllms)) {
							$srno++;
							echo '
							<input type="hidden" name="id_cat['.$srno.']" id="id_cat['.$srno.']" value="'.$rowsvalues['cat_id'].'">
							<tr>
								<td>'.$rowsvalues['cat_name'].'</td>
								<!--<td>
									<div class="form-group mt-sm">
										<div class="col-md-12">
											<select class="form-control" name="duration['.$srno.']" id="duration['.$srno.']" required title="Must Be Required" data-plugin-selectTwo data-width="100%" >
												<option vlaue="">Select</option>
												<option vlaue="Yearly">Yearly</option>
												<option vlaue="Half">Half</option>
												<option vlaue="Quatar">Quatar</option>
												<option vlaue="Monthly">Monthly</option>
												<option vlaue="Once">Once</option>
												<option vlaue="1st Term">1st Term</option>
												<option vlaue="2nd Term">2nd Term</option>
												<option vlaue="Final Term">Final Term</option>
											</select>
										</div>
									</div>
								</td>-->
								<td>
									<div class="form-group mt-sm">
										<div class="col-md-12">
											<input type="number" class="form-control" name="amount['.$srno.']" id="amount['.$srno.']" required title="Must Be Required" value="0"/>
										</div>
									</div>
								</td>
								<td>
									<div class="form-group mt-sm">
										<div class="col-md-12">
											<select class="form-control" name="type['.$srno.']" id="type['.$srno.']" required title="Must Be Required" data-plugin-selectTwo data-width="100%" >
											<option vlaue="">Select</option>
											<option vlaue="Refundable">Refundable</option>
											<option vlaue="Non-Refundable">Non-Refundable</option>
											</select>
										</div>
									</div>
								</td>
							</tr>';
  						}
  						echo'
					</tbody>
			  	</table>  
			</div>		  
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_feesetup" name="submit_feesetup">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>';
}else{
	header("Location: feesetup.php");
}
?>
