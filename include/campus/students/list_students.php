<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))) {	

	if(isset($_GET['show_students'])){
		$class_id = $_GET['id_class'];
	}else{
		$class_id = '';
	}

	if(isset($_GET['id_campus']) && !empty($_GET['id_campus'])){
		$id_campus = $_GET['id_campus'];
	}else{				
		$id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
	}

	if(isset($_GET['id_section']) && !empty($_GET['id_section'])){
		$id_section = $_GET['id_section'];
		$sql3 = 'AND s.id_section = '.$id_section.'';
	}else{				
		$id_section = '';
		$sql3 = '';
	}

	if(isset($_GET['is_hostel']) && !empty($_GET['is_hostel'])){
		$is_hostel = $_GET['is_hostel'];
		$sql4 = 'AND s.is_hostel = '.$is_hostel.'';
	}else{				
		$is_hostel = '';
		$sql4 = '';
	}

	if(isset($_GET['std_status']) && !empty($_GET['std_status'])){
		$std_status = $_GET['std_status'];
		$sql5 = 'AND s.std_status = '.$std_status.'';
	}else{				
		$std_status = '';
		$sql5 = '';
	}

	if(isset($_GET['id_session']) && !empty($_GET['id_session'])){
		$id_session = $_GET['id_session'];
		$sql6 = 'AND s.id_session = '.$id_session.'';
	}else{				
		$id_session = '';
		$sql6 = '';
	}

	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form action="students.php" class="mb-lg validate" enctype="multipart/form-data" method="get" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list"></i>  Select Class</h2>
			</header>
			<div class="panel-body">			
				<div class="row form-group mb-md">';
					// GENERATE REGISTERATION NUMBER 
					// $sqllmscampus = $dblms->querylms("SELECT  c.campus_id, c.campus_code, cg.group_code_numeric, b.brand_code_numeric, d.dist_code
					// 									FROM ".CAMPUS." c 
					// 									INNER JOIN sms_students s ON s.id_campus = c.campus_id
					// 									INNER JOIN ".CAMPUS_GROUPS." cg ON cg.group_id = c.id_group
					// 									INNER JOIN ".BRANDS." b ON b.brand_id = c.id_brand
					// 									INNER JOIN ".DISTRICTS." d ON d.dist_id  = c.id_dist
					// 									WHERE c.is_deleted = '0'
					// 									AND c.campus_id > '50'
					// 									GROUP By s.id_campus
					// 								");
					// $update = 0;
					// while($value_campus = mysqli_fetch_array($sqllmscampus)) {

					// 	$sqllms	= $dblms->querylms("SELECT s.std_id
					// 				FROM ".STUDENTS." s
					// 				WHERE s.std_id != '' 
					// 				AND s.id_campus = '".$value_campus['campus_id']."'
					// 				");

					// 	$regnoStr = STD_PREFIX.$value_campus['group_code_numeric'].$value_campus['brand_code_numeric'].'-'.$value_campus['dist_code'].$value_campus['campus_code'].'-'.substr(date("Y"), -2);

					// 	while($row = mysqli_fetch_array($sqllms)) {

					// 		$sqllmsstudentregno = $dblms->querylms("SELECT std_regno FROM ".STUDENTS." 
					// 										WHERE std_regno LIKE '".$regnoStr."%'
					// 										AND id_campus = '".$value_campus['campus_id']."'
					// 										ORDER by std_regno DESC LIMIT 1 ");
					// 		$value_regno = mysqli_fetch_array($sqllmsstudentregno);
					// 		if(mysqli_num_rows($sqllmsstudentregno) < 1) {
					// 			$regno	= $regnoStr.'-0001';
					// 		}else{
					// 			$regno = $value_regno['std_regno'];
					// 			$regno++;
					// 		}
					// 		$sqllmmReg  = $dblms->querylms("UPDATE ".STUDENTS." SET  
					// 						std_regno		=	'".$regno."'
					// 					WHERE std_id		=	'".$row['std_id']."'");

					// 		if($sqllmmReg){
					// 			$update++;
					// 		}
					// 	}
					// }
					// echo 'Records: '.$update;
					// exit;
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
						echo'
						<div class="col-md-4">
							<label class="control-label">Sub Campus</label>
							<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required" onchange="get_class(this.value)">
								<option value="">Select</option>';
								$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																FROM ".CAMPUS." 
																WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																AND campus_status	= '1'
																AND is_deleted		= '0'
																ORDER BY campus_id ASC");
								while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
									echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $_GET['id_campus'] ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
								}
								echo'
							</select>
						</div>';
					}
					echo'
					<div class="col-md-4">
						<label class="control-label">Session</label>
						<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_session" name="id_session" title="Must Be Required" onchange="get_class(this.value)">
							<option value="">Select</option>';
							$sqlSession = $dblms->querylms("SELECT session_id, session_name
																	FROM ".SESSIONS." 
																	WHERE session_status	= '1'
																	AND is_deleted			= '0' 
																  ");
							while($valSession = mysqli_fetch_array($sqlSession)) {
								echo '<option value="'.$valSession['session_id'].'" '.($valSession['session_id'] == $_GET['id_session'] ? 'selected' : '').'>'.$valSession['session_name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-4">
						<label class="control-label">Class</label>
						<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value)" title="Must Be Required">
							<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
															FROM ".CLASSES." 
															WHERE class_status = '1'
															AND class_id IN (".$_SESSION['userlogininfo'] ['LOGINCAMPUSCLASSES'].")
															ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['class_id'].'" '.($valuecls['class_id'] == $_GET['id_class'] ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-4">
						<label class="control-label">Section</label>
						<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section" title="Must Be Required">';
							$sqlSection	= $dblms->querylms("SELECT section_id, section_name 
															FROM ".CLASS_SECTIONS."
															WHERE id_class      = '".$class_id."'
															AND section_status  = '1'
															AND is_deleted      = '0'
															AND id_campus IN (".$id_campus.")
															ORDER BY section_name ASC");
							if(mysqli_num_rows($sqlSection) > 0){
								echo'<option value="">Select</option>';
								while($valSection = mysqli_fetch_array($sqlSection)) {
									echo '<option value="'.$valSection['section_id'].'" '.($valSection['section_id'] == $id_section ? 'selected' : '').'>'.$valSection['section_name'].'</option>';
								}
							}else{
								echo '<option value="">No Record Found</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-4">
						<label class="control-label">Is Hostelize</label>
						<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="is_hostel" name="is_hostel" title="Must Be Required">
							<option value="">Select</option>';
							foreach ($statusyesno as $hostel_status) {
								echo '<option value="'.$hostel_status['id'].'" '.($hostel_status['id'] == $is_hostel ? 'selected' : '').'>'.$hostel_status['name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-4">
						<label class="control-label">Status</label>
						<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="status" name="std_status" title="Must Be Required">
							<option value="">Select</option>';
							foreach ($stdstatus as $status) {
								echo '<option value="'.$status['id'].'" '.($status['id'] == $std_status ? 'selected' : '').'>'.$status['name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="col-md-12 text-center">
					<button type="submit" id="show_students" name="show_students" class="mr-xs btn btn-primary">Show Students</button>
				</div>
			</div>
		</form>
	</section>

	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
		if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'add' => '1'))) {	
				echo'
				<a href="students.php?view=add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Student</a>
				<a href="students.php?view=import_admissions" class="btn btn-primary mr-sm btn-xs pull-right"><i class="fa fa-plus-square"></i> Import Student</a>';
			}			
			if(isset($class_id) && !empty($class_id)){
				$sql2 = "AND s.id_class = '".$class_id."' ORDER BY s.std_rollno ASC";
				// PRINT CARDS
				echo'<a href="student_card_print.php?id_class='.$_GET['id_class'].'&id_campus='.$id_campus.'&flag=1" target="_blank" class="btn btn-primary btn-xs pull-right mr-xs"><i class="fa fa-id-card"></i> Print Card Back</a>';
				echo'<a href="student_card_print.php?id_class='.$_GET['id_class'].'&id_campus='.$id_campus.'&flag=2" target="_blank" class="btn btn-primary btn-xs pull-right mr-xs"><i class="fa fa-id-card"></i> Print Card Front</a>';
			}else{
				$sql2 = "ORDER BY s.std_id DESC";
			}			
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Students List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center" width="50">Sr.</th>
						<th width="40">Photo</th>
						<th>Student Name</th>
						<th>Father Name</th>
						<th width="40">Roll no</th>
						<th>Class</th>
						<th>Section</th>
						<th>Phone</th>
						<th>CNIC</th>
						<th>Gender</th>
						<th width="90" class="center">Tuition Fee</th>';
						// echo '<th width="100" class="center">Documents</th>';
						echo'
						<th width="70" class="center">Status</th>
						<th width="50" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT DISTINCT s.*, c.class_name, sc.section_name, hr.fee, fsd.amount, s.id_session
												FROM ".STUDENTS." s
												INNER JOIN ".CLASSES."  c  ON c.class_id = s.id_class
												LEFT JOIN ".CLASS_SECTIONS." sc ON sc.section_id = s.id_section
												LEFT JOIN ".FEESETUP." fs ON fs.id_class = s.id_class AND fs.id_section = s.id_section AND fs.id_session = s.id_session AND fs.id_campus = s.id_campus
												LEFT JOIN ".FEESETUPDETAIL." fsd ON fsd.id_setup = fs.id AND fsd.id_cat = '2'
												LEFT JOIN ".HOSTELS_REGISTRATION." hr ON hr.id_user = s.std_id AND hr.is_deleted = '0'
												WHERE s.is_deleted = '0'
												AND s.id_campus = '".$id_campus."' $sql3 $sql4 $sql5 $sql6 $sql2 ");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						// SCHOLARSHIP, CONCESSION, FINE
						$sqlScholarship = $dblms->querylms("SELECT
															SUM(CASE WHEN id_type = '1' AND id_feecat = '2' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN percent ELSE NULL END) as scholarship_percent,
															SUM(CASE WHEN id_type = '1' AND id_feecat = '2' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN amount ELSE NULL END) as scholarship_amount,
															SUM(CASE WHEN id_type = '2' AND id_feecat = '2' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN percent ELSE NULL END) as concession_percent,
															SUM(CASE WHEN id_type = '2' AND id_feecat = '2' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN amount ELSE NULL END) as concession_amount
															FROM ".SCHOLARSHIP." 
															WHERE id_campus	= '".cleanvars($id_campus)."' 
															AND id_session  = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
															AND status		= '1' 
															AND is_deleted	= '0'
															AND id_std		= '".cleanvars($rowsvalues['std_id'])."'
														");
						$valScholarship = mysqli_fetch_array($sqlScholarship);
						// SCHOLARSHIP
						if($valScholarship['scholarship_percent'] != '0'){
							$scholarship = ($rowsvalues['amount'] * $valScholarship['scholarship_percent']) / 100;
						}
						elseif($valScholarship['scholarship_amount'] != '0'){
							$scholarship = $valScholarship['scholarship_amount'];
						}
						// CONCESSION
						if($valScholarship['concession_percent'] != '0'){
							$concession = ($rowsvalues['amount'] * $valScholarship['concession_percent']) / 100;
						}
						elseif($valScholarship['concession_amount'] != '0'){
							$concession = $valScholarship['concession_amount'];
						}
						$discount = $scholarship + $concession;
						$srno++;
						if($rowsvalues['std_photo']){
							$photo = "uploads/images/students/".$rowsvalues['std_photo']."";
						}else{
							$photo = "uploads/default-student.jpg";
						}
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td><img src="'.$photo.'" style="width:40px; height:40px;"></td>
							<td>'.$rowsvalues['std_name'].'</td>
							<td>'.$rowsvalues['std_fathername'].'</td>
							<td>'.$rowsvalues['std_rollno'].'</td>
							<td>'.$rowsvalues['class_name'].'</td>
							<td>'.$rowsvalues['section_name'].'</td>
							<td>'.$rowsvalues['std_phone'].'</td>
							<td>'.$rowsvalues['std_nic'].'</td>
							<td>'.$rowsvalues['std_gender'].'</td>
							<td class="center">'.(isset($rowsvalues['amount']) && !empty($rowsvalues['amount']) ? ($rowsvalues['amount'] - $discount).' Rs.' : 'None').'</td>';
							/*
							echo'
							<td class="center">';
								if(!empty($rowsvalues['std_idcard']) || !empty($rowsvalues['std_otherdocuments']) || !empty($rowsvalues['std_leavingcertificate']) || !empty($rowsvalues['std_birthcertificate'])){
									echo'
									<div class="dropdown">
										<button class="btn btn-xs btn-success dropdown-toggle" type="button" data-toggle="dropdown">Documents <i class="fa fa-caret-down"></i></button>
										<ul class="dropdown-menu pull-right">';
											if(!empty($rowsvalues['std_idcard'])){
												echo'<li><a href="uploads/images/students/id_card/'.$rowsvalues['std_idcard'].'" target="_blank">ID Card</a></li>';
											}
											if(!empty($rowsvalues['std_birthcertificate'])){
												echo'<li><a href="uploads/images/students/birth_certificate/'.$rowsvalues['std_birthcertificate'].'" target="_blank">Birth Certificate</a></li>';
											}
											if(!empty($rowsvalues['std_leavingcertificate'])){
												echo'<li><a href="uploads/images/students/leaving_certificate/'.$rowsvalues['std_leavingcertificate'].'" target="_blank">Leaving Certificate</a></li>';
											}
											if(!empty($rowsvalues['std_otherdocuments'])){
												echo'<li><a href="uploads/images/students/other_documents/'.$rowsvalues['std_otherdocuments'].'" target="_blank">Other Documents</li>';
											}
											echo'
										</ul>
									</div>';
								} else {
									echo 'None';
								}
								echo'
							</td>';
							*/
							echo'
							<td class="center">'.get_stdstatus($rowsvalues['std_status']).'</td>
							<td class="center">
								<div class="dropdown">
									<button class="btn btn-xs btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="glyphicon glyphicon-option-horizontal mt-xs"></i></button>
									<ul class="dropdown-menu pull-right">';
										if($rowsvalues['std_status'] == 2 || $rowsvalues['std_status'] == 5){
											echo'<li><a target="_blank" href=#"> <i class="fa fa-print"></i> Print</a></li>';
										}
										if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'edit' => '1'))) {	
											echo'
											<li><a title="Front Card" target="_blank" href="student_card_print.php?id='.$rowsvalues['std_id'].'&flag=2"> <i class="fa fa-id-card"></i> Card Front</a></li>
											<li><a title="Back Card" target="_blank" href="student_card_print.php?id='.$rowsvalues['std_id'].'&flag=1"> <i class="fa fa-id-card"></i> Card Back</a></li>
											<li><a href="students.php?id='.$rowsvalues['std_id'].'&id_campus='.$id_campus.''.(!empty($class_id) ? '&class_id='.$class_id.'' : '').'"> <i class="fa fa-user-circle-o"></i> Profile</a></li>';
										}
										if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'delete' => '1'))) {	
											echo'<li><a href="#" onclick="confirm_modal(\'students.php?deleteid='.$rowsvalues['std_id'].'\');"><i class="el el-trash"></i> Delete</a></li>';
										}
										echo'
									</ul>
								</div>
							</td>
						</tr>';
					}
					echo'
				</tbody>
			</table>
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>