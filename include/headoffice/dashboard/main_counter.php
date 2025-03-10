<?php 
// Total Student
$sqllmsstudents	= $dblms->querylms("SELECT COUNT(std_id) as total
									FROM ".STUDENTS."
									WHERE std_id != '' AND std_status = '1' AND is_deleted != '1'");
$value_std = mysqli_fetch_array($sqllmsstudents);

// Male Student
$sqllmsMaleStd	= $dblms->querylms("SELECT COUNT(std_id) as total
									FROM ".STUDENTS."
									WHERE std_id != '' AND std_status = '1' AND std_gender = 'Male' AND is_deleted != '1'");
$valueMaleStd = mysqli_fetch_array($sqllmsMaleStd);

// Female Student
$sqllmsFemaleStd	= $dblms->querylms("SELECT COUNT(std_id) as total
									FROM ".STUDENTS."
									WHERE std_id != '' AND std_status = '1' AND std_gender = 'Female' AND is_deleted != '1'");
$valueFemaleStd = mysqli_fetch_array($sqllmsFemaleStd);

// Total Emplyee
$sqllmsemployee	= $dblms->querylms("SELECT COUNT(emply_id) as total
									FROM ".EMPLOYEES."
									WHERE emply_id != '' AND emply_status = '1' AND is_deleted != '1'");
$value_emp = mysqli_fetch_array($sqllmsemployee);

// Male Emplyee
$sqllmsMaleEmpl	= $dblms->querylms("SELECT COUNT(emply_id) as total
								FROM ".EMPLOYEES."
								WHERE emply_id != '' AND emply_status = '1' AND emply_gender = 'Male' AND is_deleted != '1'");
$valueMaleEmpl = mysqli_fetch_array($sqllmsMaleEmpl);

// Female Emplyee
$sqllmsFemaleEmpl	= $dblms->querylms("SELECT COUNT(emply_id) as total
								FROM ".EMPLOYEES."
								WHERE emply_id != '' AND emply_status = '1' AND emply_gender = 'Female' AND is_deleted != '1'");
$valueFemaleEmpl = mysqli_fetch_array($sqllmsFemaleEmpl);

//Total Campus
$sqlCampus	= $dblms->querylms("SELECT COUNT(campus_id) as total
								FROM ".CAMPUS."
								WHERE campus_id != '' AND campus_status = '1' AND is_deleted != '1'");
$valueCampus = mysqli_fetch_array($sqlCampus);

//Total Male Campus
$sqlCampusMale	= $dblms->querylms("SELECT COUNT(campus_id) as total
								FROM ".CAMPUS." 
								WHERE campus_id != '' AND campus_status = '1' AND campus_for = '1' AND is_deleted != '1'");
$valueCampusMale = mysqli_fetch_array($sqlCampusMale);

//Total FeMale Campus
$sqlCampusFemale	= $dblms->querylms("SELECT COUNT(campus_id) as total
								FROM ".CAMPUS." 
								WHERE campus_id != '' AND campus_status = '1' AND campus_for = '2' AND is_deleted != '1'");
$valueCampusFemale = mysqli_fetch_array($sqlCampusFemale);

//Total Campus for Both
$sqlCampusBoth	= $dblms->querylms("SELECT COUNT(campus_id) as total
								FROM ".CAMPUS." 
								WHERE campus_id != '' AND campus_status = '1' AND campus_for = '3' AND is_deleted != '1'");
$valueCampusBoth = mysqli_fetch_array($sqlCampusBoth);

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
<div class="col-md-6 col-lg-12 col-xl-6">
	<div class="row">
		<a href="campuses.php" class="col-md-12 col-lg-6 col-xl-6">
			<section class="panel panel-featured-left panel-featured-secondary">
				<div class="panel-body">
					<div class="widget-summary">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-secondary">
								<i class="fa fa-building"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<div class="col-md-6">
									<h4 class="title">Total Schools</h4>
									<div class="info"><strong class="amount">'.$valueCampus['total'].'</strong></div>
								</div>
								<div class="col-md-6">
									<h4 class="title">Boys Campuses</h4>
									<div class="info">
										<strong class="amount">'.$valueCampusMale['total'].'</strong>
									</div>
								</div>
								<div class="col-md-6">
									<h4 class="title" title="Girls Campuses" style="text-overflow: ellipsis; overflow: hidden;white-space: nowrap;">Girls Campuses</h4>
									<div class="info">
										<strong class="amount">'.$valueCampusFemale['total'].'</strong>
									</div>
								</div>
								<div class="col-md-6">
									<h4 class="title" title="Boys & Girls Campuses" style="text-overflow: ellipsis; overflow: hidden;white-space: nowrap;">Boys & Girls Campuses</h4>
									<div class="info">
										<strong class="amount">'.$valueCampusBoth['total'].'</strong>
									</div>
								</div>
								
							</div>
							<div class="summary-footer">
								<span class="text-muted text-uppercase">School Stats</span>
							</div>
						</div>
					</div>
				</div>
			</section>
		</a>
		<a href="campuses.php" class="col-md-12 col-lg-6 col-xl-6">
			<section class="panel panel-featured-left panel-featured-secondary">
				<div class="panel-body">
					<div class="widget-summary">
						<div class="widget-summary-col">
							<div class="summary">';
								$sqlGroup	= $dblms->querylms("SELECT group_id,group_name 
																		FROM ".CAMPUS_GROUPS." 
																		WHERE group_id != '' AND group_status = '1' AND is_deleted != '1'");
								while($valueGroup = mysqli_fetch_array($sqlGroup)){
										//Total FeMale Campus
										$sqlStats	= $dblms->querylms("SELECT COUNT(campus_id) as total
																		FROM ".CAMPUS." 
																		WHERE campus_id != '' AND id_group = '".$valueGroup['group_id']."' AND  is_deleted != '1'");
										$valuesqlStats = mysqli_fetch_array($sqlStats);
									echo'<div class="col-md-4">
										<h4 class="title" data-tooltip title="'.$valueGroup['group_name'].'" style="text-overflow: ellipsis; overflow: hidden;white-space: nowrap;">'.$valueGroup['group_name'].'</h4>
										<div class="info"><strong class="amount">'.$valuesqlStats['total'].'</strong></div>
									</div>';
									unset($sqlStats,$valuesqlStats);
								}
							echo'	
							</div>
							<div class="summary-footer">
								<span class="text-muted text-uppercase">Building Wise Stats</span>
							</div>
						</div>
					</div>
				</div>
			</section>
		</a>
		<a href="campuses.php" class="col-md-12 col-lg-6 col-xl-6">
			<section class="panel panel-featured-left panel-featured-secondary">
				<div class="panel-body">
					<div class="widget-summary">
						<div class="widget-summary-col">
							<div class="summary">';
								$sqlLevel	= $dblms->querylms("SELECT level_id,level_name 
																		FROM ".CAMPUS_LEVELS." 
																		WHERE level_id != '' AND level_status = '1' AND is_deleted != '1'");
								while($valueLevel = mysqli_fetch_array($sqlLevel)){
										//Total FeMale Campus
										$sqlStats	= $dblms->querylms("SELECT COUNT(campus_id) as total
																		FROM ".CAMPUS." 
																		WHERE campus_id != '' AND id_level = '".$valueLevel['level_id']."' AND  is_deleted != '1'");
										$valuesqlStats = mysqli_fetch_array($sqlStats);
									echo'
									<div class="col-md-4">
										<h4 class="title" data-tooltip title="'.$valueLevel['level_name'].'" style="text-overflow: ellipsis; overflow: hidden;white-space: nowrap;">'.$valueLevel['level_name'].' Campuses</h4>
										<div class="info"><strong class="amount">'.$valuesqlStats['total'].'</strong></div>
									</div>';
									unset($sqlStats,$valuesqlStats);
								}
							echo'
								<div style="clear:both;"></div>
							</div>
							<div class="summary-footer">
								<span class="text-muted text-uppercase">Level Wise Stats</span>
							</div>
						</div>
					</div>
				</div>
			</section>
		</a>

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
										<h4 class="title">Total Employees</h4>
										<div class="info">
											<strong class="amount">'.$value_emp['total'].'</strong>
										</div>
									</div>
									<div class="col-md-4">
										<h4 class="title">Male Employees</h4>
										<div class="info">
											<strong class="amount">'.$valueMaleEmpl['total'].'</strong>
										</div>
									</div>
									<div class="col-md-4">
										<h4 class="title">Female Employees</h4>
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

		<!--
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
		-->
				
	</div>
</div>
<div style="clear:both;"></div>';
?>