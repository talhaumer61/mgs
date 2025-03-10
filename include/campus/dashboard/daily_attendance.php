<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '49', 'view' => '1'))){ 
	echo '
	<div class="col-md-12">
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list"></i> Today Attendance</h2>
			</header>
			<div class="panel-body">
				<div style="height: 500px; overflow-y: auto;">
					<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">';
						$sqllms	= $dblms->querylms("SELECT d.id_setup, d.id_std, d.status, s.std_id, s.std_status, s.std_name, s.std_fathername, s.std_regno, s.std_photo, c.class_name, cs.section_name
														FROM ".STUDENT_ATTENDANCE." a
														INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." d ON d.id_setup = a.id
														INNER JOIN ".STUDENTS." s ON s.std_id = d.id_std    
														INNER JOIN ".CLASSES." c ON c.class_id = a.id_class
														INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = a.id_section                               
														WHERE a.dated = '".date('Y-m-d')."'
														AND a.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
													");
						$srno = 0;
						if(mysqli_num_rows($sqllms) > 0 ) {
							echo'
							<thead>
								<tr>
									<th width="40" class="center">Sr.</th>
									<th width="40">Photo</th>
									<th>Full Name </th>
									<th>Regno </th>
									<th>Class (Section) </th>
									<th width="100" class="center">Status </th>
								</tr>
							</thead>
							<tbody>';
								while($rowsvalues = mysqli_fetch_array($sqllms)) {        
									$srno++;
									echo'
									<tr>
										<td class="center">'.$srno.'</td>
										<td class="center"> <img src="uploads/images/students/'.$rowsvalues['std_photo'].'" width="35" height="35"</td> 								
										<td>'.$rowsvalues['std_name'].' '.$rowsvalues['std_fathername'].'</td>
										<td>'.$rowsvalues['std_regno'].'</td>
										<td>'.$rowsvalues['class_name'].' ('.$rowsvalues['section_name'].')</td>
										<td class="center">'.get_attendtype1($rowsvalues['status']).'</td>
									</tr>';
								}
								echo '
							</tbody>';
						}else{
							echo'
							<tr>
								<th class="center" style="padding:3rem; color: red;">No Attendance Marked Today</th>
							</tr>';
						}
						echo'
					</table>
				</div>
			</div>
		</section>
	</div>';
}
?>