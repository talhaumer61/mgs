<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('9', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'view' => '1'))) {
	// if (isset($_POST['id_level']) && !empty($_POST['id_level'])) {
	// 	$id_level 	= cleanvars($_POST['id_level']);
	// 	$sqllmsLevel	= $dblms->querylms("SELECT level_classes
	// 										FROM ".CAMPUS_LEVELS."
	// 										WHERE level_status = '1'
	// 										AND is_deleted = '0'
	// 										AND level_id = '".$id_level."'
	// 										ORDER BY level_id ASC");
	// 	$valLevel = mysqli_fetch_array($sqllmsLevel);
	// 	$level_classes = $valLevel['level_classes'];
	// 	$sql      	= 'AND FIND_IN_SET(c.class_id,\''.$level_classes.'\')';
	// } else {
	// 	$id_level 	= '';
	// 	$sql      	= '';
	// }
	$sql = '';
	if(isset($_POST['id_campus']) && !empty($_POST['id_campus'])){
        $id_campus = $_POST['id_campus'];
    }else{				
        $id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
    }
	echo'
	<section class="panel panel-featured panel-featured-primary mt-sm">
		<div class="panel-body">
			<style>
				#my_table2 th,
				td {
					width: 200px !important;
				}
				#widthDiv{
					width: 200px !important;
				}
			</style>
			<form action="" method="POST">
				<div class="row">';
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
						echo'
						<div class="col-md-4">
							<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required">
								<option value="">Select Sub Campus</option>';
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
					<!--
					<div class="col-md-4">
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_level">
							<option value="">Select Wing</option>';
							$sqllmsLevel	= $dblms->querylms("SELECT level_id, level_name, level_code
																FROM ".CAMPUS_LEVELS."
																WHERE level_status = '1'
																AND is_deleted = '0'
																ORDER BY level_id ASC");
							while($valueLevel = mysqli_fetch_array($sqllmsLevel)) {
							echo '<option value="'.$valueLevel['level_id'].'" '.(($id_level == $valueLevel['level_id'])? 'selected': '').'>'.$valueLevel['level_name'].'</option>';
							}
						echo '
						</select>
					</div>
					-->
					<div class="col-md-2">
						<div class="form-group">
							<button id="filter_Challan" class="mr-xs btn btn-primary"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
				<div class="table-responsive mt-sm mb-md">
					<table class="table table-bordered table-striped table-condensed mb-none" id="my_table my_table2">
						<tbody>	
							<tr>
								<th style="">Periods <i class="fa fa-hand-o-down"></i></th>';
									$sqllmssub			= $dblms->querylms("SELECT period_id, period_name, period_timestart, period_timeend
																			FROM ".PERIODS."
																			WHERE id_campus = '".cleanvars($id_campus)."' ");

									$sqllms_sections 	= $dblms->querylms("SELECT sec.section_id, sec.section_name, sec.section_strength, sec.id_class, sec.section_status,c.class_id, c.class_name
																			FROM ".CLASS_SECTIONS." sec
																			INNER JOIN ".CLASSES." c ON c.class_id = sec.id_class
																			WHERE sec.section_id != '' AND sec.is_deleted != '1'
																			AND sec.id_campus = '".cleanvars($id_campus)."' 
																			$sql
																			ORDER BY sec.section_id ASC");
									$classArray = array();
									while($rowsections = mysqli_fetch_array($sqllms_sections)){ 
										$classArray[] = $rowsections;
										echo'<th style="text-align: center; ">'.$rowsections['class_name'].' '.$rowsections['section_name'].'</th>';
									}
									echo'
								</tr>';
								$periods = array();
								while($rowsub = mysqli_fetch_array($sqllmssub)){
									$periods[] = $rowsub;
									echo'
									<tr>
										<td style="">'.$rowsub['period_name'].'</td>';
										foreach($classArray as $itemClass){
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
																			INNER JOIN ".CLASS_ROOMS."    r 	ON 	r.room_id 		= d.id_room
																			INNER JOIN ".EMPLOYEES." 	 e 	ON 	e.emply_id 		= d.id_teacher
																			WHERE t.id_campus = '".cleanvars($id_campus)."'  
																			AND t.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
																			AND d.id_period = '".$rowsub['period_id']."'
																			AND c.class_id = '".$itemClass['class_id']."' LIMIT 1");
											$rowsdetail = mysqli_fetch_array($sqllmsdetail);
											echo '					
											<td style="text-align:center;">
												<div class="btn-group" id="widthDiv">';
												if(mysqli_num_rows($sqllmsdetail) > 0){
													echo'
													<button class="mb-xs mt-xs mr-xs btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
														'.$rowsdetail['subject_name'].'
														('.$rowsub['period_timestart'].' - '.$rowsub['period_timeend'].')
														<br>Teacher : '.$rowsdetail['emply_name'].'
														<br>Class Room : '.$rowsdetail['room_no'].'
														<br>Day : '.get_daytypes($rowsdetail['day']).'
													</button>';
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
			</form>
		</div>
		<!-- <div class="panel-footer">
			<div class="text-right">
				<a href="timetable-documents_print.php" class="btn btn-sm btn-primary " target="include/marks/marks_sheetprint.php">
					<i class="glyphicon glyphicon-print"></i> Print
				</a>
			</div>
		</div> -->
</section>';
}else{
	header("location: dashboard.php");
}
?>