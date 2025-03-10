<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('29', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '29', 'view' => '1')) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('27', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '27', 'view' => '1'))) {
	echo'
	<title>Trial Balance Sheet | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Trial Balance Sheet Summary</h2>
		</header>
		<div class="row">
			<div class="col-md-12">
				<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="fa fa-list"></i>  Trial Balance Sheet Summary</h2>
					</header>
					<form action="CompTrialBalanceSummary.php" target="_blank" method="POST" accept-charset="utf-8">
						<div class="panel-body">
							<div class="row mb-lg">
								<div class="col-md-offset-4 col-md-4">
									<div class="form-group">
										<label class=" control-label">Date <span class="required" aria-required="true">*</span></label>
										<div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
											<span class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</span>
											<input type="text" class="form-control" required title="Must Be Required" name="start_date" value="'.$start_date.'">
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control" required title="Must Be Required" name="end_date" value="'.$end_date.'">
										</div>
									</div>
								</div>
							</div>
							<center>
								<button type="submit" name="view_report" id="view_report" class="btn btn-primary"><i class="fa fa-search"></i> Take Print</button>
							</center>
						</div>
					</form>
				</section>
			</div>
		</div>
	</section>';
}else{
	header("location: dashboard.php");
}
?>