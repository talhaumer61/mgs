<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('73', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'view' => '1'))) {
	$id_campus = (!empty($_GET['id_campus']) ? cleanvars($_GET['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));
	if (!empty($_GET['id_class'])) {
		$id_class = cleanvars($_GET['id_class']);
		$classSql = ' AND s.id_class = '.$id_class.' ';
	} else {
		$id_class = '';
		$classSql = '';
	}
	
	if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
		echo'
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
			</header>
			<form action="#" id="form" enctype="multipart/form-data" method="get" accept-charset="utf-8">
				<div class="panel-body">
					<div class="row">
						<div class="col-md-6">
							<label class="control-label">Class</label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class">
								<option value="">Select Class</option>';
								$sqlCampLevel = $dblms->querylms("SELECT GROUP_CONCAT(l.level_classes) campus_classes
																	FROM ".CAMPUS." c
																	LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
																	WHERE campus_id IN (".$id_campus.") ");
								$valCampLevel = mysqli_fetch_array($sqlCampLevel);
								$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
																	FROM ".CLASSES."
																	WHERE class_status = '1'
																	AND class_id IN (".$valCampLevel['campus_classes'].")
																	ORDER BY class_id ASC");
								while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['class_id'].'" '.(($valuecls['class_id'] == $id_class)? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
								}
								echo '
							</select>
						</div>';
						if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
							echo'
							<div class="col-md-6">
								<div class="form-group mb-md">
									<label class="control-label">Sub Campus</label>
									<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus">
										<option value="">Select</option>';
										$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																			FROM ".CAMPUS." 
																			WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																			AND campus_status	= '1'
																			AND is_deleted		= '0'
																			ORDER BY campus_id ASC");
										while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
											echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $id_campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
										}
										echo'
									</select>
								</div>
							</div>';
						}
						echo'
					</div>
					<center>
						<button type="submit" class="btn btn-primary"><i class="fa fa fa-search"></i> Search</button>
					</center>
				</div>
			</form>
		</section>';
	}

	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('73', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'add' => '1'))) {
				echo'<a href="#make_scholarship" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Scholarship</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i> Scholarship List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th width="40" class="center" width="40">Sr.</th>
						<th>Student Regno.</th>
						<th>Student (Guardian)</th>
						<th>Scholarship Name</th>
						<th>Scholrship</th>
						<th>Session </th>
						<th>Note </th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT s.id, s.status, s.concession_type, s.percent, s.amount, s.note,
												c.cat_id, c.cat_name, c.cat_type,
												st.std_id, st.std_name, st.std_fathername, st.std_regno,
												se.session_id, se.session_name
												FROM ".SCHOLARSHIP." s
												INNER JOIN ".SCHOLARSHIP_CAT." c ON c.cat_id = s.id_cat
												INNER JOIN ".STUDENTS." st ON st.std_id = s.id_std $classSql
												INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
												WHERE s.id_campus = '".cleanvars($id_campus)."' 
												AND s.id_type = '1' AND c.cat_type = '1'
												AND s.is_deleted = '0'
												ORDER BY s.id ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['std_regno'].'</td>
							<td>'.$rowsvalues['std_name'].' ('.$rowsvalues['std_fathername'].')</td>
							<td>'.$rowsvalues['cat_name'].'</td>
							<td>'.(!empty($rowsvalues['percent']) ? $rowsvalues['percent'].' %' : 'Rs. '.$rowsvalues['amount']).'</td>
							<td>'.$rowsvalues['session_name'].'</td>
							<td>'.$rowsvalues['note'].'</td>
							<td class="center">'.get_status($rowsvalues['status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('73', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'edit' => '1'))) { 
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/scholarship/scholarship_update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('73', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '73', 'delete' => '1'))) { 
									echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'scholarship.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
								}
								echo'
							</td>
						</tr>';
					}
					echo'
				</tbody>
			</table>
			<div id="printResult" hidden>
				<table class="table table-bordered table-striped table-condensed mb-none">
					<thead>
						<tr>
							<th width="40" class="center" width="40">Sr.</th>
							<th>Student Regno.</th>
							<th>Student (Guardian)</th>
							<th>Scholarship Name</th>
							<th>Scholrship</th>
							<th>Session </th>
							<th>Note </th>
						</tr>
					</thead>
					<tbody>';
						$sqllms	= $dblms->querylms("SELECT s.id, s.status, s.concession_type, s.percent, s.amount, s.note,
													c.cat_id, c.cat_name, c.cat_type,
													st.std_id, st.std_name, st.std_fathername, st.std_regno,
													se.session_id, se.session_name
													FROM ".SCHOLARSHIP." s
													INNER JOIN ".SCHOLARSHIP_CAT." c ON c.cat_id = s.id_cat
													INNER JOIN ".STUDENTS." st ON st.std_id = s.id_std $classSql
													INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
													WHERE s.id_campus = '".cleanvars($id_campus)."' 
													AND s.id_type = '1' AND c.cat_type = '1'
													AND s.is_deleted = '0'
													ORDER BY s.id ASC");
						$srno = 0;
						while($rowsvalues = mysqli_fetch_array($sqllms)) {
							$srno++;
							echo '
							<tr>
								<td class="center">'.$srno.'</td>
								<td>'.$rowsvalues['std_regno'].'</td>
								<td>'.$rowsvalues['std_name'].' ('.$rowsvalues['std_fathername'].')</td>
								<td>'.$rowsvalues['cat_name'].'</td>
								<td>'.(!empty($rowsvalues['percent']) ? $rowsvalues['percent'].' %' : 'Rs. '.$rowsvalues['amount']).'</td>
								<td>'.$rowsvalues['session_name'].'</td>
								<td>'.$rowsvalues['note'].'</td>
							</tr>';
						}
						echo'
					</tbody>
				</table>
			</div>
			
			<div class="text-right mt-lg on-screen">
				<button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary"><i class="glyphicon glyphicon-print"></i></button>
			</div>
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>
<script type="text/javascript">
	function print_report(printResult) {
		var printContents = document.getElementById(printResult).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>