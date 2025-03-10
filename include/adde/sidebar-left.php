<?php
//-----------------------------------------------------
$sqllmsemp  = $dblms->querylms("SELECT emply_id, id_class, id_section, id_type
										FROM ".EMPLOYEES." 
										WHERE id_campus	= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
										AND id_loginid = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' LIMIT 1");
$value_emp = mysqli_fetch_array($sqllmsemp);
//-----------------------------------------------------
echo '
<!-- start: sidebar -->
<aside id="sidebar-left" class="sidebar-left">
	<div class="sidebar-header">
		<div class="sidebar-title">
			Navigation
		</div>
		<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>

	<div class="nano">
		<div class="nano-content">
			<nav id="menu" class="nav-main" role="navigation">
				<ul class="nav nav-main">

					<!-- DASHBOARD -->
					<li class=" ">
						<a href="dashboard.php">
							<i class="fa fa-tachometer"></i>
							<span>Dashboard</span>
						</a>
					</li>

					<!-- INSPECTION START -->
					<li class=" ">
						<a href="inspectionSchedule.php"><i class="fa fa-tripadvisor"></i><span>Inscpection Schedule</span></a>
					</li>
					<!-- INSPECTION END -->

					<!-- Monthly Performa  START -->
					<li class="nav-parent  ">
						<a>
							<i class="fa fa-file-text"></i>
							<span>Facility Performa</span>
						</a>
						<ul class="nav nav-children">
							<li class="">
								<a href="performa.php">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Monthly Performa</span>
								</a>
							</li>
							<li class="">
								<a href="facility_question.php">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Facility Questions</span>
								</a>
							</li>
							<li class="">
								<a href="facility_cat.php">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Facility Category</span>
								</a>
							</li>
						</ul>
					</li>
					<!-- Monthly Performa END -->

					
					<!-- USER PROFILE START -->
					<li class=" ">
						<a href="profile.php"><i class="fa fa-lock"></i><span>My Profile</span></a>
					</li>
					<!-- USER PROFILE END -->
				</ul>
			</nav>
	</div>

<script>
// Maintain Scroll Position
	if (typeof localStorage !== "undefined") {
		if (localStorage.getItem("sidebar-left-position") !== null) {
			var initialPosition = localStorage.getItem("sidebar-left-position"),
			sidebarLeft = document.querySelector("#sidebar-left .nano-content");
			sidebarLeft.scrollTop = initialPosition;
		}
	}
</script>

	</div>
</aside>
<!-- end: sidebar -->';