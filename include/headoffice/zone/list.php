<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'view' => '1'))){
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'add' => '1'))){
				echo'
				<a href="#make_zone" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
					<i class="fa fa-plus-square"></i> Make Zone
				</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Zone List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center" width="40">Sr.</th>
						<th>Name</th>
						<th>Code</th>
						<th>Ordering</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT z.zone_id, z.zone_status, z.zone_ordering, z.zone_name, z.zone_code
													FROM ".ZONES." z
													WHERE z.zone_id != '' AND z.is_deleted != '1'
													ORDER BY z.zone_id ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['zone_name'].'</td>
							<td>'.$rowsvalues['zone_code'].'</td>
							<td>'.$rowsvalues['zone_ordering'].'</td>
							<td class="center">'.get_status($rowsvalues['zone_status']).'</td>
							<td class="center">';
							if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'edit' => '1'))){
								echo'
								<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/zone/update.php?id='.$rowsvalues['zone_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'delete' => '1'))){
							echo'
								<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'class.php?deleteid='.$rowsvalues['zone_id'].'\');"><i class="el el-trash"></i></a>';
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
}
else{
	header("Location: dashboard.php");
}
?>