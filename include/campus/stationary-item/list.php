<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('34', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '34', 'view' => '1'))) {
	$id_campus = (!empty($_GET['id_campus']) ? cleanvars($_GET['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));
	
	if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
		echo'
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
			</header>
			<form action="#" id="form" enctype="multipart/form-data" method="get" accept-charset="utf-8">
				<div class="panel-body">
					<div class="row">';
						if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
							echo'
							<div class="col-md-6 col-md-offset-3">
								<div class="form-group mb-md">
									<label class="control-label">Sub Campus</label>
									<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus">
										<option value="">Select</option>';
										$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																			FROM ".CAMPUS." 
																			WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																			AND campus_status	= '1'
																			AND is_deleted		= '0'
																			ORDER BY campus_id ASC");
										while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
											echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $id_campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
										}
										echo'
									</select>
								</div>
							</div>';
						}
						echo'
					</div>
					<center>
						<button type="submit" class="btn btn-primary"><i class="fa fa fa-search"></i> Search</button>
					</center>
				</div>
			</form>
		</section>';
	}

	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
		if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('34', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '34', 'add' => '1'))) {
				echo'<a href="#make_item" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Stationary Item</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Stationary Items List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th width="40" class="text-center">Sr.</th>
						<th>Item</th>
						<th>Code</th>
						<th>Category</th>
						<th>School Price</th>
						<th>Student Price</th>
						<th>Detail</th>
						<th width="70px;" class="text-center">Status</th>
						<th width="100" class="text-center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT i.item_id, i.item_status, i.id_cat, i.item_name, i.item_code, i.school_price,
													i.std_price, i.item_detail, c.cat_name
													FROM ".INVENTORY_ITEMS." i  
													INNER JOIN ".INVENTORY_CATEGORY." c ON c.cat_id = i.id_cat 
													WHERE i.is_deleted = '0'
													AND i.id_campus = '".cleanvars($id_campus)."'
													ORDER BY i.item_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo'
						<tr>
							<td class="text-center">'.$srno.'</td>
							<td>'.$rowsvalues['item_name'].'</td>
							<td>'.$rowsvalues['item_code'].'</td>
							<td>'.$rowsvalues['cat_name'].'</td>
							<td>'.$rowsvalues['school_price'].'</td>
							<td>'.$rowsvalues['std_price'].'</td>
							<td>'.$rowsvalues['item_detail'].'</td>
							<td class="text-center">'.get_status($rowsvalues['item_status']).'</td>
							<td class="text-center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('34', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '34', 'edit' => '1'))) { 
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/stationary-item/update.php?id='.$rowsvalues['item_id'].'\');"><i class="glyphicon glyphicon-edit"></i> </a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('34', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '34', 'delete' => '1'))) { 
									echo'<a class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'stationary_item.php?deleteid='.$rowsvalues['item_id'].'\');"><i class="el el-trash"></i></a>';
								}
							echo'
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