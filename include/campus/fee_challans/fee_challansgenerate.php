<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'add' => '1'))) { 
	$today			= date('m/d/Y');
	$id_month		= "";
	$id_class		= "";
	$class_name		= "";
	$id_section		= "";
	$section_name	= "";
	$section_filter	= "";
	$is_hostel		= ((isset($_POST['is_hostel']) && !empty($_POST['is_hostel'])))	? cleanvars($_POST['is_hostel'])	: '';
	$id_campus		= ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])))	? cleanvars($_POST['id_campus'])	: cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);

	if(isset($_POST['id_class'])){
		// EXPLODE ARRAY
		$aray		= explode('|', $_POST['id_class']);
		$id_class	= $aray[0];
		$class_name	= $aray[1];
	}
	if(isset($_POST['id_section']) && !empty($_POST['id_section'])){
		// EXPLODE ARRAY
		$aray			= explode('|', $_POST['id_section']);
		$id_section		= $aray[0];
		$section_name	= $aray[1];
		$section_filter	= "AND fs.id_section	= '".cleanvars($id_section)."'";
	}
	if(isset($_POST['id_month'])){
		$id_month	= $_POST['id_month'];
	}
	if($is_hostel == '1'){
        $sql = 'AND c.cat_for IN (1,2)';
    }elseif($is_hostel == '2'){
        $sql = 'AND c.cat_for IN (1)';
    }
	$campus_flag 	= ((!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])))? 'col-md-4': 'col-md-6';
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<header class="panel-heading">
				<h4 class="panel-title"><i class="fa fa-plus-square"></i> Make Class Fee Challans</h4>
			</header>
			<div class="panel-body">
				<div class="row mb-2">';
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
						echo'
						<div class="'.$campus_flag.'">
							<label class="control-label">Sub Campus</label>
							<select class="form-control" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" onchange="get_class(this.value)"> 
								<option value="">Select</option>';
								$sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
																	FROM ".CAMPUS." 
																	WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
																	AND campus_status	= '1'
																	AND is_deleted		= '0'
																	ORDER BY campus_id ASC");
								while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
									echo '<option value="'.$valSubCampus['campus_id'].'" '.(($valSubCampus['campus_id'] == $id_campus) ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
								}
								echo'
							</select>
						</div>';
					endif;
					echo'
					<div class="'.$campus_flag.'">
						<div class="form-group">
							<label class="control-label">Class <span class="required">*</span></label>
							<select data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" onchange="get_feeclasssection(this.value)" required title="Must Be Required" class="form-control">
								<option value="">Select</option>';
								$sqllms	= $dblms->querylms("SELECT class_id, class_name
															FROM ".CLASSES." 
															WHERE class_status = '1' AND is_deleted != '1' 
															AND class_id IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
															ORDER BY class_id ASC");
								while($rowsvalues = mysqli_fetch_array($sqllms)){
									echo'<option value="'.$rowsvalues['class_id'].'|'.$rowsvalues['class_name'].'" '.($rowsvalues['class_id']==$id_class ? 'selected' : '').'>'.$rowsvalues['class_name'].'</option>';		
								}
								echo'
							</select> 
						</div>
					</div>
					<div class="'.$campus_flag.'">
						<div class="form-group">
							<label class="control-label">Section </label>
							<select data-plugin-selectTwo data-width="100%" name="id_section" id="id_section" class="form-control populate">						
								<option value="">Select</option>';
								$sqllms	= $dblms->querylms("SELECT section_id, section_name
															FROM ".CLASS_SECTIONS."
															WHERE id_campus     = '".$id_campus."'
															AND id_class		= '".cleanvars($id_class)."'
															AND section_status	= '1'
															AND is_deleted		= '0'
															ORDER BY section_name ASC");
								while($rowsvalues = mysqli_fetch_array($sqllms)){
									echo'<option value="'.$rowsvalues['section_id'].'|'.$rowsvalues['section_name'].'" '.($rowsvalues['section_id']==$id_section ? 'selected' : '').'>'.$rowsvalues['section_name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
					
				</div>
				<div class="row mb-lg">
					<div class="'.$campus_flag.'">
						<div class="form-group">
							<label class="control-label">For Month <span class="required">*</span></label>
							<select data-plugin-selectTwo data-width="100%" name="id_month" id="id_month" required title="Must Be Required" class="form-control populate">						
								<option value="">Select</option>';
								foreach($monthtypes as $month){
									echo'<option value="'.$month['id'].'" '.($id_month==$month['id'] ? 'selected' : '').'>'.$month['name'].'</option>';
								}
								echo '
							</select>
						</div>
					</div>
					<div class="'.$campus_flag.'">
						<div class="form-group">
							<label class="control-label">Is Hostelize <span class="text-danger">*</span></label>
							<select required="" class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="is_hostel" name="is_hostel" title="Must Be Required">
								<option value="">Select</option>';
								foreach ($statusyesno as $hostel_status) {
									echo '<option value="'.$hostel_status['id'].'" '.($hostel_status['id'] == $is_hostel ? 'selected' : '').'>'.$hostel_status['name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
				</div>
				<center>
					<button type="submit" name="challans_details" id="challans_details" class="btn btn-primary"><i class="fa fa-search"></i> Check Details</button>
				</center>
			</div>
		</form>
	</section>';

	// FILTER RESULTS
	if(isset($_POST['challans_details'])){
		$DueMonth = $_POST['id_month'];
		
		$sqlSession = $dblms->querylms("SELECT session_startdate
										FROM ".SESSIONS." 
										WHERE session_id	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
										AND session_status	= '1'
										AND is_deleted		= '0' ");
		$valueSession = mysqli_fetch_array($sqlSession);
		$session_startMonth = date('m', strtotime(cleanvars($valueSession['session_startdate'])));
		$session_startYear = date('Y', strtotime(cleanvars($valueSession['session_startdate'])));

		if($DueMonth >= $session_startMonth){
			$DueYear = $session_startYear;
			$DueDate = date(''.$DueMonth.'/15/'.$DueYear.'');
		}elseif($DueMonth == ''){
			$DueDate = date('m/15/Y');
		}else{
			$DueYear = $session_startYear + 1;
			$DueDate = date(''.$DueMonth.'/15/'.$DueYear.'');
		}
		
		// GET SECTIONS
		$sqlSections = $dblms->querylms("SELECT fs.id_section, cs.section_name
											FROM ".FEESETUP." fs
											INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id  = fs.id_section
											WHERE fs.is_deleted	= '0'
											AND fs.status		= '1'
											AND fs.id_class		= '".cleanvars($id_class)."'
											$section_filter
											AND fs.id_campus	= '".cleanvars($id_campus)."'
											AND fs.id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'");					
		if(mysqli_num_rows($sqlSections)>0){
			echo'
			<section class="panel panel-featured panel-featured-primary">
				<form action="fee_challans.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<header class="panel-heading">
						<h2 class="panel-title"><i class="fa fa-info-circle"></i>
						Challan Details of <b>'.$class_name.'</b> </h2>
					</header>
					<div class="panel-body">
						<input type="hidden" name="id_campus" id="id_campus" value="'.$id_campus.'">
						<input type="hidden" name="id_month" id="id_month" value="'.$id_month.'">
						<input type="hidden" name="id_class" id="id_class" value="'.$id_class.'">
						<input type="hidden" name="is_hostel" id="is_hostel" value="'.$is_hostel.'">';
						$iForJs = 0;
						while($valSec = mysqli_fetch_array($sqlSections)){	
							// GET FEE SETUP AND AMOUNTS
							$sqlFees = $dblms->querylms("SELECT fs.id, d.id, d.id_setup, d.id_cat, d.amount, d.duration, c.cat_id, c.cat_name
															FROM ".FEESETUP." fs
															INNER JOIN ".FEESETUPDETAIL." d ON d.id_setup = fs.id
															INNER JOIN ".FEE_CATEGORY." c ON c.cat_id = d.id_cat
															WHERE fs.is_deleted	= '0'
															AND fs.status		= '1'
															AND fs.id_class		= '".cleanvars($id_class)."'
															AND fs.id_section	= '".$valSec['id_section']."'
															AND fs.id_campus	= '".cleanvars($id_campus)."'
															$sql
															AND fs.id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
															ORDER BY c.cat_id ASC");	
							echo '
							<section class="panel panel-featured panel-featured-primary">
								<header class="panel-heading">
									<h2 class="panel-title"><b>'.$valSec['section_name'].'</b> </h2>
								</header>
								<div class="panel-body">
									<div class="row">
										<div class="form-group mt-sm">
											<div class="col-sm-4">
												<label class="control-label">Section <span class="required">*</span></label>
												<input type="hidden" name="id_section[]" id="id_section" value="'.$valSec['id_section'].'">
												<input type="text" class="form-control" value="'.$valSec['section_name'].'" required title="Must Be Required" readonly>
											</div>
											<div class="col-sm-4">
												<label class="control-label">Issue Date <span class="required">*</span></label>
												<input type="text" class="form-control" name="issue_date[]" id="issue_date" value="'.$today.'" data-plugin-datepicker required title="Must Be Required" readonly/>
											</div>
											<div class="col-sm-4">
												<label class="control-label">Due Date <span class="required">*</span></label>
												<input type="text" class="form-control" name="due_date[]" id="due_date" value="'.$DueDate.'" data-plugin-datepicker required title="Must Be Required"/>
											</div>
										</div>';
										$srno = 0;
										$amount = 0;
										$total_amount = 0;
										
										echo'
										<div class="form-group">';
											$iForJs++;
											while($valFees = mysqli_fetch_array($sqlFees)){
												$srno++;
												if($valFees['cat_id']==2){
													$tuitionFee 		= $valFees['amount'];

													$scholarship 		= ($tuitionFee * $valScholarship['scholarship']) / 100;
													$concession 		= ($tuitionFee * $valScholarship['concession']) / 100;
													$fine 				= $valScholarship['fine'];
												}
												echo'
												<div class="col-md-4">
													<input class="form-control" type="hidden" name="id_cat_'.$valSec['id_section'].'['.$srno.']" id="id_cat['.$srno.']" value="'.$valFees['cat_id'].'">
													<label class="control-label">'.$valFees['cat_name'].' <span class="required">*</span></label>
													<input class="form-control sum_'.$iForJs.'" type="number" class="form-control" name="amount_'.$valSec['id_section'].'['.$srno.']" id="amount_'.$valSec['id_section'].'_['.$srno.']" value="'.$valFees['amount'].'" required title="Must Be Required"/>
												</div>';
												$amount = $valFees['amount'];
												$total_amount = $total_amount + $amount;
											}
											echo'
										</div>							
										<div class="form-group">
											<div class="col-md-12">
												<label class="control-label">Total Payable <span class="required">*</span></label>
												<input class="form-control total_amount_'.$iForJs.'" type="text" class="form-control" name="total_amount[]" id="total_amount_'.$valSec['id_section'].'" value="'.$total_amount.'" required title="Must Be Required" readonly/>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-12">
												<label class="control-label">Note</label>
												<textarea class="form-control" rows="2" name="note_'.$valSec['id_section'].'['.$valSec['id_section'].']" id="note"></textarea>
											</div>
										</div>
									</div>
								</div>
							</section>';
						}	
						echo '
					</div>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-12">
								<center><button type="submit" name="challans_generate" id="challans_generate" class="btn btn-primary">Generate Challans</button></center>
							</div>
						</div>
					</footer>
				</form>
			</section>';
		}else{
			echo'<h4 class="panel-body text-danger center">No Monthly Fee Structure Added! <br><br> Kindly Add Fee Details for Particular Class and Section First</h4>';
		}
	}
}else{
	header("Location: fee_challans.php");
}
?>