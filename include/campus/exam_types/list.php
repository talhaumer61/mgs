<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '11', 'view' => '1'))){
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1)){ 
				echo'<a href="#make_examtype" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Exam Type</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Exam Types List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center" width="70">No.</th>
						<th>Type Name</th>
						<th>Type Detail</th>
						<th width="70" class="center">Status</th>';						
						if(($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1)){ 
							echo'<th width="100" class="center">Options</th>';
						}
						echo'
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT type_id, type_status, type_name, type_details
												FROM ".EXAM_TYPES." 
												WHERE is_deleted = '0'
												AND (id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' OR id_campus = '".$_SESSION['userlogininfo']['PARENTCAMPUS']."') 
												ORDER BY type_id ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['type_name'].'</td>
							<td>'.$rowsvalues['type_details'].'</td>
							<td class="center">'.get_status($rowsvalues['type_status']).'</td>';
							if(($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1)){ 
								echo'
								<td class="center">
									<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/exam_types/update.php?id='.$rowsvalues['type_id'].'\');"><i class="glyphicon glyphicon-edit"></i> </a>
									<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'exam_types.php?deleteid='.$rowsvalues['type_id'].'\');"><i class="el el-trash"></i></a>
								</td>';								
							}
							echo'
						</tr>';
					}
					echo'
				</tbody>
			</table>
		</div>
	</section>';
} else {
	header("Location: dashboard.php");
}
?>