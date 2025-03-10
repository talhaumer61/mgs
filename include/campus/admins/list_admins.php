<?php
if($_SESSION['userlogininfo']['CAMPUSTYPE'] == 1) { 
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<a href="admins.php?view=add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make User Login</a>
		<h2 class="panel-title"><i class="fa fa-list"></i>  User Login List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th width="40" class="center">Sr.</th>
					<th width="40">Photo</th>
					<th>Type</th>
					<th>Username</th>
					<th>FullName</th>
					<th>Email</th>
					<th>Phone</th> 
					<th width="70" class="center">Status</th>
					<th width="100" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT a.adm_id, a.adm_status, a.adm_type, a.adm_username, a.adm_fullname, a.adm_email, a.adm_phone, a.adm_photo, a.adm_photo
												FROM ".ADMINS." a  
												WHERE a.id_campus	= '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
												AND a.adm_logintype	= '2'
												AND a.adm_type		= '6'
												AND a.adm_id	   != '".$_SESSION['userlogininfo']['LOGINIDA']."'
												AND a.is_deleted	= '0'
												ORDER BY a.adm_username ASC
											");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$photo = (!empty($rowsvalues['adm_photo']) ? 'uploads/images/admins/'.$rowsvalues['adm_photo'].'' : 'uploads/defualt.png');
					$srno++;
					echo'
					<tr>
						<td class="center">'.$srno.'</td>
						<td><img src="'.$photo.'" width="40" height="40"></td>
						<td>'.get_admtypes($rowsvalues['adm_type']).'</td>
						<td>'.$rowsvalues['adm_username'].'</td>
						<td>'.$rowsvalues['adm_fullname'].'</td>
						<td>'.$rowsvalues['adm_email'].'</td>
						<td>'.$rowsvalues['adm_phone'].'</td>
						<td class="center">'.get_status($rowsvalues['adm_status']).'</td>
						<td class="center">
							<a href="admins.php?id='.$rowsvalues['adm_id'].'" class="btn btn-primary btn-xs""><i class="glyphicon glyphicon-edit"></i> Edit</a>
							<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'admins.php?deleteid='.$rowsvalues['adm_id'].'\');"><i class="el el-trash"></i></a>
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