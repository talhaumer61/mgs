<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('77', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '77', 'view' => '1'))) {
	require_once("fine/query.php");
	echo '
	<title> Fine Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Fine Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				include_once("fine/list_fine.php");
				include_once("include/modals/fine/fine_add.php");
			echo '
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
		function get_section(id_class) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
			var id_campus = $("#id_campus").val(); 
			$.ajax({  
				type: "POST", 
				url: "include/ajax/get_section.php",  
				data: {
						  'id_campus'   : id_campus
						, 'id_class' 	: id_class
					},
				success: function(msg){  
					$("#id_section").html(msg); 
					$("#loading").html(''); 
				}
			});  
		}
		function get_sectionstudent(id_section) {
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
			var id_class  = $("#id_class").val(); 
			var id_campus = $("#id_campus").val(); 
			console.log(id_class);
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_section-student.php",
				data: {
						id_class		: id_class
						, id_section	: id_section
						, id_campus		: id_campus
					},
				success: function(msg){  
					console.log(msg);
					$("#id_std").html(msg); 
					$("#loading").html(''); 
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
} else {
	header("location: dashboard.php");
}
?>