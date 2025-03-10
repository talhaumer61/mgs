<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('6', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '6', 'view' => '1'))) {
	require_once("classes/query_classsections.php");
	echo'
	<title> Section Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Section Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				include_once("classes/list_classsections.php");
				include_once("include/modals/class/modal_classsections_add.php");
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
			function get_class(id_campus) {
				$.ajax({  
					type: "POST",  
					url: "include/ajax/get_class.php",  
					data: {
						'id_campus'	: id_campus
					},
					success: function(msg){  
						$("#id_class").html(msg);
					}
				});
			}
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
				window.location.href = delete_url;
			} );
		}
	</script> 

	<script type="text/javascript">
		';
		// echo'
		// $("#formadd").submit(function(event){
		// 	event.preventDefault();
			
		// 	cosole.log("hello");
			
		// 	$.ajax({
		// 		type: "POST",  
		// 		url: "include/ajax/get_fromadd_response.php",';
		// 		$Array = array();
		// 		for($i=0; $i<=sizeof($_POST['check']); $i++){
		// 			if(isset($_POST['check'][$i])){
		// 				$std_id = $_POST['std_id'][$i];
		// 				array_push($Array, $std_id);
		// 				$std_ids = implode(',', $Array);
		// 			}
		// 		}
		// 		echo' 
		// 		data: {
		// 			std_ids	: '.$std_ids.'
		// 		},
		// 		success: function(msg){  
		// 			$("#get_formadd_response").html(msg); 
		// 			$("#loading").html(""); 
		// 		}
		// 	}); 
		// }';

		// echo'
		// $("#formadd").submit(function(event){

		// 	/* stop form from submitting normally */
		// 	event.preventDefault();

		// 	/* get the action attribute from the <form action=""> element */
		// 	var $form = $(this),

		// 	console.log($form);
		// 	url = $form.attr("action");

		// 	/* Send the data using post with element id name and name2*/
		// 	var posting = $.post(url, {';
		// 		$Array = array();
		// 		for($i=0; $i<=sizeof($_POST['check']); $i++){
		// 			if(isset($_POST['check'][$i])){
		// 				$std_id = $_POST['std_id'][$i];
		// 				array_push($Array, $std_id);
		// 				$std_ids = implode(',', $Array);
		// 			}
		// 		}
		// 		echo'
		// 		console.log(std_ids);

		// 		std_ids: "'.$std_ids.'"
		// 	});


		// 	/* Alerts the results */
		// 	posting.done(function(data) {
		// 		$("#result").text("success");
		// 	});
		// 	posting.fail(function() {
		// 		$("#result").text("failed");
		// 	});
		// });';
		echo'
	</script>
	<!-- INCLUDES BOTTOM -->';
}else{
	header("Location: dashboard.php");
}
?>