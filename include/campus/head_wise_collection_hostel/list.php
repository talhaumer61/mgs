<?php
if($_SESSION['userlogininfo']['LOGINAFOR'] == 2){	
	$id_campus 		= ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])))? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
	$campus_flag 	= ((!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])))? 'col-md-4': 'col-md-6';
	$id_campus_classes 		= ((isset($valCampLevel['campus_classes']) && !empty($valCampLevel['campus_classes'])))? cleanvars($valCampLevel['campus_classes']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUSCLASSES']);
	echo'
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i> Head Wise Collection Report Hostel </h2>
		</header>
		<form action="HeadWiseCollectionReportPrint.php" target="_blank" id="form" enctype="multipart/form-data" method="POST" accept-charset="utf-8">
			<div class="panel-body">
				<div class="row mb-lg">';
					if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
						echo'
						<div class="col-md-4">
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
									echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $id_campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
								}
								echo'
							</select>
						</div>';
					endif;
					echo'
					<div class="'.$campus_flag.'">
						<div class="form-group">
							<label class="control-label">Class </label>
							<select data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" onchange="get_section(this.value)" class="form-control">
								<option value="">Select</option>';
								$sqllms	= $dblms->querylms("SELECT class_id, class_name
															FROM ".CLASSES." 
															WHERE class_status = '1' 
															AND is_deleted = '0' 
															AND class_id IN (".$id_campus_classes.")
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
							<select data-plugin-selectTwo data-width="100%" name="id_section" onchange="get_sectionstudent(this.value)" id="id_section" class="form-control populate">						
								<option value="">Select</option>';
								$sqllms	= $dblms->querylms("SELECT section_id, section_name
															FROM ".CLASS_SECTIONS."
															WHERE id_campus     = '".$id_campus."'
															AND id_class		= '".$id_class."'
															AND section_status	= '1'
															AND is_deleted		= '0'
															ORDER BY section_name ASC");
								while($rowsvalues = mysqli_fetch_array($sqllms)){
									echo'<option value="'.$rowsvalues['section_id'].'" '.($rowsvalues['section_id'] == $id_section ? 'selected' : '').'>'.$rowsvalues['section_name'].'</option>';
								}
								echo'
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<label class="control-label">Student </label>
						<select class="form-control populate" data-plugin-selectTwo data-width="100%" id="id_std" name="id_std" >
							<option value="">Select Section First</option>
						</select>
					</div>
					<div class="col-md-8">				
						<div class="form-group">
							<label class=" control-label">Date <span class="required" aria-required="true">*</span></label>
							<div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
								<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
								<input type="text" class="form-control" required title="Must Be Required" value="'.$start_date.'" name="start_date">
								<span class="input-group-addon">to</span>
								<input type="text" class="form-control" required title="Must Be Required" value="'.$end_date.'" name="end_date">
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<label class="control-label">Head <span class="required" aria-required="true">*</span></label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" multiple name="id_head[]" required>';
							$sqllmsfeeCat  	= $dblms->querylms("SELECT cat_id, cat_name 
																FROM ".FEE_CATEGORY." 
																WHERE cat_status = '1' 
																AND is_deleted  = '0'");
							while($valuecat = mysqli_fetch_array($sqllmsfeeCat)) {
								echo '<option value="'.$valuecat['cat_id'].'|'.$valuecat['cat_name'].'" '.((in_array($valuecat['cat_id'], $tmp1))? 'selected' : '').'>'.$valuecat['cat_name'].'</option>';
							}
							echo '
						</select>
					</div>
					<input type="hidden" name="is_hostel" value="1">
				</div>
				<center>
					<button type="submit" name="view_report" id="view_report" class="btn btn-primary"><i class="fa fa-search"></i> Take Print</button>
				</center>
			</div>
		</form>
	</section>';
}else{
    header("Location: dashboard.php");
}
?>