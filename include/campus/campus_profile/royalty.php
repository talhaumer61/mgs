<?php
// CAMPUS ID
$campus = cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);

// ROYALTY ADDED?
$sqllmsRoyalty = $dblms->querylms("SELECT id, grand_total
									FROM ".ROYALTY_SETTING." r
									WHERE id_campus	= '".cleanvars($campus)."'
									AND is_deleted	= '0' ORDER BY id DESC LIMIT 1");
if(mysqli_num_rows($sqllmsRoyalty) > 0){
	$valRoyaltyCheck = mysqli_fetch_array($sqllmsRoyalty);
	$grandTotal = $valRoyaltyCheck['grand_total'];
}else{
	$grandTotal = '';
}
echo'
<div id="royalty" class="tab-pane">';
	$sqlRoyalty	= $dblms->querylms("SELECT r.id
								FROM ".ROYALTY_SETTING." r
								WHERE r.is_deleted	= '0'
								AND r.id_campus = '".cleanvars($campus)."'
							");
	if(mysqli_num_rows($sqlRoyalty) > 0){
		echo'
		<div class="panel-body">';
			while($valRoyalty = mysqli_fetch_array($sqlRoyalty)){
				echo'
				<div class="table-responsive">
					<table class="table table-bordered table-condensed table-striped mb-none">
						<thead>
							<tr>
								<th width="40" class="center">Sr.</th>
								<th>Title</th>
								<th width="200">Amount</th>
							</tr>
						</thead>
						<tbody>';
							$sqlRoyaltyDet	= $dblms->querylms("SELECT SUM(rd.total_amount) total, p.part_name
															FROM ".ROYALTY_SETTING." r 
															INNER JOIN ".ROYALTY_SETTING_DET." rd ON rd.id_setup = r.id
															INNER JOIN ".ROYALTY_PARTICULARS." p ON p.part_id = rd.id_particular
															WHERE r.is_deleted	= '0'
															AND r.id_campus		= '".$campus."'
															GROUP BY rd.id_particular");
							$sr = 0;
							$grandTotal = 0;
							while($valRoyaltyDet = mysqli_fetch_array($sqlRoyaltyDet)){
								$sr++;
								echo'
								<tr>
									<td class="center">'.$sr.'</td>
									<td>'.$valRoyaltyDet['part_name'].'</td>
									<td>'.$valRoyaltyDet['total'].'</td>
								</tr>
								';
								$grandTotal = $grandTotal + $valRoyaltyDet['total'];
							}
							echo'
							<tr>
								<th colspan="2" class="center">Total</th>
								<th>'.$grandTotal.'</th>
							</tr>
						</tbody>
					</table>
				</div>';			
			}
			echo'
		</div>';
	}else{
		echo'
		<div class="panel-body">
			<h2 class="text text-danger text-center">Royalty Not Added.</h2>
		</div>';
	}
	echo'
</div>';
?>

<script type="text/javascript">
	// RETURN ROYALTY DETAILS
	function get_royalty_type(royalty_type) {  
		$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_royalty_type.php",
			data: "royalty_type="+royalty_type,
			success: function(msg){  
				$(".getroyaltytype").html(msg); 
				$("#loading").html(''); 
			}
		});  
	}
	// GRAND TOTAL AMOUNT
	$(document).on("input", ".totalAmount", function() {
		var grandTotal = 0;
		$(".totalAmount").each(function(){
			grandTotal += +$(this).val();
		});
		$("#grandTotal").val(grandTotal);
	});
</script>