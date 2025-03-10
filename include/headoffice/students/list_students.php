<?php 

if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))){
	if(isset($_POST['campus'])){$campus = $_POST['campus'];}else{$campus = '';}	
	if(isset($_POST['id_class'])){$class = $_POST['id_class'];}else{$class = '';}
	if(isset($_POST['id_session'])){$session = $_POST['id_session'];}else{$session = '';}
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
		</header>
		<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<div class="panel-body">
			<div class="row mb-lg">
				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">Campus</label>
						<select data-plugin-selectTwo data-width="100%" name="campus" id="campus" title="Must Be Required" class="form-control populate">
							<option value="">Select</option>';
							$sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
															FROM ".CAMPUS." c  
															WHERE c.campus_id != '' AND campus_status = '1'
															ORDER BY c.campus_name ASC");
							while($value_campus = mysqli_fetch_array($sqllmscampus)){
								if($value_campus['campus_id'] == $campus){
									echo'<option value="'.$value_campus['campus_id'].'" selected>'.$value_campus['campus_name'].'</option>';
								}else{
									echo'<option value="'.$value_campus['campus_id'].'">'.$value_campus['campus_name'].'</option>';
								}
							}
							echo'
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<label class="control-label">Class </label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class" >
						<option value="">Select</option>';
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
												FROM ".CLASSES." 
												WHERE class_status = '1'
												ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['class_id'].'"'; if(isset($_POST['id_class']) && $_POST['id_class'] == $valuecls['class_id']){ echo'selected';} echo'>'.$valuecls['class_name'].'</option>';
							}
						echo '
					</select>
				</div>
				<div class="col-md-4">
					<label class="control-label">Session </label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_session" >
						<option value="">Select</option>';
							$sqllmssession	= $dblms->querylms("SELECT `session_id`, `session_name`
												FROM ".SESSIONS." 
												WHERE session_status = '1'
												ORDER BY session_id ASC");
							while($valuesession = mysqli_fetch_array($sqllmssession)) {
								echo '<option value="'.$valuesession['session_id'].'"'; if(isset($_POST['id_session']) && $_POST['id_session'] == $valuesession['session_id']){ echo'selected';} echo'>'.$valuesession['session_name'].'
						</option>';
							}
						echo '
					</select>
				</div>
			</div>
			<center>
				<button type="submit" name="view_students" id="view_students" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
			</center>
		</div>
		</form>
	</section>';

	// SEARCH RESULT AND LIST
	if(isset($_POST['view_students'])){
		echo '
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list"></i> Students List</h2>
			</header>
			<div class="panel-body">
				<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
					<thead>
					<tr>
						<th width="40" class="center">Sr.</th>
						<th width="40" class="center">Photo</th>
						<th>Student Name</th>
						<th>Father Name</th>
						<th width="70" class="center">Roll no</th>
						<th class="center">Class</th>
						<th class="center">Phone</th>
						<th>CNIC</th>
						<th width="130">Documents</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
					</thead>
					<tbody>';
						// CAMPUS
						if($campus){
							$sql1 = "AND s.id_campus = '".$campus."'";
						}else{
							$sql1 = "";
						}
						// CLASS
						if($class){
							$sql2 = "AND s.id_class = '".$class."'";
						}else{
							$sql2 = "";
						}
						// SESSION
						if($session){
							$sql3 = "AND s.id_session = '".$session."'";
						}else{
							$sql3 = "";
						}
						$sqllms	= $dblms->querylms("SELECT s.*, c.class_name
														FROM ".STUDENTS." s
														INNER JOIN ".CLASSES."         c  ON c.class_id 	   	= s.id_class
														WHERE s.std_id != '' $sql1 $sql2  $sql3
														ORDER BY s.std_regno ASC");
						$srno = 0;
						while($rowsvalues = mysqli_fetch_array($sqllms)) {
							$srno++;
							echo '
							<tr>
								<td class="center">'.$srno.'</td>
								<td>';
									if($rowsvalues['std_photo']) { 
										echo'<img src="uploads/images/students/'.$rowsvalues['std_photo'].'" style="width:40px; height:40px;">';
									}else{
										echo'<img src="uploads/default-student.jpg" style="width:40px; height:40px;">';
									}
									echo'
								</td>
								<td>'.$rowsvalues['std_name'].'</td>
								<td>'.$rowsvalues['std_fathername'].'</td>
								<td>'.$rowsvalues['std_rollno'].'</td>
								<td>'.$rowsvalues['class_name'].'</td>
								<td>'.$rowsvalues['std_phone'].'</td>
								<td>'.$rowsvalues['std_nic'].'</td>
								<td>';
									if(!empty($rowsvalues['std_idcard'])){
										echo'<a href="uploads/images/students/id_card/'.$rowsvalues['std_idcard'].'" target="_blank">ID Card</a><br>';
									}
									if(!empty($rowsvalues['std_birthcertificate'])){
										echo'<a href="uploads/images/students/birth_certificate/'.$rowsvalues['std_birthcertificate'].'" target="_blank">Birth Certificate</a><br>';
									}
									if(!empty($rowsvalues['std_leavingcertificate'])){
										echo'<a href="uploads/images/students/leaving_certificate/'.$rowsvalues['std_leavingcertificate'].'" target="_blank">Leaving Certificate</a><br>';
									}
									if(!empty($rowsvalues['std_otherdocuments'])){
										echo'<a href="uploads/images/students/other_documents/'.$rowsvalues['std_otherdocuments'].'" target="_blank">Other Documents</a>';
									}
									echo'
								</td>
								<td class="center">'.get_stdstatus($rowsvalues['std_status']).'</td>
								<td class="center">
									<a class="btn btn-success btn-xs" href="students.php?id='.$rowsvalues['std_id'].'"> <i class="fa fa-user-circle-o"></i></a>
								</td>
							</tr>';
						}
						echo '
					</tbody>
				</table>
			</div>
		</section>';
	}
}else{
	header("Location: dashboard.php");
}
?>