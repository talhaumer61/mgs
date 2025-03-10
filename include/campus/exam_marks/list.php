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
			<a href="'.moduleName().'.php?view=add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Add Marks</a>
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
						<th width="70" class="center">Publish</th>
						<th width="120" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$condition = array(
											 'select'       =>  'em.*, t.type_name, c.class_name, cs.section_name, sb.subject_name, cm.campus_name, em.is_publish'
											,'join'			=>	'INNER JOIN '.EXAM_TYPES.' t ON t.type_id = em.id_exam AND t.id_campus = '.($_SESSION['userlogininfo']['CAMPUSTYPE']  == 1 ? $_SESSION['userlogininfo']['LOGINCAMPUS'] : $_SESSION['userlogininfo']['PARENTCAMPUS']).' AND t.is_deleted = 0
																 INNER JOIN '.CLASSES.' c ON c.class_id = em.id_class
																 INNER JOIN '.CLASS_SECTIONS.' cs ON cs.section_id = em.id_section
																 INNER JOIN '.CLASS_SUBJECTS.' sb ON sb.subject_id = em.id_subject
																 LEFT JOIN '.CAMPUS.' cm ON cm.campus_id = em.id_campus' 
											,'where'        =>  array(
																		 'em.is_deleted' 	=> 0
																		,'em.id_session'  	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
															)
											,'search_by'	=>	' AND em.id_campus IN ('.$id_campus.')'
											,'order_by'  	=>  'em.id DESC'
											,'return_type'  =>  'all'
					);
					$EXAM_ATTENDANCE = $dblms->getRows(EXAM_MARKS.' em', $condition);
					$srno = 0;
					foreach ($EXAM_ATTENDANCE as $key => $value) {
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
							<td class="center">'.get_notification($value['is_publish']).'</td>
							<td class="center">';
							if($_SESSION['userlogininfo']['CAMPUSTYPE']==1||$value['is_publish']==2){
								echo'
								<a href="'.moduleName().'.php?id='.$value['id'].'" class="btn btn-primary btn-xs" onclick=""><i class="glyphicon glyphicon-edit"></i> Edit</a>
								<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\''.moduleName().'.php?deleteid='.$value['id'].'\');"><i class="el el-trash"></i></a>';
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