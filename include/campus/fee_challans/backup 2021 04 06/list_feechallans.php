<style>
.card{
	padding: 20px;
	font-size: 30px;
	border-radius:10px;
	margin-left: 4%;
	margin-right: 4%;
	}
.val{
	font-size: 20px;
	margin-left: 18%;
	}
.span{
	font-size:14px;
	}
</style>
<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){ 

//------------------------------------------------------
$sqllmspaid	= $dblms->querylms("SELECT f.status, SUM(f.total_amount) as paid
								   FROM ".FEES." f
								   WHERE f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   AND f.status = '1' AND f.is_deleted != '1'");
$value_paid = mysqli_fetch_array($sqllmspaid);
if($value_paid['paid']){$paid = $value_paid['paid'];}else{$paid = 0;}
//------------------------------------------------------
$sqllmspending	= $dblms->querylms("SELECT f.status, SUM(f.total_amount) as pending
								   FROM ".FEES." f
								   WHERE f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   AND f.status = '2' AND f.is_deleted != '1'");
$value_pending = mysqli_fetch_array($sqllmspending);
if($value_pending['pending']){$pending = $value_pending['pending'];}else{$pending = 0;}
//------------------------------------------------------
$sqllmsunpaid	= $dblms->querylms("SELECT f.status, SUM(f.total_amount) as unpaid
								   FROM ".FEES." f
								   WHERE f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   AND f.status = '3' AND f.is_deleted != '1'");
$value_unpaid = mysqli_fetch_array($sqllmsunpaid);
if($value_unpaid['unpaid']){$unpaid = $value_unpaid['unpaid'];}else{$unpaid = 0;}
//------------------------------------------------------
echo '
<div class="row mt-none mb-md">
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-success card mb-sm">
		<i class="fa fa-star" aria-hidden="true"></i> Total Paid
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($paid).'</p>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-warning card mb-sm">
		<i class="fa fa-refresh" aria-hidden="true"></i> Total Pending
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($pending).'</p>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-danger card mb-sm">
		<i class="fa fa-ban" aria-hidden="true"></i> Total Unpaid
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($unpaid).'</p>
	</div>
</div>
<section class="panel panel-featured panel-featured-primary">
<header class="panel-heading">';
	if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){ 
		echo'<a href="#print_challan" class="modal-with-move-anim ml-sm btn btn-primary btn-xs pull-right"><i class="fa fa-print"></i> Print Challan</a>';
	}
	if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))){ 
		echo'<a href="fee_challans.php?view=bulk" class="btn btn-primary ml-sm btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Class Challan</a>';
	}
	if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))){ 
		echo'<a href="#make_challan" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Single Challan</a>';
	}
	
	echo'
	<h2 class="panel-title"><i class="fa fa-list"></i>  Challans List</h2>
</header>
<div class="panel-body">
<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
<thead>
	<tr>
		<th style="text-align:center;">#</th>
		<th>Challan #</th>
		<th>Issue Date</th>
		<th>Due Date</th>
		<th>Session</th>
		<th>Class</th>
		<th>Section</th>
		<th>Student</th>
		<th>Total Amount</th>
		<!--<th>Tuition Fee</th>
		<th>Sch</th>
		<th>Concess</th> -->
		<th>Discount</th> 
		<th>Fine</th>
		<th>Payable</th>
		<th>Balance</th>
		<th width="70px;" style="text-align:center;">Status</th>
		<th width="100" style="text-align:center;">Options</th>
	</tr>
</thead>
<tbody>';
//-----------------------------------------------------
$sqllms	= $dblms->querylms("SELECT f.id, f.status, f.challan_no, f.issue_date, f.due_date, f.total_amount, f.paid_amount, f.remaining_amount, f.note, f.id_session,
								   c.class_id, c.class_name,
								   cs.section_id, cs.section_name,
								   s.session_id, s.session_name,
								   st.std_id, st.std_name
								   FROM ".FEES." f				   
								   INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
								   INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section							 
								   INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session							 
								   INNER JOIN ".STUDENTS." st ON st.std_id 	 = f.id_std
								   WHERE f.is_deleted != '1'
								   AND s.is_deleted != '1'
								   AND f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   ORDER BY f.id DESC");
$srno = 0;
//-----------------------------------------------------
while($rowsvalues = mysqli_fetch_array($sqllms)) {
	//---------------------------Scholarship--------------------------
	$sql_scholarship	= $dblms->querylms("SELECT SUM(percent) as scholarship
									   FROM ".SCHOLARSHIP." 
									   WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
									   AND id_session = '".$rowsvalues['id_session']."'
									   AND   id_type = '1' AND status = '1' AND is_deleted != '1'
									   AND id_std = '".$rowsvalues['std_id']."' ");
	//-----------------------------------------------------
	$values_scholarship = mysqli_fetch_array($sql_scholarship);
	
	$values_scholarship['scholarship']."<br>";
	//----------------------------Fee Concession-------------------------
	$sql_concess	= $dblms->querylms("SELECT SUM(percent) as concession
									   FROM ".SCHOLARSHIP." 
									   WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
									   AND id_session = '".$rowsvalues['id_session']."'
									   AND   id_type = '2' AND status = '1' AND is_deleted != '1'
									   AND id_std = '".$rowsvalues['std_id']."' ");
	//-----------------------------------------------------
	$values_concess = mysqli_fetch_array($sql_concess);
	$values_concess['concession'];
	//----------------------------Fine-------------------------
	$chln_mnth  = date("n", strtotime($rowsvalues['due_date']));
	
	$sql_fine	= $dblms->querylms("SELECT SUM(amount) as fine
									   FROM ".SCHOLARSHIP." 
									   WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
									   AND id_session = '".$rowsvalues['id_session']."'
									   AND MONTH(date) = '".$chln_mnth."'
									   AND id_type = '3' AND status = '1' AND is_deleted != '1'
									   AND id_std = '".$rowsvalues['std_id']."' ");
	//-----------------------------------------------------
	$values_fine = mysqli_fetch_array($sql_fine);
	//------------------------TUITION FEE------------------------
	$sql_tuitionfee	= $dblms->querylms("SELECT amount
									   FROM ".FEESETUPDETAIL." d
									   INNER JOIN ".FEESETUP." f ON f.id = d.id_setup
									   WHERE f.id_class = '".$rowsvalues['class_id']."' AND f.id_section = '".$rowsvalues['section_id']."'
									   AND   d.id_cat = '1'");
	//-----------------------------------------------------
	$values_tuitionfee = mysqli_fetch_array($sql_tuitionfee);
	$tuition_fee = $values_tuitionfee['amount'];
	//-----------------------------------------------------
	//---------------------------Scholarship--------------------------
	/*$sql_pre_blnc	= $dblms->querylms("SELECT SUM(total_amount) as total, paid_amount
									   FROM ".FEES." 
									   WHERE id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
									   AND id_std = '".$rowsvalues['std_id']."' ");
	//-----------------------------------------------------
	$values_blnc = mysqli_fetch_array($sql_pre_blnc);
	//-----------------------------------------------------*/

//-----------payabel amount after Scholarship & Fine----------
	//--------------TOTAL PERCENTAGE DEDUCTION IN FEE--------------------
	$total_fee = $rowsvalues['total_amount'];
	$dis_per = $values_scholarship['scholarship'] + $values_concess['concession'];
	//-----------------------DISCOUNT IN TUTION FEE------------------------------
	$dis_amount = ($tuition_fee * $dis_per) / 100;
	//-----------------------DISCOUNT IN TUTION FEE------------------------------
	$after_dis = $total_fee - $dis_amount;
	//--------------------PAYABLE AFTER DISCOUNT AND FINE------------------------
	$payable = ($total_fee  + $values_fine['fine'] + $rowsvalues['remaining_amount']) - $dis_amount;
	//-----------------------------------------------------
	$srno++;
//-----------------------------------------------------
echo '
<tr>
	<td style="text-align:center;">'.$srno.'</td>
	<td>'.$rowsvalues['challan_no'].'</td>
	<td>'.$rowsvalues['issue_date'].'</td>
	<td>'.$rowsvalues['due_date'].'</td>
	<td>'.$rowsvalues['session_name'].'</td>
	<td>'.$rowsvalues['class_name'].'</td>
	<td>'.$rowsvalues['section_name'].'</td>
	<td>'.$rowsvalues['std_name'].'</td>
	<td>'.number_format(round($total_fee)).'</td>
	<!-- <td>'.$tuition_fee.'</td>
	<td>'.$values_scholarship['scholarship'].'%</td>
	<td>'.$values_concess['concession'].'%</td> -->
	<td>'.$dis_amount.'</td> 
	<td>'.$values_fine['fine'].'</td>
	<td>'.number_format(round($payable)).'</td>
	<td>'.number_format(round($rowsvalues['remaining_amount'])).'</td>
	<td style="text-align:center;">'.get_payments($rowsvalues['status']).'</td>
	<td style="text-align:center;">';
	echo '
		<a class="btn btn-success btn-xs" style="text-align:center;" href="feechallanprint.php?id='.$rowsvalues['challan_no'].'" target="_blank"> <i class="fa fa-file"></i></a>';
		if($rowsvalues['status'] != '1'){
			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))){ 
				echo '<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs ml-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/fee_challans/modal_feechallan_update.php?id='.$rowsvalues['id'].'\');"><i class="glyphicon glyphicon-edit"></i> </a>';
			}
			if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'delete' => '1'))){ 
				echo '<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'fee_challans.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
			}
		}
		echo '
	</td>
</tr>';
//-----------------------------------------------------
}
//-----------------------------------------------------
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