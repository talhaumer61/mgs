<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))) {
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i> Latest Admissions</h2>
		</header>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-4" style="margin-bottom: 1rem;">
					<select class="form-control id_class"data-plugin-selectTwo data-width="100%" id="id_class" onchange="get_latestStudents();">
						<option value="">Select Class</option>';
						$sqlCampLevel = $dblms->querylms("SELECT GROUP_CONCAT(l.level_classes) campus_classes
															FROM ".CAMPUS." c
															LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
															WHERE campus_id IN (".$id_campus.") ");
						$valCampLevel = mysqli_fetch_array($sqlCampLevel);						


						$sqllmscls	= $dblms->querylms("SELECT class_id, class_name
															FROM ".CLASSES."
															WHERE is_deleted != '1'
															AND class_id != '' AND class_status = '1'
															AND class_id IN (".$valCampLevel['campus_classes'].")
															ORDER BY class_id ASC");
						while($valuecls = mysqli_fetch_array($sqllmscls)) {
						echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
						}
					echo '
					</select>
				</div>
				<div class="col-md-4" style="margin-bottom: 1rem;">
					<select class="form-control"data-plugin-selectTwo data-width="100%" id="id_section" onchange="get_latestStudents();">
						<option value="">Select Section</option>	
					</select>
				</div>
				<div class="col-md-4" style="margin-bottom: 1rem;">
					<select class="form-control"data-plugin-selectTwo data-width="100%" id="id_group" onchange="get_latestStudents();">
						<option value="">Select Group	</option>';
						$sqllms = array ( 
											'select' 		=> '
																	  group_id 
																	, group_code 
																	, group_name 
																',
											'where' 		=> array( 
																		'group_status'    	=> '1'
																	),
											'order_by' 		=> 'group_name ASC',
											'return_type' 	=> 'all' 
										); 
						foreach($dblms->getRows(GROUPS, $sqllms) as $key => $val):
							echo '<option value="'.$val['group_id'].'">'.$val['group_name'].'</option>';
						endforeach;
						echo '
					</select>
				</div>
			</div>
			<table id="AddLtsStd" class="table table-bordered table-striped table-condensed mb-none"></table>
		</div>
	</section>	
	<script>
		$(".id_class").change(function(){	
			var id_class	= $("#id_class").val();				
			var id_campus	= "'.$id_campus.'";
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_section.php",								  
				data: {
					 "id_class"		: id_class
					,"id_campus"	: id_campus
				},
				success: function(msg){  
					$("#id_section").html(msg);
				}
			});  
		});
		function get_latestStudents() {  
			var id_class 		= $("#id_class").val();
			var id_section 		= $("#id_section").val();
			var id_group 		= $("#id_group").val();			
			var id_campus 		= "'.$id_campus.'";
			if (id_class == "" && id_section == "" && id_group == "") {
				$.ajax({ 
					type: "POST",  
					url: "include/ajax/get_latestStudent.php",					  
					data: {
							"id_campus"		: id_campus
						},
					success: function(msg){
						$("#AddLtsStd").html(msg);
					}
				});  
			} else {
				$.ajax({  
					type: "POST",  
					url: "include/ajax/get_latestStudent.php",  
					data: {
								 "id_class" 		: id_class
								,"id_section" 		: id_section
								,"id_group" 		: id_group
								,"id_campus" 		: id_campus
							},
					success: function(msg){	
						$("#AddLtsStd").html(msg);
					}
				});  
			}
		}	
		get_latestStudents();
	</script>';
}
?>