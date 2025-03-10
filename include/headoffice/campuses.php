<?php 
require_once("campuses/query_campuses.php");
echo'
<title> Admin Panel | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Admin Panel </h2>
	</header>
	<div class="row">
		<div class="col-md-12">';
			if($view == "add"){
				include_once("campuses/add.php");
			}	
			elseif(isset($_GET['id'])){
				include_once("campuses/profile.php");
			}
			else{
				include_once("campuses/list_campuses.php");
			}
			include_once("include/modals/campuses/modal_campus_add.php");
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

		$('#id_type').change(function(){
			var id_type	= $('#id_type').val();
			$.ajax({  
				type: "POST",
				url: "include/ajax/get_parent_campuses.php",
				data: {
					'id_type'	: id_type
				},
				success: function(msg){  
					$("#parent_campuses").html(msg);
				}
			});  
		});
		$('#id_type_edit').change(function(){
			var id_type_edit 	= $('#id_type_edit').val();
			var campus_id		= $('#campus_id').val();
			$.ajax({  
				type: "POST",
				url: "include/ajax/get_parent_campuses.php",
				data: {
					 'id_type_edit'	: id_type_edit
					,'campus_id'	: campus_id
				},
				success: function(msg){  
					$("#parent_campuses").html(msg);
				}
			});  
		});

		var idPer 	= $("#id_permissions");
		$(document).ready(function() {
			idPer.on("change", function() {
			if ($(this).val() !== null && $(this).val().includes("all")) {
				selectAllOptions();
			}
			});
			function selectAllOptions() {
				const isChecked = $("#id_permissions option[value='all']").prop("selected");
				$(".opt_permission").prop("selected", isChecked);
			}
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
?>