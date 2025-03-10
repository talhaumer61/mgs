<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'view' => '1'))){
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'add' => '1'))){
				echo'<a href="#make_particular" class="modal-with-move-anim btn btn-primary btn-xs pull-right">';
			}
			echo'
			<i class="fa fa-plus-square"></i> Make Particular</a>
			<h2 class="panel-title"><i class="fa fa-list"></i>  Royalty List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center" width="40">Sr.</th>
						<th>Title</th>
						<th>For</th>
						<th>Type</th>
						<th width="70px" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT part_id, part_status, part_name, part_for, part_type
												FROM ".ROYALTY_PARTICULARS." 
												WHERE part_id != '' AND is_deleted != '1'  
												ORDER BY part_name ");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)){
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['part_name'].'</td>
							<td>'.get_royaltyFor($rowsvalues['part_for']).'</td>
							<td>'.get_royaltyType($rowsvalues['part_type']).'</td>
							<td class="center">'.get_status($rowsvalues['part_status']).'</td>
							<td class="text-center">';
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'edit' => '1'))){
								echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/royalty/particulars/update.php?id='.$rowsvalues['part_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'delete' => '1'))){
								echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'message.php?deleteid='.$rowsvalues['part_id'].'\');"><i class="el el-trash"></i></a>';
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