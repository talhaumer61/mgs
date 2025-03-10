<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('57', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '57', 'view' => '1'))) {
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><a href="resource_pack.php"><i class="fa fa-home"></i> Resource Pack</a> / Syllabus Break-Down</h2>
		</header>
		<div class="panel-body">
			<div class="row row-sm">';
				$sqllms	= $dblms->querylms("SELECT s.syllabus_id, s.syllabus_status, s.syllabus_term, s.id_session,
											s.syllabus_file, s.file_thumbnail, s.id_class, s.id_subject, s.note,
											se.session_id, se.session_status, se.session_name,
											c.class_id, c.class_status, c.class_name,
											cs.subject_id, cs.subject_status, cs.subject_name
											FROM ".SYLLABUS." s 
											INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
											INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
											LEFT JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = s.id_subject
											WHERE s.syllabus_status	= '1' 
											AND s.is_deleted		= '0'
											AND s.syllabus_type		= '1'
											AND s.id_session		= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
											AND s.id_class IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
											ORDER BY s.syllabus_id DESC");
											
				if(mysqli_num_rows($sqllms)>0){

					if($page == 1){
						$srno = 0;
					}else{
						$srno = ($page - 1) * $Limit;
					}
					
					while($rowsvalues = mysqli_fetch_array($sqllms)){
						$srno++;
						
						$ext = pathinfo($rowsvalues['syllabus_file'], PATHINFO_EXTENSION);
						if($ext == 'pdf'){
							$icon = 'pdf';
						}elseif($ext == 'docx'){
							$icon = 'word';
						}elseif($ext == 'ppt'){
							$icon = 'powerpoint';
						}else{
							$icon = 'empty-folder';
						}
						echo'
						<div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-sm">
							<div class="card card-file">
								<div class="dropdown">
									<span class="status_span">'.get_status($rowsvalues['syllabus_status']).'</span>
									<a href="#" class="dropdown-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
										if($rowsvalues['note']){
											echo'<li><a href="#show_modal" class="modal-with-move-anim-pvs dropdown-item" onclick="showAjaxModalZoom(\'include/modals/syllabus-breakdown/modal_syllabus_details.php?id='.$rowsvalues['syllabus_id'].'\');"><i class="fa fa-info-circle"></i> View Details</a></li>';
										}
										if($rowsvalues['syllabus_file']){
											echo'<li><a href="uploads/syllabus/'.$rowsvalues['syllabus_file'].'" class="dropdown-item" target="_blank"><i class="fa fa-eye"></i> View File </a></li>';
											echo'<li><a href="uploads/syllabus/'.$rowsvalues['syllabus_file'].'" download="'.$rowsvalues['session_name'].'-'.$rowsvalues['class_name'].'-'.$rowsvalues['subject_name'].'" class="dropdown-item"><i class="fa fa-download"></i> Download</a></li>';
										}
										echo'
									</div>
								</div>
								<div class="card-file-thumb">
									<img src="'.(!empty($rowsvalues['file_thumbnail']) ? 'uploads/syllabus/thumbnail/'.$rowsvalues['file_thumbnail'].'' : 'assets/images/files/'.$icon.'.png').'" width="100" height="100">
								</div>
								<div class="card-body">
									<h6>'.$rowsvalues['class_name'].' ('.get_term($rowsvalues['syllabus_term']).')</h6>
									<span>'.$rowsvalues['session_name'].'</span>
									<p class="mb-none">File Type: <span>'.$icon.'</span></p>
								</div>
								<div class="card-footer">
									<span class="d-none d-sm-inline" >Subject: </span>'.($rowsvalues['subject_name'] ? $rowsvalues['subject_name'] : 'All Subjects').'
								</div>
							</div>
						</div>';
					}
				}else{
					echo'<h2 class="text text-center text-danger mt-lg">No Record Found!</h2>';
				}
				echo'
			</div>';
			// echo'
			// <table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			// 	<thead>
			// 		<tr>
			// 			<th class="center">#</th>
			// 			<th>Session</th>
			// 			<th>Term</th>
			// 			<th>Class</th>
			// 			<th>Subject</th>
			// 			<th width="100" class="center">Options</th>
			// 		</tr>
			// 	</thead>
			// 	<tbody>';
			// 	$sqllms	= $dblms->querylms("SELECT s.syllabus_id, s.syllabus_status, s.syllabus_term, s.id_session,
			// 								s.syllabus_file, s.id_class, s.id_subject, s.note,
			// 								se.session_id, se.session_status, se.session_name,
			// 								c.class_id, c.class_status, c.class_name,
			// 								cs.subject_id, cs.subject_status, cs.subject_name
			// 								FROM ".SYLLABUS." s 
			// 								INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
			// 								INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
			// 								LEFT JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = s.id_subject
			// 								WHERE s.syllabus_status = '1' AND s.is_deleted != '1'
			// 								AND s.syllabus_type = '1'
			// 								AND S.id_class IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
			// 								AND s.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
			// 								ORDER BY se.session_name ASC");
			// 	$srno = 0;
			// 	while($rowsvalues = mysqli_fetch_array($sqllms)){
			// 		$srno++;
			// 		echo '
			// 		<tr>
			// 			<td class="center">'.$srno.'</td>
			// 			<td>'.$rowsvalues['session_name'].'</td>
			// 			<td>'.get_term($rowsvalues['syllabus_term']).'</td>
			// 			<td>'.$rowsvalues['class_name'].'</td>
			// 			<td>';
			// 			if($rowsvalues['subject_name']){
			// 				echo' '.$rowsvalues['subject_name'].' ';}
			// 			else{
			// 				echo'All Subjects';
			// 			} echo'</td>
			// 			<td class="center">';
			// 				if($rowsvalues['note']){
			// 					echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-warning btn-xs ml-xs" onclick="showAjaxModalZoom(\'include/modals/syllabus-breakdown/modal_syllabus_details.php?id='.$rowsvalues['syllabus_id'].'\');"><i class="glyphicon glyphicon-comment"></i></a>';
			// 				}
			// 				echo'
			// 				<a href="uploads/syllabus/'.$rowsvalues['syllabus_file'].'" download="'.$rowsvalues['session_name'].'-'.$rowsvalues['class_name'].'-'.$rowsvalues['subject_name'].'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i> </a>
			// 				<a href="uploads/syllabus/'.$rowsvalues['syllabus_file'].'" class="btn btn-info btn-xs");" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>
			// 			</td>
			// 		</tr>';
			// 	}
			// 	echo'
			// 	</tbody>
			// </table>';
			echo'
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
