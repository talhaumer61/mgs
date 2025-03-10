<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))) {
	echo'
	<title> Students Fee Report | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Students Fee Report</h2>
		</header>';
		include("student_fee_detail_report/list.php");
		echo '
	</section>';?>
	<script type="text/javascript">
		function get_LevelWiseClass(id_level) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_classlevel.php",  
				data: "id_level="+id_level,  
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
		function get_ClassWiseSection(id_class) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_section.php",  
				data: "id_class="+id_class,  
				success: function(msg){  
					$("#id_section").html(msg); 
					$("#loading").html(''); 
				}
			});  
		}
		function print_report(printResult) {
			document.getElementById('header').style.display 		= 'block';
			document.getElementById('printfooter').style.display 	= 'block';
			document.getElementById('printBtn').style.display 		= 'none';
			
			var printContents = document.getElementById(printResult).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			var css = `@media print {
										@page {
											size: A4 landscape;
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
			document.getElementById('header').style.display 		= 'none';
			document.getElementById('printfooter').style.display 	= 'none';
			document.getElementById('printBtn').style.display 		= 'block';
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