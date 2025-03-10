<?php
//	FEE LEDGER , TOTAL AMOUNT, TOTAL PAID
$sqllms	= $dblms->querylms("SELECT SUM(total_amount) as total_amount, SUM(paid_amount) as paid_amount
								FROM ".FEES."
								WHERE is_deleted = '0'
								AND id_campus IN (".$id_campus.") ");
$values = mysqli_fetch_array($sqllms);
$total_amount = $values['total_amount'];
$paid_amount = $values['paid_amount'];
$paid_percent = ($paid_amount / $total_amount) * 100;

//	FEE LEDGER , TOTAL PAID TODAY
$date = date('Y-m-d');
$sqllmsTodayPaid	= $dblms->querylms("SELECT SUM(paid_amount) as today_paid
								FROM ".FEES."
								WHERE is_deleted = '0'
								AND paid_date = '".$date."'
								AND id_campus IN (".$id_campus.") ");
$valuesTodayPaid = mysqli_fetch_array($sqllmsTodayPaid);

//	NO OF HOTELS STUDENTS UNDER CAMPUS
$sqllmsHostel	= $dblms->querylms("SELECT COUNT(std_id ) as hostel_stds
								FROM ".STUDENTS."
								WHERE is_deleted	=	'0'
								AND std_status 		= 	'1'
								AND is_hostel 		= 	'1'
								AND id_campus IN (".$id_campus.") ");
$valuesHostel = mysqli_fetch_array($sqllmsHostel);

echo '	
<div class="row">						
	<div class="col-lg-6 text-center">
		<section class="panel panel-featured-left panel-featured-primary">
			<div class="panel-body">
				<div class="liquid-meter-wrapper liquid-meter-md mt-lg">
					<div class="liquid-meter">
					<meter min="0" max="100" value="'.$paid_percent.'" id="meterSales"></meter>
					</div>
					<div class="liquid-meter-selector" id="meterSalesSel">
						<b>'.$paid_amount.' / '.$total_amount.'</b>
					</div>
				</div>
				 <!--See: assets/javascripts/dashboard/custom_dashboard.js for more settings.-->
			</div>
		</section>
	</div>
	<div class="col-md-6">
		<section class="panel panel-featured-left panel-featured-primary">
			<div class="panel-body">
				<div class="widget-summary widget-summary-sm">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fa fa-usd"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title">Payment</h4>
							<div class="info">
								<strong class="amount">'.($valuesTodayPaid['today_paid']>0 ? $valuesTodayPaid['today_paid'] : '0').'</strong>
								<span class="text-primary text-uppercase">Today Payments</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="panel panel-featured-left panel-featured-primary">
			<div class="panel-body">
				<div class="widget-summary widget-summary-sm">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fa fa-hotel"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title">Hostel</h4>
							<div class="info">
								<strong class="amount">'.($valuesHostel['hostel_stds']>0 ? $valuesHostel['hostel_stds'] : '0').'</strong>
								<span class="text-primary text-uppercase">total Hostels</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="panel panel-featured-left panel-featured-primary">
			<div class="panel-body">
				<div class="widget-summary widget-summary-sm">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="fa fa-bus"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title">Transport</h4>
							<div class="info">
								<strong class="amount">0</strong>
								<span class="text-primary text-uppercase">total route</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>';