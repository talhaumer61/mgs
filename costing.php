<?php
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

include_once("include/header.php");
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('29', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '29', 'view' => '1'))) {
	require_once("include/campus/balancesheet/query_earning-costing-trans.php");
	echo '
	<title> Costing Sheet | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Costing Sheet</h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
			include_once("include/campus/balancesheet/list_costing.php");
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('29', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '29', 'add' => '1'))) {
				include_once("include/modals/balancesheet/costing_add.php");
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
					sDom: "<'text-right mb-md'T>" + $.fn.dataTable.defaults.sDom,
					oTableTools: {
						sSwfPath: 'assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf',
						aButtons: [
							{
								sExtends: 'pdf',
								sButtonText: 'PDF',
								mColumns: [1,2,3,4,5,6]
							},
							{
								sExtends: 'csv',
								sButtonText: 'CSV',
								mColumns: [1,2,3,4,5,6]
							},
							{
								sExtends: 'xls',
								sButtonText: 'Excel',
								mColumns: [1,2,3,4,5,6]
							},
							{
								sExtends: 'print',
								sButtonText: 'Print',
								sInfo: '',
								fnClick: function (nButton, oConfig) {
									datatable.fnSetColumnVis(0, false);
									datatable.fnSetColumnVis(7, false);
									
									this.fnPrint( true, oConfig );
									
									window.print();
									
									$(window).keyup(function(e) {
										if (e.which == 27) {
											datatable.fnSetColumnVis(0, true);
											datatable.fnSetColumnVis(7, true);
										}
									});
								}
							}
						]
					}
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
}
include_once("include/footer.php");
?>