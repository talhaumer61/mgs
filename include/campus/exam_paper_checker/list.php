<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'view' => '1'))){
	if(($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1)){ 
		if (!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])) {
			$id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'].','.$_SESSION['userlogininfo']['SUBCAMPUSES'];
		} else {
			$id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
		}
	}else{		
		$id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
	}
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<a href="'.moduleName().'.php?view=add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Assign Checker</a>
			<h2 class="panel-title"><i class="fa fa-list"></i>  '.moduleName(false).' List</h2>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
				<thead>
					<tr>
						<th class="center" width="40">Sr.</th>';
						if($_SESSION['userlogininfo']['CAMPUSTYPE'] == 1){
							echo'<th>Campus</th>';
						}
						echo'
						<th>Exam</th>
						<th>Class</th>
						<th>Section</th>
						<th>Subject</th>
						<th>Paper Checker</th>
						<th>Quantity</th>
						<th>Hand Over date</th>
						<th>Submission date</th>
						<th width="120" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$condition = array(
											 'select'       =>  'pc.*, t.type_name, c.class_name, cs.section_name, sb.subject_name, cm.campus_name, e.emply_name'
											,'join'			=>	'INNER JOIN '.EXAM_TYPES.' t ON t.type_id = pc.id_exam
																 INNER JOIN '.CLASSES.' c ON c.class_id = pc.id_class
																 INNER JOIN '.CLASS_SECTIONS.' cs ON cs.section_id = pc.id_section
																 INNER JOIN '.CLASS_SUBJECTS.' sb ON sb.subject_id = pc.id_subject
																 INNER JOIN '.EMPLOYEES.' e ON e.emply_id = pc.id_emply
																 LEFT JOIN '.CAMPUS.' cm ON cm.campus_id = pc.id_campus' 
											,'where'        =>  array(
																		 'pc.is_deleted' 	=> 0
																		,'pc.id_session'  	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
															)
											,'search_by'	=>	' AND pc.id_campus IN ('.$id_campus.')'
											,'order_by'  	=>  'pc.id DESC'
											,'return_type'  =>  'all'
					);
					$EXAM_PAPER_CHECKER = $dblms->getRows(EXAM_PAPER_CHECKER.' pc', $condition);
					$srno = 0;
					foreach ($EXAM_PAPER_CHECKER as $key => $value) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>';
							if($_SESSION['userlogininfo']['CAMPUSTYPE'] == 1){
								echo'<td>'.$value['campus_name'].'</td>';
							}
							echo'
							<td>'.$value['type_name'].'</td>
							<td>'.$value['class_name'].'</td>
							<td>'.$value['section_name'].'</td>
							<td>'.$value['subject_name'].'</td>
							<td>'.$value['emply_name'].'</td>
							<td>'.$value['paper_qty'].'</td>
							<td>'.$value['date_handover'].'</td>
							<td>'.$value['date_submission'].'</td>
							<td class="center">
								<a href="'.moduleName().'.php?id='.$value['id'].'" class="btn btn-primary btn-xs" onclick=""><i class="glyphicon glyphicon-edit"></i> Edit</a>
								<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$value['id'].'\');"><i class="el el-trash"></i></a>
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