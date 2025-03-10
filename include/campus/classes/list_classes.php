<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('47', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'view' => '1'))) {
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Class List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
				<thead>
					<tr>
						<th style="text-align:center;">No.</th>
						<th>Class Name</th>
						<th>Class Numeric</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT c.class_id, c.class_name, c.class_code, c.class_status  
											FROM ".CLASSES." c  
											WHERE c.class_id != '' AND c.class_id IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
											AND is_deleted != '1'
											ORDER BY c.class_id ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td style="text-align:center;">'.$srno.'</td>
							<td>'.$rowsvalues['class_name'].'</td>
							<td>'.$rowsvalues['class_code'].'</td>
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