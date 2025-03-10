<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('58', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '58', 'view' => '1'))) {
echo '
<title>Students Learning Resources | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Students Learning Resources Panel </h2>
	</header>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title"><a href="resource_pack.php"><i class="fa fa-home"></i> Resource Pack</a> / Student\'s Learning Resources</h2>
				</header>
				<div class="panel-body">
					<div class="row row-sm">';
						$sql = "SELECT r.res_id, r.res_file, r.file_thumbnail, r.id_class, r.week,
								r.id_term, r.id_session, r.note, se.session_name, c.class_name,
								cs.subject_name
								FROM ".LEARNING_RESOURCES." r 
								INNER JOIN ".SESSIONS." se ON se.session_id = r.id_session
								INNER JOIN ".CLASSES." c ON c.class_id = r.id_class
								LEFT JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = r.id_subject
								WHERE r.res_status = '1' AND r.is_deleted != '1'
								AND r.id_class IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
								AND r.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
								ORDER BY r.res_id DESC ";

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
								
								$ext = pathinfo($rowsvalues['res_file'], PATHINFO_EXTENSION);
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
											<span class="status_span">'.get_status($rowsvalues['res_status']).'</span>
											<a href="#" class="dropdown-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
											<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">';
												if($rowsvalues['note']){
													echo'<li><a href="#show_modal" class="modal-with-move-anim-pvs dropdown-item" onclick="showAjaxModalZoom(\'include/modals/learning_resources/modal_res_details.php?id='.$rowsvalues['res_id'].'\');"><i class="fa fa-info-circle"></i> View Details</a></li>';
												}
												if($rowsvalues['res_file']){
													echo'<li><a href="uploads/learning_resources/'.$rowsvalues['res_file'].'" class="dropdown-item" target="_blank"><i class="fa fa-eye"></i> View File </a></li>';
													echo'<li><a href="uploads/learning_resources/'.$rowsvalues['res_file'].'" download="'.$rowsvalues['session_name'].'-'.$rowsvalues['week'].'-'.$rowsvalues['class_name'].'-'.$rowsvalues['subject_name'].'"  class="dropdown-item"><i class="fa fa-download"></i> Download</a></li>';
												}
												echo'
											</div>
										</div>
										<div class="card-file-thumb">
											<img src="'.(!empty($rowsvalues['file_thumbnail']) ? 'uploads/learning_resources/thumbnail/'.$rowsvalues['file_thumbnail'].'' : 'assets/images/files/'.$icon.'.png').'" width="100" height="100">
										</div>
										<div class="card-body">
											<h6>'.$rowsvalues['class_name'].' ('.$rowsvalues['session_name'].')</h6>
											<h6>Week: <span class="d-none d-sm-inline" >'.$rowsvalues['week'].' </span> | File Type: <span class="d-none d-sm-inline" >'.$icon.' </span></h6>
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
					// 			<th class="text-center">#</th>
					// 			<th>Week</th>
					// 			<th>Class</th>
					// 			<th>Subject</th>
					// 			<th width="80" class="text-center">Options</th>
					// 		</tr>
					// 	</thead>
					// 	<tbody>';
					// 		$sqllms	= $dblms->querylms("SELECT r.res_id, r.res_file, r.id_class, r.week,
					// 										r.id_term, r.id_session, r.note, se.session_name, c.class_name,
					// 										cs.subject_name
					// 										FROM ".LEARNING_RESOURCES." r 
					// 										INNER JOIN ".SESSIONS." se ON se.session_id = r.id_session
					// 										INNER JOIN ".CLASSES." c ON c.class_id = r.id_class
					// 										LEFT JOIN ".CLASS_SUBJECTS." cs ON cs.subject_id = r.id_subject
					// 										WHERE r.res_status = '1' AND r.is_deleted != '1'
					// 										AND r.id_class IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
					// 										AND r.id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
					// 										ORDER BY r.res_id DESC");
					// 		$srno = 0;
					// 		while($rowsvalues = mysqli_fetch_array($sqllms)) {
					// 			$srno ++;
					// 			echo '
					// 			<tr>
					// 				<td style="text-align:center;">'.$srno.'</td>
					// 				<td>'.$rowsvalues['week'].'</td>
					// 				<td>'.$rowsvalues['class_name'].'</td>
					// 				<td>'.$rowsvalues['subject_name'].'</td>
					// 				<td width="100" class="text-center">';
					// 					if($rowsvalues['note']){
					// 						echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-warning btn-xs" onclick="showAjaxModalZoom(\'include/modals/learning_resources/modal_res_details.php?id='.$rowsvalues['res_id'].'\');"><i class="glyphicon glyphicon-comment"></i></a>';
					// 					}
					// 					echo'
					// 					<a href="uploads/learning_resources/'.$rowsvalues['res_file'].'" download="'.$rowsvalues['session_name'].'-'.$rowsvalues['week'].'-'.$rowsvalues['class_name'].'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i> </a>
					// 					<a href="uploads/learning_resources/'.$rowsvalues['res_file'].'" class="btn btn-info btn-xs");" target="_blank"><i class="glyphicon glyphicon-eye-open"></i> </a>';
					// 					echo'
					// 				</td>
					// 			</tr>';
					// 		}
					// 		echo '
					// 	</tbody>
					// </table>';
					echo'
				</div>
			</section>
		</div>
	</div>
</section>

<script type="text/javascript">
	function showAjaxModalZoom( url ) {
		// PRELODER SHOW ENABLE / DISABLE
		jQuery( \'#show_modal\' ).html( \'<div style="text-align:center; "><img src="assets/images/preloader.gif" /></div>\' );
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
		$.ajax( {
			url: url,
			success: function ( response ) {
				jQuery( \'#show_modal\' ).html( response );
			}
		} );
	}
</script>
<div id="show_modal" class="mfp-with-anim modal-block modal-block-primary mfp-hide"></div>';
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
	var datatable = $('#table_export').dataTable({
			bAutoWidth : false,
			ordering: false,
		});
	});
</script>
<?php
}else{
	header("Location: dashboard.php");
}
?>