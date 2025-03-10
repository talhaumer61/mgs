<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '70', 'view' => '1'))){ 
	$id_campus = (!empty($_GET['id_campus']) ? cleanvars($_GET['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']));
	
	if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
		echo'
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
			</header>
			<form action="#" id="form" enctype="multipart/form-data" method="get" accept-charset="utf-8">
				<div class="panel-body">
					<div class="row">';
						if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
							echo'
							<div class="col-md-6 col-md-offset-3">
								<div class="form-group mb-md">
									<label class="control-label">Sub Campus</label>
									<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus">
										<option value="">Select</option>';
										$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																			FROM ".CAMPUS." 
																			WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																			AND campus_status	= '1'
																			AND is_deleted		= '0'
																			ORDER BY campus_id ASC");
										while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
											echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $id_campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
										}
										echo'
									</select>
								</div>
							</div>';
						}
						echo'
					</div>
					<center>
						<button type="submit" class="btn btn-primary"><i class="fa fa fa-search"></i> Search</button>
					</center>
				</div>
			</form>
		</section>';
	}

	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '70', 'add' => '1'))){ 
				echo'
				<a href="feesetup.php?view=add" class="btn btn-primary btn-xs pull-right mr-xs"><i class="fa fa-plus-square"></i> Make Fee Structure</a>
				<a href="feesetup.php?view=percentage_add" class="btn btn-info btn-xs pull-right mr-xs"><i class="fa fa-plus-square"></i> Increase Structure</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Feesetup List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th style="text-align:center;">#</th>
						<th>Increasing Date</th>
						<th>Session</th>
						<th>Class</th>
						<th>Section</th>
						<th width="70px;" style="text-align:center;">Status</th>
						<th width="100" style="text-align:center;">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT f.id, f.status, f.dated, f.id_class, f.id_section, f.id_session,c.class_name, cs.section_name, s.session_name, s.session_startdate
												FROM ".FEESETUP." f				   
												INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
												INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section							 
												INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session
												WHERE f.is_deleted != '1'
												AND f.id_campus = '".$id_campus."' 
												ORDER BY f.dated ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td style="text-align:center;">'.$srno.'</td>
							<td>'.$rowsvalues['dated'].'</td>
							<td>'.$rowsvalues['session_name'].'</td>
							<td>'.$rowsvalues['class_name'].'</td>
							<td>'.$rowsvalues['section_name'].'</td>
							<td style="text-align:center;">'.get_status($rowsvalues['status']).'</td>
							<td style="text-align:center;">';
							if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '70', 'edit' => '1'))){ 
							echo'
								<a href="feesetup.php?id='.$rowsvalues['id'].'" class="btn btn-primary btn-xs");"><i class="glyphicon glyphicon-edit"></i></a>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '70', 'delete' => '1'))){ 
							echo'
								<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'feesetup.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
							}
							$sqllmsForPrint		= $dblms->querylms("SELECT f.id, f.status, f.dated, f.id_class, f.id_section, f.id_session,c.class_name, cs.section_name, s.session_name
																	FROM ".FEESETUP." f				   
																	INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
																	INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section							 
																	INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session
																	WHERE f.is_deleted  = '0'
																	AND f.id_class 		= '".$rowsvalues['id_class']."'
																	AND f.id_section 	= '".$rowsvalues['id_section']."'
																	AND f.id_session   != '".$rowsvalues['id_session']."'
																	AND f.dated   	   < '".$rowsvalues['dated']."'
																	AND f.id_campus 	= '".$id_campus."' 
																	ORDER BY f.id DESC LIMIT 1");
							$RowForPrint = mysqli_fetch_array($sqllmsForPrint);
							if(mysqli_num_rows($sqllmsForPrint)){ 
								echo'
								<a href="feeSetupReportPrint.php?prv_session='.$RowForPrint['id_session'].'&cur_session='.$rowsvalues['id_session'].'&id_class='.$rowsvalues['id_class'].'&id_section='.$rowsvalues['id_section'].'&class_name='.$rowsvalues['class_name'].'&section_name='.$rowsvalues['section_name'].'&session_name='.$rowsvalues['session_name'].'&session_startdate='.$rowsvalues['session_startdate'].'" target="_blank" class="btn btn-info btn-xs");"><i class="glyphicon glyphicon-print"></i></a>';
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
}
else{
	header("Location: dashboard.php");
}
?>