<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('9', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'view' => '1'))) {
	$id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];

	if(isset($_POST['id_campus']) && !empty($_POST['id_campus'])){
		$id_campus = $_POST['id_campus'];
	}

	//-----------------------------------------------
	if(isset($_POST['id_teacher'])){
		$values = explode("|",$_POST['id_teacher']);
		$teacher_id = $values[0];
		$teacher_name = $values[1];
	}
	else{
		$teacher_id = "";
		$teacher_name = "";
	}

	//-----------------------------------------------	
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form action="#" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<div class="panel-body">
				<div class="row mt-sm">';				
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
						echo'
						<div class="col-md-4 col-md-offset-2">
							<label class="control-label">Sub Campus</label>
							<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required" onchange="get_teachers(this.value)">
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
					}
					echo'
					<div class="col-md-4 '.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? '' : 'col-md-offset-4').'">
						<label class="control-label">Teacher</label>
						<select data-plugin-selectTwo data-width="100%" name="id_teacher" id="id_teacher" title="Must Be Required" class="form-control populate">
							<option value="">Select</option>';
							$sqllms	= $dblms->querylms("SELECT emply_id, emply_name
														FROM ".EMPLOYEES." 
														WHERE emply_status = '1' AND is_deleted != '1' AND id_type = '1'
														AND id_campus = '".$id_campus."'
														ORDER BY emply_name ASC");
							while($rowsvalues = mysqli_fetch_array($sqllms)){
								echo'<option value="'.$rowsvalues['emply_id'].'|'.$rowsvalues['emply_name'].'"'; if($teacher_id == $rowsvalues['emply_id']){echo'selected';} echo'>'.$rowsvalues['emply_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>		
				<div class="row mt-md">
					<div class="col-md-12 text-center">
						<button type="submit" id="view_timetable" name="view_timetable" class="mr-xs btn btn-primary">Get Details</button>
					</div>
				</div>
			</div>
			</div>
		</form>';
	//---------------------VIEW TIMETABLE DETAILS--------------------------------
	if(isset($_POST['view_timetable'])){
		//-----------------------------------------------------
		echo'<section class="panel panel-featured panel-featured-primary appear-animation mt-sm" data-appear-animation="fadeInRight" data-appear-animation-delay="100">';
			//-----------------------------------------------------
			$sqllms	= $dblms->querylms("SELECT t.id
											FROM ".TIMETABLE." t
											INNER JOIN ".TIMETABEL_DETAIL." d ON	d.id_setup = t.id
											WHERE d.id_teacher = '".$teacher_id."'
											AND t.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."' 
											AND t.id_campus = '".$id_campus."' 
											LIMIT 1");
			$rowsvalues = mysqli_fetch_array($sqllms);
			//-----------------------------------------------------
				if(mysqli_num_rows($sqllms) > 0){
					echo '
					<header class="panel-heading">
						<h2 class="panel-title"><i class="fa fa-clock-o"></i> ';
						echo'
						Daily Class Routiene of <b>'.$teacher_name.'</b>
						<button onclick="print_report(\'printResultTeacher\')" class="mr-xs btn-xs pull-right btn btn-primary"><i class="glyphicon glyphicon-print"></i> Print</button>
						</h2>
					</header>
					
					<div class="panel-body" id="printResultTeacher">
						<header class="panel-heading" id="header" style="display:none;">
							<h2 class="panel-title"><i class="fa fa-clock-o"></i> ';
							echo'
							Daily Class Routiene of <b>'.$teacher_name.'</b></h2>
						</header>
						<div class="table-responsive mt-sm mb-md" >
							<table class="table table-bordered table-striped table-condensed  mb-none" id="my_table">
								<tbody>	
									<tr>
										<th>
											Days <i class="fa fa-hand-o-down"></i> |
											Periods <i class="fa fa-hand-o-right"></i>
										</th>';
										//-----------------------------------------
										$sqllmssub	= $dblms->querylms("SELECT 
																			period_id, period_name, period_timestart, period_timeend
																			FROM ".PERIODS."
																			WHERE id_campus = '".$id_campus."' ");
										//-----------------------------------------------------
										$periods = array();
										while($rowsub = mysqli_fetch_array($sqllmssub)){ 
											$periods[] = $rowsub;
											echo'
											<th style="text-align: center;">'.$rowsub['period_name'].'</th>';
										}
										echo'
									</tr>';
									for($i=1; $i<=7; $i++){
										echo'
										<tr>
											<td>'. get_daytypes($i).'</td>';
											//-----------------------------------------------------
											foreach($periods as $itemperiod) { 
												//----------------------------------------------------- 
												$day = get_daytypes($i);
												$loop = $i;
												//----------------------------------------------------- 
												$sqllmsdetail	= $dblms->querylms("SELECT d.id, d.day, d.id_subject, d.id_room, d.id_period, d.id_teacher, d.id_setup,
																				t.id, t.status, t.id_session, t.id_class, t.id_section, t.id_campus,
																				c.class_id, c.class_status, c.class_name,
																				se.section_id, se.section_status, se.section_name, 
																				s.subject_id, s.subject_status, s.subject_name,
																				r.room_id, r.room_status, r.room_no, r.room_capacity,
																				e.emply_id, e.emply_status, e.emply_name, e.id_type
																				FROM ".TIMETABEL_DETAIL." 	 d 
																				INNER JOIN ".TIMETABLE."  	 t 	ON 	t.id 			= d.id_setup
																				INNER JOIN ".CLASSES."  	 	 c 	ON 	c.class_id 		= t.id_class
																				INNER JOIN ".CLASS_SECTIONS." se	ON 	se.section_id 	= t.id_section
																				INNER JOIN ".CLASS_SUBJECTS." s 	ON 	s.subject_id 	= d.id_subject
																				LEFT JOIN ".CLASS_ROOMS."    r 	ON 	r.room_id 		= d.id_room
																				INNER JOIN ".EMPLOYEES." 	 e 	ON 	e.emply_id 		= d.id_teacher
																				WHERE d.id_period = '".$itemperiod['period_id']."' AND d.day = '".$i."'
																				AND d.id_teacher = '".$teacher_id."'
																				AND t.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."' 
																				AND t.id_campus = '".$id_campus."' 
																			");
												//----------------------------------------------------- 
												echo '					
												<td style="text-align:center;">
													<div class="btn-group">';
														if(mysqli_num_rows($sqllmsdetail) > 0){
															while($rowsdetail = mysqli_fetch_array($sqllmsdetail)){
															echo'
															<button class="mb-xs mt-xs mr-xs btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
																'.$rowsdetail['subject_name'].'
																('.$itemperiod['period_timestart'].' - '.$itemperiod['period_timeend'].')
																<br>Room : '.$rowsdetail['room_no'].'
																<br>Class : '.$rowsdetail['class_name'].'
																<br>Section : '.$rowsdetail['section_name'].'
															</button>';
															}
														}
														echo '
													</div>
												</td>';
											}
											echo'
										</tr>';
									}
									echo'			
								</tbody>
							</table>
						</div>	
					</div>
					<!-- <div class="panel-footer">
						<div class="text-right">
							<a href="timetable-documents_print.php" class="btn btn-sm btn-primary " target="include/marks/marks_sheetprint.php">
								<i class="glyphicon glyphicon-print"></i> Print
							</a>
						</div>
					</div> --> ';
				}
				else{
					echo'
					<div class="panel-body">
						<h2 class="text-center text-danger">No Result Found!</h2>
					</div>';
				}
			echo'
		</section>';
		
		//main Section
		echo'</section>';
	}
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