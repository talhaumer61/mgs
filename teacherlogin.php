<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

include_once("include/header.php");
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'view' => '1'))) {
	require_once("include/campus/teacherlogin/query_teacher.php");
	echo'
	<title> Teacher Login | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Teacher Login </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				include_once("include/campus/teacherlogin/list_teacher.php");
				include_once("include/modals/teacherlogin/add_teacher.php");
				echo'
			</div>
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
			var datatable = $('#table_export').dataTable({
				bAutoWidth : false,
				ordering: false,
			});
		});
		$("#hideEmply").hide();
		function get_dept(id_campus) {  
			$.ajax({  
				type	: "POST",  
				url		: "include/ajax/get_dept.php",  
				data	: "id_campus="+id_campus,  
				success: function(msg){  
					$("#id_dept").html(msg); 
				}
			});  
		}
		function get_deptemployee(id_dept) {  
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_dept-emply.php",  
				data: "id_dept="+id_dept,  
				success: function(msg){  
					console.log(msg)
					$("#hideEmply").show(); 
					$("#id_employe").html(msg); 
				}
			});  
		}
		function get_employeedetail(id_employe) {  
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_employeedetail.php",  
				data: "id_employee="+id_employe,
				success: function(msg){  
					$("#getemployeedetail").html(msg); 
				}
			});  
		}
	</script>
	<?php
	echo'	
	<!-- INCLUDES MODAL -->
	<script type="text/javascript">
		function showAjaxModalZoom( url ) {
			// PRELODER SHOW ENABLE / DISABLE
			jQuery( \'#show_modal\' ).html( \'<div style="text-align:center; "><img src="assets/images/preloader.gif" /></div>\' );
			// SHOW AJAX RESPONSE ON REQUEST SUCCESS
			$.ajax( {
				url: url,
				success: function ( response ) {
					jQuery( \'#show_modal\' ).html( response );
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
					swal( "Oops", "We couldn\'t\ connect to the server!", "error" );
				} );
			} );
		}
	</script>';
}else{
	header("Location: dashboard.php");
}
include_once("include/footer.php");
?>