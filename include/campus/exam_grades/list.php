<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINIDA'] == 1) ||($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || ($_SESSION['userlogininfo']['LOGINIDA'] == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '14', 'view' => '1'))){ 
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1)){ 
				echo'<a href="#make_hostel" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Add Grade</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i> Grade List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
				<thead>
					<tr>
						<th style="text-align:center;">No.</th>
						<th>Grade Name</th>
						<th>Grade Lower Mark</th>
						<th>Grade Upper Mark</th>
						<th>Grade Comment</th>
						<th width="70px;" style="text-align:center;">Status</th>';						
						if(($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1)){ 
							echo'<th width="100" style="text-align:center;">Options</th>';
						}
						echo'
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT g.grade_id, g.grade_name, g.grade_point, g.grade_lowermark, g.grade_uppermark, g.grade_comment, g.grade_status  
													FROM ".GRADESYSTEM." g  
													WHERE g.is_deleted	= '0'
													AND (g.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' OR g.id_campus = '".$_SESSION['userlogininfo']['PARENTCAMPUS']."') 
													ORDER BY g.grade_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo'
						<tr>
							<td style="text-align:center;">'.$srno.'</td>
							<td>'.$rowsvalues['grade_name'].'</td>
							<td>'.$rowsvalues['grade_lowermark'].'</td>
							<td>'.$rowsvalues['grade_uppermark'].'</td>
							<td>'.$rowsvalues['grade_comment'].'</td>
							<td style="text-align:center;">'.get_status($rowsvalues['grade_status']).'</td>';							
							if(($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1)){ 
								echo'
								<td class="center">
									<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/exam_grades/update.php?id='.$rowsvalues['grade_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>
									<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'exam_grades.php?deleteid='.$rowsvalues['grade_id'].'\');"><i class="el el-trash"></i></a>
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
}
else{
	header("Location: dashboard.php");
}
?>