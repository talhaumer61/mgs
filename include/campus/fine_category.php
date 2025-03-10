<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('76', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '76', 'view' => '1'))) {
	echo '
	<title> Fine Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Fine Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				include_once("fine/list_cat.php");
				echo'
			</div>
		</div>
	</section>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
		var datatable = $("#table_export").dataTable({
				bAutoWidth : false,
				ordering: false,
			});
		});
	</script>';
} else {
	header("location: dashboard.php");
}
?>