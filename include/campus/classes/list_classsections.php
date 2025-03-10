<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('6', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '6', 'view' => '1'))) {
	
	$id_campus 	= (!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? $_SESSION['userlogininfo']['LOGINCAMPUS'].','.$_SESSION['userlogininfo']['SUBCAMPUSES'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
	if (!empty($_GET['id_class'])) {
		$classSql = ' AND sec.id_class = '.cleanvars($_GET['id_class']).' ';
	} else {
		$classSql = '';
	}

	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';		
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('6', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '6', 'add' => '1'))) {
				echo'<a href="#make_section" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Section</a>';
			}
			echo'
			<h2 class="panel-title"><i class="fa fa-list"></i>  Section List</h2>
		</header>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<form id="searchForm" method="GET">
						<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_class" onchange="classChange();" name="id_class">
							<option value="">Select Class</option>';
							$sqlCampLevel = $dblms->querylms("SELECT GROUP_CONCAT(l.level_classes) campus_classes
																FROM ".CAMPUS." c
																LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
																WHERE campus_id IN (".$id_campus.") ");
							$valCampLevel = mysqli_fetch_array($sqlCampLevel);
							$sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
																FROM ".CLASSES."
																WHERE class_status = '1'
																AND class_id IN (".$valCampLevel['campus_classes'].")
																ORDER BY class_id ASC");
							while($valuecls = mysqli_fetch_array($sqllmscls)) {
								echo '<option value="'.$valuecls['class_id'].'" '.(($valuecls['class_id'] == $id_class)? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
							}
							echo '
						</select>
					</form>
				</div>
				<div class="col-md-4"></div>
			</div>
		</div>
		<div class="panel-body">
			<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
				<thead>
					<tr>
						<th width="40" class="center">Sr.</th>
						<th width="130">Section Name</th>
						<th width="130">Section Strength</th>
						<th width="150">Class Name</th>
						<th>Campus</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					$sqllms	= $dblms->querylms("SELECT sec.section_id, sec.section_name, sec.section_strength, sec.id_class, sec.section_status, sec.id_campus,
												c.class_id, c.class_name, cm.campus_name
												FROM ".CLASS_SECTIONS." sec
												INNER JOIN ".CLASSES." c ON c.class_id = sec.id_class
												INNER JOIN ".CAMPUS." cm ON cm.campus_id = sec.id_campus
												WHERE sec.is_deleted	= '0'
												AND sec.id_campus IN (".$id_campus.")
												$classSql
												GROUP BY sec.section_id
												ORDER BY sec.section_name ASC");
					$srno = 0;
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['section_name'].'</td>
							<td>'.$rowsvalues['section_strength'].'</td>
							<td>'.$rowsvalues['class_name'].'</td>
							<td>'.$rowsvalues['campus_name'].'</td>
							<td class="center">'.get_status($rowsvalues['section_status']).'</td>
							<td class="center">';
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('6', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '6', 'edit' => '1'))) { 
									echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/class/modal_classsections_update.php?id='.$rowsvalues['section_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
								}
								if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('6', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '6', 'delete' => '1'))) { 
									echo'<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'classsections.php?deleteid='.$rowsvalues['section_id'].'&id_campus='.$rowsvalues['id_campus'].'\');"><i class="el el-trash"></i></a>';
								}
								echo'
							</td>
						</tr>';
					}
					echo'
				</tbody>
			</table>
			<div id="printResult" hidden>
				<table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
					<thead>
						<tr>
							<th width="40" class="center">Sr.</th>
							<th width="130">Section Name</th>
							<th width="130">Section Strength</th>
							<th width="150">Class Name</th>
							<th>Campus</th>
						</tr>
					</thead>
					<tbody>';
						$sqllms	= $dblms->querylms("SELECT sec.section_id, sec.section_name, sec.section_strength, sec.id_class, sec.section_status, sec.id_campus,
													c.class_id, c.class_name, cm.campus_name, count(DISTINCT s.std_id) AS std_count
													FROM ".CLASS_SECTIONS." sec
													INNER JOIN ".CLASSES." c ON c.class_id = sec.id_class
													INNER JOIN ".CAMPUS." cm ON cm.campus_id = sec.id_campus
													INNER JOIN ".STUDENTS." s ON s.id_section = sec.section_id
													WHERE sec.is_deleted	= '0'
													AND sec.id_campus IN (".$id_campus.")
													$classSql
													GROUP BY sec.section_id
													ORDER BY sec.section_name ASC");
						$srno = 0;
						while($rowsvalues = mysqli_fetch_array($sqllms)) {
							$srno++;
							echo '
							<tr>
								<td class="center">'.$srno.'</td>
								<td>'.$rowsvalues['section_name'].'</td>
								<td>'.$rowsvalues['std_count'].'</td>
								<td>'.$rowsvalues['class_name'].'</td>
								<td>'.$rowsvalues['campus_name'].'</td>
							</tr>';
						}
						echo'
					</tbody>
				</table>
			</div>
			<div class="text-right mt-lg on-screen">
				<button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary"><i class="glyphicon glyphicon-print"></i></button>
			</div>
		</div>
	</section>
	<div id="result"></div>';
}else{
	header("Location: dashboard.php");
}
?>
<script type="text/javascript">
	function classChange() {
        $('#searchForm').submit();
    }
	function print_report(printResult) {
		var printContents = document.getElementById(printResult).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		window.print();
		document.body.innerHTML = originalContents;
	}
</script>