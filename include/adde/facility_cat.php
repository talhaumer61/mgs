<?php 
echo '
<title> Facility Panel | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Facility Panel </h2>
	</header>
	<!-- INCLUDEING PAGE -->
	<div class="row">
		<div class="col-md-12">';
			include_once("facility_cat/list.php");
			echo '
		</div>
	</div>
</section>';
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var datatable = $('#table_export').dataTable({
			bAutoWidth : false,
			ordering: false,
		});
	});
</script>