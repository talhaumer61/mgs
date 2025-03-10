<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))) { 
	require_once("challan_description/query.php");
	echo'
	<title> Challan Description | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2> Challan Description </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				include_once("include/modals/challan_description/add.php");
				include_once("challan_description/list.php");
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
		
		function get_latefee_type() {
			selectedOption = $('#id_latefeetype').val();
			if (selectedOption === "1") {
				$('#late_fee').html(`
										<div class="form-group mb-md">
											<label class="col-md-3 control-label">Late Fee <span class="required">*</span></label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="late_fee_type[]" required placeholder="0.00">
											</div>
										</div>
									`);
			} else if (selectedOption === "2") {				
				$('#late_fee').html(`
										<div class="form-group mb-md">
											<label class="col-md-3 control-label">Fee within 5 days <span class="required">*</span></label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="late_fee_type[]" required placeholder="0.00">
											</div>
										</div>
										<div class="form-group mb-md">
											<label class="col-md-3 control-label">Fee After 5 days <span class="required">*</span></label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="late_fee_type[]" required placeholder="0.00">
											</div>
										</div>
									`);
			}
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
?>