<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('47', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '47', 'view' => '1'))) {
	echo '
	<title> Class Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Class Panel </h2>
		</header>
		<!-- INCLUDEING PAGE -->
		<div class="row">
			<div class="col-md-12">';
				include_once("classes/list_classes.php");
				echo '
			</div>
		</div>
	</section>';
} else {
	header("location: dashboard.php");
}
?>