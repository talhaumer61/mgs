<?php 
include "../../dbsetting/lms_vars_config.php";
include "../../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../../functions/login_func.php";
include "../../functions/functions.php";
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))) { 
	$sqllmsChallanDes = array ( 
									'select' 	=> '
														  chl_desc_id 
														, chl_desc_status 
														, late_fee_type
														, chl_desc 
														, id_class
													',
									'where' 	=> array( 
															  'is_deleted'    	=> '0'
															, 'chl_desc_id'    	=> cleanvars($_GET['id'])
														),
									'return_type' 	=> 'single' 
								); 	
	$rowsChallanDes  			= $dblms->getRows(CHALLAN_DESCRIPTION, $sqllmsChallanDes);
	$late_fee_type_array 		= explode(',', $rowsChallanDes['late_fee_type']);
	echo '
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<form action="challan_description.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<input type="hidden" name="chl_desc_id" id="id" value="'.cleanvars($_GET['id']).'">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Challan Description And Late Fee</h2>
					</header>
					<div class="panel-body">
						<!--
						<div class="form-group">
							<label class="col-md-3 control-label">Class <span class="required">*</span></label>
							<div class="col-md-9">
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_class" name="id_class">
									<option value="">Select</option>';
										$sqllmsClass = array ( 
																'select' 	=> '
																					  class_id 
																					, class_name 
																				',
																'where' 	=> array( 
																						  'is_deleted'    		=> '0'
																						, 'class_status'    	=> '1'
																					),
																'return_type' 	=> 'all' 
															); 
										$rowsClass  = $dblms->getRows(CLASSES, $sqllmsClass);
										foreach($rowsClass as $key => $val):
											echo '<option value="'.$val['class_id'].'" '.(($val['class_id'] == $rowsChallanDes['id_class'])? 'selected': '').'>'.$val['class_name'].'</option>';
										endforeach;
										echo '
								</select>
							</div>
						</div>
						-->
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Late Fee Type <span class="required">*</span></label>
							<div class="col-md-9">
								<select class="form-control id_latefeetype" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_latefeetype" name="id_latefeetype" onchange="get_latefee_type()">
									<option value="">Select</option>';
									foreach(get_latefee_type() as $key => $val):
										echo '<option value="'.$key.'" '.(($key == $late_fee_type_array[0])? 'selected': '').'>'.$val.'</option>';
									endforeach;
									echo'
								</select>
							</div>
						</div>						
						<div id="late_fee">';
						if($late_fee_type_array[0] == '1'){
							echo'
							<div class="form-group mb-md">
								<label class="col-md-3 control-label">Late Fee <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="late_fee_type[]" value="'.$late_fee_type_array[1].'" required placeholder="0.00">
								</div>
							</div>';
						}elseif($late_fee_type_array[0] == '2'){
							echo'
							<div class="form-group mb-md">
								<label class="col-md-3 control-label">Fee within 5 days <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="late_fee_type[]" value="'.$late_fee_type_array[1].'" required placeholder="0.00">
								</div>
							</div>
							<div class="form-group mb-md">
								<label class="col-md-3 control-label">Fee After 5 days <span class="required">*</span></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="late_fee_type[]" value="'.$late_fee_type_array[2].'" required placeholder="0.00">
								</div>
							</div>';
						}
						echo'
						</div>
						<div class="form-group mb-md" style="display: none;" id="late_fee1">
							<label class="col-md-3 control-label">Late Fee <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="late_fee_type[]" id="latefee_fixed" value="'.((count($late_fee_type_array) == 2)? ((!empty($late_fee_type_array[1])) ? $late_fee_type_array[1]: ''): '').'" placeholder="0.00">
							</div>
						</div>
						<div class="form-group mb-md" style="display: none;" id="late_fee2">
							<label class="col-md-3 control-label">Fee within 5 days <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="late_fee_type[]" id="latefee_slot1" value="'.((count($late_fee_type_array) == 3)? ((!empty($late_fee_type_array[1])) ? $late_fee_type_array[1]: ''): '').'" placeholder="0.00">
							</div>
						</div>
						<div class="form-group mb-md" style="display: none;" id="late_fee3">
							<label class="col-md-3 control-label">Fee After 5 days <span class="required">*</span></label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="late_fee_type[]" id="latefee_slot2" value="'.((!empty($late_fee_type_array[2])) ? $late_fee_type_array[2]: '').'" placeholder="0.00">
							</div>
						</div>
						<div class="form-group mb-md">
							<label class="col-md-3 control-label">Description <span class="required">*</span></label>
							<div class="col-md-9">
								<textarea data-plugin-summernote class="form-control summernote summernoteEx" required name="chl_desc" id="chl_desc">'.html_entity_decode(html_entity_decode($rowsChallanDes['chl_desc'])).'</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
							<div class="col-md-9">
								<div class="radio-custom radio-inline">
									<input type="radio" id="chl_desc_status" name="chl_desc_status" value="1"'; if($rowsChallanDes['chl_desc_status'] == 1) {echo'checked';} echo'>
									<label for="radioExample1">Active</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" id="chl_desc_status" name="chl_desc_status" value="2"'; if($rowsChallanDes['chl_desc_status'] == 2){echo'checked';} echo'>
									<label for="radioExample2">Inactive</label>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" class="btn btn-primary" id="update_ChallanDes" name="update_ChallanDes">Update</button>
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