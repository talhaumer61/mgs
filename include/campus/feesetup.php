<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || (arrayKeyValueSearch($_SESSION['userroles'], 'right_name', '70')))
{
	require_once("fee/query_fee.php");
	echo '
	<title>Fee Structure | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Fee Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				if($view && $view == 'add'){
					include_once("fee/add_fee_detail.php");
				} else if($view && $view == 'percentage_add'){
					include_once("fee/percentage_add_fee_detail.php");
				} else if(isset($_GET['id'])){
					include_once("fee/edit_fee_detail.php");
				} else{
					include_once("fee/list_feesetup.php");
				}
				echo '
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

			function get_feestruclasssection(id_class) {  
				$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
				$.ajax({  
					type: "POST",  
					url: "include/ajax/get_feestructureclasssection.php",  
					data: "id_class="+id_class,
					success: function(msg){  
						$("#getfeestruclasssection").html(msg); 
						$("#loading").html(''); 
					}
				});  
			}

			function calculateAmount(i, increasingType) {
				var finalAmount = 0;
				// Retrieve the pre_amount value
				var preAmount = parseFloat(document.getElementById("pre_amount_"+i).value);
				// console.log(preAmount);

				// Retrieve the second value from the percentage field
				var increasingValue = document.getElementById("percentage_"+i).value;
				// console.log(percentageValue);

				if (increasingType == 2) {
					// Calculate the percentage amount
					var percentageAmount = (preAmount * increasingValue) / 100;
					// Round off the percentage amount to the nearest whole number
					finalAmount = preAmount + Math.round(percentageAmount);

				} else if (increasingType == 1) {
					// Calculate the fixed amount
					finalAmount = Math.round(Number(preAmount) + Number(increasingValue));
				} else {
					// Calculate the percentage amount
					var percentageAmount = (preAmount * increasingValue) / 100;
					// Round off the percentage amount to the nearest whole number
					finalAmount = preAmount + Math.round(percentageAmount);
				}
				document.getElementById("amount_"+i).value = finalAmount;
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
		</script>
		<?php 
		echo '
	</section>
	</div>
	</section>	
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
} else {
	header("location: dashboard.php");
}
?>