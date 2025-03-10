<?php
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();
include_once("include/header.php");

if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))) {
	if(isset($_POST['status'])){$status = $_POST['status'];}
	if(isset($_POST['start_date'])){$start_date = $_POST['start_date'];}else{$start_date = date('d-m-Y');}
	if(isset($_POST['end_date'])){$end_date = $_POST['end_date'];}else{$end_date = date('d-m-Y');}
	if(isset($_POST['end_date'])){$id_level = $_POST['end_date'];}else{$end_date = date('d-m-Y');}
	$id_campus 		= ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])))? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
	$campus_flag 	= ((!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])))? 'col-md-4': 'col-md-6';

	if (isset($_POST['view_students'])) {

		if (isset($_POST['id_class']) && !empty($_POST['id_class'])) {
			$array 			= explode("|",$_POST['id_class']);
			$id_class		= $array[0];
			$sqlForIdClass	= 'AND f.id_class = '.$id_class.'';
		} else {
			$id_class		= '';
			$sqlForIdClass	= '';
		}
		if (isset($_POST['id_section']) && !empty($_POST['id_section'])) {
			$id_section		= cleanvars($_POST['id_section']);
			$sqlForIdSection= 'AND f.id_section = '.$id_section.'';
		} else {
			$id_section		= '';
			$sqlForIdSection= '';
		}
	}
	echo'
	<title>Fee Report | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Fee Report</h2>
		</header>
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list"></i>  Select Report Status</h2>
			</header>
			<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<div class="panel-body">
					<div class="row mb-lg">';
						if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
							echo'
							<div class="'.$campus_flag.'">
								<label class="control-label">Sub Campus</label>
								<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" onchange="get_class(this.value)"> 
									<option value="">Select</option>';
									$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																		FROM ".CAMPUS." 
																		WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																		AND campus_status	= '1'
																		AND is_deleted		= '0'
																		ORDER BY campus_id ASC");
									while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
										echo '<option value="'.$valSubCampus['campus_id'].'" '.(($valSubCampus['campus_id'] == $id_campus) ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
									}
									echo'
								</select>
							</div>';
						endif;
						echo '
						<div class="'.$campus_flag.'">
							<label class="control-label">Class </label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value);">
								<option value="">Select</option>';
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
						</div>
						<div class="'.$campus_flag.'">
							<label class="control-label">Section</label>
							<select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section" title="Must Be Required">';
								$sqlSection	= $dblms->querylms("SELECT section_id, section_name 
																FROM ".CLASS_SECTIONS."
																WHERE id_class      = '".$id_class."'
																AND section_status  = '1'
																AND is_deleted      = '0'
																AND id_campus IN (".$id_campus.")
																ORDER BY section_name ASC");
								if(mysqli_num_rows($sqlSection) > 0){
									echo'<option value="">Select</option>';
									while($valSection = mysqli_fetch_array($sqlSection)) {
										echo '<option value="'.$valSection['section_id'].'" '.($valSection['section_id'] == $id_section ? 'selected' : '').'>'.$valSection['section_name'].'</option>';
									}
								}else{
									echo '<option value="">No Record Found</option>';
								}
								echo'
							</select>
						</div>
					</div>
					<div class="row mb-lg">
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Status <span class="required">*</span></label>
								<select data-plugin-selectTwo data-width="100%" id="status" name="status" required title="Must Be Required" class="form-control populate">
									<option value="">Select</option>';
									foreach($payments as $payment){
										if($payment['id'] != 3){
											echo'<option value="'.$payment['id'].'" '.($payment['id'] == $status ? 'selected' : '').'>'.$payment['name'].'</option>';
										}
									}
									echo'
								</select>
							</div>
						</div>
						<div class="col-md-8">				
							<div class="form-group">
								<label class=" control-label">Date <span class="required" aria-required="true">*</span></label>
								<div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
									<span class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</span>
									<input type="text" class="form-control" required title="Must Be Required" value="'.$start_date.'" name="start_date">
									<span class="input-group-addon">to</span>
									<input type="text" class="form-control" required title="Must Be Required" value="'.$end_date.'" name="end_date">
								</div>
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
			$sqlDate = "";
			if($status == '1' || $status == '4'){
				$sqlDate = "AND (f.paid_date BETWEEN '".date('Y-m-d' , strtotime(cleanvars($start_date)))."' AND '".date('Y-m-d' , strtotime(cleanvars($end_date)))."') ";
			}
			if($status == '2' || $status == '3'){
				$sqlDate = "AND (f.issue_date BETWEEN '".date('Y-m-d' , strtotime(cleanvars($start_date)))."' AND '".date('Y-m-d' , strtotime(cleanvars($end_date)))."') ";
			}
			echo'
			<section class="panel panel-featured panel-featured-primary appear-animation fadeInRight appear-animation-visible" data-appear-animation="fadeInRight" data-appear-animation-delay="100" style="animation-delay: 100ms;">
			<header class="panel-heading">
				<h2 class="panel-title"> <i class="fa fa-pie-chart"></i> '.get_payments1($status).' Fee Report</h2>
			</header>
				<div class="panel-body">';
					$sqllmsfee	= $dblms->querylms("SELECT f.challan_no, f.issue_date, f.paid_date, f.total_amount, f.paid_amount, f.id_collector, s.std_name, s.std_fathername, s.std_phone, s.std_whatsapp, s.std_rollno, s.std_regno, c.class_name, se.session_name, a.adm_fullname, cs.section_name
														FROM ".FEES." f
														INNER JOIN ".STUDENTS." s ON s.std_id = f.id_std AND s.std_status = '1' AND s.is_deleted = '0' 
														INNER JOIN ".CLASSES." c ON c.class_id = f.id_class
														INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section
														INNER JOIN ".SESSIONS." se ON se.session_id = f.id_session
														LEFT JOIN ".ADMINS." a ON a.adm_id = f.id_collector
														WHERE f.status		= '".$status."'
														AND f.is_deleted	= '0'
														AND f.id_campus = '".$id_campus."'
														$sqlDate
														$sqlForIdClass
														$sqlForIdSection
														ORDER BY f.id_class ASC");
					if(mysqli_num_rows($sqllmsfee) > 0){
						echo'
						<div id="printResult">
							<style type="text/css">  
								@media all {
									.page-break	{ display: none; }
								}
								@media print {
									.page-break	{ display: block; page-break-before: always; }
									@page { 
										size: A4 landscape;
										margin: 4mm 4mm 4mm 4mm; 
									}
									#printPageButton {
										display: none;
									}
								}
							</style>
							<div class="invoice mt-md">
								<table class="table table-bordered table-striped table-condensed mb-none">
									<thead>
										<tr class="h5 text-dark">
											<th width="90">Challan</th>
											<th width="90">Issue Date</th>
											<th width="80">Total</th>
											<th width="90">Paid</th>
											<th width="90">Paid Date</th>
											<th width="150">Name (Father)</th>
											<th width="30" class="center">Roll</th>
											<th width="150">Class (Section)</th>
											<th width="110">Phone</th>
											<th width="110">Whatsapp</th>
											<th>Collector</th>
										</tr>
									</thead>
									<tbody>';
										$srno = 0;
										$total_amount = 0;
										$total_paid = 0;
										while($value_fee = mysqli_fetch_array($sqllmsfee)) {
											$srno++;
											$total_amount = $total_amount + $value_fee['total_amount'];
											$total_paid = $total_paid + $value_fee['paid_amount'];
											echo'
											<tr>
												<td>'.$value_fee['challan_no'].'</td>
												<td>'.date('d M,Y', strtotime($value_fee['issue_date'])).'</td>
												<td>'.number_format($value_fee['total_amount']).'</td>
												<td>'.number_format($value_fee['paid_amount']).'</td>
												<td>'.($value_fee['paid_date'] == '0000-00-00' ? 'NUll' : date('d M,Y', strtotime($value_fee['paid_date']))).'</td>
												<td>'.$value_fee['std_name'].' ('.$value_fee['std_fathername'].')</td>
												<td class="center">'.$value_fee['std_rollno'].'</td>
												<td>'.$value_fee['class_name'].' ('.$value_fee['section_name'].')</td>
												<td>'.$value_fee['std_phone'].'</td>
												<td>'.$value_fee['std_whatsapp'].'</td>
												<td>'.$value_fee['adm_fullname'].'</td>
											</tr>';
										}
										echo'
									</tbody>
								</table>
								<div class="invoice-summary">
									<div class="row">
										<div class="col-sm-4 col-sm-offset-8">
											<table class="table table-bordered table-striped table-condensed mb-none">
												<tbody>
													<tr class="b-top-none">
														<td colspan="2">'.get_payments1($status).' Amount</td>
														<td class="text-left">Rs. '.(($status == '2' || $status == '3') ? $total_amount : $total_paid).'</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>		
							</div>
						</div>
						<div class="text-right mr-lg on-screen">
							<button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary"><i class="glyphicon glyphicon-print"></i></button>
						</div>';
					}else{
						echo '<h2 class="center">No Record Found</h2>';
					}
					echo'
				</div>
			</section>';
		}
		echo'
	</section>';
	?>
	<script type="text/javascript">
		function get_class(id_campus) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_class.php",  
				data: "id_campus="+id_campus,  
				success: function(msg){  
					$("#id_class").html(msg); 
					$("#loading").html(''); 
				}
			});  
		}
		function get_section(id_class) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
			var id_campus = $("#id_campus").val(); 
			$.ajax({  
				type: "POST", 
				url: "include/ajax/get_section.php",  
				data: {
							'id_campus'   : id_campus
						, 'id_class' 	: id_class
					},
				success: function(msg){  
					$("#id_section").html(msg); 
					$("#loading").html(''); 
				}
			});  
		}
		function print_report(printResult) {
			var printContents = document.getElementById(printResult).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		}
		jQuery(document).ready(function($) {	
			var datatable = $('#table_export').dataTable({
				bAutoWidth : false,
				ordering: false,
			});
		});
	</script>
	<?php
}else{
    header("Location: dashboard.php");
}
include_once("include/footer.php");
?>