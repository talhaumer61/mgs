<?php	
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('55', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '55', 'view' => '1'))) {
	error_reporting();
	$month = $_POST['month'];
	$dept = $_POST['dept'];	
	$id_campus 		= ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])))? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
	$campus_flag 	= ((!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])))? 'col-md-4': 'col-md-6';
	echo'
	<title> Attendance Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Attendance Panel </h2>
		</header>
		<section class="panel panel-featured panel-featured-primary">
			<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title">
						<i class="fa fa-list"></i> <span class="hidden-xs">Employees Attendance	Report		
					</h2>
				</header>
				<div class="panel-body">
					<div class="row mb-lg">';
						if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
							echo'
							<div class="'.$campus_flag.'">
								<label class="control-label">Sub Campus</label>
								<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" onchange="get_dept(this.value);"> 
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
							<div class="input-group"> 
								<label class="control-label">
									Department <span class="required">*</span>
								</label>
								<select name="dept" id="dept" class="form-control"  data-plugin-selectTwo data-width="100%" required>
									<option value="">Select</option>';
									$sqllmscls	= $dblms->querylms("SELECT dept_id, dept_name 
																	FROM ".DEPARTMENTS."
																	WHERE id_campus = '".$id_campus."' 
																	ORDER BY dept_name ASC");
									while($valuecls = mysqli_fetch_array($sqllmscls)) {
										if($valuecls['dept_id'] == $dept) { 
											echo '<option value="'.$valuecls['dept_id'].'" selected>'.$valuecls['dept_name'].'</option>';
										} else { 
											echo '<option value="'.$valuecls['dept_id'].'">'.$valuecls['dept_name'].'</option>';
										}
									}
									echo '
								</select>
							</div>
						</div>
						<div class="'.$campus_flag.'">
							<div class="input-group"> 
								<label class="control-label">
									Month <span class="required">*</span>
								</label>
								<select name="month" class="form-control"  data-plugin-selectTwo data-width="100%" required>
								
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
					</div>
					<center>
						<button type="submit" class="btn btn-primary" id="view_attendance" name="view_attendance"><i class="fa fa-check-square-o"></i> View Attendance</button>
					</center>
				</div>
			</form>
		</section>';
		if(isset($_POST['view_attendance'])){
		echo'
			<section class="panel panel-featured panel-featured-primary appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
				<form action="attendance_employees.php" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="fa fa-bar-chart-o"></i> 
							Employees  Attendance Report Of <b>'.get_monthtypes($month).'</b> 
						</h2>
					</header>
					<div class="panel-body">
						<div class="table-responsive" id="printResult">
							<table class="table table-bordered table-striped table-condensed mb-none ">
								<thead>
									<tr>
										<th style="width:40px; text-align: center;">#</th>
										<th style="text-align: center;">Photo</th>
										<th> Employees <i class="fa fa-hand-o-down"></i> | Date <i class="fa fa-hand-o-right"></i>
										</th>';
										$year	=	date('Y');
										$month	=	$_POST['month'];
										$date 	= 	new DateTime("$year-$month-01");
										$days = $date->format('t');
										
										// $days =  cal_days_in_month(CAL_GREGORIAN, $_POST['month'], 2020);
										echo 'Days: '. $days;
											for($i = 1; $i<=$days; $i++) { 
												$datearray[] = $i;
											echo '<th style="text-align: center;">'.$i.'</th>';
										}
										echo'
									</tr>
								</thead>
								<tbody>';
									$sqllms	= $dblms->querylms("SELECT e.emply_id, e.emply_status, e.emply_photo, e.emply_name, e.id_designation, e.emply_email, e.id_campus, d.designation_id, d.designation_status, d.designation_name
																FROM ".EMPLOYEES." e
																INNER JOIN ".DESIGNATIONS." d ON d.designation_id = e.id_designation
																WHERE e.id_campus IN (".$id_campus.") 
																AND  e.id_dept = '".$dept."' AND e.emply_status = '1' ");
									$srno = 0;
									while($rowsvalues = mysqli_fetch_array($sqllms)) {  
										$srno++; 
										echo'
										<tr>
											<td style="width:40px; text-align: center;">'.$srno.'</td>
											<td class="center"><img src="uploads/images/employees/'.$rowsvalues['emply_photo'].'" width="35" height="35"></td>
											<td>
												<b>'.$rowsvalues['emply_name'].'</b>
												<span class="ml-sm label label-primary"> '.$rowsvalues['designation_name'].'</span>
											</td>';	
											foreach($datearray as $date) {
												$sqlatten	= $dblms->querylms("SELECT *
																				FROM ".EMPLOYEES_ATTENDCE." a
																				INNER JOIN ".EMPLOYEES_ATTENDCE_DETAIL." d ON d.id_setup = a.id
																				WHERE a.id_campus IN (".$id_campus.") 
																				AND a.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."' 
																				AND MONTH(a.dated) = '".$month."' AND DAY(a.dated) = '".$date."'
																				AND d.id_emply = '".$rowsvalues['emply_id']."'
																			");
												$rowsatten = mysqli_fetch_array($sqlatten);
												echo'<td style="text-align: center;"> '. get_attendtype($rowsatten['status']).' </td>';					
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
		}
		echo '
	</section>';
}
else{
	header("Location: dashboard.php");
}
 ?>
<script type="text/javascript">		
	function get_dept(id_campus) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_dept.php",  
			data: "id_campus="+id_campus,  
			success: function(msg){  
				$("#dept").html(msg); 
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