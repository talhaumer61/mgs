<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'view' => '1'))){
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
		if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'add' => '1'))){
		echo'
		<a href="#make_group" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
			<i class="fa fa-plus-square"></i> Make Campus Group
		</a>';
		}
		echo'
		<h2 class="panel-title"><i class="fa fa-list"></i> Campus Group List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th class="center" width="50">Sr.</th>
					<th>Logo</th>
					<th>Name</th>
					<th>Alpha Code</th>
					<th>Numeric Code</th>
					<th>Ordering</th>
					<th width="70px;" class="center">Status</th>
					<th width="100" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT group_id, group_status, group_ordering, group_name, group_code, group_code_numeric, group_logo
												FROM ".CAMPUS_GROUPS."
												WHERE group_id != '' AND is_deleted != '1'
												ORDER BY group_ordering ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					if($rowsvalues['group_logo']){
						$photo = "uploads/images/campus_groups/".$rowsvalues['group_logo']." ";
					}else{
						$photo = "uploads/logo.png";
					}
					echo'
					<tr>
						<td class="center">'.$srno.'</td>
						<td class="center" width=70><img src="'.$photo.'" style="height: 50px; width:50px;"></td>
						<td>'.$rowsvalues['group_name'].'</td>
						<td>'.$rowsvalues['group_code'].'</td>
						<td>'.$rowsvalues['group_code_numeric'].'</td>
						<td>'.$rowsvalues['group_ordering'].'</td>
						<td class="center">'.get_status($rowsvalues['group_status']).'</td>
						<td class="center">';
						if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'edit' => '1'))){
							echo'
							<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/campus_group/update.php?id='.$rowsvalues['group_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
						}
						if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'delete' => '1'))){
						echo'
							<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'campus_group	.php?deleteid='.$rowsvalues['group_id'].'\');"><i class="el el-trash"></i></a>';
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