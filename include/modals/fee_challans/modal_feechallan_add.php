<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))) { 
	
	$today = date('m/d/Y');
	// if(isset($_POST['id_month'])){
	// 	$DueMonth = $_POST['id_month'];
	// 	$DueDate = date(''.$DueMonth.'/15/Y');
	// }else{
	// 	$DueDate = date('m/15/Y');
	// }
	echo'
	<div id="make_challan" class="zoom-anim-dialog modal-block modal-block-lg modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="fee_challans.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Single Fee Challan</h2>
				</header>
				<div class="panel-body">			
					<!-- <div class="form-group mt-sm">
						<div class="col-md-12">
							<label class="control-label">Student Reg No <span class="required">*</span></label>
							<input type="text" class="form-control" name="regno" id="regno" required title="Must Be Required" onchange="get_challan-detail(this.value)"/>
						</div>
					</div> -->

					<div class="form-group">';
						if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
							echo'
							<div class="col-md-12">
								<label class="control-label">Sub Campus</label>
								<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" onchange="get_CampusClass(this.value)"> 
									<option value="'.$id_campus.'">Select</option>';
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
							</div>';
						endif;
						echo'
						<div class="col-md-3">
							<label class="control-label">For Month <span class="required">*</span></label>
							<select data-plugin-selectTwo data-width="100%" name="id_month" id="id_month" required title="Must Be Required" class="form-control populate" onchange="get_duedate(this.value)">						
								<option value="">Select</option>';
								foreach($monthtypes as $month){
									echo'<option value="'.$month['id'].'">'.$month['name'].'</option>';
								}
								echo '
							</select>
						</div>	
						<div class="col-md-3">
							<label class="control-label">Class <span class="required">*</span></label>
							<select class="form-control id_Campusclass" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_class" name="id_class" onchange="get_section(this.value)">
								<option value="">Select</option>';
								$sqllmsclass = $dblms->querylms("SELECT class_id, class_name 
																		FROM ".CLASSES." 
																		WHERE class_status = '1' ORDER BY class_id ASC");
								while($value_class 	= mysqli_fetch_array($sqllmsclass)) {
									echo '<option value="'.$value_class['class_id'].'">'.$value_class['class_name'].'</option>';
								}
								echo '
							</select>
						</div>
						<div class="col-md-3">
							<label class="control-label">Section <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" onchange="get_sectionstudent(this.value)" id="id_section" name="id_section">
								<option value="">Select class first</option>
							</select>
						</div>
						<div class="col-md-3">
							<label class="control-label">Student <span class="required">*</span></label>
							<select class="form-control selectTwo" required title="Must Be Required" id="id_std" name="id_std" onchange="get_fatherdetail(this.value)">
								<option value="">Select Class First</option>
							</select>
						</div>
					</div>

					<div id="getchallandetail"></div>

					<div style="clear: both;"></div>
					
					<div class="form-group">
						<div class="col-md-6">
							<label class="control-label">Issue Date <span class="required">*</span></label>
							<input type="text" class="form-control" name="issue_date" id="issue_date" value="'.$today.'" required title="Must Be Required" readonly/>
						</div>
						<div class="col-md-6" id="getduedate">
							<label class="control-label">Due Date <span class="required">*</span></label>
							<input type="text" class="form-control" name="due_date" id="due_date" value="" data-plugin-datepicker required title="Must Be Required"/>
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-12">
							<label class="control-label">Note</label>
							<textarea class="form-control" rows="2" name="note" id="note"></textarea>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="one_challan_generate" name="one_challan_generate">Save</button>
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
	function get_sectionstudent(id_section) {
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		var id_class  = $("#id_class").val(); 
		var id_campus = $("#id_campus").val(); 
		console.log(id_class);
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_section-student.php",
			data: {
					 id_class		: id_class
					, id_section	: id_section
					, id_campus		: id_campus
				},
			success: function(msg){  
				console.log(msg);
				$("#id_std").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	function get_fatherdetail(id_std) { 
		var id_month = $("#id_month").val(); 
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_challan-detail.php",
			data: {
					 id_month: id_month
					,id_std:id_std
				},
			success: function(msg){  
				$("#getchallandetail").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	function get_CampusClass(id_campus) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_class.php",  
			data: "id_campus="+id_campus,  
			success: function(msg){  
				$(".id_Campusclass").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	jQuery(document).ready(function($) {
		$(".selectTwo").select2({
			dropdownParent: $("#make_challan"),
			minimumResultsForSearch: 0,
			width: "100%"
		});
	});
</script>