<?php 
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i> Campus Facility Category List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th class="center" width="70px;">Sr #</th>
					<th>Category</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT cat_id, cat_status, cat_ordering, cat_name
												FROM ".FACILITY_CATS."
												WHERE cat_id != '' AND cat_status = '1' AND is_deleted != '1'
												ORDER BY cat_ordering ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td class="center">'.$srno.'</td>
						<td>'.$rowsvalues['cat_name'].'</td>
					</tr>';
				}
				echo '
			</tbody>
		</table>
	</div>
</section>';
?>