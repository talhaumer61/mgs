<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'view' => '1'))) {
	require_once("employee/query_employees.php");
	echo'
	<title>Employee Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Employee Panel</h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				if ($view === 'add'){
					include_once("employee/employee_add.php");
				}elseif(isset($_GET['id'])){
					include_once("employee/employee-profile.php");
				}else{
					if ($view === 'import_employee') {
						include_once("employee/import_employee.php");
					} else {
						include_once("employee/list_employee.php");
					}
				}
				include_once("include/modals/employee/bankaccount_add.php");
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
			function get_dept_and_designation(id_campus) { 
				$.ajax({  
					type: "POST",  
					url: "include/ajax/get_dept_and_designation.php",  
					data: {
						'id_campus'	: id_campus
					},  
					success: function(msg){
						$("#get_dept_and_designation").html(msg); 
					}
				});  
			}			
			function get_dept_and_designation_edit(id_campus) { 
				$.ajax({  
					type: "POST",  
					url: "include/ajax/get_dept_and_designation.php",  
					data: {
						'id_campus'	: id_campus
						,'flag'		: 'edit'
					},  
					success: function(msg){
						$("#get_dept_and_designation").html(msg); 
					}
				});  
			}
			function get_section(id_class) {
				$.ajax({
					type: "POST",
					url: "include/ajax/get_section.php",
					data: "id_class=" + id_class,
					success: function(msg) {
						$("#id_section").html(msg);
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
	</script>';
}else{
	header("location: dashboard.php");
}
?>