<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('8', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'view' => '1'))) {
	$id_campus = (!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? $_SESSION['userlogininfo']['LOGINCAMPUS'].','.$_SESSION['userlogininfo']['SUBCAMPUSES'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
	
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('8', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'add' => '1'))) {
				echo'<a href="#make_timetable" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Period</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Periods List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>												
						<th width="40" class="center">Sr.</th>
						<th>Period Name</th>
						<th>Start</th>
						<th>End</th>
						<th>Start (Friday)</th>
						<th>End (Friday)</th>
						<th>Campus</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT p.period_id, p.period_status, p.period_name, p.period_timestart, p.period_timeend, p.period_timestart_friday, p.period_timeend_friday, p.id_campus, c.campus_name
												FROM ".PERIODS." p
												INNER JOIN ".CAMPUS." c ON c.campus_id = p.id_campus 
												WHERE p.is_deleted	= '0'
												AND p.id_campus IN (".$id_campus.")
												ORDER BY p.period_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['period_name'].'</td>
							<td>'.$rowsvalues['period_timestart'].'</td>
							<td>'.$rowsvalues['period_timeend'].'</td>
							<td>'.$rowsvalues['period_timestart_friday'].'</td>
							<td>'.$rowsvalues['period_timeend_friday'].'</td>
							<td>'.$rowsvalues['campus_name'].'</td>
							<td class="center">'.get_status($rowsvalues['period_status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('8', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'edit' => '1'))) {
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/timetable/period/modals_period_update.php?id='.$rowsvalues['period_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('8', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'delete' => '1'))) {
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'timetable_period.php?deleteid='.$rowsvalues['period_id'].'&id_campus='.$rowsvalues['id_campus'].'\');"><i class="el el-trash"></i></a>';
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