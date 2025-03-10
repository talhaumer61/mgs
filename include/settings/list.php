<?php
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('51', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '51', 'view' => '1'))) {
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('51', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '51', 'add' => '1'))) {
				echo'<a href="#make_setting" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make New Settings</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-cogs"></i> Settings</h2>
		</header>
		<div class="panel-body">
			<div class="container" style="margin-bottom: 25px;">
				<img src="assets/images/banner.jpg" width="100%" alt="Session Setting Instructions">
			</div>';
			$sqllms	= $dblms->querylms("SELECT adm_session, acd_session, exam_session
											FROM ".SETTINGS." 
											WHERE id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
											AND status		= '1'
											AND is_deleted	= '0'
										");
			if(mysqli_num_rows($sqllms)>0){
				$rowsvalues = mysqli_fetch_array($sqllms);

				// ADMISSION
				$sqllms_adm	= $dblms->querylms("SELECT session_name
												FROM ".SESSIONS."  
												WHERE '".$rowsvalues['adm_session']."' = session_id");
				$value_adm = mysqli_fetch_array($sqllms_adm);
				// ACADEMIC
				$sqllms_acd	= $dblms->querylms("SELECT session_name
												FROM ".SESSIONS."  
												WHERE '".$rowsvalues['acd_session']."' = session_id");
				$value_acd = mysqli_fetch_array($sqllms_acd);
				// EXAM
				$sqllms_exam	= $dblms->querylms("SELECT session_name
												FROM ".SESSIONS."  
												WHERE '".$rowsvalues['exam_session']."' = session_id");
				$value_exam = mysqli_fetch_array($sqllms_exam);
				echo'
				<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
					<thead>
						<tr>
							<th>Admission Session</th>
							<th>Academic Session</th>
							<th>Exam Session</th>
						</tr>
					</thead>
					<tbody>';
						echo'
						<tr>
							<td>'.$value_adm['session_name'].'</td>
							<td>'.$value_acd['session_name'].'</td>
							<td>'.$value_exam['session_name'].'</td>
						</tr>
					</tbody>
				</table>';
			}else{
				echo'<h2 class="text-danger center">No Record Found</h2>';
			}
			echo'
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>