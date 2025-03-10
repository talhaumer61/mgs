<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '60', 'view' => '1'))){ 
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
		if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '60', 'add' => '1'))){ 
			echo'
			<a href="#make_worksheet" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
			<i class="fa fa-plus-square"></i> Make Work Sheet
			</a>';
		}
		echo'
		<h2 class="panel-title"><a href="resource_pack.php"><i class="fa fa-home"></i> Resource Pack</a> / Syllabus Work Sheets</h2>
	</header>
	<div class="panel-body">
		<div class="row row-sm">';
			$sql = "SELECT s.syllabus_id, s.syllabus_status, s.syllabus_term, s.id_session,
					s.syllabus_file, s.file_thumbnail, s.id_month, s.id_week, s.id_class, s.id_subject, s.note,
					se.session_name, c.class_name, cs.subject_name
					FROM ".SYLLABUS." s 
					INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
					INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
					INNER JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = s.id_subject
					WHERE s.syllabus_type	= '3'
					AND s.is_deleted		= '0'
					ORDER BY s.syllabus_id DESC ";

			$sqllms	= $dblms->querylms($sql);
			//--------------------------------------------------
			$count = mysqli_num_rows($sqllms);
			if($page == 0) { $page = 1; }			//if no page var is given, default to 1.
			$prev		= $page - 1;				//previous page is page - 1
			$next		= $page + 1;				//next page is page + 1
			$lastpage	= ceil($count/$Limit);		//lastpage is = total pages / items per page, rounded up.
			$lpm1		= $lastpage - 1;

			$sqllms	= $dblms->querylms("$sql LIMIT ".($page-1)*$Limit .",$Limit");
			if(mysqli_num_rows($sqllms)>0){
				if($page == 1){
					$srno = 0;
				}else{
					$srno = ($page - 1) * $Limit;
				}
				while($rowsvalues = mysqli_fetch_array($sqllms)){
					$srno ++;

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
										echo'<li><a href="#show_modal" class="modal-with-move-anim-pvs dropdown-item" onclick="showAjaxModalZoom(\'include/modals/syllabus-worksheet/modal_worksheet_details.php?id='.$rowsvalues['syllabus_id'].'\');"><i class="fa fa-info-circle"></i> View Details</a></li>';
									}
									if($rowsvalues['syllabus_file']){
										echo'<li><a href="uploads/worksheet/'.$rowsvalues['syllabus_file'].'" class="dropdown-item" target="_blank"><i class="fa fa-eye"></i> View File </a></li>';
										echo'<li><a href="uploads/worksheet/'.$rowsvalues['syllabus_file'].'" download="'.$rowsvalues['session_name'].'-'.get_monthtypes($rowsvalues['id_month']).'-'.$rowsvalues['class_name'].'-'.$rowsvalues['subject_name'].'" class="dropdown-item"><i class="fa fa-download"></i> Download</a></li>';
									}
									if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) ||  Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '57', 'edit' => '1'))){ 
										echo'<li><a href="#show_modal" class="modal-with-move-anim-pvs dropdown-item" onclick="showAjaxModalZoom(\'include/modals/syllabus-worksheet/modal_worksheet_update.php?id='.$rowsvalues['syllabus_id'].'\');"><i class="fa fa-edit"></i> Edit</a></li>';
									}
									if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '57', 'delete' => '1'))){ 
										echo'<li><a href="#" class="dropdown-item" onclick="confirm_modal(\'syllabus_worksheet.php?deleteid='.$rowsvalues['syllabus_id'].'\');"><i class="fa fa-trash"></i> Delete</a></li>';
									}
									echo'
								</div>
							</div>
							<div class="card-file-thumb">
								<img src="'.(!empty($rowsvalues['file_thumbnail']) ? 'uploads/worksheet/thumbnail/'.$rowsvalues['file_thumbnail'].'' : 'assets/images/files/'.$icon.'.png').'" width="100" height="100">
							</div>
							<div class="card-body">
								<h6>'.$rowsvalues['class_name'].' ('.$rowsvalues['session_name'].')</h6>
								<h6>
									<span class="d-none d-sm-inline" >'.get_term($rowsvalues['syllabus_term']).' </span>
									| <span class="d-none d-sm-inline" >'.get_monthtypes($rowsvalues['id_month']).' </span>
									| <span class="d-none d-sm-inline" >'.$icon.' file </span>
								</h6>
							</div>
							<div class="card-footer">
								<span class="d-none d-sm-inline" >Subject: </span>'.($rowsvalues['subject_name'] ? $rowsvalues['subject_name'] : 'All Subjects').'
							</div>
						</div>
					</div>';
				}
				//-------------- Pagination ------------------
				echo'<div class="col-md-12">';
				include_once('include/pagination.php');
				echo'</div>';
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
		// 			<th>Term</th>
		// 			<th>Session</th>
		// 			<th>Class</th>
		// 			<th>Subject</th>
		// 			<th width="70px;" class="center">Status</th>
		// 			<th width="100" class="center">Options</th>
		// 		</tr>
		// 	</thead>
		// 	<tbody>';
		// 		$sqllms	= $dblms->querylms("SELECT s.syllabus_id, s.syllabus_status, s.syllabus_term, s.id_session,
		// 									s.syllabus_file, s.id_month, s.id_week, s.id_class, s.id_subject, s.note,
		// 									se.session_name, c.class_name, cs.subject_name
		// 									FROM ".SYLLABUS." s 
		// 									INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
		// 									INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
		// 									INNER JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = s.id_subject
		// 									WHERE s.syllabus_type = '3'
		// 									ORDER BY s.syllabus_id DESC");
		// 		$srno = 0;
		// 		while($rowsvalues = mysqli_fetch_array($sqllms)) {
		// 			$srno++;
		// 			echo '
		// 			<tr>
		// 				<td class="center">'.$srno.'</td>
		// 				<td>'.get_term($rowsvalues['syllabus_term']).'</td>
		// 				<td>'.$rowsvalues['session_name'].'</td>
		// 				<td>'.$rowsvalues['class_name'].'</td>
		// 				<td>'.$rowsvalues['subject_name'].'</td>
		// 				<td class="center">'.get_status($rowsvalues['syllabus_status']).'</td>
		// 				<td width="150" class="center">';
		// 				if($rowsvalues['note']){
		// 					echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-warning btn-xs" onclick="showAjaxModalZoom(\'include/modals/syllabus-worksheet/modal_worksheet_details.php?id='.$rowsvalues['syllabus_id'].'\');"><i class="glyphicon glyphicon-comment"></i></a>';	}
		// 				echo'
		// 				<a href="uploads/worksheet/'.$rowsvalues['syllabus_file'].'" download="'.$rowsvalues['session_name'].'-'.get_monthtypes($rowsvalues['id_month']).'-'.$rowsvalues['class_name'].'-'.$rowsvalues['subject_name'].'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i> </a>
		// 				<a href="uploads/worksheet/'.$rowsvalues['syllabus_file'].'" class="btn btn-info btn-xs");" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>';
						
		// 				if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) ||  Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '60', 'edit' => '1'))){ 
		// 				echo'
		// 					<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/syllabus-worksheet/modal_worksheet_update.php?id='.$rowsvalues['syllabus_id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>';
		// 				}
		// 				if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '60', 'delete' => '1'))){ 
		// 				echo'
		// 					<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'syllabus_dlp.php?deleteid='.$rowsvalues['syllabus_id'].'\');"><i class="el el-trash"></i></a>';
		// 				}
		// 				echo'
		// 				</td>
		// 			</tr>';
		// 		}
		// 	echo'
		// 	</tbody>
		// </table>';
		echo'
	</div>
</section>';
}
else{
	header("Location: dashboard.php");
}
?>