
	
<!doctype html>
<html class=" sidebar-light sidebar-left-big-icons">
	
	<head>
		<!-- BASIC -->
		<meta charset="UTF-8">
		<title>Teacher Note Panel | Rudras School</title>
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
					<img src="uploads/teacher_image/1.jpg" alt="user-image" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
				</figure>
				<div class="profile-info" data-lock-name="Anzo Perez" data-lock-email="info@pvssystem.com">
					<span class="name">Anzo Perez</span>
					<span class="role">Teacher</span>
				</div>
				<i class="fa custom-caret"></i>
			</a>

			<div class="dropdown-menu">
				<ul class="list-unstyled">
					<li class="divider"></li>
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
						<a href="student/category">
							<span><i class="fa fa-genderless" aria-hidden="true"></i> Make Category</span>
						</a>
					</li>
				</ul>
			</li>

			<!-- TEACHER -->
			<li class=" ">
				<a href="/teacher">
					<i class="fa fa-users"></i>
					<span>Teacher</span>
				</a>
			</li>
			
			<!-- HRM -->
			<li class="nav-parent  ">
				<a>
					<i class="glyphicon glyphicon-retweet"></i>
					<span>HRM / Payroll</span>
				</a>
				<ul class="nav nav-children">

					<!-- PAYROLL -->
					<li class=" ">
						 <a href="payroll/payslip">
							  <i class="fa fa-usd"></i>
							 <span>Generate Payslip</span>
						 </a>
					</li>
					
				   <!-- LEAVE CONTROL -->
					<li class=" ">
						 <a href="leave">
							  <i class="fa fa-hotel"></i>
							 <span>Leave Control</span>
						 </a>
					</li>
				</ul>
			</li>

			<!-- ACADEMIC -->
			<li class="nav-parent nav-expanded nav-active ">
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
								<a href="timetable/exam_view">
									<span>Exam Timetable</span>
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
					
					<!-- TEACHER NOTE -->
					<li class="nav-active ">
						 <a href="content">
						    <i class="glyphicon glyphicon-save-file"></i>
						    <span>Teacher Note</span>
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
							<span>Attendance</span>
						</a>

						<ul class="nav nav-children">

							<li class="">
								<a href="attendance">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Student Attendance</span>
								</a>
							</li>

							<li class="">
								<a href="attendance/student_report">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> Attendance Report</span>
								</a>
							</li>

							<li class="">
								<a href="attendance/teacher_own_report">
									<span><i class="fa fa-genderless" aria-hidden="true"></i> My Attendance</span>
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</li>

			<!-- LIBRARY -->
			<li class=" ">
				<a href="library">
					<i class="fa fa-fax"></i>
					<span>Library</span>
				</a>
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
			<li class=" ">
				<a href="event">
					<i class="fa fa-file-text-o"></i>
					<span>Events</span>
				</a>
			</li>

			<!-- MESSAGE -->
			<li class=" ">
				<a href="message">
					<i class="fa fa-envelope-o"></i>
					<span>Message</span>
				</a>
			</li>

			<!-- ACCOUNT -->
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
						<h2>Teacher Note Panel</h2>
					</header>

					<!-- INCLUDEING PAGE -->
					<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<a href="#add" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
			<i class="fa fa-plus-square"></i> Make Note		</a>
		<h2 class="panel-title">
			<i class="fa fa-list"></i> Teacher Note		</h2>
	</header>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed mb-none table_default">
			<thead>
				<tr>
					<th width="60">No</th>
					<th>Title</th>
					<th>Class</th>
					<th>Description</th>
					<th>File Name</th>
					<th>Date</th>
					<th>Download</th>
					<th>Options</th>
				</tr>
			</thead>
			<tbody>
				   
					<tr>
						<td>1</td>
						<td>grsce</td>
						<td>One</td>
						<td>bisnis vnivivnernaqon  AOGOSAGASGOPAN AOSNGOASNSAA onasnoanoa onaoAOANGA</td>
						<td>500_F_108349531_3zTxUAbnKv0gGzbnc7Z5vMHPrcfOJ0xC.jpg</td>
						<td>08 Sep, 2017</td>
						<td width="100px">
							<a href="content/downloader/9bfbb67c9555a47e936b65cdbbafdb7a.jpg" class="btn btn-primary btn-xs">
								<i class="fa fa-download"></i>  Download							</a>
						</td>
						<td width="120px">
							<!-- NOTE UPDATE LINK -->
							<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-xs btn-primary" onclick="showAjaxModalZoom('ajax/modal/notes/modal_note_update.php?id=1');">
							<i class="glyphicon glyphicon-edit"></i> Edit							</a>
							
							<!-- NOTE DELETE LINK -->
							<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal('content/maintain/delete/1');">
							<i class="el el-trash"></i>
							</a>
						</td>
					</tr>
							</tbody>
	    </table>

	</div>
</section>

<!-- MODAL FORM -->
<div id="add" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<div class="panel panel-featured panel-featured-primary">
		<form action="content/maintain/make" class="form-horizontal mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<div class="panel-heading">
			<h2 class="panel-title">
				<i class="fa fa-plus-square"></i> Make Teacher Note			</h2>
		</div>

		<div class="panel-body">
			<div class="form-group mt-sm">
				<label class="col-sm-3 control-label">Title <span class="required">*</span></label>
				<div class="col-sm-9">
					<input type="text" name="name" class="form-control" required title="Must Be Required">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Class <span class="required">*</span></label>
				<div class="col-sm-9">
					<select name="class_id" class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" required title="Must Be Required">
						<option value="">Select</option>
													<option value="1">One</option>
											</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">Description</label>
				<div class="col-sm-9">
					<textarea name="description" class="form-control"></textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Date Of Publish <span class="required">*</span></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" data-plugin-datepicker data-plugin-options='{ "todayHighlight" : true }' name="timestamp" required title="Must Be Required"/>
				</div>
			</div>

			<div class="form-group mb-md">
				<label class="col-md-3 control-label">File Upload <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="fileupload fileupload-new" data-provides="fileupload">
						<div class="input-append">
							<div class="uneditable-input" style="width: 62%">
								<i class="fa fa-file fileupload-exists"></i>
								<span class="fileupload-preview"></span>
							</div>
							<span class="btn btn-default btn-file">
								<span class="fileupload-exists">Change</span>

							<span class="fileupload-new">Select file</span>
							<input type="file" required title=" " name="file_name"/>
							</span>
							<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-primary modal-confirm">Save</button>
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</form>
	</div>
</div>				</section>
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