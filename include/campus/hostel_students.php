<?php
if(($_SESSION['userlogininfo']['LOGINAFOR'] == 1 && $_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('87', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '87', 'view' => '1'))) {
	require_once("hostel_students/query.php");
	echo'
	<title> Hostel Students Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Hostel Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				include_once("hostel_students/list.php");
				include_once("include/modals/hostel_students/add.php");
				echo '
			</div>
		</div>
	</section>';
	?>
	<script type="text/javascript">
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
					if (msg != '') {
						$("#std_hide").removeClass("display-n"); 
					} else {
						$("#std_hide").addClass("display-n"); 
					}
					$("#id_user").html(msg); 
					$("#loading").html(''); 
				}
			});  
		}
		function get_Rooms(id_hostel) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_hostel_room.php",  
				data: "id_hostel="+id_hostel,  
				success: function(msg){  
					if (msg != '') {
						$("#room_hide").removeClass("display-n"); 
					} else {
						$("#room_hide").addClass("display-n"); 
					}
					$("#id_room").html(msg); 
					$("#loading").html(''); 
				}
			});  
		}
		function get_RoomFee(id_room) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_hostel_room_fee.php",  
				data: "id_room="+id_room,  
				success: function(msg){
					$("#room_fee").html(msg); 
					$("#loading").html(''); 
				}
			});  
		}
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
							mColumns: [0,1,2,3,4]
						},
						{
							sExtends: 'csv',
							sButtonText: 'CSV',
							mColumns: [0,1,2,3,4]
						},
						{
							sExtends: 'xls',
							sButtonText: 'Excel',
							mColumns:[0,1,2,3,4]
						},
						{
							sExtends: 'print',
							sButtonText: 'Print',
							sInfo: '',
							fnClick: function (nButton, oConfig) {
								datatable.fnSetColumnVis(5, false);
								
								this.fnPrint( true, oConfig );
								
								window.print();
								
								$(window).keyup(function(e) {
									if (e.which == 27) {
										datatable.fnSetColumnVis(5, true);
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
	header("location: dashboard.php");
}
?>