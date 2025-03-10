<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'view' => '1'))){
	if(isset($_POST['id_campus'])){$id_campus = $_POST['id_campus'];}
	if(isset($_POST['id_class'])){$class = $_POST['id_class'];}
	if(isset($_POST['id_section'])){$section = $_POST['id_section'];}
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i> Time Table</h2>
		</header>
		<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<div class="panel-body">
				<div class="row mb-lg">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Campus <span class="required">*</span></label>
							<select data-plugin-selectTwo data-width="100%" name="id_campus" id="id_campus" required title="Must Be Required" class="form-control populate">
								<option value="">Select</option>';
								$sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
																		FROM ".CAMPUS." c  
																		WHERE c.campus_id != '' AND campus_status = '1'
																		ORDER BY c.campus_name ASC");
								while($value_campus = mysqli_fetch_array($sqllmscampus)){
									echo'<option value="'.$value_campus['campus_id'].'" '.($value_campus['campus_id'] == $id_campus ? 'selected' : '').'>'.$value_campus['campus_name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Class <span class="required">*</span></label>
							<select data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" required title="Must Be Required" class="form-control populate" onchange="get_section(this.value)">
								<option value="">Select</option>';
								$sqlClass	= $dblms->querylms("SELECT c.class_id, c.class_name
															FROM ".CLASSES." c  
															WHERE c.class_status = '1'  
															ORDER BY c.class_id ASC");
								while($valClass = mysqli_fetch_array($sqlClass)){
									echo'<option value="'.$valClass['class_id'].'" '.($valClass['class_id'] == $class ? 'selected' : '').'>'.$valClass['class_name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">Section</label>
							<select data-plugin-selectTwo data-width="100%" name="id_section" id="id_section" class="form-control populate">
								<option value="">Select</option>';
								$sqlSection	= $dblms->querylms("SELECT s.section_id, s.section_name
															FROM ".CLASS_SECTIONS." s  
															WHERE s.section_status = '1' 
															AND s.is_deleted = '0' 
															AND s.id_class = '".$class."'
															AND s.id_campus = '".$id_campus."'
															ORDER BY s.section_id ASC");
								while($valSection = mysqli_fetch_array($sqlSection)){
									echo'<option value="'.$valSection['section_id'].'" '.($valSection['section_id'] == $section ? 'selected' : '').'>'.$valSection['section_name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
				</div>
				<center>
					<button type="submit" name="view_timetable" id="view_timetable" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
				</center>
			</div>
		</form>
	</section>';

	// VIEW TIMETABLE DETAILS
	if(isset($_POST['view_timetable'])){
		$sqllms	= $dblms->querylms("SELECT t.id, t.status, t.id_session, t.id_class, t.id_section, t.id_campus,
									c.class_id, c.class_status, c.class_name,
									se.section_id, se.section_status, se.section_name,
									cm.campus_name, cm.campus_logo,
									ses.session_name
									FROM ".TIMETABLE." t
									INNER JOIN ".CLASSES."  	 	 c 	ON 	c.class_id 		= t.id_class
									INNER JOIN ".CLASS_SECTIONS." se	ON 	se.section_id 	= t.id_section
									INNER JOIN ".CAMPUS." cm ON cm.campus_id = t.id_campus
									INNER JOIN ".SESSIONS." ses ON ses.session_id = t.id_session
									WHERE t.id_class = '".$class."'
									AND t.id_section = '".$section."'
									AND t.id_campus = '".$id_campus."'  
									AND t.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
									LIMIT 1");
		$rowsvalues = mysqli_fetch_array($sqllms);
		if(mysqli_num_rows($sqllms) > 0){
			echo '
			<section class="panel panel-featured panel-featured-primary appear-animation mt-sm" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
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
									<img src="uploads/images/campus/'.$rowsvalues['campus_logo'].'" class="img-fluid" style="width: 60px;"> 
									<span><b>'.$rowsvalues['campus_name'].'</b></span>
								</h2>
								<h4 style="text-align: center;"><b>Time Table</b></h4>
								<br>
								<div>
									<h5 class="mb-md">
										'.(($class && $section) ? '<b>Class: </b>'.$rowsvalues['class_name'].' ('.$rowsvalues['section_name'].')' : '').'
										<span class="pull-right">'.($rowsvalues['id_session'] ? '<b>Session: </b>'.$rowsvalues['session_name'] : '').'</span>
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
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>';
		}else{
			echo'
			<section class="panel panel-featured panel-featured-primary appear-animation mt-sm" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
				<div class="panel-body">
					<h2 class="text-center text-danger">No Result Found!</h2>
				</div>
			</section>';
		}
	}

}else{
	header("Location: dashboard.php");
}
?>