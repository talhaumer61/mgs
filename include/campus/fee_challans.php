<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))) { 
	require_once("fee_challans/query_feechallans.php");
	echo'
	<title> Fee Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Fee Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				if($view == 'add'){
					include_once("fee_challans/add_fee_detail.php");
				}
				elseif($view == 'bulk'){
					include_once("fee_challans/fee_challansgenerate.php");
				}
				elseif(isset($_GET['id'])){
					include_once("fee_challans/edit-fee.php");
				}
				elseif($view == 'api'){
					include_once("fee_challans/list_feechallans_api.php");
				}
				else{
					include_once("fee_challans/list_feechallans.php");
					include_once("include/modals/fee_challans/modal_feechallan_add.php");
					include_once("include/modals/fee_challans/print_challan.php");
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
			function get_class(id_campus) {  
				$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
				$.ajax({  
					type: "POST",  
					url: "include/ajax/get_class.php",  
					data: "id_campus="+id_campus,  
					success: function(msg){  
						$("#id_class").html(msg); 
						$("#loading").html(''); 
					}
				});  
			}
			function get_feeclasssection(id_class) {  
				$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
				var id_campus = $("#id_campus").val(); 
				$.ajax({  
					type: "POST",  
					url: "include/ajax/get_feeclasssection.php",  
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
			function get_duedate(id_month){ 
				$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
				$.ajax({  
					type: "POST",  
					url: "include/ajax/get_duedate.php",  
					data: "id_month="+id_month,
					success: function(msg){  
						$("#getduedate").html(msg); 
						$("#loading").html('');
					}
				});  
			}
			<?php
				for ($i = 1; $i<=$iForJs; $i++):
					echo '
					$(document).on("keyup", ".sum_'.$i.'", function() {
						var sum_'.$i.' = 0;
						$(".sum_'.$i.'").each(function(){
							sum_'.$i.' += +$(this).val();
						});
			
						var sub_'.$i.' = 0;
						$(".sub_'.$i.'").each(function(){
							sub_'.$i.' += +$(this).val();
						});
			
						var total_amount_'.$i.' = sum_'.$i.' - sub_'.$i.'
						$(".total_amount_'.$i.'").val(total_amount_'.$i.');
					});';
				endfor;
			?>
			$(document).on("keyup", ".sum", function() {
				var sum = 0;
				$(".sum").each(function(){
					sum += +$(this).val();
				});
				
				var sub = 0;
				$(".sub").each(function(){
					sub += +$(this).val();
				});

				var total_amount = sum - sub
				$(".total_amount").val(total_amount);
			});			
			$(document).on("keyup", ".sub", function() {
				var sum = 0;
				$(".sum").each(function(){
					sum += +$(this).val();
				});
				
				var sub = 0;
				$(".sub").each(function(){
					sub += +$(this).val();
				});

				var total_amount = sum - sub
				$(".total_amount").val(total_amount);
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
				window.location.href = delete_url;
			} );
		}
	</script>';
}else{
	header("location: dashboard.php");
}
?>