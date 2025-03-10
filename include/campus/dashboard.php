<?php
if(isset($_GET['id']) && !empty($_GET['id'])){
	$id_campus		= $_GET['id'];
	$campus_name	= $_GET['name'];
}else{
	$id_campus		= $_SESSION['userlogininfo']['LOGINCAMPUS'];
	$campus_name	= '';
}
echo'
<title> Dashboard | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>'.$campus_name.' Dashboard</h2>
	</header>';
	include "dashboard/modal.php";
	echo'
	<div class="row">';
		include "dashboard/main_counter.php";
		echo'
		<div class="col-md-6">';
			include "dashboard/financegraph.php";
			include "dashboard/sourceofadmission.php";
			include "dashboard/classwisestudents.php";
			include "dashboard/catwisestudents.php";
			include "dashboard/studentAttendancegraph.php";
		echo'
		</div>
		<div class="col-md-6">';
			include "dashboard/calculation.php";
			include "dashboard/list_today_birthday.php";
			include "dashboard/list_latest_inquiry.php";
			include "dashboard/list_latest_addmissions.php";
		echo'
		</div>';
		include "dashboard/daily_attendance.php";
		include "dashboard/event_calendar.php";
		echo'
	</div>
</section>';
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
            <?php 
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
            ?>	
			
		});
</script>