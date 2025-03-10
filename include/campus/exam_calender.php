<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'view' => '1'))) {
	echo'
	<title> Exam Calender | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2> Exam Panel  </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				include_once("exam_calender/list.php");
				echo'
			</div>
		</div>
	</section>';
}else{
	header("Location: dashboard.php");
}
?>