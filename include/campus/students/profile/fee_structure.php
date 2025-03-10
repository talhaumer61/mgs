<?php
echo'
<div id="fee_structure" class="tab-pane">';
	$sqllms	= $dblms->querylms("SELECT f.id, f.status, f.dated, f.id_class, f.id_section, f.id_session, fd.*, c.cat_id, c.cat_name
								FROM ".FEESETUP." f
								INNER JOIN ".FEESETUPDETAIL." fd ON fd.id_setup = f.id
								INNER JOIN ".FEE_CATEGORY." c ON c.cat_id = fd.id_cat
								INNER JOIN ".STUDENTS." st ON st.id_class = f.id_class
								WHERE f.is_deleted	= '0'
								AND f.id_campus		= '".cleanvars($id_campus)."'
								AND f.id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
								AND st.std_id		= '".cleanvars($_GET['id'])."'
								GROUP By fd.id_cat
								ORDER BY fd.id_cat ASC");
	if(mysqli_num_rows($sqllms)>0){
		echo'
		<table class="table table-bordered table-striped table-condensed mb-none" id = "">
			<thead>
				<tr>
					<th width="40" class="center">Sr.</th>
					<th>Name</th>
					<th>Duration</th>
					<th>Type</th>
					<th>Amount</th>
				</tr>
			</thead>
			<tbody>';
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)){
					$srno++;
					echo'
					<tr>
						<td class="center">'.$srno.'</td>
						<td>'.$rowsvalues['cat_name'].'</td>
						<td>'.$rowsvalues['duration'].'</td>
						<td>'.$rowsvalues['type'].'</td>
						<td>'.$rowsvalues['amount'].'</td>
					</tr>';
				}
				echo'
			</tbody>
		</table>';
	}else{
		echo'<h2 class="text text-center text-danger mt-lg">No Record Found!</h2>';
	}
	echo'
</div>';
?>