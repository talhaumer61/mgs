<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('27', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '27', 'view' => '1'))) {
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('27', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '27', 'add' => '1'))) {
				echo'<a href="#make_earning" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Income Voucher</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i> Income List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center">No.</th>
						<th>Title</th>
						<th>Amount</th>
						<th class="center">Method</th>
						<th>Head</th>
						<th>Voucher ID</th>
						<th>Bill Number</th>
						<th>Date</th>
						<th>Due Date</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT t.trans_id, t.trans_title, t.trans_amount, t.voucher_no, t.bill_number, t.trans_method, t.dated, t.due_date,
												h.head_name
												FROM ".ACCOUNT_TRANS." t
												INNER JOIN ".ACCOUNT_HEADS." h ON h.head_id = t.id_head
												WHERE t.trans_type = '1' AND t.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
												ORDER BY t.trans_id DESC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo'
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['trans_title'].'</td>
							<td>Rs. '.$rowsvalues['trans_amount'].'</td>
							<td class="center">'.get_paymethod($rowsvalues['trans_method']).'</td>
							<td>'.$rowsvalues['head_name'].'</td>
							<td>'.$rowsvalues['voucher_no'].'</td>
							<td>'.$rowsvalues['bill_number'].'</td>
							<td>'.date('d M, Y' , strtotime(cleanvars($rowsvalues['dated']))).'</td>
							<td>'.($rowsvalues['due_date'] != '0000-00-00' ? date('d M, Y' , strtotime(cleanvars($rowsvalues['due_date']))) : '').'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('27', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '27', 'edit' => '1'))) { 
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/balancesheet/earning_update.php?id='.$rowsvalues['trans_id'].'\');"><i class="glyphicon glyphicon-edit"></i> </a>';
								}
								// if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('27', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '27', 'delete' => '1'))) { 
								// 	echo'<a href="#" class="btn btn-danger btn-xs ml-xs"><i class="el el-trash"></i></a>';
								// }
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