<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'view' => '1'))){ 
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'add' => '1'))){ 
				echo'<a href="campuses.php?view=add" class="btn btn-primary btn-xs pull-right">
					<i class="fa fa-plus-square"></i> Make Campus 
				</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i> Campus List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center">#</th>
						<th>Logo</th>
						<th>Reg no#</th>
						<th>Name</th>
						<th>Brand</th>
						<th>Group</th>
						<th>Level</th>
						<th>For</th>
						<th>Zone</th>
						<th>Code</th>
						<th>Head</th>
						<th>E-mail</th>
						<th>Phone</th>
						<th width="100" class="center">Type</th>
						<th width="70p" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT c.campus_id, c.id_type, c.campus_status, c.campus_regno, c.campus_name, c.campus_code, c.campus_address, c.campus_email, c.campus_phone, c.campus_head, c.is_tvi, c.campus_website, c.campus_logo, c.id_level, c.campus_for, b.brand_name, cg.group_name, cl.level_name, z.zone_name 
												FROM ".CAMPUS." c  
												INNER JOIN ".BRANDS." b ON b.brand_id = c.id_brand
												INNER JOIN ".CAMPUS_GROUPS." cg ON cg.group_id = c.id_group
												INNER JOIN ".CAMPUS_LEVELS." cl ON cl.level_id = c.id_level
												LEFT JOIN ".ZONES." z ON z.zone_id = c.id_zone
												WHERE c.is_deleted = '0'
												ORDER BY c.campus_id ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						if($rowsvalues['campus_logo']) {
							$logo = "uploads/images/campus/".$rowsvalues['campus_logo'];
						}else{
							$logo = "uploads/logo.png";
						}
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td class="center"><img src="'.$logo.'" style="width:40px; height:40px;"></td>
							<td>'.$rowsvalues['campus_regno'].'</td>
							<td>'.$rowsvalues['campus_name'].'</td>
							<td>'.$rowsvalues['brand_name'].'</td>
							<td>'.$rowsvalues['group_name'].'</td>
							<td>'.$rowsvalues['level_name'].'</td>
							<td>'.get_campusfor($rowsvalues['campus_for']).'</td>
							<td>'.$rowsvalues['zone_name'].'</td>
							<td>'.$rowsvalues['campus_code'].'</td>
							<td>'.$rowsvalues['campus_head'].'</td>
							<td>'.$rowsvalues['campus_email'].'</td>
							<td>'.$rowsvalues['campus_phone'].'</td>
							<td class="center">'.get_campus_type($rowsvalues['id_type']).'</td>
							<td class="center">'.get_status($rowsvalues['campus_status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'edit' => '1'))){ 
								echo'<a href="campuses.php?id='.$rowsvalues['campus_id'].'" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i></a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'delete' => '1'))){ 
								echo'
									<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'campuses.php?deleteid='.$rowsvalues['campus_id'].'\');"><i class="el el-trash"></i></a>';
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