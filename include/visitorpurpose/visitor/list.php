<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('43', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '43', 'view' => '1'))) {
	$id_campus = (!empty($_GET['id_campus']) ? cleanvars($_GET['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));
	
	if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
		echo'
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
			</header>
			<form action="#" id="form" enctype="multipart/form-data" method="get" accept-charset="utf-8">
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
						<button type="submit" class="btn btn-primary"><i class="fa fa fa-search"></i> Search</button>
					</center>
				</div>
			</form>
		</section>';
	}

	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('43', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '43', 'add' => '1'))) { 
				echo'<a href="#make_visitor" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Add Visitor</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i> Visitor List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th style="text-align:center;">#</th>
						<th>Id Purpose</th>
						<th>Card No</th>
						<th>Name</th>
						<th>Phone</th>
						<th>Email</th>
						<th>Cnic</th>
						<th>Num Of Person</th>
						<th>Dated</th>
						<th>Time In</th>
						<th>Time Out</th>
						<th>Note</th>
						<th width="70px;" style="text-align:center;">Status</th>
						<th width="100" style="text-align:center;">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT v.id, v.status, v.id_purpose, v.card_no, v.name, v.phone, v.email, v.cnic, v.num_of_person, v.dated, v.time_in, v.time_out, v.note,
												p.purpose_id,p.purpose_name
												FROM ".VISITOR." v 
												INNER JOIN ".VISITOR_PURPOSES." p ON p.purpose_id = v.id_purpose
												WHERE v.id_campus = '".$id_campus."'  
												ORDER BY v.card_no ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td style="text-align:center;">'.$srno.'</td>
							<td>'.$rowsvalues['purpose_name'].'</td>
							<td>'.$rowsvalues['card_no'].'</td>
							<td>'.$rowsvalues['name'].'</td>
							<td>'.$rowsvalues['phone'].'</td>
							<td>'.$rowsvalues['email'].'</td>
							<td>'.$rowsvalues['cnic'].'</td>
							<td>'.$rowsvalues['num_of_person'].'</td>
							<td>'.$rowsvalues['dated'].'</td>
							<td>'.$rowsvalues['time_in'].'</td>
							<td>'.$rowsvalues['time_out'].'</td>
							<td>'.$rowsvalues['note'].'</td>
							<td style="text-align:center;">'.get_status($rowsvalues['status']).'</td>
							<td>';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('43', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '43', 'edit' => '1'))) { 
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/visitorpurpose/visitor/update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('43', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '43', 'delete' => '1'))) { 
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'visitors.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
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
} else {
	header("location: dashboard.php");
}
?>