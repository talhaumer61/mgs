<?php	
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('56', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '56', 'view' => '1'))) {
	if(!empty($_POST['id_class'])){
		$arraycls	= explode('|', $_POST['id_class']);	
		$class		= $arraycls[0];
	}else{
		$class		= '';
	}
	$section 		= (!empty($_POST['id_section']))	? cleanvars($_POST['id_section'])	: '';
	$month 			= (!empty($_POST['month']))			? cleanvars($_POST['month'])		: '';
	$id_campus 		= ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])))? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
	$campus_flag 	= ((!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])))? 'col-md-3': 'col-md-4';
	echo'
	<title> Attendance Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Attendance Panel </h2>
		</header>
		<section class="panel panel-featured panel-featured-primary">
			<form action="#" id="form" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title">
						<i class="fa fa-list"></i> <span class="hidden-xs">Students Attendance Report			
					</h2>
				</header>
				<div class="panel-body">
					<div class="row form-group">';
						if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
							echo'
							<div class="'.$campus_flag.'">
								<label class="control-label">Sub Campus</label>
								<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" onchange="get_class(this.value)"> 
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
							</div>';
						endif;
						echo'
						<div class="'.$campus_flag.'">
							<label class="control-label">Class <span class="required">*</span></label>
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value)">
								<option value="">Select</option>';
									$sqlCampLevel 	= $dblms->querylms("SELECT GROUP_CONCAT(l.level_classes) campus_classes
																		FROM ".CAMPUS." c
																		LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
																		WHERE campus_id IN (".$id_campus.") ");
									$valCampLevel 	= mysqli_fetch_array($sqlCampLevel);
									$sqllmscls		= $dblms->querylms("SELECT class_id, class_status, class_name 
																		FROM ".CLASSES."
																		WHERE class_status = '1'
																		AND class_id IN (".$valCampLevel['campus_classes'].")
																		ORDER BY class_id ASC");
									while($valuecls = mysqli_fetch_array($sqllmscls)) {
										if($valuecls['class_id'] == $class){
											echo '<option value="'.$valuecls['class_id'].'" selected>'.$valuecls['class_name'].'</option>';
										}else{
											echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
										}
								}
								echo '
							</select>
						</div>
						<div class="'.$campus_flag.'">
							<label class="control-label">Section <span class="required">*</span></label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section" required>
								<option value="">Select</option>';
								$sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
																FROM ".CLASS_SECTIONS."
																WHERE id_class	=	'".$class."'
																AND section_status  = '1'
                                    							AND is_deleted      = '0'
																AND id_campus IN (".$id_campus.")
																ORDER BY section_name ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									if($valuecls['section_id'] == $section){
										echo '<option value="'.$valuecls['section_id'].'" selected>'.$valuecls['section_name'].'</option>';
									} else{
										echo '<option value="'.$valuecls['section_id'].'">'.$valuecls['section_name'].'</option>';
									}
								}
								echo'
							</select>
						</div>
						<div class="'.$campus_flag.'">
							<label class="control-label">Month <span class="required">*</span></label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="month" name="month" required>
								<option>Select Month</option>';
									foreach($monthtypes as $listtype) 
									{ 
										if($month == $listtype['id']){
											echo '<option value="'.$listtype['id'].'" selected>'.$listtype['name'].'</option>';
										}else{
											echo '<option value="'.$listtype['id'].'">'.$listtype['name'].'</option>';
										}
										
									}
									echo'
							</select>
						</div>
					</div>
					<center>
						<button type="submit" class="btn btn-primary mt-md" id="view_attendance" name="view_attendance">
							<i class="fa fa-check-square-o"></i> View Attendance
						</button>
					</center>
				</div>
			</form>
		</section>';
		$sqllms	= $dblms->querylms("SELECT  s.std_id, s.std_status, s.std_name, s.id_class, s.id_section, s.id_session, s.std_rollno, s.std_regno, s.std_photo, s.id_campus 
										FROM ".STUDENTS." s
										WHERE s.id_campus = '".cleanvars($id_campus)."'  
										AND s.std_status  = '1'
										AND s.is_deleted  = '0'
										AND s.id_class	  = '".$class."' AND s.id_section  = '".$section."'
										AND s.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'");
		if(isset($_POST['view_attendance']) && mysqli_num_rows($sqllms) > 0){
			echo '
			<section class="panel panel-featured panel-featured-primary" >
				<form action="attendance_employees.php" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="fa fa-bar-chart-o"></i> 
							Students Attendance Report Of <b> '.get_monthtypes($month).' </b>
						</h2>
					</header>
					<div class="panel-body">
						<div class="table-responsive" id="printResult">
							<table class="table table-bordered table-striped table-condensed mb-none ">
								<thead>
									<tr>
										<th style="width:40px; text-align: center;">#</th>
										<th width="40">Photo</th>
										<th style="text-align: center;">
											Students <i class="fa fa-hand-o-down"></i> |
											Date <i class="fa fa-hand-o-right"></i>
										</th>';
										// Assuming $_POST['month'] contains a valid month value

										$month = isset($_POST['month']) ? $_POST['month'] : null;
										$year = date('Y'); // You can set the year dynamically if needed
										$firstDayOfMonth = new DateTime("$year-$month-01");
										$lastDayOfMonth = new DateTime("$year-$month-" . $firstDayOfMonth->format('t'));
										$days = $lastDayOfMonth->format('j');

										for($i = 1; $i<=$days; $i++) { 
											$datearray[] = $i;
											echo '<th style="text-align: center;">'.$i.'</th>';
										}
										echo'
									</tr>
								</thead>
								<tbody>';
									$srno = 0;
									while($rowsvalues = mysqli_fetch_array($sqllms)) { $srno++; echo'
										<tr>
											<td style="width:40px; text-align: center;">'.$srno.'</td>
											<td class="center"> <img src="uploads/images/students/'.$rowsvalues['std_photo'].'" width="35" height="35"</td>
											<td><b>'.$rowsvalues['std_name'].'</b></td>';
											foreach($datearray as $date) {
												$sqlatten	= $dblms->querylms("SELECT a.id, a.dated, a.id_class, a.id_section, a.id_session, a.id_campus,
																				d.id, d.id_setup, d.id_std, d.status 
																				FROM ".STUDENT_ATTENDANCE." a
																				INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." d ON d.id_setup = a.id
																				WHERE a.id_campus 	= '".cleanvars($id_campus)."'
																				AND   a.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
																				AND   a.id_class	= '".$class."' AND   a.id_section = '".$section."' 
																				AND MONTH(a.dated) = '".$month."' AND DAY(a.dated) = '".$date."'
																				AND   d.id_std 	= '".$rowsvalues['std_id']."'
																			");
												$rowsatten = mysqli_fetch_array($sqlatten);
												echo'<td style="text-align: center;"> '. get_attendtype($rowsatten['status']).'</td>';							
											}
											echo'
										</tr>';
									}
									echo'				
								</tbody>
							</table>
						</div>
					</div>
				</form>
			</section>
			<div class="text-right mt-lg on-screen">
				<button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary"><i class="glyphicon glyphicon-print"></i></button>
			</div>';
		} else {
			echo'
			<section class="panel panel-featured panel-featured-primary appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
				<header class="panel-heading">
					<center>
						<h1 class="text-danger">
							<b>No Record Found</b>
						</h1>
					</center>
				</header>
			</section>';
		}
		echo'
	</section>';
}else{
	header("Location: dashboard.php");
}
?>
<script type="text/javascript">		
	function get_section(id_class) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		var id_campus = $("#id_campus").val(); 
		$.ajax({  
			type: "POST", 
			url: "include/ajax/get_section.php",  
			data: {
					  'id_campus'   : id_campus
					, 'id_class' 	: id_class
				},
			success: function(msg){  
				$("#id_section").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	function get_class(id_campus) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_class.php",  
			data: "id_campus="+id_campus,  
			success: function(msg){  
				$("#id_class").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	function print_report(printResult) {
		var printContents = document.getElementById(printResult).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>