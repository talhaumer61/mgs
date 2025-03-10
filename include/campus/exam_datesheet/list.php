<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'view' => '1'))){
	$exam 	= (isset($_POST['id_exam'])		?$_POST['id_exam'] 	:"");
	$class 	= (isset($_POST['id_class'])	?$_POST['id_class'] :"");
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i> Filters</h2>
		</header>
		<div class="panel-body">
			<form method="POST">
				<div class="row mt-sm">
					<div class="col-md-4 col-md-offset-2">
						<label class="control-label">Exam</label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_exam">
							<option value="">Select</option>';
							// EXAM TYPES
							$condition = array(
													'select'       =>  'type_id, type_status, type_name'
													,'where'        =>  array(
																				 'type_status'	=> 1
																				,'is_deleted'  	=> 0
																				,'id_campus'	=> ($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1 ? $_SESSION['userlogininfo']['LOGINCAMPUS'] : $_SESSION['userlogininfo']['PARENTCAMPUS'])
																	)
													,'return_type'  =>  'all'
							);
							$EXAM_TYPES = $dblms->getRows(EXAM_TYPES, $condition);
							foreach ($EXAM_TYPES AS $key => $val) {
								echo'
								<option value="'.$val['type_id'].'" '.($val['type_id'] == $exam?'selected':'').'>'.$val['type_name'].'</option>';
							}
							echo '
						</select>
					</div>
					<div class="col-md-4">
						<label class="control-label">Class</label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_class">
							<option value="">Select</option>';
							// CLASSES
							$condition = array(
													 'select'       => ' DISTINCT c.class_id, c.class_status, c.class_name'
													,'join'			=> 'LEFT JOIN '.STUDENTS.' AS s ON c.class_id = s.id_class'
													,'where'        =>  array(
																				 'c.class_status'	=> 1
																				,'c.is_deleted'  	=> 0
																	)
													,'search_by'  	=>  ' AND c.class_id IN ('.cleanvars($_SESSION['userlogininfo']['LOGINCAMPUSCLASSES']).')'
													,'return_type'  =>  'all'
							);
							$CLASSES = $dblms->getRows(CLASSES.' AS c', $condition, $Sql);
							foreach ($CLASSES AS $key => $val) {
								echo'
								<option value="'.$val['class_id'].'" '.($val['class_id'] == $class?'selected':'').'>'.$val['class_name'].'</option>';
							}
							echo '
						</select>
					</div>
					<div class="row">
						<div class="col-md-12 text-center mt-sm">
							<button type="submit" name="view_details" class="mr-xs btn btn-primary">Get Record</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1)){ 
				echo '<a href="exam_datesheet.php?view=add" class="btn btn-primary btn-xs pull-right mr-sm"><i class="fa fa-plus-square"></i> Make Datesheet</a>';
			}
			echo '
			<h2 class="panel-title"><i class="fa fa-list"></i>  Datesheets List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
				<thead>
					<tr>
						<th class="center" width="40">Sr.</th>
						<th>Exam</th>
						<th>Class</th>
						<th width="70" class="center">Publish</th>
						<th width="120" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$condition = array(
											 'select'       =>  'd.id, d.status, t.type_name, c.class_name'
											,'join'       	=>  'INNER JOIN '.EXAM_TYPES.' AS t	ON t.type_id = d.id_exam
																 INNER JOIN '.CLASSES.' AS c ON c.class_id = d.id_class'
											,'where'        =>  array(
																		 'd.is_deleted' 	=> 0
																		,'d.id_session'  	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
															)
											,'search_by'	=>	' AND (d.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR d.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
											,'order_by'  	=>  'd.id ASC'
											,'return_type'  =>  'all'
					);
					if (!empty($exam)) {
						$condition['where']['d.id_exam'] = cleanvars($exam);
					}
					if (!empty($class)) {
						$condition['where']['d.id_class'] = cleanvars($class);
					}
					$DATESHEET 	= $dblms->getRows(DATESHEET.' AS d', $condition);
					foreach ($DATESHEET AS $key => $val) {
						echo '
						<tr>
							<td class="center">'.($key+1).'</td>
							<td>'.$val['type_name'].'</td>
							<td>'.$val['class_name'].'</td>
							<td class="center">'.get_notification($val['status']).'</td>
							<td class="center">
								<a href="exam_datesheet_print.php?routine='.$val['id'].'" target="_blank" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-print"></i></a>';
								if(($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1)){ 
									echo'<a href="exam_datesheet.php?id='.$val['id'].'" class="btn btn-primary btn-xs ml-xs" onclick=""><i class="glyphicon glyphicon-edit"></i></a>';
									echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'exam_datesheet.php?deleteid_datesheet='.$val['id'].'\');"><i class="el el-trash"></i></a>';
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