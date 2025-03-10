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

//-----------------------------------------------
if(isset($_POST['campus']) && ($_POST['campus'] > 0)){
	$campus = $_POST['campus'];
	$sql2 	= "AND r.id_campus = '".$_POST['campus']."'";
} else{
	$campus = 0;
	$sql2   = "";
}
if(isset($_POST['zone_id']) && ($_POST['zone_id'] > 0)){
	$zone 	= $_POST['zone'];
	$sql3 	= "AND c.id_zone = '".$_POST['zone_id']."'";
} else{
	$zone = 0;
	$sql3 = "";
}
//-----------------------------------------------

//------------------------------------------------------
$sqllmspaid	= $dblms->querylms("SELECT COUNT(r.roy_id) as count_paid, SUM(r.paid_amount) as paid
								   FROM ".ROYALTY." r				   						 
								   INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus 
								   WHERE r.roy_status = '1' AND r.is_deleted != '1' $sql2 $sql3");
$value_paid = mysqli_fetch_array($sqllmspaid);
if($value_paid['paid']){$paid = $value_paid['paid'];}else{$paid = 0;}
//------------------------------------------------------
$sqllmspending	= $dblms->querylms("SELECT COUNT(r.roy_id) as count_pending, SUM(r.total_amount) as pending
								   FROM ".ROYALTY." r				   						 
								   INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus 
								   WHERE r.roy_status = '2' AND r.is_deleted != '1' $sql2 $sql3");
$value_pending = mysqli_fetch_array($sqllmspending);
if($value_pending['pending']){$pending = $value_pending['pending'];}else{$pending = 0;}
//------------------------------------------------------
$sqllmsunpaid	= $dblms->querylms("SELECT COUNT(r.roy_id) as count_unpaid, SUM(r.total_amount) as unpaid
								   FROM ".ROYALTY." r				   						 
								   INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus 
								   WHERE r.roy_status = '3' AND r.is_deleted != '1' $sql2 $sql3");
$value_unpaid = mysqli_fetch_array($sqllmsunpaid);
if($value_unpaid['unpaid']){$unpaid = $value_unpaid['unpaid'];}else{$unpaid = 0;}
//------------------------------------------------------
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
	<div class="col-sm-12 col-md-12 col-lg-3 bg bg-danger card mb-sm">
		<i class="fa fa-ban" aria-hidden="true"></i> Total Unpaid
		<p class="val mt-md"><span class="span">Rs:</span> '.number_format($unpaid).'</p>
		<p class="count pull-right"><span class="span">Challan:</span> '.$value_unpaid['count_unpaid'].'</p>
	</div>
</div>

<section class="panel panel-featured panel-featured-primary">
<header class="panel-heading">
	<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
</header>
<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<div class="panel-body">
	<div class="row mb-lg">
		<div class="col-md-offset-3 col-md-3">
			<div class="form-group">
				<label class="control-label">Campus </label>
				<select data-plugin-selectTwo data-width="100%" name="campus" id="campus" class="form-control populate">
					<option value="">Select </option>
					<option value="0"';if($campus == 0){echo'selected';} echo'>All Campuses</option>';
					$sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
															FROM ".CAMPUS." c  
															WHERE c.campus_id != '' AND campus_status = '1'
															ORDER BY c.campus_name ASC");
					while($value_campus = mysqli_fetch_array($sqllmscampus)){
						if($value_campus['campus_id'] == $campus){
							echo'<option value="'.$value_campus['campus_id'].'" selected>'.$value_campus['campus_name'].'</option>';
						}
						else{
							echo'<option value="'.$value_campus['campus_id'].'">'.$value_campus['campus_name'].'</option>';
						}
					}
					echo'
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label">Zone </label>
				<select data-plugin-selectTwo data-width="100%" name="zone_id" id="zone_id" class="form-control populate">
					<option value="">Select </option>
					<option value="0"';if($zone == 0){echo'selected';} echo'>All Zones</option>';
					$sqllmsZone	= $dblms->querylms("SELECT zone_id, zone_name, zone_code
															FROM ".ZONES." 
															WHERE zone_id != '' AND zone_status = '1' AND is_deleted != '1'
															ORDER BY zone_ordering ASC");
					while($valueZone = mysqli_fetch_array($sqllmsZone)){
						if($valueZone['zone_id'] == $zone){
							echo'<option value="'.$valueZone['zone_id'].'" selected>'.$valueZone['zone_name'].'</option>';
						}
						else{
							echo'<option value="'.$valueZone['zone_id'].'">'.$valueZone['zone_name'].'</option>';
						}
					}
					echo'
				</select>
			</div>
		</div>
	</div>
	<center>
		<button type="submit" name="view_details" id="view_details" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
	</center>
</div>
</form>
</section>';
//-----------------------------------------------
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">';
		if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){ 
			echo'<a href="#printRoyaltyChallan" class="modal-with-move-anim ml-sm btn btn-primary btn-xs pull-right"><i class="fa fa-print"></i> Print Challan</a>';
		}
		if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))){ 
			echo'<a href="#make_challan" class="modal-with-move-anim btn btn-primary btn-xs pull-right ml-xs"><i class="fa fa-plus-square"></i> Make Single Challan</a>';
		}
		if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))){ 
			echo'<a href="#make_bulk_challans" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Bulk Challan</a>';
		}
		
		echo'
		<h2 class="panel-title"><i class="fa fa-list"></i> Royalty Challans List</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
			<thead>
				<tr>
					<th style="text-align:center;">#</th>
					<th>Challan #</th>
					<th>Month</th>
					<th>Issue Date</th>
					<th>Due Date</th>
					<th>Session</th>
					<th>Campus</th>
					<th>Total</th>
					<th>Arrears</th>
					<th width="70px;" style="text-align:center;">Status</th>
					<th width="100" style="text-align:center;">Options</th>
				</tr>
			</thead>
			<tbody>';
			//-----------------------------------------------------
			$sqllms	= $dblms->querylms("SELECT *
											FROM ".ROYALTY." r				   						 
											INNER JOIN ".SESSIONS." s ON s.session_id = r.id_session		 	
											INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus $sql2 $sql3
											ORDER BY r.roy_id DESC");
			$srno = 0;
			//-----------------------------------------------------
			while($rowsvalues = mysqli_fetch_array($sqllms)) {
				$srno++;
			//-----------------------------------------------------
			//  $total = $rowsvalues['total_amount'] + $rowsvalues['remaining_amount'];
				echo '
				<tr>
					<td style="text-align:center;">'.$srno.'</td>
					<td>'.$rowsvalues['challan_no'].'</td>
					<td>'.get_monthtypes($rowsvalues['id_month']).'</td>
					<td>'.$rowsvalues['issue_date'].'</td>
					<td>'.$rowsvalues['due_date'].'</td>
					<td>'.$rowsvalues['session_name'].'</td>
					<td>'.$rowsvalues['campus_name'].'</td>
					<td>'.$rowsvalues['total_amount'].'</td>
					<td>'.$rowsvalues['remaining_amount'].'</td>
					<td style="text-align:center;">'.get_payments($rowsvalues['roy_status']).'</td>
					<td style="text-align:center;">';
					echo '
						<!-- <a class="btn btn-success btn-xs" style="text-align:center;" href="feechallanprint.php?id='.$rowsvalues['challan_no'].'" target="_blank"> <i class="fa fa-file"></i></a> -->';
						if($rowsvalues['roy_status'] != '1'){
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))){ 
								echo '<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs ml-xs mr-xs" onclick="showAjaxModalZoom(\'include/modals/royalty/monthlyRoyalty/update.php?id='.$rowsvalues['roy_id'].'\');"><i class="glyphicon glyphicon-edit"></i> </a>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'delete' => '1'))){ 
								echo '<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'royalty.php?deleteid='.$rowsvalues['roy_id'].'\');"><i class="el el-trash"></i></a>';
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