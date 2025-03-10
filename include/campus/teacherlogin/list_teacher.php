<?php
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'view' => '1'))) {

	$id_cmapus = (isset($_POST['id_campus']) && !empty($_POST['id_campus']))? $_POST['id_campus']: $_SESSION['userlogininfo']['LOGINCAMPUS'];

	if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
		echo'
		<form method="POST">
			<div class="form-group mb-md">
				<div class="col-md-4 col-md-offset-4">
					<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required">
						<option value="">Select Campus</option>';
						$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
														FROM ".CAMPUS." 
														WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
														AND campus_status	= '1'
														AND is_deleted		= '0'
														ORDER BY campus_id ASC");
						while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
							echo '<option value="'.$valSubCampus['campus_id'].'" '.(($valSubCampus['campus_id'] == $id_cmapus)? 'selected': '').'>'.$valSubCampus['campus_name'].'</option>';
						}
						echo'
					</select>
				</div>
				<div class="col-md-1">
					<button type="submit" class="btn btn-primary">Search</button>
				</div>
			</div>
		</form>';
	}
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'add' => '1'))) {
				echo'<a href="#make_teacherlogin" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Teacher</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i> Teacher List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
				<thead>
					<tr>
						<th width="40" class="center">Sr.</th>
						<th width="40" class="center">Photo</th>
						<th>Username</th>
						<th>Full Name</th>
						<th>Email</th>
						<th class="center">Phone</th> 
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT a.adm_id, a.adm_status, a.adm_username, a.adm_fullname, a.adm_email, a.adm_phone, a.adm_photo, e.emply_photo
												FROM ".ADMINS." a
												INNER JOIN ".EMPLOYEES." e ON e.id_loginid = a.adm_id
												WHERE a.adm_logintype = '3' 
												AND a.id_campus = '".$id_cmapus."' 
												AND a.is_deleted = '0'
												ORDER BY a.adm_username ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						if($rowsvalues['adm_photo']){
							$photo = '<img src="uploads/images/admins/'.$rowsvalues['adm_photo'].'" style="width:40px; height:40px;">';
						}elseif($rowsvalues['adm_photo']){
							$photo = '<img src="uploads/images/employees/'.$rowsvalues['emply_photo'].'" style="width:40px; height:40px;">';
						}else{
							$photo = '<img src="uploads/defualt.png" style="width:40px; height:40px;">';
						}
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$photo.'</td>
							<td>'.$rowsvalues['adm_username'].'</td>
							<td>'.$rowsvalues['adm_fullname'].'</td>
							<td>'.$rowsvalues['adm_email'].'</td>
							<td>'.$rowsvalues['adm_phone'].'</td>
							<td class="center">'.get_status($rowsvalues['adm_status']).'</td>
							<td>';
								if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'edit' => '1'))) {
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/teacherlogin/edit_teacher.php?id='.$rowsvalues['adm_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'delete' => '1'))) {
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'teacherlogin.php?deleteid='.$rowsvalues['adm_id'].'\');"><i class="el el-trash"></i></a>';
								}
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