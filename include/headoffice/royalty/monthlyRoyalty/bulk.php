<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))){   


if(isset($_POST['id_month'])){$month_id = $_POST['id_month'];} else{$month_id = '';}
echo'
<section class="panel panel-featured panel-featured-primary">
	<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fa fa-plus-square"></i> Make Royalty Challans</h4>
	</header>
	
	<div class="panel-body">
		<div class="row mb-lg">
			 <div class="col-md-4"></div>
			 <div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Month <span class="required">*</span></label>
					<select data-plugin-selectTwo data-width="100%" name="id_month" id="id_month" required title="Must Be Required" class="form-control">
						<option value="">Select</option>';
						foreach($monthtypes as $month) {
							if($month['id'] == $month_id){
								echo '<option value="'.$month['id'].'" selected >'.$month['name'].'</option>';
							}
							else{
								echo '<option value="'.$month['id'].'" >'.$month['name'].'</option>';
							}
						}
						echo'
						</select>
				</div>
			</div>        
		</div>
		<center>
			<button type="submit" name="royalty_details" id="royalty_details" class="btn btn-primary"><i class="fa fa-search"></i> Check Details</button>
		</center>
	</div>
	</form>
</section>
';
if(isset($_POST['royalty_details'])){
//-----------------------------------------------------
 $sqllmsRoylaty	= $dblms->querylms("SELECT part_id, part_name, part_amount
                                        FROM ".ROYALTY_PARTICULARS."
                                        WHERE part_status = '1'  AND is_deleted != '1' ");
//-----------------------------------------------------
if(mysqli_num_rows($sqllmsRoylaty) > 0){
//-----------------------------------------------------
echo'
<section class="panel panel-featured panel-featured-primary">
<form action="royalty.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-dollar"></i>
		Challan Details of '.get_monthtypes($month_id).'</h2>
	</header>
<div class="panel-body">
	<div class="row mt-sm">
		<div class="col-sm-4">
			<div class="form-group">
				<label class="control-label">Month <span class="required">*</span></label>
				<input type="hidden" name="id_month" id="id_month" value="'.$month_id.'">
				<input type="text" class="form-control" value="'.get_monthtypes($month_id).'" required title="Must Be Required" readonly>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<label class="control-label">Issue Date <span class="required">*</span></label>
				<input type="text" class="form-control" name="issue_date" id="issue_date" data-plugin-datepicker required title="Must Be Required"/>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<label class="control-label">Due Date <span class="required">*</span></label>
				<input type="text" class="form-control" name="due_date" id="due_date" data-plugin-datepicker required title="Must Be Required"/>
			</div>
		</div>
	</div>
	<div class="row mt-sm">';
		//-----------------------------------------------------
		$srno=0;
		$total_amount = 0;
		//-----------------------------------------------------
		while($rowsvalues = mysqli_fetch_array($sqllmsRoylaty)) {
			//-----------------------------------------------------
			$srno++;
			//-----------------------------------------------------
			echo '
				<div class="col-sm-3">
					<div class="form-group mr-xs">
						<input class="form-control" type="hidden" name="id_particular['.$srno.']" id="id_particular['.$srno.']" value="'.$rowsvalues['part_id'].'">
						<label class="control-label">'.$rowsvalues['part_name'].' <span class="required">*</span></label>
						<input class="form-control" type="text" class="form-control" name="amount['.$srno.']" id="amount['.$srno.']" value="'.$rowsvalues['part_amount'].'" required title="Must Be Required" readonly/>
					</div>
				</div>';
			$amount = $rowsvalues['part_amount'];
			$total_amount = $total_amount + $amount;
		}
		//------------------------------------------------
		echo'
	</div>
	<input type="hidden" name="total_amount" value="'.$total_amount.'">
	<div class="row mt-sm mb-lg">
		<div class="col-sm-12">
			<div class="form-group">
				<label class="control-label">Note</label>
				<textarea type="text" class="form-control" name="roy_detail"></textarea>
			</div>
		</div>
	</div>
</div>
<footer class="panel-footer mt-sm">
	<div class="row">
		<div class="col-md-12">
			<center><button type="submit" name="bulk_challans_generate" id="bulk_challans_generate" class="btn btn-primary">Generate Challans</button></center>
		</div>
	</div>
</footer>
</form>
</section>';
}
else{
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<div class="panel-body">
			<div class="col-sm-12">
				<div class="form-group">
					<h2 style="text-align:center;">No Record Found!</h2>
				</div>
			</div>
		</div>
	</section>';
}
}
	echo '';

}
else{
	header("Location: fee_challans.php");
}
?>