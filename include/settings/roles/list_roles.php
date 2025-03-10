<?php 
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<a href="#make_hostel" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Add Roles</a>
		<h2 class="panel-title"><i class="fa fa-list"></i>  Roles List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
			<thead>
				<tr>
					<th class="center" width="50">ID</th>
					<th>Role Name</th>
					<th>Role Type</th>
					<th>Role For</th>
					<th width="70" class="center">Status</th>
					<th width="100" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT r.role_id, r.role_name, r.role_type, r.id_type, r.role_status  
											FROM ".ROLES." r  
											ORDER BY r.role_id ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo'
					<tr>
						<td class="center">'.$rowsvalues['role_id'].'</td>
						<td>'.$rowsvalues['role_name'].'</td>
						<td>'.get_roletypes($rowsvalues['role_type']).'</td>
						<td>'.get_rolefor($rowsvalues['id_type']).'</td>
						<td class="center">'.get_status($rowsvalues['role_status']).'</td>
						<td class="center">
							<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/roles/modal_roles_update.php?id='.$rowsvalues['role_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>
							<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'roles.php?deleteid='.$rowsvalues['role_id'].'\');"><i class="el el-trash"></i></a>
						</td>
					</tr>';
				}
				echo'
			</tbody>
		</table>
	</div>
</section>';