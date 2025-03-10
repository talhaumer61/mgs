<?php 
if($_SESSION['userlogininfo']['LOGINAFOR'] == 1){
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<a href="#add" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
			<i class="fa fa-plus-square"></i> Make Campus Login</a>
			<h2 class="panel-title"><i class="fa fa-list"></i> Campus Login List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
				<thead>
					<tr>
						<th width="40" class="center">Sr.</th>
						<th width="40">Photo</th>
						<th>Username</th>
						<th>Full Name</th>
						<th>Email</th>
						<th>Phone</th> 
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT adm_id, adm_status, adm_username, adm_fullname, adm_email, adm_phone, adm_photo
												FROM ".ADMINS."
												WHERE adm_logintype = '2'
												AND is_deleted	= '0' 
												ORDER BY adm_username ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						if($rowsvalues['adm_photo']){
							$photo = '<img src="uploads/images/admins/'.$rowsvalues['adm_photo'].'" style="width:40px; height:40px;">';
						}else{
							$sqllmsemp	= $dblms->querylms("SELECT emply_photo
																FROM ".EMPLOYEES."  
																WHERE id_loginid = '".$rowsvalues['adm_id']."' AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'");
							$value_emp = mysqli_fetch_array($sqllmsemp);
							if(isset($value_emp['emply_photo'])) {
								$photo = '<img src="uploads/images/employees/'.$value_emp['emply_photo'].'" style="width:40px; height:40px;">';
							} else {
								$photo = 'No Image';
							}
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
							<td>
								<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/campuslogin/edit_campuslogin.php?id='.$rowsvalues['adm_id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>

								<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/campuslogin/change_pass_campus.php?id='.$rowsvalues['adm_id'].'\');"><i class="glyphicon glyphicon-lock"></i></a>

								<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'campuslogin.php?deleteid='.$rowsvalues['adm_id'].'\');"><i class="el el-trash"></i></a>
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