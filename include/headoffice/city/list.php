<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'view' => '1'))){
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'add' => '1'))){
				echo'
				<a href="#add" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
					<i class="fa fa-plus-square"></i> Add Area / City
				</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i> Area List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center" width="40">Sr.</th>
						<th>Name</th>
						<th>Code</th>
						<th>Ordering</th>
						<th>District</th>
						<th>Province</th>
						<th width="70px;" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT c.city_id, c.city_status, c.city_ordering, c.city_name, c.city_code, d.dist_name, d.dist_code, z.zone_name, z.zone_code, p.prov_name, p.prov_code  
												FROM ".TEHSIL_CITIES." c
												INNER JOIN ".DISTRICTS." d ON d.dist_id = c.id_dist
												INNER JOIN ".ZONES." z ON z.zone_id = c.id_zone
												INNER JOIN ".PROVINCES." p ON p.prov_id = c.id_prov
												WHERE c.city_id != '' AND c.is_deleted != '1'
												ORDER BY c.city_id ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['city_name'].'</td>
							<td>'.$rowsvalues['city_code'].'</td>
							<td>'.$rowsvalues['city_ordering'].'</td>
							<td>'.$rowsvalues['dist_name'].'</td>
							<td>'.$rowsvalues['prov_name'].'</td>
							<td class="center">'.get_status($rowsvalues['city_status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'edit' => '1'))){
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/city/update.php?id='.$rowsvalues['city_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'delete' => '1'))){
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'city.php?deleteid='.$rowsvalues['city_id'].'\');"><i class="el el-trash"></i></a>';
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