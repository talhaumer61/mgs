<?php 
echo '
<div id="fee_challans" class="tab-pane">';
	$sqllms	= $dblms->querylms("SELECT f.id, f.status, f.id_std, f.challan_no, f.issue_date, f.due_date, f.scholarship, f.concession, f.fine, f.total_amount, f.paid_amount, f.remaining_amount, f.note, f.id_session
								FROM ".FEES." f
								WHERE f.is_deleted	= '0'
								AND f.id_type IN (1,2)
								AND f.id_campus		= '".$id_campus."'
								AND f.id_std		= '".$_GET['id']."'
								ORDER BY f.id DESC");
	if(mysqli_num_rows($sqllms)>0){
		echo'
		<div class="row mb-lg">
			<div class="col-md-10">
				<div class="form-group">
					<div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" class="form-control" id="start_date">
						<span class="input-group-addon">to</span>
						<input type="text" class="form-control" id="end_date">
					</div>
				</div>
			</div>
			<input type="hidden" class="form-control" id="id_std" value="'.$_GET['id'].'">
			<div class="col-md-2">
				<div class="form-group">
					<button id="filter_Challan" class="mr-xs btn btn-primary"><i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		<table class="table table-bordered table-striped table-condensed mb-none" id = "">
			<thead>
				<tr>
					<th width="40" class="center">Sr.</th>
					<th>Challan No</th>
					<th>Issue Date</th>
					<th>Due Date</th>
					<th>Total Amount</th>
					<th>Discount</th> 
					<th>Fine</th>
					<th>Payable</th>
					<th width="70" class="center">Status</th>
				</tr>
			</thead>
			<tbody id="addhereChallan">';
				$srno = 0;
				while($rowsvalues = mysqli_fetch_array($sqllms)){
					$discount = $rowsvalues['scholarship'] + $rowsvalues['concession'];
					$total = $rowsvalues['total_amount'] + $discount - $rowsvalues['fine'];
					$srno++;
					echo '
					<tr>
						<td class="center">'.$srno.'</td>
						<td>'.$rowsvalues['challan_no'].'</td>
						<td>'.$rowsvalues['issue_date'].'</td>
						<td>'.$rowsvalues['due_date'].'</td>
						<td>'.$total.'</td>
						<td>'.$discount.'</td> 
						<td>'.$rowsvalues['fine'].'</td>
						<td>'.$rowsvalues['total_amount'].'</td>
						<td class="center">'.get_payments($rowsvalues['status']).'</td>
					</tr>';
				}
				echo'
			</tbody>
		</table>';
	}else{
		echo'<h2 class="text text-center text-danger mt-lg">No Record Found!</h2>';
	}
	echo'
</div>';
?>
<script>
	$('#filter_Challan').on("click", function(){
		var startDate 	= $("#start_date").val();
		var endDate 	= $("#end_date").val();
		var idStd 		= $("#id_std").val();
		$.ajax({  
			type: "POST",  
			url: "include/ajax/get_VouchersFromDate.php",  
			data: {
					  'startDate' 	: startDate
					, 'endDate'		: endDate
					, 'idStd'		: idStd
				},
			success: function(msg){  
				$("#addhereChallan").html(msg);
			}
		});  
	});
</script>
