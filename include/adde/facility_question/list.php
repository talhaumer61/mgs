<?php 
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i> Inspection Statement List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th class="center" width="70px;">Sr #</th>
					<th>Question</th>
					<th>Category</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT q.question_id, q.question_name, c.cat_name
												FROM ".FACILITY_QESTIONS." q
												INNER JOIN ".FACILITY_CATS." c ON c.cat_id = q.id_cat
												WHERE q.question_id != '' AND q.question_status = '1' AND q.is_deleted != '1'
												ORDER BY q.question_ordering ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo '
					<tr>
						<td class="center">'.$srno.'</td>
						<td>'.$rowsvalues['question_name'].'</td>
						<td>'.$rowsvalues['cat_name'].'</td>
					</tr>';
				}
				echo '
			</tbody>
		</table>
	</div>
</section>';
?>