<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('63', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '63', 'view' => '1'))) { 
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><a href="resource_pack.php"><i class="fa fa-home"></i> Resource Pack</a> / Video Lectures</h2>
		</header>
		<div class="panel-body">
			<div class="row row-sm">';
				$sql = "SELECT v.id, v.status, v.thumbnail, v.title, v.facebook_code, v.youtube_code, se.session_name, c.class_name, cs.subject_name
						FROM ".VIDEO_LECTURE." v 
						INNER JOIN ".SESSIONS." se ON se.session_id = v.id_session
						INNER JOIN ".CLASSES." c ON c.class_id = v.id_class
						INNER JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = v.id_subject
						WHERE v.id != '' AND v.status = '1' AND v.is_deleted != '1' 
						AND v.id_class IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
						AND v.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
						ORDER BY v.id DESC ";

				$sqllms	= $dblms->querylms($sql);
				
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
						echo'
						<div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-sm">
							<div class="card card-file">
								<div class="dropdown">
									<span class="status_span">'.get_status($rowsvalues['status']).'</span>
									<a href="#" class="dropdown-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';

										echo'<li><a href="#show_modal" class="modal-with-move-anim-pvs dropdown-item" onclick="showAjaxModalZoom(\'include/modals/video-lecture/modal_video_view.php?id='.$rowsvalues['id'].'\');"><i class="fa fa-play-circle-o"></i> Play Video</a></li>';
										
										echo'
									</div>
								</div>
								<div class="card-file-thumb">
									<a href="#show_modal" class="modal-with-move-anim-pvs" onclick="showAjaxModalZoom(\'include/modals/video-lecture/modal_video_view.php?id='.$rowsvalues['id'].'\');">
										<img src="'.(!empty($rowsvalues['thumbnail']) ? 'uploads/video_lectures/thumbnail/'.$rowsvalues['thumbnail'].'' : 'assets/images/files/youtube.png').'" width="100" height="100">
									</a>
								</div>
								<div class="card-body">
									<h6>Title: <span class="d-none d-sm-inline" >'.$rowsvalues['title'].' </span></h6>
									<h6>'.$rowsvalues['class_name'].' ('.$rowsvalues['session_name'].')</h6>
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
			// 			<th>Session</th>
			// 			<th>Class</th>
			// 			<th>Subject</th>
			// 			<th>Title</th>
			// 			<th width="100" class="center">Options</th>
			// 		</tr>
			// 	</thead>
			// 	<tbody>';
			// 		$sqllms	= $dblms->querylms("SELECT v.id, v.status, v.title, v.facebook_code, v.youtube_code, se.session_name, c.class_name, cs.subject_name
			// 									FROM ".VIDEO_LECTURE." v 
			// 									INNER JOIN ".SESSIONS." se ON se.session_id = v.id_session
			// 									INNER JOIN ".CLASSES." c ON c.class_id = v.id_class
			// 									INNER JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = v.id_subject
			// 									WHERE v.id != '' AND v.status = '1' AND v.is_deleted != '1' 
			// 									AND v.id_class IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
			// 									AND v.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
			// 									ORDER BY v.id DESC");
			// 		$srno = 0;
			// 		while($rowsvalues = mysqli_fetch_array($sqllms)) {
			// 			$srno++;
			// 			echo '
			// 			<tr>
			// 				<td class="center">'.$srno.'</td>
			// 				<td>'.$rowsvalues['session_name'].'</td>
			// 				<td>'.$rowsvalues['class_name'].'</td>
			// 				<td>'.$rowsvalues['subject_name'].'</td>
			// 				<td>'.$rowsvalues['title'].'</td>
			// 				<td class="center">
			// 					<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-info btn-xs" onclick="showAjaxModalZoom(\'include/modals/video-lecture/modal_video_view.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-link"></i></a>
			// 				</td>
			// 			</tr>';
			// 		}
			// 	echo'
			// 	</tbody>
			// </table>';
			echo'
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>