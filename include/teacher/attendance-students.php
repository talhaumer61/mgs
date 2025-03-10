<?php
//-----------------------------------------------
	require_once("attendance-students/query.php");
//-----------------------------------------------
echo '
<title> Attendance Panel | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Attendance Panel </h2>
	</header>
<!-- INCLUDEING PAGE -->
<div class="row">
<div class="col-md-12">';
//-----------------------------------------------
	if($view)
	{
		include_once("attendance-students/add.php");
	}
	else if(isset($_GET['id']))
	{
		include_once("attendance-students/edit.php");
	}
	else{
		include_once("attendance-students/list.php");
	}
//-----------------------------------------------
echo '
</div>
</div>';
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
<?php 
//-----------------------------------------------
if(isset($_SESSION['msg'])) { 
//-----------------------------------------------
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
//-----------------------------------------------
    unset($_SESSION['msg']);
//-----------------------------------------------
}
//-----------------------------------------------
?>	
		var datatable = $('#table_export').dataTable({
			bAutoWidth : false,
			ordering: false,
		});
	});

	function print_report(printResult) {
		document.getElementById('header').style.display = 'block';
		
		// Remove the class 'radio-custom' from radio button divs in the printable area
		var radioCustomDivs = document.querySelectorAll('.radio-custom');
		for (var i = 0; i < radioCustomDivs.length; i++) {
			radioCustomDivs[i].classList.remove('radio-custom');
		}
		
		var printContents = document.getElementById(printResult).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;

		var css = `
			@media print {
				@page {
					margin: 4mm;
				}
			}
		`;

		var head = document.head || document.getElementsByTagName('head')[0];
		var style = document.createElement('style');
		style.type = 'text/css';
		style.media = 'print';

		if (style.styleSheet) {
			style.styleSheet.cssText = css;
		} else {
			style.appendChild(document.createTextNode(css));
		}

		head.appendChild(style);
		window.print();
		
		// Add the class 'radio-custom' back to the radio button divs
		for (var i = 0; i < radioCustomDivs.length; i++) {
			radioCustomDivs[i].classList.add('radio-custom');
		}
		
		document.body.innerHTML = originalContents;
		document.getElementById('header').style.display = 'none';
	}
	
	function get_section(id_class) {  
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
	<?php
	if(isset($srno)){
		echo'
		function mark_all_present() {
			var count = 1+'.$srno.';
			
			for(var i = 1; i < count; i++) {
				document.getElementById(\'pstatus_\' + i).checked = true;
			}
		}
	
		function mark_all_absent() {
			var count = 1+'.$srno.';
			
			for(var i = 1; i < count; i++){
				document.getElementById(\'astatus_\' + i).checked = true;
			}
		}
		
		function mark_all_holiday() {
			var count = 1+'.$srno.';
			
			for(var i = 1; i < count; i++){
				document.getElementById(\'hstatus_\' + i).checked = true;
			}
		}
		
		function mark_all_late() {
			var count = 1+'.$srno.';
			
			for(var i = 1; i < count; i++){
				document.getElementById(\'lstatus_\' + i).checked = true;
			}
		}';
	}
	
	?>
</script>
<?php 
//------------------------------------
echo '
</section>
</div>
</section>	
';
//-----------------------------------------------