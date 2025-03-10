<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'edit' => '1'))) {	
	if(isset($_GET['id'])) {
		$id_campus = (!empty($_GET['id_campus']) ? $_GET['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
		echo '
		<section role="main" class="content-body">
			<!-- INCLUDEING PAGE -->
			<div class="row appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">';
				include_once("profile/detail.php");
				echo '
				<div class="col-md-8">
					<div class="tabs tabs-primary">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#edit" data-toggle="tab"><i class="fa fa-user"></i> <span class="hidden-xs"> Profile</span></a>
							</li>
							<li>
								<a href="#fee_structure" data-toggle="tab"><i class="fa fa-money"></i> <span class="hidden-xs"> Fee Structure</span></a>
							</li>
							<li>
								<a href="#concession_scholarship" data-toggle="tab"><i class="fa fa-money"></i> <span class="hidden-xs"> Concession / Scholarship</span></a>
							</li>
							<li>
								<a href="#fee_challans" data-toggle="tab"><i class="fa fa-cc-visa"></i> <span class="hidden-xs"> Vouchers</span></a>
							</li>
							<li>
								<a href="#resetpass" data-toggle="tab"><i class="fa fa-lock"></i> <span class="hidden-xs"> Password</span></a>
							</li>
						</ul>
						<div class="tab-content">';
							include_once("profile/edit_profile.php");
							include_once("profile/fee_structure.php");
							include_once("profile/concession_scholarship.php");
							include_once("profile/fee_challans.php");
							include_once("profile/bank_details.php");
							include_once("profile/change_password.php");
							echo '
						</div>
					</div>
				</div>
			</div>
		</section>';
	}
} else {
	header("Location: students.php");
}
?>