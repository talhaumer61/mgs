<?php 
echo'
<!doctype html>
<html class=" sidebar-dark sidebar-left-big-icons">
<head>
	<meta charset="UTF-8">';
	include_once("header-css.php");
	echo'
	<script type="text/javascript">
	jQuery(document).ready(function()	{
		var barcode = "";
		var interval;
		document.addEventListener("keydown", function(evt) {
			if (interval)
				clearInterval(interval);
			if (evt.code == "Enter") {
				evt.preventDefault();
				if (barcode)
					handleBarcode(barcode);
				barcode = "";
				return;
			}
			if (evt.key != "Shift")
				barcode += evt.key;
			interval = setInterval(() => barcode = "", 20);
		});
		function handleBarcode(scanned_barcode) {
			var barcodeArray 	= scanned_barcode.split(",");
			var id_challan 		= barcodeArray[0];
			var id_campus 		= barcodeArray[1];
			jQuery("#barcodeModal").html("<div style=\"text-align:center;\"><img src=\"assets/images/preloader.gif\" /></div>");
			$.ajax( {
				url: `include/modals/fee_challans/modal_feechallan_pay.php?id=${id_challan}&id_campus=${id_campus}`,
				success: function (response) {
					jQuery("#barcodeModal").html(response);
					$("#barcodeModal").modal("show");
				}
			});
		}
	});
	</script>
</head>
<body class="" data-loading-overlay>
	<section class="body">
		<div class="modal fade col-md-6 col-sm-10" id="barcodeModal" style="position: absolute; left: 50%;top: 35%;transform: translate(-50%, -19%);"></div>';
		// include_once("header-top.php");
		include_once(get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/header-top.php");
		echo'
		<div class="inner-wrapper">
		<!-- INCLUDEING NAVIGATION -->';
			include_once(get_logintypes($_SESSION['userlogininfo']['LOGINAFOR'])."/sidebar-left.php");
			$sqlstring	= "";
			$adjacents	= 3;
			if(!($Limit)) 	{ $Limit = 20; } 
			if($page)		{ $start = ($page - 1) * $Limit; } else {	$start = 0;	}
?>