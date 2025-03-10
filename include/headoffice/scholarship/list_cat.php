<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '72', 'view' => '1'))){ 
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i>  Scholarship Category List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th style="text-align:center; width: 50px;">#</th>
					<th>Name</th>
					<th>Detail</th>
					<th width="70px;" style="text-align:center;">Status</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT cat_id, cat_status, cat_type, cat_name, cat_detail
												FROM ".SCHOLARSHIP_CAT."
												WHERE cat_type = '1'
												ORDER BY cat_id ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td style="text-align:center;">'.$srno.'</td>
						<td>'.$rowsvalues['cat_name'].'</td>
						<td>'.$rowsvalues['cat_detail'].'</td>
						<td style="text-align:center;">'.get_status($rowsvalues['cat_status']).'</td>
					</tr>';
				}
				echo '
			</tbody>
		</table>
	</div>
</section>';
}
else{
	header("Location: dashboard.php");
}
?>