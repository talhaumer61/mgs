<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))) {
		
	$id_campus 		= ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])))? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
	$campus_flag 	= ((!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])))? 'col-md-3': 'col-md-4';

	
	$sqlCampLevel = $dblms->querylms("SELECT GROUP_CONCAT(l.level_classes) campus_classes
										FROM ".CAMPUS." c
										LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
										WHERE campus_id IN (".$id_campus.") ");
    $valCampLevel = mysqli_fetch_array($sqlCampLevel);

	$id_campus_classes 		= ((isset($valCampLevel['campus_classes']) && !empty($valCampLevel['campus_classes'])))? cleanvars($valCampLevel['campus_classes']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUSCLASSES']);

	$id_session 	= (!empty($_POST['id_session']))	? $_POST['id_session']	: '';
	$id_head 		= (!empty($_POST['id_head']))		? $_POST['id_head']	: '';

	$array 			= explode('|', $_POST['id_class']);
	$id_class 		= (!empty($array[0]))? $array[0]: '';
	$class_name 	= (!empty($array[1]))? $array[1]: '';

	$array 			= explode('|', $_POST['id_section']);
	$id_section 	= (!empty($array[0]))? $array[0]: '';
	$section_name 	= (!empty($array[1]))? $array[1]: '';

	$start_date 	= (!empty($_POST['start_date']))? $_POST['start_date']	: '';
	$end_date 		= (!empty($_POST['end_date']))? $_POST['end_date']		: '';

	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i> Students Fee Report</h2>
		</header>
		<form action="#" id="form" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
			<div class="panel-body">
				<div class="row form-group">
					<div class="col-md-4">
						<label class="control-label">Session <span class="required">*</span></label>
						<select data-plugin-selectTwo data-width="100%" name="id_session" id="id_session" required title="Must Be Required" class="form-control">
							<option value="">Select</option>';
							$sqllms	= $dblms->querylms("SELECT session_id, session_name
														FROM ".SESSIONS." 
														WHERE is_deleted = '0'");
							while($rowsvalues = mysqli_fetch_array($sqllms)){
								echo'<option value="'.$rowsvalues['session_id'].'" '.($rowsvalues['session_id'] == $id_session ? ' selected ' : '').($rowsvalues['session_name'] == $_SESSION['userlogininfo']['ACA_SESSION_NAME'] ? ' selected ' : '').'>'.$rowsvalues['session_name'].'</option>';		
							}
							echo'
						</select> 
					</div>
					<div class="col-md-8">
						<label class=" control-label">Date</label>
						<div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<input type="text" class="form-control" value="'.$start_date.'" name="start_date">
							<span class="input-group-addon">to</span>
							<input type="text" class="form-control" value="'.$end_date.'" name="end_date">
						</div>
					</div>
				</div>
				<div class="row form-group">';
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
									echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $id_campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
								}
								echo'
							</select>
						</div>';
					endif;
					echo'
					<div class="'.$campus_flag.'">
						<label class="control-label">Class </label>
						<select data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" onchange="get_section(this.value)" class="form-control">
							<option value="">Select</option>';
							$sqllms	= $dblms->querylms("SELECT class_id, class_name
														FROM ".CLASSES." 
														WHERE class_status = '1' 
														AND is_deleted = '0' 
														AND class_id IN (".$id_campus_classes.")
														ORDER BY class_id ASC");
							while($rowsvalues = mysqli_fetch_array($sqllms)){
								echo'<option value="'.$rowsvalues['class_id'].'|'.$rowsvalues['class_name'].'" '.($rowsvalues['class_id']==$id_class ? 'selected' : '').'>'.$rowsvalues['class_name'].'</option>';		
							}
							echo'
						</select> 
					</div>
					<div class="'.$campus_flag.'">
						<label class="control-label">Section </label>
						<select data-plugin-selectTwo data-width="100%" name="id_section" id="id_section" class="form-control populate">						
							<option value="">Select</option>';
							$sqllms	= $dblms->querylms("SELECT section_id, section_name
														FROM ".CLASS_SECTIONS."
														WHERE id_campus     = '".$id_campus."'
														AND id_class		= '".$id_class."'
														AND section_status	= '1'
														AND is_deleted		= '0'
														ORDER BY section_name ASC");
							while($rowsvalues = mysqli_fetch_array($sqllms)){
								echo'<option value="'.$rowsvalues['section_id'].'|'.$rowsvalues['section_name'].'" '.($rowsvalues['section_id'] == $id_section ? 'selected' : '').'>'.$rowsvalues['section_name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="'.$campus_flag.'">
						<label class="control-label">Fee Head <span class="text-danger">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="id_head" required>
							<option value="">Select</option>';
							$sqllmsfeeCat  	= $dblms->querylms("SELECT cat_id, cat_name
																FROM ".FEE_CATEGORY." 
																WHERE cat_status = '1' 
																AND is_deleted  = '0'");
							while($valuecat = mysqli_fetch_array($sqllmsfeeCat)) {
								echo '<option value="'.$valuecat['cat_id'].'" '.(($valuecat['cat_id'] == $id_head)? 'selected' : '').'>'.$valuecat['cat_name'].'</option>';
							}
							echo '
						</select>
					</div>		
				</div>
				<center>
					<button type="submit" name="view_report" id="view_report" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
				</center>
			</div>
		</form>
	</section>';

	if(isset($_POST['view_report'])){
		$sqlDate 		= "";
		$sqlCampus 		= "";
		$sqlClass 		= "";
		$sqlSection 	= "";
		if (!empty($start_date) && !empty($end_date)) {
			$sqlDate 	= "AND (f.paid_date BETWEEN '".date('Y-m-d' , strtotime(cleanvars($start_date)))."' AND '".date('Y-m-d' , strtotime(cleanvars($end_date)))."') ";
			$sqlDate 	= "AND (f.issue_date BETWEEN '".date('Y-m-d' , strtotime(cleanvars($start_date)))."' AND '".date('Y-m-d' , strtotime(cleanvars($end_date)))."') ";
		}
		$sqlCampus 		=  (!empty($id_campus))		? "AND f.id_campus 		= '$id_campus'" 	: "";
		$sqlClass 		=  (!empty($id_class))		? "AND f.id_class 		= '$id_class'" 		: "";
		$sqlSection 	=  (!empty($id_section))	? "AND f.id_section 	= '$id_section'" 	: "";
		echo'
		<section class="panel panel-featured panel-featured-primary appear-animation fadeInRight appear-animation-visible" data-appear-animation="fadeInRight" data-appear-animation-delay="100" style="animation-delay: 100ms;">
			<header class="panel-heading">
				<h2 class="panel-title"> <i class="fa fa-pie-chart"></i> Report View</h2>
			</header>
			<div class="panel-body">';
				$sqCampus	= $dblms->querylms("SELECT campus_id, campus_name, campus_address, campus_phone, campus_email
												FROM ".CAMPUS."
												WHERE is_deleted = '0'
												AND campus_id = $id_campus LIMIT 1");
				$valCampus = mysqli_fetch_array($sqCampus);

				$sqllmsfee	= $dblms->querylms("SELECT f.id, f.challan_no, f.total_amount, f.paid_amount, f.id_collector, f.concession, fp.id, fp.amount, s.std_name, s.std_phone, s.std_whatsapp, s.std_rollno, s.std_regno, c.class_name, cs.section_name, se.session_name, a.adm_fullname, s.std_fathername, s.std_fathername, s.std_nic, s.std_dob, s.std_admissiondate
												FROM ".FEES." f
												INNER JOIN 	".FEE_PARTICULARS." fp 	ON fp.id_fee = f.id
												LEFT JOIN 	".STUDENTS." 	s 		ON s.std_id = f.id_std
												INNER JOIN 	".CLASSES." 	c 		ON c.class_id = f.id_class
												INNER JOIN ".CLASS_SECTIONS." cs 	ON cs.section_id = f.id_section 
												INNER JOIN 	".SESSIONS." 	se 		ON se.session_id = f.id_session
												LEFT JOIN 	".ADMINS." 		a 		ON a.adm_id = f.id_collector
												LEFT JOIN 	".CAMPUS." 		ss 		ON ss.campus_id = f.id_campus
												WHERE f.is_deleted	= '0'														
												AND fp.id_cat = '".$id_head."'
												$sqlCampus
												$sqlClass
												$sqlSection
												$sqlDate
												GROUP BY f.id_std
												ORDER BY f.id_class ASC");

				if(mysqli_num_rows($sqllmsfee) > 0){
					echo'
					<div id="printResult">
							<div id="header" style="display: none;">
								<table class="table">
									<thead>
										<tr>
											<th colspan="3" class="center align-middle"><img src="uploads/images/campus/'.((!empty($valCampus['campus_logo']))? $valCampus['campus_logo']: $_SESSION['userlogininfo']['LOGINCAMPUSLOGO']).'" width="130"></th>
											<th colspan="8">
												<center>
													<h2>'.$valCampus['campus_name'].'</h2>
													<h5>'.$valCampus['campus_address'].'</h5>
												</center>
												<br>
												<span style="padding-right: 30%;">Contact No: '.$valCampus['campus_phone'].'</span>
												<span>Email: '.$valCampus['campus_email'].'</span>
											</th>
											<th colspan="3" class="center"><h4>StudentFeeDetail</h4></th>
										</tr>
									</thead>
								</table>
							</div>
						<style>
						.ttable th, td {
							border: 1px solid grey;
							padding: 5px;
						}
						</style>
						<div class="table-responsive">
							<table class="ttable" style="width: 100%;">
								<thead>
									<tr>
										<th class="center" width="50">Sr#</th>
										<th class="center" width="150">Reg.</th>
										<th class="center" width="50">Roll</th>
										<th class="center" width="150">Class</th>
										<th>Student</th>
										<th>Father</th>
										<th>Student CNIC</th>
										<th class="center">DOB</th>
										<th class="center">Date of Admission</th>
										<th>Contact</th>
										<th>WhatsApp</th>
										<th class="center">Scheduled</th>
										<th class="center">Discount</th>
										<th class="center">Final Dues</th>
									</tr>
								</thead>
								<tbody>';
									$srno = 0;
									$totalFeePart 		= 0;
									$totalConcession 	= 0;
									$totaldiscount 		= 0;
									while($value_fee = mysqli_fetch_array($sqllmsfee)) {
										$array = explode(',',$value_fee['concession']);
										$concession  		= $array[0];
										$fee_id_head 	 	= $array[1];
										$srno++;
										$totalFeePart 		+= $value_fee['amount'];
										$totalConcession 	+= $concession;
										$totaldiscount 		+= abs($value_fee['amount'] - $concession);
										echo'
										<tr>
											<td class="center">'.$srno.'</td>
											<td class="center">'.$value_fee['std_regno'].'</td>
											<td class="center">'.$value_fee['std_rollno'].'</td>
											<td class="center">'.$value_fee['class_name'].'('.$value_fee['section_name'].')</td>
											<td>'.$value_fee['std_name'].'</td>
											<td>'.$value_fee['std_fathername'].'</td>
											<td>'.$value_fee['std_nic'].'</td>
											<td class="center">'.$value_fee['std_dob'].'</td>
											<td class="center">'.$value_fee['std_admissiondate'].'</td>
											<td>'.$value_fee['std_phone'].'</td>
											<td>'.$value_fee['std_whatsapp'].'</td>
											<td class="center">'.number_format($value_fee['amount']).'</td>
											<td class="center">'.number_format($concession).'</td>
											<td class="center">'.number_format(abs($value_fee['amount'] - $concession)).'</td>
										</tr>';
									}
									echo'
									<tr>
										<th colspan="10" class="text-right">Total</th>
										<th class="center ">'.number_format($totalFeePart).'</th>
										<th class="center">'.number_format($totalConcession).'</th>
										<th class="center">'.number_format($totaldiscount).'</th>
									</tr>
								</tbody>
							</table>
							<span id="printfooter" style="display: none;">
								<span>This report is generated by <b>'.$_SESSION['userlogininfo']['LOGINNAME'].'</b> on <b>'.date('d M,Y').'</b></span><br><br>
								<span style="padding-right: 30%;"><b>Printed by</b> __________________________</span>
								<span><b>Principal</b> __________________________</span>
							<span>
						</div>
					</div>
					<div class="mt-lg on-screen">
						<button onclick="print_report(\'printResult\')" id="printBtn" class="pull-right btn btn-primary"><i class="glyphicon glyphicon-print"></i></button>
					</div>';
				}else{
					echo '<h2 class="center">No Record Found</h2>';
				}
				echo'
			</div>
		</section>';
	}
}else{
    header("Location: dashboard.php");
}
?>