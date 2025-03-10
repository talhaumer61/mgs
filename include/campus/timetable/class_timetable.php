<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('9', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'view' => '1'))) {

$id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
$id_campus = '';

if(isset($_POST['id_section'])){$id_section = $_POST['id_section'];}else{$id_section = "";}
	
if(isset($_POST['id_campus']) && !empty($_POST['id_campus'])){
	$id_campus = $_POST['id_campus'];
}
if(!empty($_POST['id_class'])){
	$array		= explode("|",$_POST['id_class']);
	$id_class	= $array[0];
}
echo'
<section class="panel panel-featured panel-featured-primary">
	<form action="#" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<div class="panel-body">';
			if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
				echo'
				<div class="col-md-4">
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
			<div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-4' : 'col-md-6').'">
				<label class="control-label">Class <span class="required">*</span></label>
				<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value)">
					<option value="">Select</option>';
					$sqlLevelClasses    = $dblms->querylms("SELECT  l.level_classes
															FROM ".CAMPUS." c
															LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
															WHERE campus_id = '".cleanvars($id_campus)."' LIMIT 1");
					$valLevelClasses    = mysqli_fetch_array($sqlLevelClasses);

					$sqllmsclass	= $dblms->querylms("SELECT class_id, class_name
														FROM ".CLASSES."
														WHERE class_id != '' AND class_status = '1'
														AND class_id IN (".$valLevelClasses['level_classes'].")
														ORDER BY class_id ASC");
					while($value_class 	= mysqli_fetch_array($sqllmsclass)) {
						echo'<option value="'.$value_class['class_id'].'" '.($value_class['class_id'] == $id_class ? 'selected' : '').'>'.$value_class['class_name'].'</option>';
					}
					echo '
				</select>
			</div>
			<div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-4' : 'col-md-6').'">
				<div class="form-group">
					<label class="control-label">Section <span class="required">*</span></label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section" required>';
						$sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
														FROM ".CLASS_SECTIONS."
														WHERE id_campus     = '".$id_campus."'
														AND id_class        = '".$id_class."'
														AND section_status  = '1'
														AND is_deleted      = '0'
														ORDER BY section_name ASC");
						if(mysqli_num_rows($sqllmscls) > 0){
							echo'<option value="">Select</option>';
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['section_id'].'" '.($valuecls['section_id'] == $id_section ? 'selected' : '').'>'.$valuecls['section_name'].'</option>';
							}
						}else{
							echo '<option value="">No Record Found</option>';
						}
						echo'
					</select>
				</div>
			</div>
		</div>	
		<div class="row mt-md">
			<div class="col-md-12 text-center">
				<button type="submit" id="view_timetable" name="view_timetable" class="mr-xs btn btn-primary">Get Details</button>
			</div>
		</div>	
		</div>
	</form>';

	// VIEW TIMETABLE DETAILS
	if(isset($_POST['view_timetable'])){
		echo'<section class="panel panel-featured panel-featured-primary appear-animation mt-sm" data-appear-animation="fadeInRight" data-appear-animation-delay="100">';
		$sqllms	= $dblms->querylms("SELECT t.id, t.status, t.id_session, t.id_class, t.id_section, t.id_campus,
									c.class_id, c.class_status, c.class_name, cm.campus_name,
									se.section_id, se.section_status, se.section_name
									FROM ".TIMETABLE." t
									INNER JOIN ".CAMPUS." cm ON cm.campus_id = t.id_campus
									INNER JOIN ".CLASSES." c ON	c.class_id = t.id_class
									INNER JOIN ".CLASS_SECTIONS." se ON se.section_id = t.id_section
									WHERE t.id_class	= '".$id_class."'
									AND t.id_section	= '".$id_section."'
									AND t.id_session	= '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
									AND t.id_campus		= '".cleanvars($id_campus)."'
									LIMIT 1");
		$rowsvalues = mysqli_fetch_array($sqllms);
		if(mysqli_num_rows($sqllms) > 0){
		echo '
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-clock-o"></i>
				Daily Class Routiene of <b>'.$rowsvalues['class_name'].'</b> (<b> '.$rowsvalues['section_name'].' </b>)			
				<button onclick="print_report(\'printResult\')" class="mr-xs btn-xs pull-right btn btn-primary"><i class="glyphicon glyphicon-print"></i> Print</button>
			</h2>
		</header>
		<div class="panel-body">
			<div id="printResult">
				<div class="invoice mt-md">
					<div id="header" style="display:none;">
						<h2 style="text-align: center;">
							<img src="'.$_SESSION['userlogininfo']['LOGINCAMPUSLOGO'].'" class="img-fluid" style="width: 60px;"> 
							<span><b>'.$rowsvalues['campus_name'].'</b></span>
						</h2>
						<h4 style="text-align: center;"><b>Time Table</b></h4>
						<br>
						<div>
							<h5 class="mb-md">
								'.(($id_class && $id_section) ? '<b>Class: </b>'.$rowsvalues['class_name'].' ('.$rowsvalues['section_name'].')' : '').'
								<span class="pull-right">'.($rowsvalues['id_session'] ? '<b>Session: </b>'.$_SESSION['userlogininfo']['ACA_SESSION_NAME'] : '').'</span>
							</h5>
						</div>
					</div>
					<div class="table-responsive mt-sm mb-md">
						<table class="table table-bordered table-striped table-condensed mb-none" id="my_table">
							<tbody>
								<tr>
									<th>Duration</th>
									<th>Time</th>
									<th>Period</th>';
									$sqlDays	= $dblms->querylms("SELECT td.day
																	FROM ".TIMETABEL_DETAIL." td
																	INNER JOIN ".TIMETABLE." t ON t.id = td.id_setup AND td.id_setup = '".$rowsvalues['id']."'
																	GROUP BY td.day");
									$days = array();
									while ($valDays = mysqli_fetch_array($sqlDays)) {
										$days[] = $valDays;
										echo'<th>'.get_daytypes($valDays['day']).'</th>';
									}
									echo'
									<th>Friday Time</th>
									<th>Friday Duration</th>
								</tr>';
									$sqlPeriods	= $dblms->querylms("SELECT p.*
																	FROM ".PERIODS." p
																	WHERE p.is_deleted	= '0'
																	AND p.period_status	= '1'
																	AND p.id_campus		= '".cleanvars($id_campus)."'
																	");
									while ($valPeriods = mysqli_fetch_array($sqlPeriods)) {

										// DURATION PERIOD
										if(!empty($valPeriods['period_timestart'])){									
											$start_time = $valPeriods['period_timestart'];
											$end_time = $valPeriods['period_timeend'];
											$start_datetime = DateTime::createFromFormat('h:i A', $start_time);
											$end_datetime = DateTime::createFromFormat('h:i A', $end_time);
											$interval = $end_datetime->diff($start_datetime);
											$minutes = ($interval->h * 60) + $interval->i;
										}else{
											$minutes = '0';
										}

										// DURATION PERIOD FRIDAY
										if(!empty($valPeriods['period_timestart_friday'])){
											$start_time_friday = $valPeriods['period_timestart_friday'];
											$end_time_friday = $valPeriods['period_timeend_friday'];
											$start_datetime_friday = DateTime::createFromFormat('h:i A', $start_time_friday);
											$end_datetime_friday = DateTime::createFromFormat('h:i A', $end_time_friday);
											$interval_friday = $end_datetime_friday->diff($start_datetime_friday);
											$minutes_friday = ($interval_friday->h * 60) + $interval_friday->i;
										}else{
											$minutes_friday = '0';
										}

										echo'
										<tr>
											<td>'.$minutes.' Min</td>
											<td>'.$valPeriods['period_timestart'].'-'.$valPeriods['period_timeend'].'</td>
											<td>'.$valPeriods['period_name'].'</td>';
											foreach ($days as $period_day) {
												$sqlDetail	= $dblms->querylms("SELECT td.*, s.subject_name
																					FROM ".TIMETABEL_DETAIL." td
																					LEFT JOIN ".CLASS_SUBJECTS." s ON s.subject_id = td.id_subject
																					WHERE td.id_setup	= '".$rowsvalues['id']."'
																					AND td.id_period	= '".$valPeriods['period_id']."'
																					AND td.day			= '".$period_day['day']."'
																				");
												if(mysqli_num_rows($sqlDetail) > 0){
													$valDetail = mysqli_fetch_array($sqlDetail);
													echo'<td>'.($valDetail['id_subject'] == '99998' ? 'Break' : $valDetail['subject_name']).'</td>';
												}else{											
													echo'<td></td>';
												}
											}
											echo'
											<td>'.$valPeriods['period_timestart_friday'].'-'.$valPeriods['period_timeend_friday'].'</td>
											<td>'.$minutes_friday.' Min</td>
										</tr>';
									}
									echo'
								</tr>			
							</tbody>
						</table>';
						// echo'
						// <table class="table table-bordered table-striped table-condensed  mb-none" id="my_table">
						// 	<tbody>
						// 		<tr>
						// 			<th>
						// 				Days <i class="fa fa-hand-o-down"></i> |
						// 				Periods <i class="fa fa-hand-o-right"></i>
						// 			</th>';
						// 			$sqllmssub	= $dblms->querylms("SELECT period_id, period_name, period_timestart, period_timeend
						// 											FROM ".PERIODS."
						// 											WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' ");
						// 			$periods = array();
						// 			while($rowsub = mysqli_fetch_array($sqllmssub)){ 
						// 				$periods[] = $rowsub;
						// 				echo'<th style="text-align: center;">'.$rowsub['period_name'].'</th>';
						// 			}
						// 			echo'
						// 		</tr>
						// 		<tr>';
						// 			for($i=1; $i<=7; $i++){
						// 			echo'<td>'. get_daytypes($i).'</td>';
						// 			foreach($periods as $itemperiod) {  
						// 				$day = get_daytypes($i);
						// 				$loop = $i;

						// 				$sqllmsdetail	= $dblms->querylms("SELECT d.id, d.day, d.id_subject, d.id_room, d.id_period, d.id_teacher, d.id_setup,
						// 													t.id, t.status, t.id_session, t.id_class, t.id_section, t.id_campus,
						// 													c.class_id, c.class_status, c.class_name,
						// 													se.section_id, se.section_status, se.section_name, 
						// 													s.subject_id, s.subject_status, s.subject_name,
						// 													r.room_id, r.room_status, r.room_no, r.room_capacity,
						// 													e.emply_id, e.emply_status, e.emply_name, e.id_type
						// 													FROM ".TIMETABEL_DETAIL." 	 d 
						// 													INNER JOIN ".TIMETABLE."  	 t 	ON 	t.id 			= d.id_setup
						// 													INNER JOIN ".CLASSES."  	 	 c 	ON 	c.class_id 		= t.id_class
						// 													INNER JOIN ".CLASS_SECTIONS." se	ON 	se.section_id 	= t.id_section
						// 													INNER JOIN ".CLASS_SUBJECTS." s 	ON 	s.subject_id 	= d.id_subject
						// 													INNER JOIN ".CLASS_ROOMS."    r 	ON 	r.room_id 		= d.id_room
						// 													INNER JOIN ".EMPLOYEES." 	 e 	ON 	e.emply_id 		= d.id_teacher
						// 													AND t.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."' 
						// 													AND t.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
						// 													AND d.id_period = '".$itemperiod['period_id']."'AND d.day = ".$i."
						// 													AND d.id_setup = '".$rowsvalues['id']."' LIMIT 1");
						// 				$rowsdetail = mysqli_fetch_array($sqllmsdetail); 
						// 				echo '					
						// 				<td style="text-align:center;">
						// 					<div class="btn-group">';
						// 						if(mysqli_num_rows($sqllmsdetail) > 0){
						// 							echo'
						// 							<button class="mb-xs mt-xs mr-xs btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
						// 								'.$rowsdetail['subject_name'].'
						// 								('.$itemperiod['period_timestart'].' - '.$itemperiod['period_timeend'].')
						// 								<br>Teacher : '.$rowsdetail['emply_name'].'
						// 								<br>Class Room : '.$rowsdetail['room_no'].'
						// 								<br>Class : '.$rowsdetail['class_name'].'
						// 								<br>Section : '.$rowsdetail['section_name'].'
						// 								<br>Day : '.get_daytypes($rowsdetail['day']).'
						// 							</button>
						// 							<!-- <ul class="dropdown-menu">
						// 								<li>
						// 									<a href="timetable/class_update/4">
						// 										<i class="glyphicon glyphicon-edit"></i>
						// 										Edit
						// 									</a>
						// 								</li>
						// 								<li>
						// 									<a href="#" onclick="confirm_modal("timetable/maintain/delete/4");">
						// 										<i class="el el-trash"></i> Delete										</a>
						// 								</li>
						// 							</ul> -->';
						// 						}
						// 						echo'
						// 					</div>
						// 				</td>';
						// 			}
						// 			echo'</tr>';
						// 		}
						// 		echo'			
						// 	</tbody>
						// </table>';
						echo'
					</div>
				</div>
			</div>
		</div>
		<!-- <div class="panel-footer">
			<div class="text-right">
				<a href="timetable-documents_print.php" class="btn btn-sm btn-primary " target="include/marks/marks_sheetprint.php">
					<i class="glyphicon glyphicon-print"></i> Print
				</a>
			</div>
		</div> --> ';
	}else{
		echo'
		<div class="panel-body">
			<h2 class="text-center text-danger">No Result Found!</h2>
		</div>';
	}
	echo'
</div>';
}
echo '
</section>';
//MAin Section
echo '
</section>';
}
else{
	header("location: dashboard.php");
}
?>
<script>
	function print_report(printResult) {
		document.getElementById('header').style.display = 'block';
		var printContents = document.getElementById(printResult).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		var css = `@media print {
									@page {
										size: landscape;
										margin: 0;
									}
	
								}
				`,
		head = document.head || document.getElementsByTagName('head')[0],
		style = document.createElement('style');
		style.type = 'text/css';
		style.media = 'print';
		if (style.styleSheet){
		style.styleSheet.cssText = css;
		} else {
		style.appendChild(document.createTextNode(css));
		}
		head.appendChild(style);
		window.print();
		document.body.innerHTML = originalContents;
		document.getElementById('header').style.display = 'none';
	}
</script>