<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || (arrayKeyValueSearch($_SESSION['userroles'], 'right_name', '9'))){
	echo '
	<title> Timetable Panel | '.TITLE_HEADER.'</title>
	<section role="main" class="content-body">
		<header class="page-header">
			<h2>Class Timetable Panel </h2>
		</header>
		<div class="row">
			<div class="col-md-12">';
				include_once("timetable/timetable_view.php");
				echo'
			</div>
		</div>
	</section>';
	?>
	
	<script type="text/javascript">
		function get_section(id_class) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');			
			var id_campus  = $('#id_campus').val();
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_section.php",
				data: { 
					 id_class	: id_class 
					,id_campus	: id_campus 
				},
				success: function(msg){  
					console.log(id_campus);
					$("#id_section").html(msg); 
					$("#loading").html(''); 
				}
			});  
		}
		function print_report(printResult) {
			document.getElementById('header').style.display = 'block';
			var printContents = document.getElementById(printResult).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			var css = '@page {   }',
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
}else{
	header("Location: dashboard.php");
}
?>