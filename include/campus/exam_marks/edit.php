<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'edit' => '1'))) {
 	$condition = array(
							 'select'       =>  'em.*, cm.campus_name, t.type_name, c.class_name, cs.section_name, sb.subject_name, dd.dated'
							,'join'			=>	'INNER JOIN '.EXAM_TYPES.' t ON t.type_id = em.id_exam
												 INNER JOIN '.DATESHEET_DETAIL.' dd ON dd.id_subject=em.id_subject
												 INNER JOIN '.DATESHEET.' d on d.id = dd.id_setup
												 INNER JOIN '.CAMPUS.' cm ON cm.campus_id = em.id_campus
												 INNER JOIN '.CLASSES.' c ON c.class_id = em.id_class
												 INNER JOIN '.CLASS_SECTIONS.' cs ON cs.section_id = em.id_section
												 INNER JOIN '.CLASS_SUBJECTS.' sb ON sb.subject_id = em.id_subject' 
							,'where'        =>  array(
														 'em.is_deleted' 	=> 0
														,'em.id_session'  	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'em.id'  			=> cleanvars($_GET['id'])
														,'d.id_session'			=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
											)
							,'order_by'  	=>  'em.id DESC'
							,'return_type'  =>  'single'
	);
	$row = $dblms->getRows(EXAM_MARKS.' em', $condition);

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

		// STUDENTS FROM ATTENDANCE
		$condition = array(
							 'select'		=>  's.std_id, s.std_rollno, s.std_regno, s.std_name, s.std_fathername, s.std_photo, s.id_section, md.obtain_marks, md.total_marks, md.status, md.remarks'
							,'join'			=>	'INNER JOIN '.EXAM_ATTENDANCE_DETAIL.' ad ON ad.id_setup = ea.id AND ad.status = 1
												 INNER JOIN '.STUDENTS.' s ON s.std_id = ad.id_std AND s.std_status = 1 AND s.is_deleted = 0
												 LEFT JOIN '.EXAM_MARKS_DETAILS.' md ON md.id_std = s.std_id AND md.id_setup = '.$row['id'].''
							,'where'        =>  array(
														 'ea.status'		=>	1
														,'ea.is_publish'  	=>	1
														,'ea.is_deleted'  	=>	0
														,'ea.id_campus'		=>	cleanvars($row['id_campus'])
														,'ea.id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
														,'ea.id_exam'		=>	cleanvars($row['id_exam'])
														,'ea.id_class'		=>	cleanvars($row['id_class'])
														,'ea.id_section'	=>	cleanvars($row['id_section'])
														,'ea.id_subject'	=>	cleanvars($row['id_subject'])
													)
							,'order_by'		=>	' s.std_id ASC'
							,'return_type'  =>  'all'
		);
		$EXAM_ATTENDANCE = $dblms->getRows(EXAM_ATTENDANCE.' ea', $condition);
		if($EXAM_ATTENDANCE){
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<form action="'.moduleName().'.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
					<header class="panel-heading">
						<h4 class="panel-title"><i class="fa fa-plus-square"></i> Update Marks</h4>
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
									<input type="radio"  name="is_publish" value="1" '.($row['is_publish']==1?"checked":"").' required>
									<label >Yes</label>
								</div>
								<div class="radio-custom radio-inline">
									<input type="radio"  name="is_publish" value="2" '.($row['is_publish']==2?"checked":"").' required>
									<label >No</label>
								</div>
							</div>';
							if($_SESSION['userlogininfo']['CAMPUSTYPE'] != 1){
								echo'<label class="text-danger ml-xl">(Once Published. Record can not be edited or deleted.)</label>';
							}
							echo'
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
									<th class="text-center">Total Marks</th>
									<th class="text-center">Obtained Marks</th>
									<th class="text-center">Remarks</th>
								</tr>
							</thead>
							<tbody>';
								$srno = 0;
								foreach ($EXAM_ATTENDANCE as $key => $student) {
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
											'.$DATESHEET['total_marks'].'
											<input type="hidden" value="'.$DATESHEET['passing_marks'].'" name="passing_marks['.$srno.']" id="passing_marks_'.$srno.'">
											<input class="form-control" type="hidden" value="'.$DATESHEET['total_marks'].'" name="total_marks['.$srno.']" id="total_marks_'.$srno.'" required readonly>
										</td>
										<td class="text-center">
											<div>
												<input class="form-control" type="number" value="'.$student['obtain_marks'].'" name="obtain_marks['.$srno.']" id="obtain_marks_'.$srno.'" max="'.$DATESHEET['total_marks'].'" required>
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
								<button type="submit" id="submit" name="update_marks" class="mr-xs btn btn-primary">Update Marks</button>
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