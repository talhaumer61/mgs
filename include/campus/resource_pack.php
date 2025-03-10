<?php
$resouceCheck = array('63', '62', '61', '60', '64', '66', '59', '58', '57');
foreach($resouceCheck as $resource){
    if(in_array($resource, $_SESSION['userlogininfo']['PERMISSIONS'])){
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
								if(in_array('57', $_SESSION['userlogininfo']['PERMISSIONS'])){
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
								if(in_array('58', $_SESSION['userlogininfo']['PERMISSIONS'])){
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
								if(in_array('59', $_SESSION['userlogininfo']['PERMISSIONS'])){
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
								if(in_array('66', $_SESSION['userlogininfo']['PERMISSIONS'])){
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
								if(in_array('64', $_SESSION['userlogininfo']['PERMISSIONS'])){
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
								if(in_array('60', $_SESSION['userlogininfo']['PERMISSIONS'])){
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
								if(in_array('61', $_SESSION['userlogininfo']['PERMISSIONS'])){
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
								if(in_array('62', $_SESSION['userlogininfo']['PERMISSIONS'])){
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
								if(in_array('63', $_SESSION['userlogininfo']['PERMISSIONS'])){
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
        break;
    }else{
		header("Location: dashboard.php");
	}
}
?>