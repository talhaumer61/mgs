<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))) { 
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))) { 
				echo'<a href="#make_challan_description" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Challan Description</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Challan Description List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th class="center" width="20">Sr.</th>
						<th >Description</th>
						<th width="120">Fine Type</th>
						<th width="120">Late Fine/In 5 Days</th>
						<th width="120">After 5 Days</th>
						<th width="50" class="center">Status</th>
						<th width="70" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllmsChallanDes = array ( 
											'select' 	=> '
																  cd.chl_desc_id 
																, cd.chl_desc_status 
																, cd.chl_desc 
																, cd.late_fee_type 
																, c.class_name 
															',
											'join' 		=> 'LEFT JOIN '.CLASSES.' AS c ON c.class_id = cd.id_class',
											'where' 	=> array( 
																	  'cd.is_deleted'    		=> '0'
																	, 'cd.chl_desc_status'    	=> '1'
																	, 'cd.id_campus'			=>	cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])
																),
											'return_type' 	=> 'all' 
										); 
					$rowsChallanDes  = $dblms->getRows(CHALLAN_DESCRIPTION.' AS cd', $sqllmsChallanDes);
					$srno = 0;
					foreach($rowsChallanDes as $key => $val):
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.html_entity_decode(html_entity_decode($val['chl_desc'])).'</td>';
							$late_fee_type_comma_sep = explode(',',$val['late_fee_type']);
							echo'
							<td>'.get_latefee_type($late_fee_type_comma_sep[0]).'</td>
							<td class="text-right">'.number_format($late_fee_type_comma_sep[1]).'</td>
							<td class="text-right">'.number_format($late_fee_type_comma_sep[2]).'</td>
							<td>'.get_admstatus($val['chl_desc_status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))) {  
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-sm late_feeEdit" onclick="showAjaxModalZoom(\'include/modals/challan_description/update.php?id='.$val['chl_desc_id'].'\');"><i class="glyphicon glyphicon-edit"></i></a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'delete' => '1'))) {  
									echo'<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'challan_description.php?deleteid='.$val['chl_desc_id'].'\');"><i class="el el-trash"></i></a>';
								}
								echo'
							</td>
						</tr>';
					endforeach;
					echo '
				</tbody>
			</table>
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>