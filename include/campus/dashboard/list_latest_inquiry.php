<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '49', 'view' => '1')))
{ 
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i> Latest Inquiries</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none">';
			$sqllms	= $dblms->querylms("SELECT q.id, q.form_no, q.status, q.name, q.fathername, q.address, q.note, q.date_added, q.source, c.class_name
										FROM ".ADMISSIONS_INQUIRY." q  
										INNER JOIN ".CLASSES." c ON c.class_id = q.id_class
										WHERE q.is_deleted != '1'
										AND q.id_campus IN (".$id_campus.")  
										ORDER BY q.id DESC LIMIT 10");
			$srno = 0;
			if(mysqli_num_rows($sqllms) > 0 ){
				echo'
				<thead>
					<tr>
						<th style="text-align:center;">No.</th>
						<th>Form no.</th>
						<th>Name</th>
						<th>Class</th>
						<th>Dated</th>
						<th width="70px;" style="text-align:center;">Status</th>
						<th width="70" style="text-align:center;">Options</th>
					</tr>
				</thead>
				<tbody>';
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td style="text-align:center;">'.$srno.'</td>
							<td>'.$rowsvalues['form_no'].'</td>
							<td><p class="mb-none">'.$rowsvalues['name'].'</p><span>'.$rowsvalues['fathername'].'</span> </td>
							<td>'.$rowsvalues['class_name'].'</td>
							<td>'.date("d M Y", strtotime($rowsvalues['date_added'])).'</td>
							<td class="text-center">'.get_status($rowsvalues['status']).'</td>
							<td class="text-center">
							';
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINIDA'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || ($_SESSION['userlogininfo']['LOGINIDA'] == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'add' => '1'))){
								$StdCheck	= $dblms->querylms("SELECT std_id
														FROM ".STUDENTS."
														WHERE admission_formno = '".cleanvars($rowsvalues['form_no'])."'
														AND id_campus IN (".$id_campus.") LIMIT 1");
								if(mysqli_num_rows($StdCheck) < 1){
									echo'<a href="students.php?inquiry='.$rowsvalues['id'].'" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-plus-sign"></i> </a>';
								}else{
									$StdValues = mysqli_fetch_array($StdCheck);
									echo'<a href="students.php?id='.$StdValues['std_id'].'" class="btn btn-info btn-xs"><i class="fa fa-user-circle-o"></i> </a>';
								}
							}
							echo'
							</td>
						</tr>';
					}
					echo '
				</tbody>';
			}else{
				echo'
				<tr>
					<th class="center" style="padding:3rem; color: red;">No Latest Inquiries Exists</th>
				</tr>';
			}
			echo'
			</table>
		</div>
	</section>';
}
?>