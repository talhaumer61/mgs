<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('49', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '49', 'view' => '1'))) { 
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('49', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '49', 'add' => '1'))) {
				echo'<a href="#admission_inquiry" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Inquiry</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Inquiry List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
				<thead>
					<tr>
						<th style="text-align:center;">No.</th>
						<th>Admission ID</th>
						<th>Name</th>
						<th>Father Name</th>
						<th>Cell No.</th>
						<th>Dated</th>
						<th>Source</th>
						<th>Class</th>
						<th>Campus</th>
						<th width="70px;" style="text-align:center;">Status</th>
						<th width="100" style="text-align:center;">Options</th>
					</tr>
				</thead>
				<tbody>';
					$id_campus = (!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? $_SESSION['userlogininfo']['LOGINCAMPUS'].','.$_SESSION['userlogininfo']['SUBCAMPUSES'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

					$sqllms	= $dblms->querylms("SELECT q.id, q.form_no, q.status, q.name, q.fathername, q.cell_no, q.address, q.note, q.date_added, q.source, c.class_name, cm.campus_name
												FROM ".ADMISSIONS_INQUIRY." q 
												INNER JOIN ".CLASSES." c ON c.class_id = q.id_class
												INNER JOIN ".CAMPUS." cm ON cm.campus_id = q.id_campus
												WHERE q.is_deleted != '1'
												AND q.id_campus IN ($id_campus)
												ORDER BY q.id DESC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo'
						<tr>
							<td style="text-align:center;">'.$srno.'</td>
							<td>'.$rowsvalues['form_no'].'</td>
							<td>'.$rowsvalues['name'].'</td>
							<td>'.$rowsvalues['fathername'].'</td>
							<td>'.$rowsvalues['cell_no'].'</td>
							<td>'.date("d M Y", strtotime($rowsvalues['date_added'])).'</td>
							<td>'.get_inquirysrc($rowsvalues['source']).'</td>
							<td>'.$rowsvalues['class_name'].'</td>
							<td>'.$rowsvalues['campus_name'].'</td>
							<td style="text-align:center;">'.get_status($rowsvalues['status']).'</td>
							<td class="center">
							';
							if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'add' => '1'))) {
								$StdCheck	= $dblms->querylms("SELECT std_id
														FROM ".STUDENTS."
														WHERE admission_formno	=	'".$rowsvalues['form_no']."'
														AND id_campus			=	'".$_SESSION['userlogininfo']['LOGINCAMPUS']."' LIMIT 1");
								if(mysqli_num_rows($StdCheck) < 1){
									echo'<a href="students.php?inquiry='.$rowsvalues['id'].'" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-plus-sign"></i> </a>';
								}
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('49', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '49', 'edit' => '1'))) { 
								echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" style="margin: 0 5px;" onclick="showAjaxModalZoom(\'include/modals/admissions/modal_admission_inquiry_update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i> </a>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('49', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '49', 'delete' => '1'))) { 
								echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'admission_inquiry.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
							}
							echo'
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