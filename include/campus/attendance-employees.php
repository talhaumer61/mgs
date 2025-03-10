<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('55', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '55', 'view' => '1'))) {
	require_once("attendance-employees/query_employees_attendce.php");
	echo'
	<title> Attendance Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Attendance Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				include_once("attendance-employees/attendance_employees_view.php");
				echo'
			</div>
		</div>
	</section>

	<script type="text/javascript">
		jQuery(document).ready(function($) {';
			if(isset($_SESSION['msg'])) { 
				echo 'new PNotify({
					title	: "'.$_SESSION['msg']['title'].'"	,
					text	: "'.$_SESSION['msg']['text'].'"	,
					type	: "'.$_SESSION['msg']['type'].'"	,
					hide	: true	,
					buttons: {
						closer	: true	,
						sticker	: false
					}
				});';
				unset($_SESSION['msg']);
			}
			echo'
		});';
		if(isset($srno)){
			echo'
			function mark_all_present() {
				var count = 1+'.$srno.';
				
				for(var i = 1; i < count; i++) {
					document.getElementById(\'pstatus_\' + i).checked = true;
				}
			}

			function mark_all_absent() {
				var count = 1+'.$srno.';
				
				for(var i = 1; i < count; i++){
					document.getElementById(\'astatus_\' + i).checked = true;
				}
			}
			
			function mark_all_holiday() {
				var count = 1+'.$srno.';
				
				for(var i = 1; i < count; i++){
					document.getElementById(\'hstatus_\' + i).checked = true;
				}
			}';
		}
		echo'
	</script>';
} else {
	header("location: dashboard.php");
}
?>