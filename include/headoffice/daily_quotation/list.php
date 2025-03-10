<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '86', 'view' => '1'))){ 
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			'.(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) ? '
				<a href="#make_quotation" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
					<i class="fa fa-plus-square"></i> Make Quotation
				</a>': '').'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Daily Quotation List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center" width="20">Sr.</th>
						<th width="80">Type</th>
						<th>Quotation</th>
						<th width="100" class="center">Status</th>
						<th width="100" class="center">Date</th>
						<th width="50" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT d.quote_id, d.quote_type, d.quote_msg, d.date
												FROM ".DAILY_QUOTATION." d
												WHERE d.is_deleted = '0' 
												ORDER BY d.quote_id DESC");
					$srno = 0;
					while($val = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.get_Quotation($val['quote_type']).'</td>
							<td>'.$val['quote_msg'].'</td>
							<td class="center">'.(date("Y-m-d") == $val['date'] ? get_QuoteStatus(1): get_QuoteStatus(0)).'</td>
							<td class="center">'.$val['date'].'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) ||  Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '86', 'edit' => '1'))){ 
								echo '
									<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/daily_quotation/update.php?id='.$val['quote_id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '86', 'delete' => '1'))){ 
								echo'
									<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'daily_quotation.php?deleteid='.$val['quote_id'].'\');"><i class="el el-trash"></i></a>';
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