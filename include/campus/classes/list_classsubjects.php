<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('5', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '5', 'view' => '1'))) {
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('5', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '5', 'add' => '1'))) {
				echo'<a href="#add_book" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Subject</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Subject List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center" width="40">Sr.</th>
						<th>Subject Code</th>
						<th>Subject Name</th>
						<th class="center">Subject Type</th>
						<th class="center">Book Name</th>
						<th class="center">Book Edition</th>
						<th>Class Name</th>						
						<th class="center" width="70">Status</th>
						<th class="center" width="100">Option</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT b.id, b.status, b.type, b.name, b.edition, b.publisher, b.id_class, b.id_subject, sub.subject_code, sub.subject_name, c.class_name
												FROM ".SUBJECT_BOOKS." b
												INNER JOIN ".CLASSES." c ON c.class_id = b.id_class
												INNER JOIN ".CLASS_SUBJECTS." sub ON sub.subject_id = b.id_subject
												WHERE b.id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
												AND b.is_deleted	= '0'
												ORDER BY c.class_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['subject_code'].'</td>
							<td>'.$rowsvalues['subject_name'].'</td>
							<td class="center">'.get_subjecttype($rowsvalues['type']).'</td>
							<td class="center">'.$rowsvalues['name'].'</td>
							<td class="center">'.$rowsvalues['edition'].'</td>
							<td>'.$rowsvalues['class_name'].'</td>
							<td class="center">'.get_status($rowsvalues['status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('5', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '5', 'edit' => '1'))) {
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/class/modal_subjectbooks_update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('5', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '5', 'delete' => '1'))) {
									echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'classsubjects.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
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
