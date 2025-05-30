<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('12', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '12', 'view' => '1'))) {
	require_once("exam_datesheet/query.php");
	echo'
	<title> '.moduleName(false).' '.moduleName(LMS_VIEW).' | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>'.moduleName(false).' '.moduleName(LMS_VIEW).' </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				if (LMS_VIEW == "class") {
					include_once(moduleName()."/".LMS_VIEW.".php");	
				} else if (LMS_VIEW == "class_comprehensive") {
					include_once(moduleName()."/".LMS_VIEW.".php");	
				} else if (LMS_VIEW == "student") {
					include_once(moduleName()."/".LMS_VIEW.".php");	
				} else if (LMS_VIEW == "student_comprehensive") {
					include_once(moduleName()."/".LMS_VIEW.".php");	
				}
				echo '
			</div>
		</div>
	</section>';
	?>
	<script type="text/javascript">
		$("#id_campus").change(function(){
			var id_campus = $(this).val();
			$.ajax({  
				type: "POST",
				url: "include/ajax/get_examtype.php",
				data: {
					"id_campus"	: id_campus
				},
				success: function(msg){
					$("#id_exam").html(msg);
				}
			});
		});

		$("#id_exam").change(function(){
			var id_exam = $(this).val();
			var id_campus = $("#id_campus").val();
			$.ajax({  
				type: "POST",
				url: "include/ajax/get_class.php",
				data: {
					 "id_exam"		: id_exam
					,"id_campus"	: id_campus
				},
				success: function(msg){
					console.log(id_exam);
					$("#id_class").html(msg);
				}
			});
		});

		$("#id_class").change(function(){
			var id_class = $(this).val();
			var id_exam = $("#id_exam").val();
			var id_campus = $("#id_campus").val();
			$.ajax({  
				type: "POST",
				url: "include/ajax/get_section.php",
				data: {
					 "id_class"		: id_class
					,"id_exam"		: id_exam
					,"id_campus"	: id_campus
				},
				success: function(msg){
					$("#id_section").html(msg);
				}
			});
			$.ajax({  
				type: "POST",
				url: "include/ajax/get_subject.php",
				data: {
					 "id_class"		: id_class
					,"id_exam"		: id_exam
					,"id_campus"	: id_campus
				},
				success: function(msg){
					$("#id_subject").html(msg);
				}
			});
		});

		$("#id_section").change(function(){
			var id_campus 	= $("#id_campus").val();
			var id_exam 	= $("#id_exam").val();
			var id_class 	= $("#id_class").val();
			var id_section 	= $(this).val();
			$.ajax({  
				type: "POST",
				url: "include/ajax/get_section-student.php",
				data: {
					 "id_campus"	: id_campus
					,"id_exam"		: id_exam
					,"id_class"		: id_class
					,"id_section"	: id_section
					,"_method"		: "student_result"
				},
				success: function(msg){
					$("#id_std").html(msg);
				}
			});
		});

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
	</script>    
	<!-- INCLUDES BOTTOM -->';
}else{
	header("Location: dashboard.php");
}
?>