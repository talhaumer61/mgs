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
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){ 


// Vars
$sql2 = "";
$sql3 = "";
$sql4 = "";
$sql5 = "";
$zone = 0;
$campus = 0;
$status = "";
$search_word = "";

// FIlters
if(isset($_GET['show'])){
	//  word
	if(isset($_GET['search_word']))
	{
		$search_word = $_GET['search_word'];
		$sql2 = "AND (r.challan_no LIKE '%".$_GET['search_word']."%' OR r.total_amount LIKE '%".$_GET['search_word']."%' OR c.campus_name LIKE '%".$_GET['search_word']."%')";
	}
	// Campus
	if(isset($_GET['campus']) && ($_GET['campus'] > 0)){
		$campus = $_GET['campus'];
		$sql3 	= "AND r.id_campus = '".$_GET['campus']."'";
	}
	// Zone
	if(isset($_GET['zone_id']) && ($_GET['zone_id'] > 0)){
		$zone 	= $_GET['zone'];
		$sql4 	= "AND c.id_zone = '".$_GET['zone_id']."'";
	} 
	// status
	if($_GET['status'])
	{
		$status = $_GET['status'];
		$sql5 = "AND r.status = '".$_GET['status']."'";
	}
}

// PAID CHALLANS
$sqllmspaid	= $dblms->querylms("SELECT COUNT(r.id) as count_paid, SUM(r.paid_amount) as paid
									FROM ".FEES." r				   						 
									INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus 
									WHERE r.status		= '1'
									AND r.id_type		= '3'
									AND r.is_deleted	= '0' $sql2 $sql3 $sql4 $sql5");
$value_paid = mysqli_fetch_array($sqllmspaid);
if($value_paid['paid']){$paid = $value_paid['paid'];}else{$paid = 0;}

// PENDING CHALLANS
$sqllmspending	= $dblms->querylms("SELECT COUNT(r.id) as count_pending, SUM(r.total_amount) as pending
										FROM ".FEES." r				   						 
										INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus 
										WHERE r.status		= '2'
										AND r.id_type		= '3'
										AND r.is_deleted	= '0' $sql2 $sql3 $sql4 $sql5");
$value_pending = mysqli_fetch_array($sqllmspending);
if($value_pending['pending']){$pending = $value_pending['pending'];}else{$pending = 0;}

// PARTIAL CHALLANS
$sqllmspartpaid	= $dblms->querylms("SELECT COUNT(r.id) as count_partpaid, SUM(r.total_amount) as partpaid
										FROM ".FEES." r				   						 
										INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus 
										WHERE r.status		= '4'
										AND r.id_type		= '3'
										AND r.is_deleted	= '0' $sql2 $sql3 $sql4 $sql5");
$value_partpaid = mysqli_fetch_array($sqllmspartpaid);
if($value_partpaid['partpaid']){$partpaid = $value_partpaid['partpaid'];}else{$partpaid = 0;}

echo '
<div class="row mt-none mb-md">
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-success card mb-sm">
		<i class="fa fa-star" aria-hidden="true"></i> Total Paid
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($paid).'</p>
		<p class="count pull-right"><span class="span">Challan:</span> '.$value_paid['count_paid'].'</p>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-warning card mb-sm">
		<i class="fa fa-refresh" aria-hidden="true"></i> Total Pending
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($pending).'</p>
		<p class="count pull-right"><span class="span">Challan:</span> '.$value_pending['count_pending'].'</p>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-info card mb-sm">
		<i class="fa fa-ban" aria-hidden="true"></i> Partial Paid
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($partpaid).'</p>
		<p class="count pull-right"><span class="span">Challan:</span> '.$value_partpaid['count_partpaid'].'</p>
	</div>
</div>
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
		// if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){ 
		// 	echo'<a href="#print_challan" class="modal-with-move-anim ml-sm btn btn-primary btn-xs pull-right"><i class="fa fa-print"></i> Print Challan</a>';
		// }
		if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))){ 
			echo'<a href="royaltyChallans.php?view=add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Challan</a>';
			echo'<a href="royaltyChallans.php?view=addBulk" class="btn btn-primary btn-xs mr-xs pull-right"><i class="fa fa-plus-square"></i> Make Bulk Challan</a>';
		}		
		echo'
		<h2 class="panel-title"><i class="fa fa-list"></i>  Royalty Challans List</h2>
	</header>
	<div class="panel-body">
		<form action="#" method="GET" autocomplete="off">
			<div class="form-group mb-md">
				<div class="col-sm-3">
					<div class="form-group">
						<label class="control-label">Search </label>
						<input type="search" name="search_word" id="search_word" class="form-control" value="'.$search_word.'" placeholder="Search">
					</div>
				</div>
				<div class="col-md-3">
					<label class="control-label">Campus </label>
					<select data-plugin-selectTwo data-width="100%" name="campus" id="campus" class="form-control populate">
						<option value="">Select </option>
						<option value="0"';if($campus == 0){echo'selected';} echo'>All Campuses</option>';
						$sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
																FROM ".CAMPUS." c  
																WHERE c.campus_id != '' AND campus_status = '1'
																ORDER BY c.campus_name ASC");
						while($value_campus = mysqli_fetch_array($sqllmscampus)){
							if($value_campus['campus_id'] == $campus) {
								echo'<option value="'.$value_campus['campus_id'].'" selected>'.$value_campus['campus_name'].'</option>';
							} else {
								echo'<option value="'.$value_campus['campus_id'].'">'.$value_campus['campus_name'].'</option>';
							}
						}
						echo'
					</select>
				</div>
				<div class="col-md-3">
					<label class="control-label">Zone </label>
					<select data-plugin-selectTwo data-width="100%" name="zone_id" id="zone_id" class="form-control populate">
						<option value="">Select </option>
						<option value="0"'; if($zone == 0){echo'selected';} echo'>All Zones</option>';
						$sqllmsZone	= $dblms->querylms("SELECT zone_id, zone_name, zone_code
																FROM ".ZONES." 
																WHERE zone_id != '' AND zone_status = '1' AND is_deleted != '1'
																ORDER BY zone_ordering ASC");
						while($valueZone = mysqli_fetch_array($sqllmsZone)){
							if($valueZone['zone_id'] == $zone) {
								echo'<option value="'.$valueZone['zone_id'].'" selected>'.$valueZone['zone_name'].'</option>';
							} else {
								echo'<option value="'.$valueZone['zone_id'].'">'.$valueZone['zone_name'].'</option>';
							}
						}
						echo'
					</select>
				</div>
				<div class="col-md-3">
					<label class="control-label">Status </label>
					<select class="form-control" data-plugin-selectTwo data-width="100%" name="status">
						<option value="">Select</option>';
						foreach($payments as $stat){
							echo '<option value="'.$stat['id'].'"'; if($status == $stat['id']){ echo'selected';} echo'>'.$stat['name'].'</option>';
						}
						echo'
					</select>
				</div>
				<div class="col-sm-12 center">
					<div class="form-group mt-lg">
						<button type="submit" name="show" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
					</div>
				</div>
			</div>
		</form>';
		$sql = "SELECT r.id, r.status, r.id_month, r.challan_no, r.issue_date, r.due_date, r.paid_date, r.total_amount, r.paid_amount, r.remaining_amount, s.session_name, c.campus_name, c.id_zone
				FROM ".FEES." r				   						 
				INNER JOIN ".SESSIONS." s ON s.session_id = r.id_session		 	
				INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus 
				WHERE r.is_deleted	= '0'
				AND r.id_type		= '3'
				$sql2 $sql3 $sql4 $sql5
				ORDER BY r.id DESC";

		$sqllms	= $dblms->querylms($sql);
		
		$count = mysqli_num_rows($sqllms);
		if($page == 0) { $page = 1; }			//if no page var is given, default to 1.
		$prev		= $page - 1;				//previous page is page - 1
		$next		= $page + 1;				//next page is page + 1
		$lastpage	= ceil($count/$Limit);		//lastpage is = total pages / items per page, rounded up.
		$lpm1		= $lastpage - 1;

		$sqllms	= $dblms->querylms("$sql LIMIT ".($page-1)*$Limit .",$Limit");

		if(mysqli_num_rows($sqllms) > 0){
			if($page == 1){
				$srno = 0;
			}else{
				$srno = ($page - 1) * $Limit;
			}
			echo'
			<table class="table table-bordered table-striped table-condensed mb-none">
				<thead>
					<tr>
						<th width="40" class="center">Sr.</th>
						<th>Challan No</th>
						<th>Month</th>
						<th>Issue Date</th>
						<th>Due Date</th>
						<th>Session</th>
						<th>Campus</th>
						<th>Payable</th>
						<th>Balance</th>
						<th width="70" class="center">Status</th>
						<th width="100" class="center">Options</th>
					</tr>
				</thead>
				<tbody>';
					while($rowsvalues = mysqli_fetch_array($sqllms)) {
						$srno++;
						echo '
						<tr>
							<td class="center">'.$srno.'</td>
							<td>'.$rowsvalues['challan_no'].'</td>
							<td>'.get_monthtypes($rowsvalues['id_month']).'</td>
							<td>'.$rowsvalues['issue_date'].'</td>
							<td>'.$rowsvalues['due_date'].'</td>
							<td>'.$rowsvalues['session_name'].'</td>
							<td>'.$rowsvalues['campus_name'].'</td>
							<td>'.number_format(round($rowsvalues['total_amount'])).'</td>
							<td>'.number_format(round($rowsvalues['total_amount'] - $rowsvalues['paid_amount'])).'</td>
							<td class="center">'.get_payments($rowsvalues['status']).'</td>
							<td class="center">';
								echo'
								<a class="btn btn-success btn-xs" class="center" href="royaltyChallanPrint.php?id='.$rowsvalues['challan_no'].'" target="_blank"> <i class="fa fa-file" data-toggle="tooltip" title="Print Challan"></i></a>';
								if($rowsvalues['status'] != '1'){
									// if($rowsvalues['status'] != '4' && ($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))){ 
									// 	echo '<a href="royaltyChallans.php?id='.$rowsvalues['challan_no'].'" class="btn btn-primary btn-xs ml-xs" onclick="showAjaxModalZoom(\'include/modals/fee_challans/modal_feechallan_update.php?id='.$rowsvalues['id'].'\');" data-toggle="tooltip" title="Edit Heads"><i class="glyphicon glyphicon-edit"></i> </a>';
									// }
									if($rowsvalues['status'] != '1'){
										if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))){ 
											echo '<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs ml-xs" onclick="showAjaxModalZoom(\'include/modals/royalty/royaltyChallan/update.php?id='.$rowsvalues['id'].'\');" data-toggle="tooltip" title="Pay"><img src="assets/images/partial_payment.png" height="15" width="auto"></a>';
										}
										if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || ($_SESSION['userlogininfo']['LOGINTYPE']  == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'delete' => '1'))){ 
											echo '<a href="#" class="btn btn-danger btn-xs ml-xs" onclick="confirm_modal(\'fee_challans.php?deleteid='.$rowsvalues['challan_no'].'\');" data-toggle="tooltip" title="Delete"><i class="el el-trash"></i></a>';
										}
									}
								}
								echo'
							</td>
						</tr>';
					}
					echo '
				</tbody>
			</table>';			
			include_once('include/pagination.php');
		}
		else{
			echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
		}
		echo'
	</div>
</section>';
}
else{
	header("Location: dashboard.php");
}
?>