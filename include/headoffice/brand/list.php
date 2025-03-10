<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'view' => '1'))){
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
		if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'add' => '1'))){
		echo'
		<a href="#make_brand" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
			<i class="fa fa-plus-square"></i> Make Brand
		</a>';
		}
		echo'
		<h2 class="panel-title"><i class="fa fa-list"></i> Brand List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th class="center" width="50">Sr #</th>
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
				$sqllms	= $dblms->querylms("SELECT brand_id, brand_status, brand_ordering, brand_name, brand_code, brand_code_numeric, brand_logo
												FROM ".BRANDS."
												WHERE brand_id != '' AND is_deleted != '1'
												ORDER BY brand_ordering ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					if($rowsvalues['brand_logo']){
						$photo = "uploads/images/brands/".$rowsvalues['brand_logo']." ";
					}else{
						$photo = "uploads/logo.png";
					}
					echo'
					<tr>
						<td class="center">'.$srno.'</td>
						<td class="center" width=70><img src="'.$photo.'" style="height: 50px; width:50px;"></td>
						<td>'.$rowsvalues['brand_name'].'</td>
						<td>'.$rowsvalues['brand_code'].'</td>
						<td>'.$rowsvalues['brand_code_numeric'].'</td>
						<td>'.$rowsvalues['brand_ordering'].'</td>
						<td class="center">'.get_status($rowsvalues['brand_status']).'</td>
						<td class="center">';
						if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'edit' => '1'))){
							echo'
							<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/brand/update.php?id='.$rowsvalues['brand_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
						}
						if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'delete' => '1'))){
						echo'
							<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'brand.php?deleteid='.$rowsvalues['brand_id'].'\');"><i class="el el-trash"></i></a>';
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