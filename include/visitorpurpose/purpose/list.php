<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('44', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '44', 'view' => '1'))) { 
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('44', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '44', 'add' => '1'))) { 
				echo'<a href="#make_purpose" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Visit Purpose</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i> Purpose List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th width=" 40" class="center">Sr.</th>
						<th>Purpose Name</th>
						<th>Purpose Detail</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT p.purpose_id, p.purpose_status, p.purpose_name, p.purpose_detail
												FROM ".VISITOR_PURPOSES." p  
												WHERE p.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
												ORDER BY p.purpose_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['purpose_name'].'</td>
							<td>'.$rowsvalues['purpose_detail'].'</td>
							<td class="center">'.get_status($rowsvalues['purpose_status']).'</td>
							<td>';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('44', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '44', 'edit' => '1'))) { 
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/visitorpurpose/purpose/update.php?id='.$rowsvalues['purpose_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('44', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '44', 'delete' => '1'))) { 
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'visitor_purposes.php?deleteid='.$rowsvalues['purpose_id'].'\');"><i class="el el-trash"></i></a>';
								}
								echo'
							</td>
						</tr>';
					}
					echo '
				</tbody>
			</table>
		</div>
	</section>';
} else {
	header("location: dashboard.php");
}
?>