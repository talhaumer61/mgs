<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'view' => '1'))){ 
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'add' => '1'))){ 
				echo'<a href="#make_feecat" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Fee Category</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Fee Category List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th width="40" class="center">Sr.</th>
						<th width="70" class="center">Priority</th>
						<th>Fee Category</th>
						<th>Detail</th>
						<th width="50" class="center">For</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT c.cat_id, c.cat_status, c.cat_name, c.cat_detail, c.cat_ordering, c.cat_for
												FROM ".FEE_CATEGORY." c  
												WHERE c.is_deleted	= '0'
												ORDER BY c.cat_ordering ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td class="center">'.$rowsvalues['cat_ordering'].'</td>
							<td>'.$rowsvalues['cat_name'].'</td>
							<td>'.$rowsvalues['cat_detail'].'</td>
							<td class="center">'.get_feecat_for($rowsvalues['cat_for']).'</td>
							<td class="center">'.get_status($rowsvalues['cat_status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'updated' => '1'))){ 
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/fee/modal_feecat_update.php?id='.$rowsvalues['cat_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'deleted' => '1'))){ 
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'fee-category.php?deleteid='.$rowsvalues['cat_id'].'\');"><i class="el el-trash"></i></a>';
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