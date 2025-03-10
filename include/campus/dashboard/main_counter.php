<?php 
// Total Student
$sqllmsstudents	= $dblms->querylms("SELECT COUNT(std_id) as total
									FROM ".STUDENTS."
									WHERE std_id != '' AND std_status = '1' AND is_deleted != '1' AND id_campus IN (".$id_campus.") ");
$value_std = mysqli_fetch_array($sqllmsstudents);

// Male Student
$sqllmsMaleStd	= $dblms->querylms("SELECT COUNT(std_id) as total
									FROM ".STUDENTS."
									WHERE std_id != '' AND std_status = '1' AND std_gender = 'Male' AND is_deleted != '1' AND id_campus IN (".$id_campus.") ");
$valueMaleStd = mysqli_fetch_array($sqllmsMaleStd);

// Female Student
$sqllmsFemaleStd	= $dblms->querylms("SELECT COUNT(std_id) as total
									FROM ".STUDENTS."
									WHERE std_id != '' AND std_status = '1' AND std_gender = 'Female' AND is_deleted != '1' AND id_campus IN (".$id_campus.") ");
$valueFemaleStd = mysqli_fetch_array($sqllmsFemaleStd);

// Total Emplyee
$sqllmsemployee	= $dblms->querylms("SELECT COUNT(emply_id) as total
									FROM ".EMPLOYEES."
									WHERE emply_id != '' AND emply_status = '1' AND is_deleted != '1' AND id_campus IN (".$id_campus.") ");
$value_emp = mysqli_fetch_array($sqllmsemployee);

// Male Emplyee
$sqllmsMaleEmpl	= $dblms->querylms("SELECT COUNT(emply_id) as total
								FROM ".EMPLOYEES."
								WHERE emply_id != '' AND emply_status = '1' AND emply_gender = 'Male' AND is_deleted != '1' AND id_campus IN (".$id_campus.") ");
$valueMaleEmpl = mysqli_fetch_array($sqllmsMaleEmpl);

// Female Emplyee
$sqllmsFemaleEmpl	= $dblms->querylms("SELECT COUNT(emply_id) as total
								FROM ".EMPLOYEES."
								WHERE emply_id != '' AND emply_status = '1' AND emply_gender = 'Female' AND is_deleted != '1' AND id_campus IN (".$id_campus.") ");
$valueFemaleEmpl = mysqli_fetch_array($sqllmsFemaleEmpl);

// DAILY QUOTATION
$checkDate = date("Y-m-d");
$sqllmsQuote = array ( 
						'select' 	=> '
											quote_type, quote_msg
										',
						'where' 	=> array( 
												  'is_deleted'  => '0'
												, 'date'   		=> $checkDate
											),
						'return_type' 	=> 'all' 
					); 
$rowsQuote  = $dblms->getRows(DAILY_QUOTATION, $sqllmsQuote);
$Msg = '';
foreach($rowsQuote as $key => $val):
	$Msg .= ' <span style="margin-right: 5rem;"><i class="fa fa-arrow-right"></i> <b>'.get_Quotation($val['quote_type']).': </b>'.$val['quote_msg'].'</span>';
endforeach;

if (!empty($Msg)):
	echo'
	<div class="form-group">
		<div class="col-md-12 mb-sm">
			<div class="input-group">
				<span class="input-group-addon" style="background: #08c;color: #fff;">Daily Quotes</span>
				<span id="msg" type="text" class="form-control" name="msg" placeholder="Additional Info">
					<marquee style="color: #000; cursor: default;" direction="left" behavior="scroll" onmouseover="this.stop();" onmouseout="this.start();">'.$Msg.'</marquee>
				</span>
			</div>
		</div>
	</div>';
endif;
echo '
<div class="counter-link">
	<a href="students.php">
		<div class="col-lg-3 col-md-6 col-sm-6 mb-md">
			<div class="panel-featured-left panel-featured-secondary p-lg" style="background: linear-gradient(45deg, #2ed8b6, #59e0c5); border-radius: 5px;">
				<div class="card-body w_sparkline">
					<div class="details">
						<span><b>Students (Boys)</b></span>
						<h3 class="mt-none mb-none counter">'.$valueMaleStd['total'].'</h3>
					</div>
					<div class="counter-box">
						<i class="fa fa-users"></i>
					</div>
				</div>
			</div>
		</div>
	</a>
	<a href="students.php">
		<div class="col-lg-3 col-md-6 col-sm-6 mb-md">
			<div class="panel-featured-left panel-featured-secondary p-lg" style="background: linear-gradient(45deg, #ffb64d, #ffcb80); border-radius: 5px;">
				<div class="card-body w_sparkline">
					<div class="details">
					<span><b>Students (Girls)</b></span>
						<h3 class="mt-none mb-none counter">'.$valueFemaleStd['total'].'</h3>
					</div>
					<div class="counter-box">
						<i class="fa fa-users"></i>
					</div>
				</div>
			</div>
		</div>
	</a>
	<a href="employee.php">
		<div class="col-lg-3 col-md-6 col-sm-6 mb-md">
			<div class="panel-featured-left panel-featured-secondary p-lg" style="background: linear-gradient(45deg, #4099ff, #73b4ff); border-radius: 5px;">
				<div class="card-body w_sparkline">
					<div class="details">
					<span><b>Employees (Male)</b></span>
						<h3 class="mt-none mb-none counter">'.$valueMaleEmpl['total'].'</h3>
					</div>
					<div class="counter-box">
						<i class="fa fa-user-o"></i>
					</div>
				</div>
			</div>
		</div>
	</a>
	<a href="employee.php">
		<div class="col-lg-3 col-md-6 col-sm-6 mb-md">
			<div class="panel-featured-left panel-featured-secondary p-lg" style="background: linear-gradient(45deg, #ff5370, #ff869a); border-radius: 5px;">
				<div class="card-body w_sparkline">
					<div class="details">
					<span><b>Employees (Female)</b></span>
						<h3 class="mt-none mb-none counter">'.$valueFemaleEmpl['total'].'</h3>
					</div>
					<div class="counter-box">
						<i class="fa fa-user-o"></i>
					</div>
				</div>
			</div>
		</div>
	</a>
</div>
<!--
<div class="row">
	<a href="students.php" class="col-md-12 col-lg-6 col-xl-6">
		<section class="panel panel-featured-left panel-featured-secondary">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-secondary">
							<i class="fa fa-users"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<div class="col-md-4">
								<h4 class="title">Total Student</h4>
								<div class="info"><strong class="amount">'.$value_std['total'].'</strong></div>
							</div>
							<div class="col-md-4">
								<h4 class="title">Male Student</h4>
								<div class="info">
									<strong class="amount">'.$valueMaleStd['total'].'</strong>
								</div>
							</div>
							<div class="col-md-4">
								<h4 class="title">Female Student</h4>
								<div class="info">
									<strong class="amount">'.$valueFemaleStd['total'].'</strong>
								</div>
							</div>
							
						</div>
						<div class="summary-footer">
							<span class="text-muted text-uppercase">total students</span>
						</div>
					</div>
				</div>
			</div>
		</section>
	</a>
	<a href="employee.php" class="col-md-12 col-lg-6 col-xl-6">
		<section class="panel panel-featured-left panel-featured-primary">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-primary">
							<i class="glyphicon glyphicon-user"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<div class="row">
								<div class="col-md-4">
									<h4 class="title">Total Employee</h4>
									<div class="info">
										<strong class="amount">'.$value_emp['total'].'</strong>
									</div>
								</div>
								<div class="col-md-4">
									<h4 class="title">Male Employee</h4>
									<div class="info">
										<strong class="amount">'.$valueMaleEmpl['total'].'</strong>
									</div>
								</div>
								<div class="col-md-4">
									<h4 class="title">Female Employee</h4>
									<div class="info">
										<strong class="amount">'.$valueFemaleEmpl['total'].'</strong>
									</div>
								</div>
							</div>
							

						</div>
						<div class="summary-footer">
							<span class="text-muted text-uppercase">total employees</span>
						</div>
					</div>
				</div>
			</div>
		</section>
	</a>
	<div class="col-md-12 col-lg-6 col-xl-6">
		<section class="panel panel-featured-left panel-featured-tertiary">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-tertiary">
							<i class="fa fa-snowflake-o"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title">Leave Application</h4>
							<div class="info"><strong class="amount">1</strong></div>
						</div>
						<div class="summary-footer">
							<span class="text-muted text-uppercase">total pending</span>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-md-12 col-lg-6 col-xl-6">
		<section class="panel panel-featured-left panel-featured-quaternary">
			<div class="panel-body">
				<div class="widget-summary">
					<div class="widget-summary-col widget-summary-col-icon">
						<div class="summary-icon bg-quaternary">
							<i class="fa fa-credit-card"></i>
						</div>
					</div>
					<div class="widget-summary-col">
						<div class="summary">
							<h4 class="title">Invoice</h4>
							<div class="info"><strong class="amount">0</strong></div>
						</div>
						<div class="summary-footer">
							<span class="text-muted text-uppercase">today payments</span>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
-->
';
?>