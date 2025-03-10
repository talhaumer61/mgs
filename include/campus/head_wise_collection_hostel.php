<?php
if($_SESSION['userlogininfo']['LOGINAFOR'] == 2){	
	echo'
	<title>Fee Head Collection Report Hostel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Head Wise Collection Report Hostel</h2>
		</header>';
		include("head_wise_collection_hostel/list.php");
		echo '
	</section>';?>
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
						, is_hostel		: '1'
					},
				success: function(msg){  
					console.log(msg);
					$("#id_std").html(msg); 
					$("#loading").html(''); 
				}
			});  
		}
		function print_report(printResult) {
			document.getElementById('header').style.display = 'block';
			document.getElementById('printfooter').style.display = 'block';
			var printContents = document.getElementById(printResult).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			var css = `@media print {
										@page {
											size: A4 landscape;
											margin: 0;
											margin: 4mm 4mm 4mm 4mm;
										}
									}
						.ttable th, td {
							border: 1px solid grey;
							padding: 5px;
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
			document.getElementById('printfooter').style.display = 'none';
		}
		jQuery(document).ready(function($) {	
			var datatable = $('#table_export').dataTable({
				bAutoWidth : false,
				ordering: false,
			});
		});
	</script><?php
} else {
    header("Location: dashboard.php");
}
?>