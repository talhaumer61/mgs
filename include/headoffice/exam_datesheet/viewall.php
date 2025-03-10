<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'view' => '1'))){ 	

//-----------------------------------------------
if(isset($_POST['id_type'])){$type = $_POST['id_type'];}else{$type = "";}
if(isset($_POST['id_campus'])){$campus = $_POST['id_campus'];}else{$campus = "";}
//-----------------------------------------------	
echo'
<section class="panel panel-featured panel-featured-primary">
	<form action="#" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fa fa-list"></i> Select Exam Type</h4>
		</div>
		<div class="panel-body">
			<div class="row mt-sm">
                <div class="col-md-4 col-md-offset-4 mb-sm">
                    <label class="control-label">Type <span class="required">*</span></label>
                        <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_type">
                        <option value="">Select</option>';
                            $sqllms_type	= $dblms->querylms("SELECT type_id, type_status, type_name 
                                                FROM ".EXAM_TYPES."
                                                WHERE type_status = '1' 
                                                ORDER BY type_id ASC");
                            while($value_type = mysqli_fetch_array($sqllms_type)) 
                            {
                                if($value_type['type_id'] == $type){
                                    echo '<option value="'.$value_type['type_id'].'" selected>'.$value_type['type_name'].'</option>';
                                }else{
                                    echo '<option value="'.$value_type['type_id'].'">'.$value_type['type_name'].'</option>';
                                }
                            }
                            echo '
                    </select>
                </div>
                <div class="col-md-4 col-md-offset-4 mb-sm">
                    <label class="control-label">Campus <span class="required">*</span></label>
                        <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_campus">
                        <option value="">Select</option>';
                            $sqllmsCamp	= $dblms->querylms("SELECT campus_id, campus_status, campus_name 
                                                FROM ".CAMPUS."
                                                WHERE campus_status = '1' AND is_deleted != '1' 
                                                ORDER BY campus_name ASC");
                            while($valueCamp = mysqli_fetch_array($sqllmsCamp)) 
                            {
                                if($valueCamp['campus_id'] == $campus){
                                    echo '<option value="'.$valueCamp['campus_id'].'" selected>'.$valueCamp['campus_name'].'</option>';
                                }else{
                                    echo '<option value="'.$valueCamp['campus_id'].'">'.$valueCamp['campus_name'].'</option>';
                                }
                            }
                            echo '
                    </select>
                </div>
			</div>		
		</div>
		<footer class="panel-footer mt-sm">
			<div class="row">
				<div class="col-md-12 text-center">
					<button type="submit" id="view_timetable" name="view_datesheet" class="mr-xs btn btn-primary">Get Datesheet</button>
				</div>
			</div>
		</footer>
	</form>
</section>';


//---------------------VIEW TIMETABLE DETAILS--------------------------------
if(isset($_POST['view_datesheet'])){
//-----------------------------------------------------
echo'<section class="panel panel-featured panel-featured-primary appear-animation mt-sm" data-appear-animation="fadeInRight" data-appear-animation-delay="100">';
//-----------------------------------------------------
$sqllms	= $dblms->querylms("SELECT t.id, t.status, t.id_exam, t.id_session, t.id_class, t.id_campus,
									e.type_name, c.class_name
								   FROM ".DATESHEET." t
								   INNER JOIN ".EXAM_TYPES." e	ON 	e.type_id 		= t.id_exam
								   INNER JOIN ".CLASSES."  	 c 	ON 	c.class_id 		= t.id_class
								   WHERE t.is_deleted != '1'
                                   AND t.id_campus = '".$campus."' 
                                   AND t.id_exam = '".$type."'");
$rowsvalues = mysqli_fetch_array($sqllms);
//-----------------------------------------------------
if(mysqli_num_rows($sqllms) > 0){
	echo'
    <header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-clock-o"></i> ';
		echo'
		'.$rowsvalues['type_name'].' Datesheet </b>)</h2>
	</header>
    
		<div class="panel-body">
			<div class="table-responsive mt-sm mb-md">
				<table class="table table-bordered table-striped table-condensed  mb-none" id="my_table">
					<tbody>	
						<tr>
							<th class="center">
								Days <i class="fa fa-hand-o-down"></i> |
								Classes <i class="fa fa-hand-o-right"></i>
							</th>';
							//-----------------------------------------
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_name
																FROM ".CLASSES." 
																WHERE class_status = '1' AND is_deleted != '1' ");
							//-----------------------------------------------------
							$classes = array();
							while($value_cls = mysqli_fetch_array($sqllmscls))
							{ 
								$classes[] = $value_cls;
								echo'
								<th style="text-align: center;">'.$value_cls['class_name'].'</th>';
							}
							echo'
						</tr>';
						
						//----------------------------------------------------- 
						$sqllmsdet	= $dblms->querylms("SELECT d.dated, d.start_time, d.end_time, e.emply_name, s.subject_name, s.subject_code, r.room_no
														FROM ".DATESHEET_DETAIL." 	 d 
														INNER JOIN ".EMPLOYEES." 	 e 	ON 	e.emply_id 		= d.id_teacher
														INNER JOIN ".CLASS_SUBJECTS." s 	ON 	s.subject_id 	= d.id_subject
														INNER JOIN ".CLASS_ROOMS."    r 	ON 	r.room_id 		= d.id_room
														ORDER BY d.dated
														");
						$srno = 0;
						while($rowsdet = mysqli_fetch_array($sqllmsdet))
						{
							$srno++;
							
							//--------------------------------------
							$dated = date("D d m Y", strtotime($rowsdet['dated']));
							//--------------------------------------
							echo '					
							<tr>
								<td class="center">'.$dated.'</td>';

								foreach($classes as $class) { 
									// echo json_encode($class);
									// echo "<br>". $class['class_id'];
									//--------------------------------------
									$sqllmsdetail	= $dblms->querylms("SELECT c.class_name, d.dated, d.start_time, d.end_time, e.emply_name, s.subject_name, s.subject_code, r.room_no
																				FROM ".DATESHEET." t
																				INNER JOIN ".DATESHEET_DETAIL." d	ON 	d.id_setup		= t.id
																				INNER JOIN ".EMPLOYEES." 	 e 	ON 	e.emply_id 		= d.id_teacher
																				INNER JOIN ".CLASS_SUBJECTS." s 	ON 	s.subject_id 	= d.id_subject
																				INNER JOIN ".CLASS_ROOMS."    r 	ON 	r.room_id 		= d.id_room
																				INNER JOIN ".CLASSES."    c	ON 	c.class_id 		= t.id_class
																				WHERE t.id = '".$rowsvalues['id']."' AND t.id_class = '".$class['class_id']."'
																				AND d.dated = '".$rowsdet['dated']."'
																			");
									//--------------------------------------
									$rowsdetail = mysqli_fetch_array($sqllmsdetail);
									//--------------------------------------
									echo'
									<td class="center">
									
										<div class="btn-group">';
										if(mysqli_num_rows($sqllmsdetail) > 0){
											echo'
											<button class="mb-xs mt-xs mr-xs btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
												Subject : '.$rowsdetail['subject_name'].' ('.$rowsdetail['subject_code'].')
												<br>Room : '.$rowsdetail['room_no'].'
												<br>Invigilator : '.$rowsdetail['emply_name'].'
												<br>Time : '.$rowsdetail['start_time'].' - '.$rowsdetail['end_time'].'
												<br>Class Name : '.$rowsdetail['class_name'].'

											</button>
											<!-- <ul class="dropdown-menu">
												<li>
													<a href="timetable/class_update/4">
														<i class="glyphicon glyphicon-edit"></i>
														Edit
													</a>
												</li>
												<li>
													<a href="#" onclick="confirm_modal("timetable/maintain/delete/4");">
														<i class="el el-trash"></i> Delete										</a>
												</li>
											</ul> -->';
										}
										echo '
										</div>
									</td>';
									
								}

								// <td>'.$day.'</td>
								// <td>'.$dated.'</td>
								// <td>'.$rowsdetail['subject_name'].' ('.$rowsdetail['subject_code'].')</td>
								// <td>'.$rowsdetail['room_no'].'</td>
								// <td>'.$rowsdetail['emply_name'].'</td>
								// <td>'.$rowsdetail['start_time'].'</td>
								// <td>'.$rowsdetail['end_time'].'</td>';
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
	</div>';
}
echo '
</section>';
}
else{
    header("location: dashboard.php");
}
?>