<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('62', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '62', 'view' => '1'))) { 
	echo '
	<title>Summer Work Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Summer Work Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				include_once("summer-work/list_summer-work.php");
				echo'
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
	<div id="show_modal" class="mfp-with-anim modal-block modal-block-primary mfp-hide"></div>';
}else{
	header("Location: dashboard.php");
}
?>