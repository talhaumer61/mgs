<?php
echo'
<title> Fee Panel | '.TITLE_HEADER.'</title>
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
		text-vertical-align: center;
		margin-left: 18%;
	}
	.span{
		font-size:14px;
	}
</style>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Fee Panel </h2>
	</header>
	<div class="row">
		<div class="col-md-12">';
			$sqllmstudent  = $dblms->querylms("SELECT std_id, id_class, id_section  
													FROM ".STUDENTS." 
													WHERE id_campus	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
													AND id_loginid = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' LIMIT 1");
			$value_stu = mysqli_fetch_array($sqllmstudent);

			// PAID AMOUNT
			$sqllmspaid	= $dblms->querylms("SELECT f.status, SUM(f.paid_amount) as paid
											FROM ".FEES." f
											WHERE f.status IN (1,4)
											AND f.id_type IN (1,2)
											AND f.id_std = '".$value_stu['std_id']."'
											AND f.is_deleted != '1'
										");
			$value_paid = mysqli_fetch_array($sqllmspaid);
			if($value_paid['paid']){$paid = $value_paid['paid'];}else{$paid = 0;}

			// PENDING AMOUNT
			$sqllmspending	= $dblms->querylms("SELECT f.status, SUM(f.paid_amount) as paid,
													SUM(
														(case when f.due_date > '".date('Y-m-d')."' then f.total_amount
														else f.total_amount + '".LATEFEE."'
														end)
													) as total
												FROM ".FEES." f
												WHERE f.status IN (2,4)
												AND f.id_type IN (1,2)
												AND f.is_deleted != '1'
												AND f.id_std = '".$value_stu['std_id']."'
											");
			$value_pending = mysqli_fetch_array($sqllmspending);
			$TotalPending = $value_pending['total'] - $value_pending['paid'];
			if($TotalPending){$pending = $TotalPending;}else{$pending = 0;}

			// TOTAL AMOUNT
			$totalreceivable = $pending + $paid;
			echo'
			<div class="row mt-none mb-md">
				<div class="col-sm-12 col-md-12 col-lg-3 bg bg-info card mb-sm">
					<i class="fa fa-money" aria-hidden="true"></i> Total Fee
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
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-list"></i>  Challans Payment List / History</h2>
				</header>
				<div class="panel-body">
					<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
						<thead>
							<tr>
								<th width="40" class="center">Sr.</th>
								<th>Challan No</th>
								<th>Issue Date</th>
								<th>Due Date</th>
								<th>Total Amount</th>
								<th>Discount</th> 
								<th>Fine</th>
								<th>Payable</th>
								<th width="70" class="center">Status</th>
								<th width="70" class="center">Print</th>
							</tr>
						</thead>
						<tbody>';
							$sqllms	= $dblms->querylms("SELECT f.id, f.status, f.id_std, f.challan_no, f.issue_date, f.due_date, f.scholarship, f.concession, f.fine, f.total_amount, f.paid_amount, f.remaining_amount, f.note, f.id_session
														FROM ".FEES." f
														WHERE f.is_deleted	= '0'
														AND f.id_type IN (1,2)
														AND f.id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
														AND f.id_std		= '".$value_stu['std_id']."'
														ORDER BY f.id DESC");
							$srno = 0;
							while($rowsvalues = mysqli_fetch_array($sqllms)){
								$discount = $rowsvalues['scholarship'] + $rowsvalues['concession'];
								$total = $rowsvalues['total_amount'] + $discount - $rowsvalues['fine'];
								$srno++;
								echo '
								<tr>
									<td class="center">'.$srno.'</td>
									<td>'.$rowsvalues['challan_no'].'</td>
									<td>'.$rowsvalues['issue_date'].'</td>
									<td>'.$rowsvalues['due_date'].'</td>
									<td>'.$total.'</td>
									<td>'.$discount.'</td> 
									<td>'.$rowsvalues['fine'].'</td>
									<td>'.$rowsvalues['total_amount'].'</td>
									<td class="center">'.get_payments($rowsvalues['status']).'</td>
									<td class="center">';
										$sqllmscheck = $dblms->querylms("SELECT f.id, f.challan_no
																			FROM ".FEES." f						 
																			INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std
																			WHERE f.status		= '2'
																			AND f.is_deleted	= '0'
																			AND f.id_std		= '".cleanvars($rowsvalues['id_std'])."'
																			AND f.id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
																			ORDER BY f.id DESC LIMIT 1");
										$valuesqllmscheck = mysqli_fetch_array($sqllmscheck);
										if($valuesqllmscheck['challan_no'] == $rowsvalues['challan_no']){
											//PRINT BUTTON
											echo'<a class="btn btn-success btn-xs mr-xs" class="center" href="feechallanprint.php?id='.$rowsvalues['challan_no'].'" target="_blank"> <i class="fa fa-file"></i></a>';
										}elseif($rowsvalues['status']==1){
											//PRINT BUTTON
											echo'<a class="btn btn-success btn-xs mr-xs" class="center" href="feechallanprint.php?id='.$rowsvalues['challan_no'].'" target="_blank"> <i class="fa fa-file"></i></a>';
										}
										echo'
									</td>
								</tr>';
							}
							echo'
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			var datatable = $("#table_export").dataTable({
				bAutoWidth : false,
				ordering: false,
			});
		});
	</script>
</section>';
?>