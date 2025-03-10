<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('8', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '8', 'view' => '1'))) {
	require_once("timetable/period/query_period.php");
	echo'
	<title> Timetable  Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Timetable Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				include_once("timetable/period/list_period.php");
				include_once("include/modals/timetable/period/modals_period_add.php");
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
				window.location.href = delete_url;
			} );
		}
	</script>';
} 
else{
	header("Location: dashboard.php");
}
?>