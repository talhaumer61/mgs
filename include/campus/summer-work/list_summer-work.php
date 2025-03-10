<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('62', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '62', 'view' => '1'))) { 
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><a href="resource_pack.php"><i class="fa fa-home"></i> Resource Pack</a> / Vacational Engagement Tasks</h2>
		</header>
		<div class="panel-body">
			<div class="row row-sm">';
				$sql = "SELECT s.summer_id, s.summer_status, s.summer_file, s.file_thumbnail, s.id_type, s.id_month, s.id_class, s.note, s.id_session,
						se.session_name, c.class_name
						FROM ".SUMMER_WORK." s 
						INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
						INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
						WHERE s.summer_status = '1' AND s.is_deleted != '1' 
						AND s.id_class IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
						AND s.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
						ORDER BY s.summer_id DESC ";

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

						$ext = pathinfo($rowsvalues['summer_file'], PATHINFO_EXTENSION);
						if($ext == 'pdf'){
							$icon = 'pdf';
						}elseif($ext == 'docx'){
							$icon = 'word';
						}elseif($ext == 'ppt'){
							$icon = 'powerpoint';
						}else{
							$icon = 'empty-folder';
						}

						// SUMMER FILE TYPE
						if($rowsvalues['id_type'] == '1'){
							$type = "Summer";
						}elseif($rowsvalues['id_type'] == '2'){
							$type = "Winter";
						}else{
							$type="";
						}
						echo'
						<div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-sm">
							<div class="card card-file">
								<div class="dropdown">
									<span class="status_span">'.get_status($rowsvalues['summer_status']).'</span>
									<a href="#" class="dropdown-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
										if($rowsvalues['note']){
											echo'<li><a href="#show_modal" class="modal-with-move-anim-pvs dropdown-item" onclick="showAjaxModalZoom(\'include/modals/summer-work/modal_summer_details.php?id='.$rowsvalues['summer_id'].'\');"><i class="fa fa-info-circle"></i> View Details</a></li>';
										}
										if($rowsvalues['summer_file']){
											echo'<li><a href="uploads/summer-work/'.$rowsvalues['summer_file'].'" class="dropdown-item" target="_blank"><i class="fa fa-eye"></i> View File </a></li>';
											echo'<li><a href="uploads/summer-work/'.$rowsvalues['summer_file'].'" download="'.$rowsvalues['session_name'].'-'.$rowsvalues['class_name'].'" class="dropdown-item"><i class="fa fa-download"></i> Download</a></li>';
										}
										echo'
									</div>
								</div>
								<div class="card-file-thumb">
									<img src="'.(!empty($rowsvalues['file_thumbnail']) ? 'uploads/summer-work/thumbnail/'.$rowsvalues['file_thumbnail'].'' : 'assets/images/files/'.$icon.'.png').'" width="100" height="100">
								</div>
								<div class="card-body">
									<h6>'.$rowsvalues['class_name'].' ('.$rowsvalues['session_name'].')</h6>
									<h6>
										<span class="d-none d-sm-inline" >'.get_monthtypes($rowsvalues['id_month']).' </span>
										| <span class="d-none d-sm-inline" >'.$icon.' file</span>
									</h6>
								</div>
								<div class="card-footer">
									<span class="d-none d-sm-inline" >Type: </span>'.$type.'
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
			// 			<th>Type</th>
			// 			<th>Month</th>
			// 			<th>Class</th>
			// 			<th width="100" class="center">Download</th>
			// 		</tr>
			// 	</thead>
			// 	<tbody>';
			// 		$sqllms	= $dblms->querylms("SELECT s.summer_id, s.summer_status, s.summer_file, s.id_type, s.id_month, s.id_class, s.note, s.id_session,
			// 									se.session_name, c.class_name
			// 									FROM ".SUMMER_WORK." s 
			// 									INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
			// 									INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
			// 									WHERE s.summer_status = '1' AND s.is_deleted != '1' 
			// 									AND s.id_class IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
			// 									AND s.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
			// 									ORDER BY s.summer_id DESC");
			// 		$srno = 0;
			// 		while($rowsvalues = mysqli_fetch_array($sqllms)) {
			// 			$srno++;
			// 			if($rowsvalues['id_type'] == '1'){
			// 				$type = "Summer";
			// 			}elseif($rowsvalues['id_type'] == '2'){
			// 				$type = "Winter";
			// 			}else{
			// 				$type="";
			// 			}
			// 			echo '
			// 			<tr>
			// 				<td class="center">'.$srno.'</td>
			// 				<td>'.$type.'</td>
			// 				<td>'.get_monthtypes($rowsvalues['id_month']).'</td>
			// 				<td>'.$rowsvalues['class_name'].'</td>
			// 				<td class="center">';
			// 					if($rowsvalues['note']){
			// 						echo '<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-warning btn-xs" onclick="showAjaxModalZoom(\'include/modals/summer-work/modal_summer_details.php?id='.$rowsvalues['summer_id'].'\');"><i class="glyphicon glyphicon-comment"></i></a>';
			// 					}
			// 					echo'
			// 					<a href="uploads/summer-work/'.$rowsvalues['summer_file'].'" download="'.$rowsvalues['session_name'].'-'.$rowsvalues['class_name'].'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i> </a>
			// 					<a href="uploads/summer-work/'.$rowsvalues['summer_file'].'" class="btn btn-info btn-xs");" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>
			// 				</td>
			// 			</tr>';
			// 		}
			// 	echo '
			// 	</tbody>
			// </table>';
			echo'
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>