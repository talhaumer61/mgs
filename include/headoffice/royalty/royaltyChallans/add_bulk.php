<?php
$id_campus = '';
$id_month = '';
$due_date = '';

// MONTHS
if($_POST['campus']){
	$id_campus = implode(",",$_POST['campus']);
}
if(isset($_POST['month'])){$id_month = $_POST['month'];}	
if(isset($_POST['duedate'])){$due_date = $_POST['duedate'];}
echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
	</header>
	<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
		<div class="panel-body">
			<div class="row">
				<div class="form-group mt-sm">
					<div class="col-md-12">
						<label class="control-label"> Campuses <span class="required">*</span></label>
						<select data-plugin-selectTwo data-width="100%" name="campus[]" id="campus" required title="Must Be Required" class="form-control populate" multiple>
							<option value="">Select</option>';
							$sqlCampus = $dblms->querylms("SELECT c.campus_id, c.campus_name
															FROM ".CAMPUS." c
															WHERE c.campus_id  != ''
															AND campus_status	= '1'
															ORDER BY c.campus_id ASC
														");
							while($valCampus = mysqli_fetch_array($sqlCampus)){
								echo'<option value="'.$valCampus['campus_id'].'" '.(strpos(','.$id_campus.',', ','.$valCampus['campus_id'].',') !== false ? 'selected' : '').'>'.$valCampus['campus_name'].'</option>';
							}
							echo'
						</select>
					</div>
				</div>
				<div class="form-group mt-sm">
					<div class="col-md-6">
						<label class="control-label"> Month <span class="required">*</span></label>
						<select data-plugin-selectTwo data-width="100%" name="month" id="month" required title="Must Be Required" class="form-control populate" required>
							<option value="">Select</option>';
							foreach($monthtypes as $month){
								echo'<option value="'.$month['id'].'" '.($id_month==$month['id'] ? 'selected' : '').'>'.$month['name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-6">
						<label class="control-label">Due Date <span class="required">*</span></label>
						<input type="text" class="form-control" name="duedate" id="duedate" value="'.$due_date.'" data-plugin-datepicker required title="Must Be Required" required/>
					</div>
				</div>
				<div class="form-group mt-sm">
					<div class="col-md-12 center">
						<button type="submit" name="view_detail" id="view_detail" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</section>';

// SEARCH RESULTS
if(isset($_POST['view_detail'])){
	$sqlRoyalty	= $dblms->querylms("SELECT r.id, r.id_campus, c.campus_name
										FROM ".ROYALTY_SETTING." r
										INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus
										WHERE r.is_deleted	= '0'
										AND r.id_campus IN (".$id_campus.")
									");
	if(mysqli_num_rows($sqlRoyalty) > 0){							
		echo'
		<section class="panel panel-featured panel-featured-primary">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-list"></i>  Royalty Detail</h2>
			</header>
			<div class="panel-body">
				<form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<input type="hidden" name="id_campus" id="id_campus" value="'.$id_campus.'">
					<input type="hidden" name="id_month" id="id_month" value="'.$id_month.'">
					<input type="hidden" name="due_date" id="due_date" value="'.$due_date.'">
					<fieldset>
						<div class="panel-body">';
							while($valRoyalty = mysqli_fetch_array($sqlRoyalty)){
								echo'
								<div class="table-responsive mt-xl">
									<table class="table table-bordered table-condensed table-striped mb-none">
										<thead>
											<tr>
												<th colspan="3" class="center">
													<h4><b>'.$valRoyalty['campus_name'].'</b></h4>
												</th>
											</tr>
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
																			AND r.id_campus		= '".$valRoyalty['id_campus']."'
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
						</div>
					</fieldset>
					<div class="panel-footer row center" style="margin-bottom: -15px;">
						<button type="submit" name="generate_bulk" id="generate_bulk" class="btn btn-primary">Generate Bulk Challan</button>
					</div>
				</form>
			</div>
		</section>';
	}else{
		echo'
		<div class="panel-body">
			<h2 class="text text-danger text-center">Royalty Not Added.</h2>
		</div>';
	}
}
?>

<script type="text/javascript">
	//Return Royalty Details
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
	//Grand Total Amount
	$(document).on("input", ".totalAmount", function() {
		var grandTotal = 0;
		$(".totalAmount").each(function(){
			grandTotal += +$(this).val();
		});
		$("#grandTotal").val(grandTotal);
	});
</script>