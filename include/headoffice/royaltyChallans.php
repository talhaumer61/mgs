<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || (arrayKeyValueSearch($_SESSION['userroles'], 'right_name', '71'))){
	require_once("royalty/royaltyChallans/query.php");
	echo'
	<title> Royalty Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Royalty Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				if($view == 'add'){
					include_once("royalty/royaltyChallans/add.php");
				}elseif($view == 'addBulk'){
					include_once("royalty/royaltyChallans/add_bulk.php");
				}elseif(isset($_GET['id'])){
					include_once("royalty/royaltyChallans/edit.php");
				}else{
					include_once("royalty/royaltyChallans/list.php");
					// include_once("include/modals/royalty/monthlyRoyalty/add.php");
					// include_once("include/modals/royalty/monthlyRoyalty/bulk.php");
					// include_once("include/modals/royalty/monthlyRoyalty/print.php");
				}
				echo'
			</div>
		</div>';
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
	</section>
	
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
	<div id="show_modal" class="mfp-with-anim modal-block modal-block-lg modal-block-primary mfp-hide"></div>
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
	header("location: dashboard.php");
}
?>