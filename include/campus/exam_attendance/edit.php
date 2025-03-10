<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'edit' => '1'))) {
 	$condition = array(
							 'select'       =>  'et.*, cm.campus_name, t.type_name, c.class_name, cs.section_name, sb.subject_name'
							,'join'			=>	'INNER JOIN '.EXAM_TYPES.' t ON t.type_id = et.id_exam
												 INNER JOIN '.CAMPUS.' cm ON cm.campus_id = et.id_campus
												 INNER JOIN '.CLASSES.' c ON c.class_id = et.id_class
												 INNER JOIN '.CLASS_SECTIONS.' cs ON cs.section_id = et.id_section
												 INNER JOIN '.CLASS_SUBJECTS.' sb ON sb.subject_id = et.id_subject' 
							,'where'        =>  array(
														 'et.is_deleted' 	=> 0
														,'et.id_session'  	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'et.id'  			=> cleanvars($_GET['id'])
											)
							,'order_by'  	=>  'et.id DESC'
							,'return_type'  =>  'single'
	);
	$row = $dblms->getRows(EXAM_ATTENDANCE.' et', $condition);

	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form action="#" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<div class="panel-heading">
				<h4 class="panel-title"><i class="fa fa-filter"></i> Select</h4>
			</div>
			<div class="panel-body">
				<div class="row">';
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
						echo'
						<div class="col-md-6 mb-xs">
							<label class="control-label">Campus</label>
							<input type="hidden" name="id_campus" id="id_campus" value="'.$row['id_campus'].'">
							<input type="text" class="form-control" value="'.$row['campus_name'].'" readonly>
						</div>';
					}else{
						echo'<input type="hidden" name="id_campus" id="id_campus" value="'.$row['id_campus'].'" readonly>';
					}
					echo'
					<div class="col-md-6 mb-xs">
						<label class="control-label">Exam Type <span class="required">*</span></label>
						<input type="text" class="form-control" value="'.$row['type_name'].'" readonly>
					</div>
					<div class="col-md-6 mb-xs">
						<label class="control-label">Class <span class="required">*</span></label>					
						<input type="text" class="form-control" value="'.$row['class_name'].'" readonly>
					</div>				
					<div class="col-md-6 mb-xs">
						<label class="control-label">Section <span class="required">*</span></label>					
						<input type="text" class="form-control" value="'.$row['section_name'].'" readonly>
					</div>
					<div class="col-md-6 mb-xs">
						<label class="control-label">Subject <span class="required">*</span></label>					
						<input type="text" class="form-control" value="'.$row['subject_name'].' - '.$row['dated'].'" readonly>
					</div>
				</div>
			</div>
		</form>
	</section>';

	if(!empty($row)){		
		// DATESHEET INFO
		$condition = array(
							'select'		=>  'd.id as datesheet_id, dd.*'
							,'join'			=>	'INNER JOIN '.DATESHEET_DETAIL.' dd ON dd.id_setup = d.id'
							,'where'        =>  array(
														 'd.status'			=>	1
														,'d.id_session'		=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'d.id_exam'	  	=>	cleanvars($row['id_exam'])
														,'d.id_class'	  	=>	cleanvars($row['id_class'])
														,'dd.id_subject'  	=>	cleanvars($row['id_subject'])
														,'d.is_deleted'  	=>	0
													)
							,'search_by'	=>	' AND (d.id_campus = '.$row['id_campus'].' OR d.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR d.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
							,'return_type'  =>  'single'
		);
		$DATESHEET = $dblms->getRows(DATESHEET.' d', $condition);
		// STUDENTS
		$condition = array(
							 'select'		=>  's.std_id, s.std_rollno, s.std_regno, s.std_name, s.std_fathername, s.std_photo, s.id_section, ad.status, ad.remarks'
							,'join'			=>	'LEFT JOIN '.EXAM_ATTENDANCE_DETAIL.' ad ON ad.id_std = s.std_id AND ad.id_setup = '.$row['id'].''
							,'where'        =>  array(
														 's.std_status'		=>	1
														,'s.is_deleted'  	=>	0
														,'s.id_campus'		=>	cleanvars($row['id_campus'])
														,'s.id_class'		=>	cleanvars($row['id_class'])
														,'s.id_section'		=>	cleanvars($row['id_section'])
													)
							,'order_by'		=>	' s.std_id ASC'
							,'return_type'  =>  'all'
		);
		$STUDENTS = $dblms->getRows(STUDENTS.' s', $condition);
		if($STUDENTS){
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<form action="'.moduleName().'.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
					<header class="panel-heading">
						<h4 class="panel-title"><i class="fa fa-plus-square"></i> Mark Attendance</h4>
					</header>
					<div class="panel-body">
						<input type="hidden" name="id" value="'.$row['id'].'">
						<input type="hidden" name="id_campus" value="'.$row['id_campus'].'">
						<input type="hidden" name="id_exam" value="'.$row['id_exam'].'">
						<input type="hidden" name="id_class" value="'.$row['id_class'].'">
						<input type="hidden" name="id_section" value="'.$row['id_section'].'">
						<input type="hidden" name="id_subject" value="'.$row['id_subject'].'">
						<input type="hidden" name="id_datesheet" value="'.$DATESHEET['datesheet_id'].'">
						<input type="hidden" name="dated" value="'.$DATESHEET['dated'].'">
						<div class="form-group mt-sm">
							<label class="col-sm-2 control-label">Publish <span class="required">*</span></label>
							<div class="col-sm-2">
								<div class="radio-custom radio-inline">
									<input type="radio" name="is_publish" value="1" '.($row['is_publish'] == 1 ? 'checked' : '').' required>
									<label >Yes</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio" name="is_publish" value="2"  '.($row['is_publish'] == 2 ? 'checked' : '').' required>
									<label >No</label>
								</div>
							</div>';
							if($_SESSION['userlogininfo']['CAMPUSTYPE'] != 1){
								echo'<label class="text-danger ml-xl">(Once Published. Record can not be edited or deleted.)</label>';
							}
							echo'
						</div>
						<div class="text-right mb-md">
							<div class="btn-group">
								<button type="button" class="btn btn-default btn-sm" onclick="mark_all_present()"><i class="fa fa-check"></i><span class="hidden-xs"> Set All Present</span></button>
								<button type="button" class="btn btn-default btn-sm" onclick="mark_all_absent()"><i class="fa fa-close"></i><span class="hidden-xs"> Set All Absent</span></button>
							</div>
						</div>
						<table class="table table-bordered table-striped table-condensed mb-none">
							<thead>
								<tr>
									<th width="40" class="center">Sr.</th>
									<th width="60" class="text-center">Roll No</th>
									<th width="150">Reg No</th>
									<th>Name</th>
									<th>Father Name</th> 
									<th width="50">Photo</th>
									<th class="text-center">Attendance</th>
									<th class="text-center">Remarks</th>
								</tr>
							</thead>
							<tbody>';
								$srno = 0;
								foreach ($STUDENTS as $key => $student) {
									$srno++;
									echo'
									<tr>
										<td width="50" class="text-center">'.$srno.'</td>
										<td class="text-center">
											<input type="hidden" name="id_std['.$srno.']" value="'.$student['std_id'].'">
											<input type="hidden" name="rollno['.$srno.']" value="'.$student['std_rollno'].'">
											<input type="hidden" name="regno['.$srno.']" value="'.$student['std_regno'].'">
											'.$student['std_rollno'].'
										</td>
										<td class="text-center">'.$student['std_regno'].'</td>
										<td>'.$student['std_name'].'</td>
										<td>'.$student['std_fathername'].'</td>
										<td><img src="uploads/'.(!empty($student['std_photo']) ? 'images/students/'.$student['std_photo'].'' : 'default-student.jpg').'" height="40" width="40" alt="'.$student['std_name'].'"></td>
										<td class="text-center">
											<div class="radio-custom radio-success radio-inline">
												<input type="radio" value="1" name="status['.$srno.']" id="pstatus_'.$srno.'" '.($student['status'] == 1 ? 'checked' : '').' required>
												<label for="pstatus_'.$srno.'">Present</label>
											</div>
											<div class="radio-custom radio-danger radio-inline">
												<input type="radio" value="2"  name="status['.$srno.']" id="astatus_'.$srno.'" '.($student['status'] == 2 ? 'checked' : '').' required>
												<label for="astatus_'.$srno.'">Absent</label>
											</div>
										</td>
										<td>
											<input type="text" class="form-control" name="remarks['.$srno.']" value="'.$student['remarks'].'">
										</td>
									</tr>';
								}
								echo'
							</tbody>
						</table>
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12 text-right">
								<button type="submit" id="submit" name="update_attendance" class="mr-xs btn btn-primary">Update Attendance</button>
								<a href="'.moduleName().'.php" class="btn btn-default">Cancel</a>
							</div>
						</div>
					</footer>
				</form>
			</section>';
		}else{
			echo'
			<section class="panel panel-featured panel-featured-primary appear-animation" data-appear-animation="fadeInRight">
				<h3 class="panel-body text-center mt-none font-bold text text-danger">No Students Found!</h3>
			</section';
		}
	}
}else{
	header("Location: ".moduleName().".php", true, 301);
}
?>