<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('3', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '3', 'view' => '1'))) {
	echo '
	<title> Class Group Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Class Groups Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				include_once("class_groups/list_classgroups.php");
				echo'
			</div>
		</div>
	</section>';
}else{
	header("location: dashboard.php");
}
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var datatable = $('#table_export').dataTable({
			bAutoWidth : false,
			ordering: false,
		});
	});
</script>