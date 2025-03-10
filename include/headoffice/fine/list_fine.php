<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '70', 'view' => '1'))){ 
	
	$campus = (isset($_POST['campus']) ? $_POST['campus'] : '');
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
		</header>
		<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<div class="panel-body">
				<div class="row mb-lg">
					<div class="col-md-offset-3 col-md-6">
						<div class="form-group">
							<label class="control-label">Campus <span class="required">*</span></label>
							<select data-plugin-selectTwo data-width="100%" name="campus" id="campus" required title="Must Be Required" class="form-control populate">
								<option value="">Select</option>';
							$sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
																	FROM ".CAMPUS." c  
																	WHERE c.campus_id != '' AND campus_status = '1'
																	ORDER BY c.campus_name ASC");
								while($value_campus = mysqli_fetch_array($sqllmscampus)){
									if($value_campus['campus_id'] == $campus){
										echo'<option value="'.$value_campus['campus_id'].'" selected>'.$value_campus['campus_name'].'</option>';
									}else{
										echo'<option value="'.$value_campus['campus_id'].'">'.$value_campus['campus_name'].'</option>';
									}
								}
								echo'
							</select>
						</div>
					</div>
				</div>
				<center>
					<button type="submit" name="view_students" id="view_students" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
				</center>
			</div>
		</form>
	</section>';

	if(isset($_POST['view_students'])){
		$sqllms	= $dblms->querylms("SELECT s.id, s.status, s.amount, s.note,
								   c.cat_id, c.cat_name, c.cat_type,
								   st.std_id, st.std_name, st.std_fathername, st.std_regno,
								   se.session_id, se.session_name
								   FROM ".SCHOLARSHIP." s
								   INNER JOIN ".SCHOLARSHIP_CAT." c ON c.cat_id = s.id_cat
								   INNER JOIN ".STUDENTS." st ON st.std_id = s.id_std
								   INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
								   WHERE s.id_campus = ".cleanvars($campus)."
								   AND s.id_type = '3'
								   AND s.id_deleted = '0'
								   ORDER BY s.id ASC");
		$srno = 0;
		if(mysqli_num_rows($sqllms) > 0){
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-list"></i> Fine List</h2>
				</header>
				<div class="panel-body">
					<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
						<thead>
							<tr>
								<th style="text-align:center; width: 50px;">#</th>
								<th>Student Regno.</th>
								<th>Student</th>
								<th>Fine Name</th>
								<th>Fine Amount</th>
								<th>Session </th>
								<th>Note </th>
								<th width="70px;" style="text-align:center;">Status</th>
							</tr>
						</thead>
						<tbody>';
							while($rowsvalues = mysqli_fetch_array($sqllms)) {
								$srno++;
								echo '
								<tr>
									<td style="text-align:center;">'.$srno.'</td>
									<td>'.$rowsvalues['std_regno'].'</td>
									<td>'.$rowsvalues['std_name'].' '.$rowsvalues['std_fathername'].'</td>
									<td>'.$rowsvalues['cat_name'].'</td>
									<td>'.$rowsvalues['amount'].'</td>
									<td>'.$rowsvalues['session_name'].'</td>
									<td>'.$rowsvalues['note'].'</td>
									<td style="text-align:center;">'.get_status($rowsvalues['status']).'</td>
								</tr>';
							}
							echo '
						</tbody>
					</table>
				</div>
			</section>';
		}else{
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<div class="panel-body">
					<h3 class="center text-danger">No Record Found!</h3>
				</div>
			</div>
			';
		}
	}
}else{
	header("Location: dashboard.php");
}
?>