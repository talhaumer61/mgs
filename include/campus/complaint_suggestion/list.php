<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('24', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '24', 'view' => '1'))) {
	if(!empty($_POST['id_campus'])){
		$id_campus = $_POST['id_campus'];
	}else{
		if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
			$id_campus = $_SESSION['userlogininfo']['SUBCAMPUSES'].','.$_SESSION['userlogininfo']['LOGINCAMPUS'];
		}else{
			$id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
		}
	}

	if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
		echo'
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
			</header>
			<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<div class="panel-body">
					<div class="row">';
						if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
							echo'
							<div class="col-md-6 col-md-offset-3">
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
						<button type="submit" name="view_payslip" id="view_payslip" class="btn btn-primary"><i class="fa fa fa-search"></i> Search</button>
					</center>
				</div>
			</form>
		</section>';
	}
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Complaints & Suggestions List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th width="40" class="center">Sr.</th>
						<th>Title</th>
						<th>Dated</th>
						<th>Type</th>
						<th>Source</th>
						<th>Compalint By</th>
						<th>Name</th>
						<th>Phone</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT id, status, id_type, complaint_by, id_complaint_by, name, phone, id_source, dated, title, detail, remarks
												FROM ".COMPLAINTS." 	 									   
												WHERE id_campus IN (".$id_campus.") 
												AND assign_to	= '2'
												AND is_deleted	= '0'");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						if($rowsvalues['id_type'] == 1){
							$type = "Complaint";
						}else if($rowsvalues['id_type'] == 2){
							$type = "Suggestion";
						}
						if($rowsvalues['id_source'] == 1){
							$source = "Website";
						}else if($rowsvalues['id_source'] == 2){
							$source = "Mobile App";
						}
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['title'].'</td>
							<td>'.date("d M Y", strtotime($rowsvalues['dated'])).'</td>
							<td>'.$type.'</td>
							<td>'.$source.'</td>
							<td>'.get_logintypes($rowsvalues['complaint_by']).'</td>
							<td>'.$rowsvalues['name'].'</td>
							<td>'.$rowsvalues['phone'].'</td>
							<td class="center">'.get_complaint($rowsvalues['status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('24', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '24', 'edit' => '1'))) {
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/complaint_suggestion/update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								// if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '24', 'delete' => '1'))){ 
								// echo'
								// 	<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'complaints.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>
								// ';
								// }
								echo'
							</td>
						</tr>';
					}
					echo'
				</tbody>
			</table>
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>