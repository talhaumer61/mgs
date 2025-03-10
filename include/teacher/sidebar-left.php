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

			<!-- ACADEMIC -->
			<li class="nav-parent  ">
				<a href="academic-calender.php">
					<i class="fa fa-calendar" aria-hidden="true"></i>
					<span>Academic Calendar</span>
				</a>
			</li>
			<li class="nav-parent  ">
				<a href="timetable.php">
					<i class="fa fa-clock-o" aria-hidden="true"></i>
					<span>Lecture Schedule</span>
				</a>
			</li>
			<!-- ACADEMIC END-->';

			if($value_emp['id_class'] && $value_emp['id_type'] == 1)
			{
			echo'			
			<!-- STUDENT ATTENANCE -->
			<li class="nav-parent  ">
				<a href="attendance_students.php">
					<i class="fa fa-line-chart" aria-hidden="true"></i>
					<span>Student Attendance</span>
				</a>
			</li>
			<!-- STUDENT ATTENANCE END-->';
			}
			echo'

			
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