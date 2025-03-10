<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('72', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '72', 'view' => '1'))) {
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('72', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '72', 'add' => '1'))) {
				echo'<a href="#make_cat" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Fee Scholarship Category</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Scholarship Category List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th width="40" class="center">Sr.</th>
						<th>Name</th>
						<th>Detail</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT cat_id, cat_status, cat_type, cat_name, cat_detail
													FROM ".SCHOLARSHIP_CAT."
													WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' AND cat_type = '1'
													AND is_deleted = '0'
													ORDER BY cat_id DESC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['cat_name'].'</td>
							<td>'.$rowsvalues['cat_detail'].'</td>
							<td class="center">'.get_status($rowsvalues['cat_status']).'</td>
							<td class="text-center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('72', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '72', 'edit' => '1'))) { 
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/scholarship/cat_update.php?id='.$rowsvalues['cat_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('72', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '72', 'delete' => '1'))) { 
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'scholarship_category.php?deleteid_cat='.$rowsvalues['cat_id'].'\');"><i class="el el-trash"></i></a>';
								}
								echo'
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