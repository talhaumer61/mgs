<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('69', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '69', 'view' => '1'))) {
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Fee Category List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th width="40" class="center">Sr.</th>
						<th>Fee Category</th>
						<th>Detail</th>
						<th width="70" class="center">Status</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT cat_id, cat_status, cat_name, cat_detail  
												FROM ".FEE_CATEGORY."  
												WHERE cat_id != '' AND cat_status = '1'
												AND is_deleted != '1'
												ORDER BY cat_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['cat_name'].'</td>
							<td>'.$rowsvalues['cat_detail'].'</td>
							<td class="center">'.get_status($rowsvalues['cat_status']).'</td>
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