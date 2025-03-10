<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '70', 'add' => '1'))){   
	if(isset($_POST['id_session'])){$id_session = $_POST['id_session'];}else{$id_session = '';}
	if(isset($_POST['id_class'])){$id_class = $_POST['id_class'];}else{$id_class = '';}
	if(isset($_POST['id_section'])){$id_section = $_POST['id_section'];}else{$id_section = '';}
	if(isset($_POST['id_increasing'])){$id_increasing = $_POST['id_increasing'];}else{$id_increasing = '';}
	$id_campus = (!empty($_POST['id_campus']) ? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Select </h2>
		</header>
		<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<div class="panel-body">';			
				if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
					echo'
					<div class="form-group mb-md">
						<label class="col-md-2 control-label">Sub Campus</label>
						<div class="col-md-9">
							<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus">
								<option value="">Select</option>';
								$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																	FROM ".CAMPUS." 
																	WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																	AND campus_status	= '1'
																	AND is_deleted		= '0'
																	ORDER BY campus_id ASC");
								while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
									echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $id_campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>';
				}
				echo'
				<div class="form-group">
					<label class="col-md-2 control-label">Session <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_session">
							<option value="">Select</option>';
							$sqllmsSes	= $dblms->querylms("SELECT session_id, session_name 
														FROM ".SESSIONS."
														WHERE session_id != '' AND is_deleted != '1'
														ORDER BY session_id DESC");
							while($valueSes = mysqli_fetch_array($sqllmsSes)) {
								echo '<option value="'.$valueSes['session_id'].'" '.($id_session == $valueSes['session_id'] ? 'selected' : '').'>'.$valueSes['session_name'].'</option>';
							}
							echo '
						</select>
					</div>
				</div>
				<div class="form-group">
				<label class="col-md-2 control-label">Class <span class="required">*</span></label>
					<div class="col-md-9">
					<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_class" onchange="get_feestruclasssection(this.value)">
						<option value="">Select</option>';
						$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
													FROM ".CLASSES."
													WHERE class_status = '1' AND is_deleted != '1'
													AND class_id IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
													ORDER BY class_id ASC");
						while($valuecls = mysqli_fetch_array($sqllmscls)) {
						echo '<option value="'.$valuecls['class_id'].'" '.($id_class == $valuecls['class_id'] ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
						}
					echo '
					</select>
				</div>
				</div>
				<div id="getfeestruclasssection">
					<div class="form-group">
						<label class="col-md-2 control-label">Section <span class="required">*</span></label>
							<div class="col-md-9">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_section">';
								if($id_class){
									$sqllmsSec	= $dblms->querylms("SELECT section_id, section_name 
																	FROM ".CLASS_SECTIONS."
																	WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
																	AND section_status 	 = 	'1' 
																	AND is_deleted 		!= 	'1' 
																	AND id_class 		 = 	'".$id_class."'
																	ORDER BY section_name ASC
																");
									while($valueSec = mysqli_fetch_array($sqllmsSec)) {
										echo '<option value="'.$valueSec['section_id'].'" '.($id_section == $valueSec['section_id'] ? 'selected' : '').'>'.$valueSec['section_name'].'</option>';
									}
								}else{
										echo '<option value="">Select</option>';
								}
								echo '
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Increasing <span class="required">*</span></label>
					<div class="col-md-9">
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_increasing">';
							echo '<option value="">Select</option>';
							foreach(get_increasing_type() as $key => $val):
								echo '<option value="'.$key.'" '.($key == $id_increasing ? 'selected' : '').'>'.$val.'</option>';
							endforeach;
							echo '
						</select>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">		
					<div class="col-md-12 text-center">
						<button type="submit" name="view_feesetup" id="view_feesetup" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
					</div>
				</div>
			</footer>
		</form>
	</section>';
	if(isset($_POST['view_feesetup'])){
		echo '
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fa fa-plus-square"></i> Make Class Fee Structure</h4>
			</header>
			<form action="feesetup.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" >
				<div class="panel-body">';
					$sqllmsfeesetup	= $dblms->querylms("SELECT f.id, f.status, f.dated, f.id_class, f.id_section, f.id_session, c.class_name, cs.section_name, s.session_name
														FROM ".FEESETUP." f						   
														INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
														INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section							 
														INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session	
														WHERE f.id_session  = '".$_POST['id_session']."'
														AND f.id_class	    = '".$_POST['id_class']."'
														AND f.id_section	= '".$_POST['id_section']."'
														AND f.id_campus 	= '".$id_campus."'
														ORDER BY f.dated ASC");
					$value_setup = mysqli_fetch_array($sqllmsfeesetup);
					if(mysqli_num_rows($sqllmsfeesetup) > 0){
						echo '
						<div class="form-group">
							<label class="col-md-2 control-label">Session <span class="required">*</span></label>
								<div class="col-md-9">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_session">
									<option value="">Select</option>';
									$sqllmscls	= $dblms->querylms("SELECT session_id, session_name 
																FROM ".SESSIONS." 
																WHERE session_id != '' AND is_deleted != '1'
																ORDER BY session_name ASC");
									while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['session_id'].'">'.$valuecls['session_name'].'</option>';
									}
								echo '
								</select>
								<input type="hidden" name="id_campus" id="id_campus" value="'.$id_campus.'">
								<input type="hidden" name="id_class" id="id_class" value="'.$id_class.'">
								<input type="hidden" name="id_section" id="id_section" value="'.$id_section.'">
								<input type="hidden" name="id_increasing" id="id_increasing" value="'.$id_increasing.'">
							</div>
							</div>
							<div class="form-group mt-sm">
								<label class="col-md-2 control-label">Increasing Date <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="dated" id="dated" autocomplete="off" data-plugin-datepicker required title="Must Be Required"/>
								</div>
							</div>
							<div class="form-group mt-sm">
								<label class="col-sm-2 control-label">Status <span class="required">*</span></label>
								<div class="col-md-9">
									<div class="radio-custom radio-inline">
										<input type="radio" id="status" name="status" value="1"'; if($value_setup['status'] == 1){ echo' checked';}echo'>
										<label for="radioExample1">Active</label>
									</div>
									<div class="radio-custom radio-inline">
										<input type="radio" id="status" name="status" value="2" '; if($value_setup['status'] == 2){ echo' checked';}echo'>
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
										<th class="text-center">Previous Amount</th>
										<th class="text-center">'.get_increasing_type($id_increasing).'</th>
										<th class="text-center">New Amount</th>
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

										$sqllmsfeedetail	= $dblms->querylms("SELECT fsd.id, fsd.duration, fsd.amount, fsd.type
																		FROM ".FEE_CATEGORY." c
																		INNER JOIN ".FEESETUPDETAIL." fsd ON fsd.id_cat = c.cat_id 													 
																		WHERE c.is_deleted != '1' 
																		AND fsd.id_setup = '".cleanvars($value_setup['id'])."' AND fsd.id_cat = '".$rowsvalues['cat_id']."'
																		LIMIT 1");
										$value_detail = mysqli_fetch_array($sqllmsfeedetail);
										echo '
										<input type="hidden" name="id_cat['.$srno.']" id="id_cat['.$srno.']" value="'.$rowsvalues['cat_id'].'">
										<tr>
											<td >'.$rowsvalues['cat_name'].'</td>
											<!--<td>
												<div class="form-group mt-sm">
													<div class="col-md-12">
														<select class="form-control" name="duration['.$srno.']" id="duration['.$srno.']" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" >
															<option vlaue="Yearly"'; if($value_detail['duration'] == 'Yearly'){echo' selected';} echo'>Yearly</option>
															<option vlaue="Half"'; if($value_detail['duration'] == 'Half'){echo' selected';} echo'>Half</option>
															<option vlaue="Quatar"'; if($value_detail['duration'] == 'Quatar'){echo' selected';} echo'>Quatar</option>
															<option vlaue="Monthly"'; if($value_detail['duration'] == 'Monthly'){echo' selected';} echo'>Monthly</option>
															<option vlaue="Once"'; if($value_detail['duration'] == 'Once'){echo' selected';} echo'>Once</option>
															<option vlaue="1st Term"'; if($value_detail['duration'] == '1st Term'){echo' selected';} echo'>1st Term</option>
															<option vlaue="2nd Term"'; if($value_detail['duration'] == '2nd Term'){echo' selected';} echo'>2nd Term</option>
															<option vlaue="Final Term"'; if($value_detail['duration'] == 'Final Term'){echo' selected';} echo'>Final Term</option>
														</select>
													</div>
												</div>
											</td>-->
											<td>
												<div class="form-group mt-sm">
													<div class="col-md-12">
														<input type="number" class="form-control" name="pre_amount['.$srno.']" id="pre_amount_'.$srno.'" value="'.$value_detail['amount'].'" required title="Must Be Required" readonly/>
													</div>
												</div>
											</td>
											<td>
												<div class="form-group mt-sm">
													<div class="col-md-12">
														<input type="number" class="form-control" name="percentage['.$srno.']" id="percentage_'.$srno.'" onkeyup="calculateAmount('.$srno.', '.$id_increasing.')" value="" title="Must Be Required"/>
													</div>
												</div>
											</td>
											<td>
												<div class="form-group mt-sm">
													<div class="col-md-12">
													<input type="number" class="form-control" name="amount['.$srno.']" id="amount_'.$srno.'" value="'.$value_detail['amount'].'" required title="Must Be Required" readonly/>
													</div>
												</div>
											</td>
											<td>
												<div class="form-group mt-sm">
													<div class="col-md-12">
														<select class="form-control" name="type['.$srno.']" id="type['.$srno.']" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" >
															<option vlaue="Refundable"'; if($value_detail['type'] == 'Refundable'){echo' selected';} echo'>Refundable</option>
															<option vlaue="Non-Refundable"'; if($value_detail['type'] == 'Non-Refundable'){echo' selected';} echo'>Non-Refundable</option>
														</select>
													</div>
												</div>
											</td>
										</tr>';
									}
									echo '
								</tbody>
							</table>';
					}else{
						echo '<h2 class="center">No Record Found</h2>';
					}
					echo '
				</div>';
				if(mysqli_num_rows($sqllmsfeesetup) > 0){
					echo '
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="submit_feesetup" name="submit_feesetup">Save</button>
								<button class="btn btn-default modal-dismiss">Cancel</button>
							</div>
						</div>
					</footer>';
				}
				echo '
			</form>
		</section>';
	}else{
		
	}
}
else{
	header("Location: feesetup.php");
}
?>
