<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '63', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '62', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '61', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '60', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '64', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '66', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '59', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '58', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '57', 'view' => '1'))){
	echo'
	<title> Resource Pack Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Resource Pack Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">
				<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="fa fa-home"></i> Resource Pack</h2>
					</header>
					<div class="panel-body">
						<div class="row row-sm">';
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '57', 'view' => '1'))){
								echo'
								<div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-sm">
									<a href="syllabus_breakdown.php">
										<div class="card card-file">
											<div class="card-file-thumb">
												<img src="assets/images/files/folder.png" width="100">
											</div>
											<div class="card-body center">
												<b>Syllabus Break-Down</b>
											</div>
										</div>
									</a>
								</div>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '58', 'view' => '1'))){
								echo'
								<div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-sm">
									<a href="learning_resources.php">
										<div class="card card-file">
											<div class="card-file-thumb">
												<img src="assets/images/files/folder.png" width="100">
											</div>
											<div class="card-body center">
												<b>Students Learning Resources</b>
											</div>
										</div>
									</a>
								</div>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '59', 'view' => '1'))){
								echo'
								<div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-sm">
									<a href="syllabus_dlp.php">
										<div class="card card-file">
											<div class="card-file-thumb">
												<img src="assets/images/files/folder.png" width="100">
											</div>
											<div class="card-body center">
												<b>Syllabus Dlp</b>
											</div>
										</div>
									</a>
								</div>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '66', 'view' => '1'))){
								echo'
								<div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-sm">
									<a href="scheme_of_study.php">
										<div class="card card-file">
											<div class="card-file-thumb">
												<img src="assets/images/files/folder.png" width="100">
											</div>
											<div class="card-body center">
												<b>Scheme Of Study</b>
											</div>
										</div>
									</a>
								</div>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '64', 'view' => '1'))){
								echo'
								<div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-sm">
									<a href="teaching_guide.php">
										<div class="card card-file">
											<div class="card-file-thumb">
												<img src="assets/images/files/folder.png" width="100">
											</div>
											<div class="card-body center">
												<b>Teaching Guides</b>
											</div>
										</div>
									</a>
								</div>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '60', 'view' => '1'))){
								echo'
								<div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-sm">
									<a href="syllabus_worksheet.php">
										<div class="card card-file">
											<div class="card-file-thumb">
												<img src="assets/images/files/folder.png" width="100">
											</div>
											<div class="card-body center">
												<b>Syllabus Work Sheets</b>
											</div>
										</div>
									</a>
								</div>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '61', 'view' => '1'))){
								echo'
								<div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-sm">
									<a href="monthly_assessment.php">
										<div class="card card-file">
											<div class="card-file-thumb">
												<img src="assets/images/files/folder.png" width="100">
											</div>
											<div class="card-body center">
												<b>Monthly Assessments</b>
											</div>
										</div>
									</a>
								</div>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '62', 'view' => '1'))){
								echo'
								<div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-sm">
									<a href="summer-work.php">
										<div class="card card-file">
											<div class="card-file-thumb">
												<img src="assets/images/files/folder.png" width="100">
											</div>
											<div class="card-body center">
												<b>Vacational Engagement Tasks</b>
											</div>
										</div>
									</a>
								</div>';
							}
							if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '63', 'view' => '1'))){
								echo'
								<div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3 mb-sm">
									<a href="video-lecture.php">
										<div class="card card-file">
											<div class="card-file-thumb">
												<img src="assets/images/files/folder.png" width="100">
											</div>
											<div class="card-body center">
												<b>Video Lectures</b>
											</div>
										</div>
									</a>
								</div>';
							}
							echo'
						</div>
					</div>
				</section>
			</div>
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>