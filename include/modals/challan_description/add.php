<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))) { 
	echo'
	<div id="make_challan_description" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="challan_description.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Challan Description And Late Fee</h2>
				</header>
				<div class="panel-body">
					<!--
					<div class="form-group">
						<label class="col-md-3 control-label">Class <span class="required">*</span></label>
						<div class="col-md-9">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_class" name="id_class">
								<option value="">Select</option>';
									$sqllmsClass =	array( 
															'select' 	=>	'
																				class_id 
																				, class_name 
																			',
															'where' 	=> array( 
																						'is_deleted'    	=> '0'
																					, 'class_status'    	=> '1'
																				),
															'return_type' 	=> 'all' 
														); 
									$rowsClass  = $dblms->getRows(CLASSES, $sqllmsClass);
									foreach($rowsClass as $key => $val):
										echo '<option value="'.$val['class_id'].'">'.$val['class_name'].'</option>';
									endforeach;
									echo '
							</select>
						</div>
					</div>
					-->
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Late Fee Type <span class="required">*</span></label>
						<div class="col-md-9">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_latefeetype" name="id_latefeetype" onchange="get_latefee_type()">
								<option value="">Select</option>';
								foreach(get_latefee_type() as $key => $val):
									echo '<option value="'.$key.'">'.$val.'</option>';
								endforeach;
								echo'
							</select>
						</div>
					</div>

					<div id="late_fee"></div>
					
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Description <span class="required">*</span></label>
						<div class="col-md-9">
							<textarea data-plugin-summernote class="form-control summernote summernoteEx" required name="chl_desc" id="chl_desc"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
						<div class="col-md-9">
							<div class="radio-custom radio-inline">
								<input type="radio" id="chl_desc_status" name="chl_desc_status" value="1" checked>
								<label for="radioExample1">Active</label>
							</div>
							<div class="radio-custom radio-inline">
								<input type="radio" id="chl_desc_status" name="chl_desc_status" value="2">
								<label for="radioExample2">Inactive</label>
							</div>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="add_ChallanDes" name="add_ChallanDes">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}
?>