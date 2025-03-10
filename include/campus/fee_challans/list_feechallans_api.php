<style>
.card{
	padding: 20px 20px 10px 20px;
	font-size: 30px;
	border-radius:10px;
	margin-left: 4%;
	margin-right: 4%;
	}
.val{
	font-size: 20px;
	margin-left: 18%;
	}
.count{
	font-size: 14px;
	margin-right: 18%;
	}
.span{
	font-size:14px;
	}
</style>
<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))) { 
	$filters = '';
	$search_text 	= (!empty($_GET['search_text']))? cleanvars($_GET['search_text'])	:'';
	$id_class 		= (!empty($_GET['id_class']))	? cleanvars($_GET['id_class'])		:'';
	$id_section 	= (!empty($_GET['id_section']))	? cleanvars($_GET['id_section'])	:'';
	$issue_date 	= (!empty($_GET['issue_date']))	? cleanvars($_GET['issue_date'])	:'';
	$due_date 		= (!empty($_GET['due_date']))	? cleanvars($_GET['due_date'])		:'';
	$pay_date 		= (!empty($_GET['pay_date']))	? cleanvars($_GET['pay_date'])		:'';
	$rem_amt 		= (!empty($_GET['rem_amt']))	? cleanvars($_GET['rem_amt'])		:'';	
	$id_campus 		= ((isset($_GET['id_campus']) && !empty($_GET['id_campus'])))? cleanvars($_GET['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
	$campus_flag 	= ((!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])))? 'col-md-3': 'col-md-4';


// PAID AMOUNT
$sqllmspaid	= $dblms->querylms("SELECT SUM(f.paid_amount) as paid
								FROM ".FEES_LIVE." f
								WHERE f.status IN (1,4) 
								AND f.id_type IN (1,2)
								AND f.id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
								AND f.is_deleted	= '0'
							");
$value_paid = mysqli_fetch_array($sqllmspaid);
if($value_paid['paid']){$paid = $value_paid['paid'];}else{$paid = 0;}

// PENDING AMOUNT
$sqllmspending	= $dblms->querylms("SELECT SUM(f.paid_amount) as paid,
									SUM(
										(case when f.due_date > '".date('Y-m-d')."' then f.total_amount
										else f.total_amount + '".LATEFEE."'
										end)
									) as total
									FROM ".FEES_LIVE." f
									WHERE f.status IN (2,4)
									AND f.id_type IN (1,2)
									AND f.is_deleted	= '0'
									AND f.id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
								");
$value_pending = mysqli_fetch_array($sqllmspending);
$TotalPending = $value_pending['total'] - $value_pending['paid'];
if($TotalPending){$pending = $TotalPending;}else{$pending = 0;}

// TOTAL AMOUNT
$totalreceivable = $pending + $paid;

echo '
<div class="row mt-none mb-md">
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-info card mb-sm">
		<i class="fa fa-money" aria-hidden="true"></i> Total Amount
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($totalreceivable).'</p>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-success card mb-sm">
		<i class="fa fa-star" aria-hidden="true"></i> Total Paid
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($paid).'</p>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-warning card mb-sm">
		<i class="fa fa-refresh" aria-hidden="true"></i> Total Pending
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($pending).'</p>
	</div>
</div>
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
		$campus_type_check = (($_SESSION['userlogininfo']['CAMPUSTYPE'] == 1))? 1: 0;
		if($campus_type_check){
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))) {  
				echo'<a href="#print_challan" class="modal-with-move-anim ml-sm btn btn-primary btn-xs pull-right"><i class="fa fa-print"></i> Print Challan</a>';
			}
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))) {  
				echo'<a href="fee_challans.php?view=bulk" class="btn btn-primary ml-sm btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Class Challan</a>';
			}
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))) {  
				echo'<a href="#make_challan" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Single Challan</a>';
			}
		}		
		echo'
		<h2 class="panel-title"><i class="fa fa-list"></i>  Challans List</h2>
	</header>
	<div class="panel-body">
		<form action="#" method="GET" autocomplete="off">
			<div class="row">
				<div class="form-group">
					<div class="'.$campus_flag.'">
						<label class="control-label">Search </label>
						<input type="hidden" name="view" id="view" class="form-control" value="api">
						<input type="search" name="search_text" id="search_text" class="form-control" value="'.$search_text.'" placeholder="Challan, Reg no OR Name..." aria-controls="table_export">
					</div>';
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
					echo'
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
					<div class="col-md-3">
						<label class=control-label">Issue Date</label>
						<input type="text" id="issue_date" name="issue_date" class="form-control" data-plugin-datepicker value="'.((!empty($issue_date))?date('m/d/Y' , strtotime(cleanvars($issue_date))):'').'"/>
					</div>
					<div class="col-md-3">
						<label class=control-label">Due Date</label>
						<input type="text" id="due_date" name="due_date" class="form-control" data-plugin-datepicker value="'.((!empty($due_date))?date('m/d/Y' , strtotime(cleanvars($due_date))):'').'"/>
					</div>
					<div class="col-md-3">
						<label class=control-label">Paying Date</label>
						<input type="text" id="pay_date" name="pay_date" class="form-control" data-plugin-datepicker value="'.((!empty($pay_date))?date('m/d/Y' , strtotime(cleanvars($pay_date))):'').'"/>
					</div>
					<div class="col-md-3">
						<label class="control-label">Remaining</label>
						<input type="search" name="rem_amt" id="rem_amt" class="notNegtive form-control" value="'.$rem_amt.'" placeholder="0.00" aria-controls="table_export">
					</div>
					<div class="text-center">
						<button type="submit" class="btn btn-primary  mt-sm"><i class="fa fa-search"></i> Search</button>
					</div>
				</div>
			</div>
		</form>
		<table class="table table-bordered table-striped table-condensed mb-none">
			<thead>
				<tr>
					<th colspan="5"></th>
					<th colspan="3" class="center">Date</th>
					<th></th>
					<th class="center">Total</th>
					<th colspan="3"></th>
					<th colspan="2" class="center">Paid</th>
					<th colspan="3"></th>
				<tr>
				<tr>
					<th width="40" class="center">Sr.</th>
					<th>Challan No</th>
					<th>Type</th>
					<th>Student</th>
					<th>Class</th>
					<th class="center">Month</th>
					<th class="center">Issue</th>
					<th class="center">Due</th>
					<th>Session</th>
					<th class="center">Amount</th>
					<th class="center">Discount</th> 
					<th class="center">Fine</th>
					<th class="center">Payable</th>
					<th class="center">Amount</th>
					<th class="center">Date</th>
					<th>Remaining</th>
					<th width="70" class="center">Status</th>
					<th width="145" class="center">Options</th>
				</tr>
			</thead>
			<tbody>';
				$sql1 = '';
				if (!empty($_GET['search_text'])) {
					$sql1 = 'AND (f.challan_no = "'.cleanvars($_GET['search_text']).'" OR st.std_name LIKE "%'.cleanvars($_GET['search_text']).'%" OR st.std_regno = "'.cleanvars($_GET['search_text']).'")';
				}
				if (!empty($_GET['id_class'])) {
					$sql1 .= ' AND f.id_class = '.cleanvars($_GET['id_class']).'';
				}
				if (!empty($_GET['id_section'])) {
					$sql1 .= ' AND f.id_section = '.cleanvars($_GET['id_section']).'';
				}
				if (!empty($_GET['issue_date'])) {
					$sql1 .= ' AND f.issue_date = \''.date('Y-m-d' , strtotime(cleanvars($_GET['issue_date']))).'\'';
				}
				if (!empty($_GET['due_date'])) {
					$sql1 .= ' AND f.due_date = \''.date('Y-m-d' , strtotime(cleanvars($_GET['due_date']))).'\'';
				}
				if (!empty($_GET['pay_date'])) {
					$sql1 .= ' AND f.paid_date = \''.date('Y-m-d' , strtotime(cleanvars($_GET['pay_date']))).'\'';
				}
				if (!empty($_GET['rem_amt'])) {
					$sql1 .= ' AND f.remaining_amount = \''.cleanvars($_GET['rem_amt']).'\'';
				}
				$sqllmsFees = "SELECT f.id, f.status, f.id_type, f.challan_no, f.issue_date, f.due_date, f.paid_date, f.scholarship, f.concession, f.fine, f.total_amount, f.paid_amount, f.remaining_amount, f.note, f.id_session, c.class_id, c.class_name, cs.section_id, cs.section_name, s.session_id, s.session_name, st.std_id, st.std_name, f.id_month
								FROM ".FEES_LIVE." f				   
								INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
								INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section							 
								INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session							 
								INNER JOIN ".STUDENTS." st ON st.std_id 	 = f.id_std
								WHERE f.id_type IN (1,2)
								AND f.is_deleted	= '0'
								AND f.id_campus		= '".$id_campus."'
								$sql1
								ORDER BY f.id DESC";

				$sqllms	= $dblms->querylms($sqllmsFees);
				$count = mysqli_num_rows($sqllms);

				if($page == 0 || empty($page)) { $page = 1; }			//if no page var is given, default to 1.
				$prev 		    = $page - 1;							//previous page is page - 1
				$next 		    = $page + 1;							//next page is page + 1
				$lastpage  		= ceil($count/$Limit);					//lastpage is = total pages / items per page, rounded up.
				$lpm1 		    = $lastpage - 1;

				$filters = "";

				$sqllms	= $dblms->querylms("$sqllmsFees LIMIT ".($page-1)*$Limit .",$Limit");

				if($page == 1){
					$srno = 0;
				}else{
					$srno = ($page - 1) * $Limit;
				}

				if (mysqli_num_rows($sqllms) > 0) {
					while($rowsvalues = mysqli_fetch_array($sqllms)){
						$srno++;

						$discount = $rowsvalues['scholarship'] + $rowsvalues['concession'];
						$total = $rowsvalues['total_amount'] + $discount - $rowsvalues['fine'];
						if($rowsvalues['id_type']==1){
							$type = 'Admission';
						}elseif($rowsvalues['id_type']==2){
							$type = 'Fee';
						}
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['challan_no'].'</td>
							<td>'.$type.'</td>
							<td>'.$rowsvalues['std_name'].'</td>
							<td>'.$rowsvalues['class_name'].'</td>
							<td class="center">'.get_monthtypes($rowsvalues['id_month']).'</td>
							<td class="center">'.$rowsvalues['issue_date'].'</td>
							<td class="center">'.$rowsvalues['due_date'].'</td>
							<td>'.$rowsvalues['session_name'].'</td>
							<td class="center">'.$total.'</td>
							<td class="center">'.$discount.'</td> 
							<td class="center">'.$rowsvalues['fine'].'</td>
							<td class="center">'.$rowsvalues['total_amount'].'</td>
							<td class="center">'.$rowsvalues['paid_amount'].'</td>
							<td class="center">'.$rowsvalues['paid_date'].'</td>
							<td class="center">'.(($rowsvalues['total_amount']-$rowsvalues['paid_amount'] > 0)? ($rowsvalues['total_amount']-$rowsvalues['paid_amount']): '0').'</td>
							<td class="center">'.get_payments($rowsvalues['status']).'</td>
							<td class="center">';
								$sqllmscheck = $dblms->querylms("SELECT f.id, f.challan_no
																	FROM ".FEES_LIVE." f						 
																	INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std
																	WHERE f.status		= '2'
																	AND f.id_type IN (1,2)
																	AND f.is_deleted	= '0'
																	AND f.id_std		= '".cleanvars($rowsvalues['std_id'])."'
																	AND f.id_campus		= '".$id_campus."'
																	ORDER BY f.id DESC LIMIT 1");
								$valuesqllmscheck = mysqli_fetch_array($sqllmscheck);
								if($valuesqllmscheck['challan_no'] == $rowsvalues['challan_no']){
									//PRINT BUTTON
									echo'<a class="btn btn-success btn-xs mr-xs" class="center" href="feechallanprint_api.php?id='.$rowsvalues['challan_no'].'&id_campus='.$id_campus.'" target="_blank"> <i class="fa fa-file"></i></a>';
									
									if($campus_type_check){
										//EDIT BUTTON
										if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || in_array($_SESSION['userlogininfo']['LOGINTYPE'],$FEE_CHALLAN_RIGHTS) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))) { 
											echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/fee_challans/modal_feechallan_update.php?id='.$rowsvalues['id'].'&id_campus='.$id_campus.'\');"><i class="glyphicon glyphicon-edit"></i> </a>';
											echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/fee_challans/modal_feechallan_pay.php?id='.$rowsvalues['id'].'&id_campus='.$id_campus.'\');"><img src="assets/images/partial_payment.png" height="15" width="auto"> </a>';
										}
										//DELETE BUTTON
										if((($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'delete' => '1'))) && $rowsvalues['status'] !=4) {
											echo '<a href="#" class="btn btn-danger btn-xs mr-xs" onclick="confirm_modal(\'fee_challans.php?deleteid='.$rowsvalues['id'].'&id_campus='.$id_campus.'\');"><i class="el el-trash"></i></a>';
										}
									}

								} else if ($rowsvalues['status'] != '1'){
									if($campus_type_check){
										//EDIT BUTTON
										if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || in_array($_SESSION['userlogininfo']['LOGINTYPE'],$FEE_CHALLAN_RIGHTS) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))) { 
											echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/fee_challans/modal_feechallan_update.php?id='.$rowsvalues['id'].'&id_campus='.$id_campus.'\');"><i class="glyphicon glyphicon-edit"></i> </a>';
											echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/fee_challans/modal_feechallan_pay.php?id='.$rowsvalues['id'].'&id_campus='.$id_campus.'\');"><img src="assets/images/partial_payment.png" height="15" width="auto"> </a>';
										}									

										// if($rowsvalues['remaining_amount'] == 0){
										// 	echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/fee_challans/modal_feechallan_partialpayment.php?id='.$rowsvalues['id'].'\');"><img src="assets/images/partial_payment.png" height="15" width="auto"></a>';
										// }

										//DELETE BUTTON
										if((($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'delete' => '1'))) && $rowsvalues['status'] !=4) { 
											echo '<a href="#" class="btn btn-danger btn-xs mr-xs" onclick="confirm_modal(\'fee_challans.php?deleteid='.$rowsvalues['id'].'&id_campus='.$id_campus.'\');"><i class="el el-trash"></i></a>';
										}
									}
								} else if ($rowsvalues['status']==1){
									echo'<a class="btn btn-success btn-xs mr-xs" class="center" href="feechallanprint_api.php?id='.$rowsvalues['challan_no'].'&id_campus='.$id_campus.'" target="_blank"> <i class="fa fa-file"></i></a>';
								}
								echo'
							</td>
						</tr>';
					}
				} else {
					echo'
					<tr>
						<td colspan="17" class="center">
							<h2 class="text-danger">No Record Found</h2>
						</td>
					</tr>';
				}
				echo'
			</tbody>
		</table>';
		$filters = 'view=api&challan_no='.$challan_no.'&id_campus='.$id_campus.'&id_class='.$id_class.'&issue_date='.$issue_date.'&due_date='.$due_date.'&pay_date='.$pay_date.'&rem_amt='.$rem_amt.'';
		include("include/pagination.php");
		echo '
	</div>
</section>';
}
else{
	header("Location: dashboard.php");
}
?>