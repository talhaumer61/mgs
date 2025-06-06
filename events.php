
	
<!doctype html>
<html class=" sidebar-light sidebar-left-big-icons">
	
	<head>
		<!-- BASIC -->
		<meta charset="UTF-8">
		<title>Event Panel | Rudras School</title>
		<meta name="keywords" content="School Management Software" />
		<meta name="description" content="Rudras School Management System (ERP)">
		<meta name="author" content="PVSSystemsIT">
				<!-- MOBILE METAS -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- WEB FONTS  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- VENDOR CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-switch/css/bootstrap-switch.min.css" />

		<!-- SPECIFIC PAGE VENDOR CSS -->
		<link rel="stylesheet" href="assets/vendor/jquery-ui/jquery-ui.css" />
		<link rel="stylesheet" href="assets/vendor/jquery-ui/jquery-ui.theme.css" />
		<link rel="stylesheet" href="assets/vendor/select2/css/select2.css" />
		<link rel="stylesheet" href="assets/vendor/select2-bootstrap-theme/select2-bootstrap.min.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="assets/vendor/dropzone/basic.css" />
		<link rel="stylesheet" href="assets/vendor/dropzone/dropzone.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" />
		<link rel="stylesheet" href="assets/vendor/summernote/summernote.css" />
		<link rel="stylesheet" href="assets/vendor/elusive-icons/css/elusive-icons.min.css" />

		<!-- SWEETALERT JS/CSS -->
		<link rel="stylesheet" href="assets/sweetalert/sweetalert_custom.css">
		<script src="assets/sweetalert/sweetalert.min.js"></script>

        <!-- PNOTIFY NOTIFICATIONS CSS -->
		<link rel="stylesheet" href="assets/vendor/pnotify/pnotify.custom.css" />

        <!-- DATATABLES PAGE CSS -->
		<link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
        
        <!-- FILEUPLOAD PAGE CSS -->
        <link rel="stylesheet" href="assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css" />
		
		<!-- FULLCALENDAR CSS -->
		<link rel="stylesheet" href="assets/vendor/fullcalendar/fullcalendar.css" />

		<!-- THEME CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme.css" />

		<!-- SKIN CSS -->
		<link rel="stylesheet" href="assets/stylesheets/skins/default.css" />

		<!-- THEME CUSTOM CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">
		
		<!-- PVS SYSTEMS CSS -->
		<link rel="stylesheet" href="assets/stylesheets/pvs-systems.css">

		<!-- HEAD LIBS -->
		<script src="assets/vendor/modernizr/modernizr.js"></script>
        
        <!-- JQUERY LIBS -->
		<script src="assets/vendor/jquery/jquery.js"></script>
	
        <!--WEB ICON-->
	    <link rel="shortcut icon" href="assets/images/favicon.png">

        <!-- DISABLE SQUARE BORDERS -->
        
        <!--HIGHCHARTS-->
        <script src="assets/vendor/highcharts/-highcharts.js" type="text/javascript"></script>
		
		<!-- NUMBER SPINNERS DISABLE -->
		<style>
			input[type="number"]::-webkit-outer-spin-button,
			input[type="number"]::-webkit-inner-spin-button {
				-webkit-appearance: none;
				margin: 0;
			}
			input[type="number"] {
				-moz-appearance: textfield;
			}
		</style>	</head>
	
	<body class="loading-overlay-showing" data-loading-overlay>
	    <!-- PAGE LOADING OVERLAY -->
		<div class="loading-overlay">
			<div class="bounce-loader">
				<div class="bounce1"></div>
				<div class="bounce2"></div>
				<div class="bounce3"></div>
			</div>
		</div>
		
		<section class="body">
			<!-- INCLUDEING HEADER -->
            <!-- START: HEADER -->
<header class="header">
	<div class="logo-container">
		<a href="dashboard" class="logo">
			<img src="uploads/logo.png" height="40" />
		</a>
		<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>

	<!-- SEARCH & USER BOX -->
	<div class="header-right">
	    			<!-- SEARCH BAR -->
			<form action="student/search" class="search nav-form" method="post" accept-charset="utf-8">
				<div class="input-group input-search">
					<input type="text" class="form-control" name="search_text" id="search_text" placeholder="Student Search...">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
					</span>
				</div>
			</form>
	    		
		<span class="separator"></span>

		<ul class="notifications">
		
			<!-- SESSION CHANGER -->
							<li>
					<a href="#modalAnim" class="modal-with-move-anim notification-icon" >
						<i class="fa fa-calendar"></i>
					</a>

					<div id="modalAnim" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
						
						<section class="panel panel-featured panel-featured-primary">
						<form action="settings/change_session" class="validate" method="post" accept-charset="utf-8">
							<header class="panel-heading">
								<h4 class="panel-title">Running Session : 2018-2019</h4>
							</header>
							<div class="panel-body">
								<div class="modal-wrapper">
									<div class="form-group">
										<label>Academic Session</label>
										<select name="year_id" class="form-control" data-plugin-selectTwo data-width="100%" data-plugin-options='{ "minimumResultsForSearch": -1 }'
										required title="Must Be Required">
											<option value="">Select Academic Session</option>
																						<option value="1"selected>2018-2019</option>
																						<option value="3">2019-2020</option>
																						<option value="4">2020-2021</option>
																						<option value="5">2021-2022</option>
																						<option value="6">2022-2023</option>
																						<option value="7">2023-2024</option>
																						<option value="8">2024-2025</option>
																						<option value="9">2025-2026</option>
																					</select>
									</div>
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 text-right">
										<button type="submit" class="btn btn-primary">Change Session</button>
										<button class="btn btn-default modal-dismiss">Cancel</button>
									</div>
								</div>
							</footer>
						</form>
						</section>
						
					</div>
				</li>
					
			 <!-- MESSAGE NOTIFICATIONS -->
			 			 
			<li>
				<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
					<i class="fa fa-envelope"></i>
									</a>

				<div class="dropdown-menu notification-menu" style="min-width: 290px;">
					<div class="notification-title">
						Messages
					</div>
					<div class="content">
						<ul>
						
							<li>
								<a href="message/read/24" class="clearfix">
									<!-- PREVIEW OF SENDER IMAGE -->
									<figure class="image">
										<img src="uploads/parent_image/1.jpg" height="30" class="img-box-boder" />
									</figure>

									<!-- PREVIEW OF SENDER NAME AND DATE -->
									<span class="title line"><strong>Krishna Ray</strong>
									<small>- 03 May, 2018</small>  </span>

									 <!-- PREVIEW OF THE LAST UNREAD MESSAGE SUB-STRING -->
									<span class="message">hello</span>
								</a>
							</li>
													</ul>
                        						<div class="text-right">
							<a href="message" class="view-more">View All</a>
						</div>
					</div>
				</div>
			</li>
		</ul>
		<span class="separator"></span>
		<div id="userbox" class="userbox">
			<a href="#" data-toggle="dropdown">
				<figure class="profile-picture">
					<img src="uploads/admin_image/1.jpg" alt="user-image" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
				</figure>
				<div class="profile-info" data-lock-name="Admin" data-lock-email="info@pvssystem.com">
					<span class="name">Admin</span>
					<span class="role">Admin</span>
				</div>
				<i class="fa custom-caret"></i>
			</a>

			<div class="dropdown-menu">
				<ul class="list-unstyled">
					<li class="divider"></li>
										<li>
						<a role="menuitem" tabindex="-1" href="settings"><i class="fa fa-wrench"></i> Settings</a>
					</li>
										<li>
						<a role="menuitem" tabindex="-1" href="profile"><i class="fa fa-user"></i> Edit Profile</a>
					</li>
					<li>
						<a role="menuitem" tabindex="-1" href="signin/logout"><i class="fa fa-power-off"></i> Logout</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>
<!-- END: HEADER -->			
			<div class="inner-wrapper">
				<!-- INCLUDEING NAVIGATION -->
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
				<a href="dashboard">
					<i class="fa fa-tachometer"></i>
					<span>Dashboard</span>
				</a>
			</li>
			
			<!-- STUDENT -->
			<li class="nav-parent  ">
				<a>
					 <i class="fa fa-slideshare"></i>
					<span>Student</span>
				</a>
				<ul class="nav nav-children">
					<li class=" ">
						<a href="student">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Student Details</span>
						</a>
					</li>
					<li class=" ">
						<a href="student/add">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Make Admission</span>
						</a>
					</li>
					<li class=" ">
						<a href="admission/view">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Online Admission</span>
						</a>
					</li>
					<li class=" ">
						<a href="student/category">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Make Category</span>
						</a>
					</li>
				</ul>
			</li>

			<!-- STUDENT PROMOTION -->
			<li class=" ">
				<a href="student/promotion">
					 <i class="fa fa-random"></i>
					<span>Student Transfer</span>
				</a>
			</li>
			
			<!-- USER -->
			<li class="nav-parent  ">
				<a>
					<i class="fa fa-user-circle"></i>
					<span>Users</span>
				</a>
				<ul class="nav nav-children">
					<li class=" ">
						<a href="parents">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Parents</span>
						</a>
					</li>
					<li class=" ">
						<a href="librarian">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Librarian</span>
						</a>
					</li>
					<li class=" ">
						<a href="accountant">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Accountant</span>
						</a>
					</li>
				</ul>
			</li>

			<!-- TEACHER -->
			<li class="nav-parent  ">
				<a>
					<i class="fa fa-users"></i>
					<span>Teacher</span>
				</a>
				<ul class="nav nav-children">
					<li class=" ">
						<a href="teacher">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Teacher List</span>
						</a>
					</li>
					<li class=" ">
						<a href="teacher/designation">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Add Designation</span>
						</a>
					</li>
					<li class=" ">
						<a href="teacher/add">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Add New Teacher</span>
						</a>
					</li>
				</ul>
			</li>
			
			<!-- HRM -->
			<li class="nav-parent  ">
				<a>
					<i class="glyphicon glyphicon-retweet"></i>
					<span>HRM / Payroll</span>
				</a>
				<ul class="nav nav-children">
					
					<!-- PAYROLL -->
					<li class="nav-parent  ">
						<a>
							<i class="fa fa-usd" aria-hidden="true"></i>
							<span>Payroll</span>
						</a>
						<ul class="nav nav-children">
							<li class=" ">
								<a href="payroll/salary_control">
									<span>Salary Control</span>
								</a>
							</li>
							<li class=" ">
								<a href="payroll/salary">
									<span>Employee Salary</span>
								</a>
							</li>
							<li class=" ">
								<a href="payroll/payslip">
									<span>Generate Payslip</span>
								</a>
							</li>
						</ul>
					</li>
					
					<!-- LEAVE MANAGEMENTS -->
					<li class="nav-parent  ">
						<a>
							<i class="fa fa-hotel" aria-hidden="true"></i>
							<span>Leave Control</span>
						</a>
						<ul class="nav nav-children">
							<li class=" ">
								<a href="leave/category">
									<span>Category</span>
								</a>
							</li>
							<li class=" ">
								<a href="leave">
									<span>Application</span>
								</a>
							</li>
							
						</ul>
					</li>
					
				</ul>
			</li>

			<!-- ACADEMIC -->
			<li class="nav-parent  ">
				<a>
					<i class="fa fa-university" aria-hidden="true"></i>
					<span>Academic</span>
				</a>

				<ul class="nav nav-children">
					
					<!-- CLASS -->
					<li class="nav-parent  ">
						<a>
							<i class="fa fa-tasks" aria-hidden="true"></i>
							<span>Class</span>
						</a>
						<ul class="nav nav-children">
							<li class=" ">
								<a href="classes">
									<span>Control Classes</span>
								</a>
							</li>
							<li class=" ">
								<a href="classes/sections">
									<span>Control Sections</span>
								</a>
							</li>
						</ul>
					</li>

					<!-- TIMETABLE -->
					<li class="nav-parent  ">
						<a>
							<i class="fa fa-clock-o" aria-hidden="true"></i> Timetable						</a>
						<ul class="nav nav-children">
							<li class="">
								<a href="timetable">
									<span>Daily Class Routine</span>
								</a>
							</li>

							<li class="">
								<a href="timetable/make_class">
									<span>Set Class Routine</span>
								</a>
							</li>

							<li class="">
								<a href="timetable/exam_view">
									<span>Exam Timetable</span>
								</a>
							</li>

							<li class="">
								<a href="timetable/make_exam">
									<span>Set Exam Timetable</span>
								</a>
							</li>
						</ul>
					</li>

				   <!-- SUBJECT -->
					<li class=" ">
						 <a href="subject">
							  <i class="fa fa-book"></i>
							 <span>Subject</span>
						 </a>
					</li>

					<!-- TEACHER ASSIGNMENT -->
					<li class=" ">
						 <a href="suggestion">
							 <i class="fa fa-download"></i>
							 <span>Set Assignment</span>
						 </a>
					</li>
					
					<!-- STUDENT AWARD -->
					<li class=" ">
						 <a href="student/award">
							 <i class="fa fa-trophy"></i>
							 <span>Award</span>
						 </a>
					</li>

				</ul>
			</li>

			<!-- ADMINISTRATION -->
			<li class="nav-parent  ">
				<a>
					<i class="fa fa-bar-chart-o" aria-hidden="true"></i>
					<span>Administration</span>
				</a>
				<ul class="nav nav-children">
					<!-- EXAM -->
					<li class="nav-parent  ">
						<a>
							<i class="fa fa-graduation-cap"></i>
							<span>Exam</span>
						</a>
						<ul class="nav nav-children">
							<li class=" ">
								<a href="exam">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Exam List</span>
								</a>
							</li>
							<li class=" ">
								<a href="exam/term">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Set Exam Term</span>
								</a>
							</li>
							<li class=" ">
								<a href="exam/attendance_report_view">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Exam Attendance</span>
								</a>
							</li>
							<li class=" ">
								<a href="exam/attendance">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Set Attendance</span>
								</a>
							</li>
						</ul>
					</li>

					<!-- MARKS -->
					<li class="nav-parent  ">
						<a>
							<i class="glyphicon glyphicon-pushpin"></i>
							<span>Marks</span>
						</a>
						<ul class="nav nav-children">
							<li class=" ">
								<a href="exam/marks">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Marks Register</span>
								</a>
							</li>
							<li class=" ">
								<a href="exam/grade">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Grades Range</span>
								</a>
							</li>
							<li class=" ">
								<a href="exam/marks_sheet">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Marks Sheet</span>
								</a>
							</li>
						</ul>
					</li>
					
					<!-- ATTENDANCE CONTROL -->
					<li class="nav-parent  ">
						<a>
							<i class="fa fa-line-chart"></i>
							<span> Attendance</span>
						</a>

						<ul class="nav nav-children">

							<li class="">
								<a href="attendance">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Student</span>
								</a>
							</li>

							<li class="">
								<a href="attendance/employees">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Employees</span>
								</a>
							</li>

						</ul>
					</li>
				</ul>
			</li>

			<!-- LIBRARY -->
			<li class="nav-parent  ">
				<a>
					<i class="fa fa-fax"></i>
					<span>Library</span>
				</a>
				<ul class="nav nav-children">

					<li class="">
						<a href="library">
							<span><i class="fa fa-circle-o"></i> Books Stock</span>
						</a>
					</li>
					
					<li class="">
						<a href="library/book_category">
							<span><i class="fa fa-circle-o"></i> Books Category</span>
						</a>
					</li>
					
					<li class="">
						<a href="library/book_maintain">
							<span><i class="fa fa-circle-o"></i> Books Maintain</span>
						</a>
					</li>
					
				</ul>
			</li>
			
			<!-- GALLERY -->
			<li class=" ">
				<a href="gallery">
					<i class="fa fa-file-picture-o"></i>
					<span>Media Gallery</span>
				</a>
			</li>
			
			<!-- TRANSPORT -->
			<li class="nav-parent  ">
				<a>
					<i class="fa fa-bus"></i>
					<span>Transport</span>
				</a>
				<ul class="nav nav-children">
				
					
					<li class="">
						<a href="transport">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Route Control</span>
						</a>
					</li>

					<li class="">
						<a href="transport/vehicle">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Vehicle Control</span>
						</a>
					</li>

					<li class="">
						<a href="transport/user">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Transport Allocation</span>
						</a>
					</li>

				</ul>
			</li>
			
			<!-- HOSTELS -->
			<li class="nav-parent  ">
				<a>
					<i class="fa fa-sitemap"></i>
					<span>Hostel</span>
				</a>
				<ul class="nav nav-children">

					<li class="">
						<a href="hostels">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Hostel Control</span>
						</a>
					</li>

					<li class="">
						<a href="hostels/room">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Room Control</span>
						</a>
					</li>
					
					<li class="">
						<a href="hostels/type">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Hostel Type</span>
						</a>
					</li>
					
					<li class="">
						<a href="hostels/user">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Hostel Users</span>
						</a>
					</li>

				</ul>
			</li>
			
			<!-- EVENTS -->
			<li class="nav-active ">
				<a href="event">
					<i class="fa fa-file-text-o"></i>
					<span>Events</span>
				</a>
			</li>
			
		   <!-- FINANCE CONTROL -->
			<li class="nav-parent  ">
				<a>
					<i class="fa fa-cc-visa"></i>
					<span>Finance Control</span>
				</a>
				<ul class="nav nav-children">
					<li class="nav-parent  ">
						<a>
							<i class="fa fa-genderless" aria-hidden="true"></i> Fees						</a>
						<ul class="nav nav-children">
							<li class=" ">
								<a href="accounting/payment">
									Make Payments Invoice								</a>
							</li>
							<li class=" ">
								<a href="accounting/invoice_history">
									Fees Collect / History								</a>
							</li>
							<li class=" ">
								<a href="accounting/fee_category">
									</i>Fee Category								</a>
							</li>
						</ul>
					</li>

					<li class="nav-parent  ">
						<a>
							<i class="fa fa-genderless" aria-hidden="true"></i> Balance Sheet						</a>
						<ul class="nav nav-children">
							<li class=" ">
								<a href="accounting/costing">
									Costing Sheet								</a>
							</li>
							<li class=" ">
								<a href="accounting/costing_category">
									Costing Category								</a>
							</li>
							<li class=" ">
								<a href="accounting/earning">
									Earning Sheet								</a>
							</li>
							<li class=" ">
								<a href="accounting/earning_category">
									Earning Category								</a>
							</li>
						</ul>
					</li>

				</ul>
			</li>
			
			<!-- BULK MESSAGE -->
			<li class="nav-parent  ">
				<a>
					<i class="glyphicon glyphicon-comment"></i>
					<span>Bulk Email / SMS</span>
				</a>
				<ul class="nav nav-children">

					<li class="">
						<a href="bulksms/templates">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Template</span>
						</a>
					</li>

					<li class="">
						<a href="bulksms">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Message</span>
						</a>
					</li>
					<li class="">
						<a href="bulksms/alert">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Alert SMS</span>
						</a>
					</li>
				</ul>
			</li>

			<!-- MESSAGE -->
			<li class=" ">
				<a href="message">
					<i class="fa fa-envelope-o"></i>
					<span>Message</span>
				</a>
			</li>

			<!-- REPORT -->
			<li class="nav-parent  ">
				<a>
					<i class="fa fa-pie-chart"></i>
					<span>Report</span>
				</a>
				<ul class="nav nav-children">

					<li class="">
						<a href="attendance/student_report">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Student Attendance</span>
						</a>
					</li>

					<li class="">
						<a href="attendance/employees_report">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Employees Attendance</span>
						</a>
					</li>
					
					<li class="">
						<a href="accounting/fees_report">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Balance Fee By Date</span>
						</a>
					</li>

				</ul>
			</li>

			<!-- SETTINGS -->
			<li class="nav-parent  ">
				<a>
					<i class="fa fa-suitcase"></i>
					<span>Settings</span>
				</a>
				<ul class="nav nav-children">
					<li class=" ">
						<a href="settings">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> System Settings</span>
						</a>
					</li>
					<li class=" ">
						<a href="payment/settings">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Payment Settings</span>
						</a>
					</li>
					<li class=" ">
						<a href="smssettings">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> SMS Settings</span>
						</a>
					</li>
					<li class=" ">
						<a href="sessions">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Session Settings</span>
						</a>
					</li>
					<li class=" ">
						<a href="database_backup">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Database Backup</span>
						</a>
					</li>
				</ul>
			</li>

			<!-- USER PROFILE -->
			<li class=" ">
				<a href="profile">
					<i class="fa fa-lock"></i>
					<span>My Profile</span>
				</a>
			</li>
		</ul>
	 </nav>

	</div>

		<script>
			// Maintain Scroll Position
			if (typeof localStorage !== 'undefined') {
				if (localStorage.getItem('sidebar-left-position') !== null) {
					var initialPosition = localStorage.getItem('sidebar-left-position'),
						sidebarLeft = document.querySelector('#sidebar-left .nano-content');
					sidebarLeft.scrollTop = initialPosition;
				}
			}
		</script>

	</div>
</aside>
<!-- end: sidebar -->
				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Event Panel</h2>
					</header>

					<!-- INCLUDEING PAGE -->
					<div class="row">
	<div class="col-md-12">
		<div class="tabs tabs-primary">
			<ul class="nav nav-tabs">
				<li class="active">
				<a href="#list" data-toggle="tab">
                    <i class="fa fa-list"></i> 
                   <span class="hidden-xs"> Event List</span>
                </a>
				</li>
				<li>
				<a href="#add" data-toggle="tab">
                   <i class="fa fa-plus-square"></i>
                   <span class="hidden-xs"> New</span>
                </a>
				</li>
			</ul>

			<div class="tab-content">
				<br>
				<div class="tab-pane box active" id="list">
					<table class="table table-bordered table-condensed table-striped mb-none table_default">
						<thead>
							<tr>
								<th>#</th>
								<th>Title</th>
								<th>Details</th>
								<th>From Date</th>
								<th>To Date</th>
								<th>Event To</th>
								<th>Status</th>
								<th>Options</th>
							</tr>
						</thead>
						<tbody>
														<tr>
								<td>1</td>
								<td>Holiday</td>
								<td>Summer vacation</td>
								<td>02/Jan/2019</td>
								<td>16/Jan/2019</td>
								<td>
								Teacher<br>Accountant<br>Student<br>Parent<br>								</td>
								<td width="50">
									<div class="switch switch-sm switch-primary switch-off" id="1">
										<input type="checkbox" name="switch" id="switch1" data-plugin-ios-switch checked='checked' />
									</div>
								</td>
								<td width="130">
									<!-- UPDATE LINK -->
									<a class="modal-with-move-anim-pvs btn btn-primary btn-xs" href="#edit_1"><i class="glyphicon glyphicon-edit"></i> Edit</a>
									<div id="edit_1" class="mfp-with-anim modal-block modal-block-primary mfp-hide">
										<div class="row">
											<div class="col-md-12">
												<section class="panel panel-featured panel-featured-primary">
													<form action="event/maintain/edit/1" class="form-horizontal validate" method="post" accept-charset="utf-8">
														<header class="panel-heading">
															<h2 class="panel-title">
																<i class="glyphicon glyphicon-edit"></i> Edit Event															</h2>
														</header>

														<div class="panel-body">
															<div class="form-group">
																<label class="col-sm-2 control-label">Title <span class="required">*</span></label>
																<div class="col-sm-10">
																	<input type="text" class="form-control" required title="Must Be Required" name="event_title"
																	value="Holiday"/>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-2 control-label">Details</label>
																<div class="col-sm-10">
																	<textarea name="event_description" data-plugin-summernote class="summernote summernoteEx" ><p>Summer vacation<br></p></textarea>
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-sm-2 control-label"> Event To <span class="required">*</span> </label>
																<div class="col-sm-10">
																	<select name="event_user[]" class="form-control mb-sm" multiple data-plugin-selectTwo data-width="100%" required
																	title="Must Be Required">
																		<option value="teacher" selected>Teacher</option>
																		<option value="librarian" >Librarian</option>
																		<option value="accountant" selected>Accountant</option>
																		<option value="parent" selected>Parents</option>
																		<option value="student" selected>Student</option>
																	</select>
																</div>
															</div>
															
															<div class="form-group">
																 <label class="col-sm-2 control-label">Date <span class="required">*</span></label>
																 <div class="col-sm-10">
																	<div class="input-daterange input-group" data-plugin-datepicker>
																		<span class="input-group-addon">
																			<i class="fa fa-calendar"></i>
																		</span>
																		<input type="text" class="form-control" required title="Must Be Required" name="start_timestamp"
																		value="01/02/2019">
																		<span class="input-group-addon">to</span>
																		<input type="text" class="form-control" required title="Must Be Required" name="end_timestamp"
																		value="01/16/2019">
																	</div>
																</div>
															</div>
															<div class="form-group mb-lg">
																<label class="col-sm-3 control-label">Send Sms To All</label>
																<div class="col-sm-9">
																	<div class="radio-custom radio-inline">
																		<input type="radio" id="radioExample1" name="check_sms" value="1" onchange="return get_hidden_event_modal(this.value)">
																		<label for="radioExample1">By SMS</label>
																	</div>

																	<div class="radio-custom radio-inline">
																		<input type="radio" id="radioExample2" name="check_sms" value="2" onchange="return get_hidden_event_modal(this.value)">
																		<label for="radioExample2">By Email</label>
																	</div>

																	<div class="radio-custom radio-inline">
																		<input type="radio" id="radioExample3" name="check_sms" value="3" checked="" onchange="return get_hidden_event_modal(this.value)">
																		<label for="radioExample3">No</label>
																	</div>
																	
																	<div id="hidden-on-sms-modal" style="display: none;">
																		<span class="badge badge-primary mt-md">
																			Sms Service Not Activated																		</span>
																	</div>
																</div>
															</div>
														</div>
														<footer class="panel-footer">
															<div class="row">
																<div class="col-md-12 text-right">
																	<button type="submit" class="btn btn-primary">Update</button>
																	<button class="btn btn-default modal-dismiss">Cancel</button>
																</div>
															</div>
														</footer>
													</form>
												</section>
											</div>
										</div>
									</div>
									
									<!-- DELETION LINK -->
									<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal('event/maintain/delete/1');">
										<i class="el el-trash"></i>
									</a>
								</td>
							</tr>
													</tbody>
					</table>
				</div>
				
				<div class="tab-pane box" id="add">
					<div class="box-content">
						<form action="event/maintain/create" class="form-horizontal validate" target="_top" method="post" accept-charset="utf-8">
						<div class="form-group">
							<label class="col-sm-3 control-label">Title <span class="required">*</span></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" required title="Must Be Required" name="event_title"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Details</label>
							<div class="col-sm-6">
								<textarea name="event_description" data-plugin-summernote class="summernote summernoteEx"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Date <span class="required">*</span></label>
							<div class="col-sm-6">
								<div class="input-daterange input-group" data-plugin-datepicker>
									<span class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</span>
									<input type="text" class="form-control" name="start_timestamp" required title="Must Be Required">
									<span class="input-group-addon">to</span>
									<input type="text" class="form-control" name="end_timestamp">
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label"> Event To <span class="required">*</span> </label>
							<div class="col-sm-6">
								<select name="event_user[]" class="form-control mb-sm" data-plugin-selectTwo multiple data-width="100%" data-plugin-options='{ "placeholder": "Select" }' required
								title="Must Be Required">
									<option value="teacher">Teacher</option>
									<option value="librarian">Librarian</option>
									<option value="accountant">Accountant</option>
									<option value="student">Student</option>
									<option value="parent">Parents</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Alert To All </label>
							<div class="col-sm-6">
								
								<div class="radio-custom radio-inline">
									<input type="radio" id="radioExample1" name="check_sms" value="1" onchange="return get_hidden_event(this.value)">
									<label for="radioExample1">By SMS</label>
								</div>
								
								<div class="radio-custom radio-inline">
									<input type="radio" id="radioExample2" name="check_sms" value="2" onchange="return get_hidden_event(this.value)">
									<label for="radioExample2">By Email</label>
								</div>
								
								<div class="radio-custom radio-inline">
									<input type="radio" id="radioExample3" name="check_sms" value="3" checked="" onchange="return get_hidden_event(this.value)">
									<label for="radioExample3">No</label>
								</div>

								<div id="hidden-on-sms" style="display: none;">
									<span class="badge badge-primary mt-md">
										Sms Service Not Activated									</span>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-6">
								<button type="submit" class="btn btn-primary"> Make Event </button>
							</div>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	//CUSTOMIZE SUMMER-NOTE
	$( document ).ready( function () {
			$('.summernoteEx').themePluginSummerNote({
				height: 180,
				placeholder: 'Add Notice',
				codemirror: {
					"theme": "ambiance"
				},
				toolbar: [
					["style", ["style"]],
					["font", ["bold", "underline", "clear"]],
					["fontname", ["fontname"]],
					["color", ["color"]],
					["para", ["ul", "ol", "paragraph"]]
					//["insert", ["link", "picture", "video"]],
				]
			});
	} );
	
	//EVENTS  STATUS
    var status = '';
    var id = 0;
    $('.switch input[type=checkbox]').on('change', function(){

        if($(this).prop('checked')) {
            status = 'chacked';
            id = $(this).parent().attr("id");
        } else {
            status = 'unchacked';
            id = $(this).parent().attr("id");
        }
		
		if((status != '' || status != null) && (id !='')) {
			
            $.ajax({
                method: 'POST',
                url: 'event/status',
                data: "id=" + id + "&status=" + status,
                dataType: "html",
                success: function(data) {
					PNotify.removeAll();
					if(data == 'Success') {

						new PNotify({
							title: 'Successful',
							text: 'Data Updated',
							shadow: true,
							type: 'success',
							buttons: {
								sticker: false
							}
						});

					} else {

						new PNotify({
						title: 'Error',
						text: 'Data Updated',
						shadow: true,
						type: 'error',
						buttons: {
							sticker: false
						}
						});
					}
                }
            });
			
		}
    });
	
	<!--ACTIVE SMS SERVICE SHOW / HIDDEN-->
	function get_hidden_event( mode_id ) {
	if(mode_id == '1'){
			$("#hidden-on-sms").show("slow");
		} else {
			$("#hidden-on-sms").hide("slow");
		}
	}
	
	<!--ACTIVE SMS SERVICE SHOW / HIDDEN-->
	function get_hidden_event_modal( mode_id ) {
	if(mode_id == '1'){
			$("#hidden-on-sms-modal").show("slow");
		} else {
			$("#hidden-on-sms-modal").hide("slow");
		}
	}
</script>				</section>
			</div>
		</section>
		
        <!-- INCLUDES MODAL -->
         	<script type="text/javascript">
		function showAjaxModalZoom( url ) {
			// PRELODER SHOW ENABLE / DISABLE
			jQuery( '#show_modal' ).html( '<div style="text-align:center; "><img src="assets/images/preloader.gif" /></div>' );

			// SHOW AJAX RESPONSE ON REQUEST SUCCESS
			$.ajax( {
				url: url,
				success: function ( response ) {
					jQuery( '#show_modal' ).html( response );
				}
			} );
		}
	</script>

	<!-- (STYLE AJAX MODAL)-->
	<div id="show_modal" class="mfp-with-anim modal-block modal-block-primary mfp-hide"></div>

	<script type="text/javascript">
		function confirm_modal( delete_url ) {
			swal( {
				title: "Are you sure?",
				text: "Are you sure that you want to delete this information?",
				type: "warning",
				showCancelButton: true,
				showLoaderOnConfirm: true,
				closeOnConfirm: false,
				confirmButtonText: "Yes, delete it!",
				cancelButtonText: "Cancel",
				confirmButtonColor: "#ec6c62"
			}, function () {
				$.ajax( {
					url: delete_url,
					type: "POST"
				} )
				.done( function ( data ) {
					swal( {
						title: "Deleted",
						text: "Information has been successfully deleted",
						type: "success"
					}, function () {
						location.reload();
					} );
				} )
				.error( function ( data ) {
					swal( "Oops", "We couldn't connect to the server!", "error" );
				} );
			} );
		}
	</script>    
		<!-- INCLUDES BOTTOM -->
				<!-- VENDOR -->
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>
		<script src="assets/vendor/bootstrap-switch/js/bootstrap-switch.min.js"></script>
		<script src="assets/vendor/jquery-ui/jquery-ui.js"></script>
		<script src="assets/vendor/jqueryui-touch-punch/jqueryui-touch-punch.js"></script>
		<script src="assets/vendor/select2/js/select2.js"></script>
		<script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
		<script src="assets/vendor/jquery-maskedinput/jquery.maskedinput.js"></script>
		<script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
		<script src="assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
		<script src="assets/vendor/bootstrap-timepicker/bootstrap-timepicker.js"></script>
		<script src="assets/vendor/fuelux/js/spinner.js"></script>
		<script src="assets/vendor/dropzone/dropzone.js"></script>
		<script src="assets/vendor/bootstrap-markdown/js/markdown.js"></script>
		<script src="assets/vendor/bootstrap-markdown/js/to-markdown.js"></script>
		<script src="assets/vendor/bootstrap-markdown/js/bootstrap-markdown.js"></script>
		<script src="assets/vendor/summernote/summernote.js"></script>
		<script src="assets/vendor/bootstrap-maxlength/bootstrap-maxlength.js"></script>
		<script src="assets/vendor/ios7-switch/ios7-switch.js"></script>
		<script src="assets/vendor/bootstrap-confirmation/bootstrap-confirmation.js"></script>

		<!-- DATATABLES PAGE VENDOR -->
		<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
		<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
        
        <!-- FILEINPUT JS -->
		<script src="assets/javascripts/fileinput.js"></script>
		<script src="assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>
        	
		<!-- PNOTIFY NOTIFICATIONS JS -->
		<script src="assets/vendor/pnotify/pnotify.custom.js"></script>
		
		<!-- ANIMATIONS APPEAR JS -->
		<script src="assets/vendor/jquery-appear/jquery-appear.js"></script>

        <!-- FORM VALIDATION -->
		<script src="assets/vendor/jquery-validation/jquery.validate.js"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>
		
		<!-- THEME CUSTOM -->
		<script src="assets/javascripts/theme.custom.js"></script>
		
		<!-- THEME INITIALIZATION FILES -->
		<script src="assets/javascripts/theme.init.js"></script>

        <!-- CALENDAR FILES -->
        <script src="assets/vendor/moment/moment.js"></script>
		<script src="assets/vendor/fullcalendar/fullcalendar.js"></script>

        <!-- CHART FILES -->
		<script src="assets/vendor/liquid-meter/liquid.meter.js"></script>
		<script src="assets/vendor/snap.svg/snap.svg.js"></script>
		<script src="assets/vendor/snap.svg/snap.svg.js"></script>
		<script src="assets/vendor/liquid-meter/liquid.meter.js"></script>
	
		<!-- USER JS -->
		<script src="assets/javascripts/user_config/dashboard.js"></script>
		<script src="assets/javascripts/user_config/forms_validation.js"></script>
		<script src="assets/javascripts/modals.js"></script>
		
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{
				$('.table_default').dataTable( {
					bAutoWidth : false,
					ordering: false
				});
			});
		</script>

		<!-- SHOW PNOTIFIVATION -->
		
		
		<script type="text/javascript">
			$('.popup-youtube').magnificPopup({
				disableOn: 700,
				type: 'iframe',
				mainClass: 'mfp-fade',
				removalDelay: 160,
				preloader: false,

				fixedContentPos: false
			});

			$('.thumbnail .mg-toggle').parent()
				.on('show.bs.dropdown', function( ev ) {
					$(this).closest('.mg-thumb-options').css('overflow', 'visible');
				})
				.on('hidden.bs.dropdown', function( ev ) {
					$(this).closest('.mg-thumb-options').css('overflow', '');
				});

			$('.thumbnail').on('mouseenter', function() {
				var toggle = $(this).find('.mg-toggle');
				if ( toggle.parent().hasClass('open') ) {
					toggle.dropdown('toggle');
				}
			});
		</script>	</body>
</html>