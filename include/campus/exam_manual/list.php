<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('79', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '79', 'view' => '1'))) {
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1)){ 
				echo'<a href="#make_manual" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Assessment Manual</a>';
			}
			echo '
			<h2 class="panel-title"><i class="fa fa-list"></i>  Assessment Manuals List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center" width="40">Sr.</th>
						<th>Session</th>
						<th>Note</th>
						<th width="70" class="center">Status</th>
						<th width="150" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT s.id, s.status, s.id_type, s.file, s.note, se.session_name
												FROM ".EXAM_DOWNLOADS." s 	
												INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
												WHERE s.id != '' AND s.is_deleted != '1' AND s.id_type = '1'
												AND s.id_session = '".$_SESSION['userlogininfo']['EXAM_SESSION']."'
												AND (s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' OR s.id_campus = '".$_SESSION['userlogininfo']['PARENTCAMPUS']."')
												ORDER BY s.id DESC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['session_name'].'</td>
							<td>'.$rowsvalues['note'].'</td>
							<td class="center">'.get_status($rowsvalues['status']).'</td>
							<td width="120" class="center">
								<a href="uploads/assessment_downloads/'.$rowsvalues['file'].'" download="'.$rowsvalues['session_name'].'-'.get_assessment($rowsvalues['id_type']).'" class="btn btn-success btn-xs mr-");"><i class="glyphicon glyphicon-download"></i></a>
								<a href="uploads/assessment_downloads/'.$rowsvalues['file'].'" class="btn btn-info btn-xs mr-xs");" target="_blank"><i class="glyphicon glyphicon-eye-open"></i></a>';
								if(($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1)){ 
									echo '<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/exam_manual/update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>';
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'exam_manual.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
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
}else{
	header("Location: dashboard.php");
}
?>