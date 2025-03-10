<?php 
echo'
<div id="concession_scholarship" class="tab-pane">';
	$sqllms	= $dblms->querylms("SELECT s.id, s.status, s.percent, s.note,
									c.cat_id, c.cat_name, c.cat_type,
									st.std_id, st.std_name, st.std_fathername, st.std_regno,
									se.session_id, se.session_name
									FROM ".SCHOLARSHIP." s
									INNER JOIN ".SCHOLARSHIP_CAT." c ON c.cat_id = s.id_cat
									INNER JOIN ".STUDENTS." st ON st.std_id = s.id_std
									INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
									WHERE s.id_campus	= '".cleanvars($_id_campus)."'
									AND st.std_id		= '".cleanvars($_GET['id'])."'
									AND s.id_type IN (1,2)
									AND c.cat_type IN (1,2)
									ORDER BY s.id ASC");
	if(mysqli_num_rows($sqllms)>0){
		echo'
		<table class="table table-bordered table-striped table-condensed mb-none" id = "">
			<thead>
				<tr>
					<th width="40" class="center">Sr.</th>
					<th>Name</th>
					<th>Percentage</th>
					<th>Session </th>
					<th>Note </th>
					<th width="70" class="center">Status</th>
				</tr>
			</thead>
			<tbody>';
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)) {
					$srno++;
					echo'
					<tr>
						<td class="center">'.$srno.'</td>
						<td>'.$rowsvalues['cat_name'].'</td>
						<td>'.$rowsvalues['percent'].' %</td>
						<td>'.$rowsvalues['session_name'].'</td>
						<td>'.$rowsvalues['note'].'</td>
						<td class="center">'.get_status($rowsvalues['status']).'</td>
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