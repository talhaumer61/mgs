<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('9', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'view' => '1'))) {
	$id_campus = (!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? $_SESSION['userlogininfo']['LOGINCAMPUS'].','.$_SESSION['userlogininfo']['SUBCAMPUSES'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
	
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('9', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'add' => '1'))) {
				echo '
				<a href="timetable.php?view=add" class="btn btn-primary btn-xs pull-right mr-sm">
				<i class="fa fa-plus-square"></i> Make Class Timetable</a>';
			}
			echo '
			<h2 class="panel-title"><i class="fa fa-list"></i>  Class Timetabel List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
				<thead>
					<tr>
						<th width="40" class="center">Sr.</th>
						<th>Session</th>
						<th>Class</th>
						<th>Section</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT t.id, t.status, t.id_session, t.id_class, t.id_section, t.id_campus,
												ss.session_id, ss.session_status, ss.session_name,
												c.class_id, c.class_status, c.class_name,
												se.section_id, se.section_status, se.section_name
												FROM ".TIMETABLE." 	t 
												INNER JOIN ".SESSIONS." ss ON ss.session_id = t.id_session
												INNER JOIN ".CLASSES." c ON c.class_id = t.id_class
												INNER JOIN ".CLASS_SECTIONS." se ON se.section_id = t.id_section
												WHERE t.id != '' AND t.is_deleted != '1'
												AND t.id_campus IN (".$id_campus.")
												AND t.id_session = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."'
												ORDER BY c.class_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['session_name'].'</td>
							<td>'.$rowsvalues['class_name'].'</td>
							<td>'.$rowsvalues['section_name'].'</td>
							<td class="center">'.get_status($rowsvalues['status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('9', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'edit' => '1'))) {
								echo'<a href="timetable.php?id='.$rowsvalues['id'].'" class="btn btn-primary btn-xs" onclick=""><i class="glyphicon glyphicon-edit"></i></a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('9', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'delete' => '1'))) {
								echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'timetable.php?deleteid='.$rowsvalues['id'].'&id_campus='.$rowsvalues['id_campus'].'\');"><i class="el el-trash"></i></a>';
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