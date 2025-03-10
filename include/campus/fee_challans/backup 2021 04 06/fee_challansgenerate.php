<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))){   


if(isset($_POST['id_class'])){$class = $_POST['id_class'];} else{$class = '';}
if(isset($_POST['id_section'])){$section = $_POST['id_section'];}	else{$section = '';}
echo'
<section class="panel panel-featured panel-featured-primary">
	<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<header class="panel-heading">
		<h4 class="panel-title"><i class="fa fa-plus-square"></i> Make Class Fee Challans</h4>
	</header>
	
	<div class="panel-body">
		<div class="row mb-lg">
			 <div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Class <span class="required">*</span></label>
					<select data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" onchange="get_feeclasssection(this.value)" required title="Must Be Required" class="form-control">
						<option value="">Select</option>';
					$sqllms	= $dblms->querylms("SELECT class_id, class_name
													FROM ".CLASSES." 
													WHERE class_status = '1' AND is_deleted != '1' 
													ORDER BY class_id ASC");
						while($rowsvalues = mysqli_fetch_array($sqllms)){
							if($rowsvalues['class_id'] == $class){
								echo'<option value="'.$rowsvalues['class_id'].'" selected>'.$rowsvalues['class_name'].'</option>';
								}else{
									echo'<option value="'.$rowsvalues['class_id'].'">'.$rowsvalues['class_name'].'</option>';
									}
						}
						echo'
						</select>
				</div>
			</div>
			<div id="getfeeclasssection">
				<div class="col-md-6">
					<div class="form-group">
						<label class="control-label">Section <span class="required">*</span></label>
						<select data-plugin-selectTwo data-width="100%" name="id_section" id="id_section" required title="Must Be Required" class="form-control populate">						
						<option value="">Select</option>';
						$sqllms	= $dblms->querylms("SELECT section_id, section_name
														FROM ".CLASS_SECTIONS."   
														WHERE section_status = '1' AND is_deleted != '1'
														ORDER BY section_name ASC");
							while($rowsvalues = mysqli_fetch_array($sqllms)){
								if($rowsvalues['section_id'] == $section){
									echo'<option value="'.$rowsvalues['section_id'].'" selected>'.$rowsvalues['section_name'].'</option>';
								}else{
									echo'<option value="'.$rowsvalues['section_id'].'">'.$rowsvalues['section_name'].'</option>';
								}
							}
							echo '
						</select>
					</div>
				</div>            
			</div>            
		</div>
		<center>
			<button type="submit" name="challans_details" id="challans_details" class="btn btn-primary"><i class="fa fa-search"></i> Check Details</button>
		</center>
	</div>
	</form>
</section>

<section class="panel panel-featured panel-featured-primary">';
if(isset($_POST['challans_details'])){
//-----------------------------------------------------
$sqllmsfeesetup	= $dblms->querylms("SELECT f.id, f.dated, f.id_class, f.id_section, f.id_session, f.id_campus,
								   c.class_id, c.class_status, c.class_name,
								   cs.section_id, cs.section_status, cs.section_name					     
								   FROM ".FEESETUP." f
								   INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
								   INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section	
								   WHERE f.is_deleted != '1'
								   AND f.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   AND f.status = '1' AND f.id_class = '".$class."' AND f.id_section = '".$section."' 
								   ORDER BY f.id DESC LIMIT 1");
//-----------------------------------------------------
if(mysqli_num_rows($sqllmsfeesetup) > 0){
//-----------------------------------------------------
$value_feesetup = mysqli_fetch_array($sqllmsfeesetup);
$fee_id = $value_feesetup['id'];
//-----------------------------------------------------
echo'
<form action="fee_challans.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-dollar"></i>
		Challan Details of <b>'.$value_feesetup['class_name'].'</b> (<b>'.$value_feesetup['section_name'].'</b>)</h2>
	</header>
<div class="panel-body">

<div class="row mt-sm">
    <div class="col-sm-3">
        <div class="form-group">
			<label class="control-label">Class <span class="required">*</span></label>
			<input type="hidden" name="id_class" id="id_class" value="'.$class.'">
			<input type="text" class="form-control" value="'.$value_feesetup['class_name'].'" required title="Must Be Required" readonly/>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label class="control-label">Section <span class="required">*</span></label>
			<input type="hidden" name="id_section" id="id_section" value="'.$section.'">
			<input type="text" class="form-control" value="'.$value_feesetup['section_name'].'" required title="Must Be Required" readonly>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label class="control-label">Issue Date <span class="required">*</span></label>
			<input type="text" class="form-control" name="issue_date" id="issue_date" data-plugin-datepicker required title="Must Be Required"/>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="form-group">
			<label class="control-label">Due Date <span class="required">*</span></label>
			<input type="text" class="form-control" name="due_date" id="due_date" data-plugin-datepicker required title="Must Be Required"/>
		</div>
	</div>
</div>';
//------------------------------------------------	
$sqllmsstudent	= $dblms->querylms("SELECT std_id
										FROM ".STUDENTS."
										WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
										AND id_class = '".cleanvars($class)."'
										AND id_section = '".cleanvars($section)."'
										AND std_status = '1' AND is_deleted != '1'
										ORDER BY std_id ASC");
$no = 0;
while($value_std = mysqli_fetch_array($sqllmsstudent)) {
	$no++;
echo '
	<input type="hidden" name="id_std['.$no.']" id="id_std['.$no.']" value="'.$value_std['std_id'].'">';
}
//------------------------------------------------	
 $sqllms	= $dblms->querylms("SELECT 	d.id, d.id_setup, d.id_cat, d.amount,
 									 c.cat_id, c.cat_name
									 FROM ".FEESETUPDETAIL." d
									 INNER JOIN ".FEE_CATEGORY." c ON c.cat_id = d.id_cat												 
									 WHERE d.id_setup = '".$fee_id."' AND c.cat_status = '1'
									 AND c.is_deleted != '1'
									 ORDER BY c.cat_name ASC");
  $srno = 0;
  $amount = 0;
  $total_amount = 0;
  //-----------------------------------------------------
  while($rowsvalues = mysqli_fetch_array($sqllms)) {
  //-----------------------------------------------------
  $srno++;
  //-----------------------------------------------------
  echo '
  <div class="mt-sm" style="margin-left: -15px; ">
	<div class="col-sm-3">
		<div class="form-group">
 			<input type="hidden" name="id_cat['.$srno.']" id="id_cat['.$srno.']" value="'.$rowsvalues['cat_id'].'">
			<label class="control-label">'.$rowsvalues['cat_name'].' <span class="required">*</span></label>
			<input type="text" class="form-control" name="amount['.$srno.']" id="amount['.$srno.']" value="'.$rowsvalues['amount'].'" required title="Must Be Required" readonly/>
		</div>
	</div>
  </div>';
  $amount = $rowsvalues['amount'];
  $total_amount = $total_amount + $amount;
}
echo'
<input type="hidden" name="total_amount" value="'.$total_amount.'">
<div class="row mt-sm mb-lg">
	<div class="col-sm-12">
		<div class="form-group">
			<label class="control-label">Note</label>
			<textarea type="text" class="form-control" name="note"></textarea>
		</div>
	</div>
</div>
</div>
<footer class="panel-footer mt-sm">
	<div class="row">
		<div class="col-md-12">
			<center><button type="submit" name="challans_generate" id="challans_generate" class="btn btn-primary">Generate Challans</button></center>
		</div>
	</div>
</footer>
</form>';
	}
	else{
		echo '
		<div class="panel-body">
			<div class="col-sm-12">
				<div class="form-group">
					<h2 style="text-align:center;">No Fee Structure is Added!</h2>
				</div>
			</div>
		</div>';
	}
}
	echo '
</section>';

}
else{
	header("Location: fee_challans.php");
}
?>