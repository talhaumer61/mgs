<?php
if(($_SESSION['userlogininfo']['LOGINAFOR']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '45', 'view' => '1'))){
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<a href="#make_parentlogin" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
		<i class="fa fa-plus-square"></i> Make AD / DE Login</a>
		<h2 class="panel-title"><i class="fa fa-list"></i> AD / DE Login List</h2>
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
				$sqllms	= $dblms->querylms("SELECT a.adm_id, a.adm_status, a.adm_username, a.adm_fullname, a.adm_email, a.adm_phone, a.adm_photo, e.emply_photo
											FROM ".ADMINS." a
											INNER JOIN ".EMPLOYEES." e ON e.id_loginid = a.adm_id
											WHERE a.adm_type	= '6'
											AND a.adm_logintype	= '6'
											AND a.is_deleted	= '0'
											AND a.id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
											ORDER BY a.adm_id DESC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)){
					$srno++;
					if(!empty($rowsvalues['adm_photo'])){
						$photo = '<img src="uploads/images/admins/'.$rowsvalues['adm_photo'].'" style="width:40px; height:40px;">';
					}elseif(!empty($rowsvalues['emply_photo'])){
						$photo = '<img src="uploads/images/employees/'.$rowsvalues['emply_photo'].'" style="width:40px; height:40px;">';
					}else{
						$photo = '<img src="uploads/admin_image/default.jpg" style="width:40px; height:40px;">';
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
							<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/addeLogin/edit.php?id='.$rowsvalues['adm_id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>
							<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/addeLogin/change_pass.php?id='.$rowsvalues['adm_id'].'\');"><i class="glyphicon glyphicon-lock"></i></a>
							<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'addeLogin.php?deleteid='.$rowsvalues['adm_id'].'\');"><i class="el el-trash"></i></a>
						</td>
					</tr>';
				}
				echo '
			</tbody>
		</table>
	</div>
</section>';
}else{
	header("Location: dashboard.php");
}
?>