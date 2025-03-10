<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('9', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '9', 'view' => '1'))) {
	require_once("timetable/query_timetable_class.php");
	($view == 'teacher')? $teacherTbActv = 'active' : $teacherTbActv = '';
	($view == 'campus')	? $campusTbActv  = 'active' : $campusTbActv  = '';
	($view == 'class')	? $classTbActv 	 = 'active' : $classTbActv   = '';
	echo'
	<title> Timetable Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Class Timetable Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				if($view == 'teacher' || $view == 'campus' || $view == 'class'){
					echo'
					<div class="tabs tabs-primary">
					<ul class="nav nav-tabs">
						<li class="'.$campusTbActv.'">
							<a href="timetable.php?view=campus" aria-expanded="true">
								<i class="fa fa-building"></i> 
								<span class=""> Whole School Time Table</span>
							</a>
						</li>
						<li class="'.$classTbActv.'">
							<a href="timetable.php?view=class" aria-expanded="false">
								<i class="fa fa-clipboard"></i> 
								<span class="">Class Wise Time Table </span>
							</a>
						</li>
						<li class="'.$teacherTbActv.'">
							<a href="timetable.php?view=teacher" aria-expanded="false">
								<i class="fa fa-user"></i> 
								<span class="">Teacher Wise Time Table</span>
							</a>
						</li>
					</ul>
					<div class="tab-content"';
				}
				if($view == 'add'){
						include_once("timetable/timetable_add.php");
				}
				elseif($view == 'campus'){
						include_once("timetable/campus_timetable.php");
				}
				elseif($view == 'class'){
						include_once("timetable/class_timetable.php");
				}
				elseif($view == 'teacher'){
						include_once("timetable/teacher_timetable.php");
				}
				elseif(isset($_GET['id'])){
						include_once("timetable/timetable_edit.php");
				}
				else{
					include_once("timetable/list_timetable_class.php");
				}
				if($view == 'teacher' || $view == 'campus' || $view == 'class'){
					//To compensate tab [pills]
					echo '
					</div>
					</div>';
				} 
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
		function get_teachers(id_campus) { 
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_teachers.php",  
				data: "id_campus="+id_campus,  
				success: function(msg){  
					console.log(id_campus);
					$("#id_teacher").html(msg); 
				}
			});  
		}
		function print_report(printResult) {
			document.getElementById('header').style.display = 'block';
			var printContents = document.getElementById(printResult).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			var css = `@media print {
										@page {
											size: landscape;
											margin: 0;
										}
      
									}
					`,
			head = document.head || document.getElementsByTagName('head')[0],
			style = document.createElement('style');
			style.type = 'text/css';
			style.media = 'print';
			if (style.styleSheet){
			style.styleSheet.cssText = css;
			} else {
			style.appendChild(document.createTextNode(css));
			}
			head.appendChild(style);
			window.print();
			document.body.innerHTML = originalContents;
			document.getElementById('header').style.display = 'none';
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
				window.location.href = delete_url;
			} );
		}
	</script>';
}else{
	header("Location: dashboard.php");
}
?>