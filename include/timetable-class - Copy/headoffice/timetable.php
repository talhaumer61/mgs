<?php 
//-----------------------------------------------
echo '
<title> Timetable Panel | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Class Timetable Panel </h2>
	</header>
<!-- INCLUDEING PAGE -->
<div class="row">
<div class="col-md-12">';
//-----------------------------------------------
	include_once("timetable/timetable_view.php");
//-----------------------------------------------
echo '
</div>
</div>
</section>';
//-----------------------------------------------
?>