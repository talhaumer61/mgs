<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '83', 'view' => '1'))){
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
		if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '83', 'add' => '1'))){
		echo'
		<a href="#make_facility_question" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
			<i class="fa fa-plus-square"></i> Make Inspection Statement
		</a>';
		}
		echo'
		<h2 class="panel-title"><i class="fa fa-list"></i> Inspection Statement List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th class="center" width="50">Sr.</th>
					<th>Title</th>
					<th>Ordering</th>
					<th>Category</th>
					<th width="70px;" class="center">Status</th>
					<th width="100" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sqllms	= $dblms->querylms("SELECT q.question_id, q.question_status, q.question_ordering, q.question_name, c.cat_name
												FROM ".FACILITY_QESTIONS." q
												INNER JOIN ".FACILITY_CATS." c ON c.cat_id = q.id_cat
												WHERE q.question_id != '' AND q.is_deleted != '1'
												ORDER BY q.question_id ASC");
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)){
					$srno++;
					echo'
					<tr>
						<td class="center">'.$srno.'</td>
						<td>'.$rowsvalues['question_name'].'</td>
						<td>'.$rowsvalues['question_ordering'].'</td>
						<td>'.$rowsvalues['cat_name'].'</td>
						<td class="center">'.get_status($rowsvalues['question_status']).'</td>
						<td class="center">';
						if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '83', 'edit' => '1'))){
							echo'
							<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/facility_question/update.php?id='.$rowsvalues['question_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
						}
						if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '83', 'delete' => '1'))){
						echo'
							<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'class.php?deleteid='.$rowsvalues['question_id'].'\');"><i class="el el-trash"></i></a>';
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