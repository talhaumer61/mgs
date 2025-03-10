<?php
error_reporting(0);
$examtype = $_POST['exm'];
$clss = $_POST['class'];	
$sction = $_POST['section'];		
echo'
<section class="panel panel-featured panel-featured-primary">
	<form action="exam_marks.php" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<header class="panel-heading">
		<h2 class="panel-title fa fa-list">
			Marks List		</h2>
	</header> 
	<div class="panel-body">
		<div class="row mb-lg">
			<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Exam <span class="required">*</span></label>
								<select name="exm" data-plugin-selectTwo data-width="100%" id="subject_holder" required title="Must Be Required" class="form-control populate">
                            	<option value="">Select</option>';
                                $sqllms	= $dblms->querylms("SELECT ex.exam_id, ex.exam_name
								   FROM ".EXAMS." ex  
								   WHERE ex.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   ORDER BY ex.exam_name ASC");
								   while($rowsvalues = mysqli_fetch_array($sqllms)){
									   if($rowsvalues['exam_id'] == $examtype){
										  echo'<option value="'.$rowsvalues['exam_id'].'" selected>'.$rowsvalues['exam_name'].'</option>';
										}else{
										  echo'<option value="'.$rowsvalues['exam_id'].'">'.$rowsvalues['exam_name'].'</option>';
										}
								   }
								echo'
								</select>
							</div>
						</div>
			 <div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Class <span class="required">*</span></label>
								<select name="class" data-plugin-selectTwo data-width="100%" id="subject_holder" required title="Must Be Required" class="form-control populate">
                            		<option value="">Select</option>';
                                $sqllms	= $dblms->querylms("SELECT c.class_id, c.class_name
								   FROM ".CLASSES." c  
								   WHERE c.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   ORDER BY c.class_name ASC");
								   while($rowsvalues = mysqli_fetch_array($sqllms)){
									   if($rowsvalues['class_id'] == $clss){
										   echo'<option value="'.$rowsvalues['class_id'].'" selected>'.$rowsvalues['class_name'].'</option>';
										   }else{
											   echo'<option value="'.$rowsvalues['class_id'].'">'.$rowsvalues['class_name'].'</option>';
											   }
								   }
								   echo'
									</select>
							</div>
						</div>
                        <div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Section <span class="required">*</span></label>
								<select name="section" data-plugin-selectTwo data-width="100%" id="subject_holder" required title="Must Be Required" class="form-control populate">
								
								
								<option value="">Select</option>';
                                $sqllms	= $dblms->querylms("SELECT s.section_id, s.section_name
								   FROM ".CLASS_SECTIONS." s  
								   WHERE s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   ORDER BY s.section_name ASC");
								   while($rowsvalues = mysqli_fetch_array($sqllms)){
									   if($rowsvalues['section_id'] == $sction){
										   echo'<option value="'.$rowsvalues['section_id'].'" selected>'.$rowsvalues['section_name'].'</option>';
										   }else{
											   echo'<option value="'.$rowsvalues['section_id'].'">'.$rowsvalues['section_name'].'</option>';
											   }
								   }
								   echo'
								</select>
							</div>
						</div>
                        
		</div>
		<center>
			<button type="submit" name="exam_marks" id="exam_marks" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
		</center>
	</div>
	</form></section>
<section class="panel panel-featured panel-featured-primary appear-animation mt-sm" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
	';
    if(isset($_POST['exam_marks'])){
	echo'
    <header class="panel-heading">
		<h2 class="panel-title"><i class="fa fa-bar-chart-o"></i> 
		Students Progress Report of '.$rowsub['calss_name'].' ('.$rowsub['Section_name'].' - '.$rowsub['session_name'].' )</h2>
	</header>
    
	<div class="panel-body">
				<div class="table-responsive mt-sm mb-md">
					<table class="table table-bordered table-striped table-condensed  mb-none" id="my_table">
						<thead>';
	

  //-----------------------------------------
  echo'

							<tr>
								<th width:"40">#</th>
								<th>
                                	Students <i class="fa fa-hand-o-down"></i> |
									Subjects <i class="fa fa-hand-o-right"></i>
                                </th>';
								//-----------------------------------------
   								$sqllmssub	= $dblms->querylms("SELECT 
																	subject_id, subject_name, subject_totalmarks, subject_passmarks
	 																FROM ".CLASS_SUBJECTS."
																	WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
																	AND id_class = '1'
																	AND subject_status = '1' ");
  								//-----------------------------------------------------
								$subjectarray = array();
 								while($rowsub = mysqli_fetch_array($sqllmssub)){ 
								$subjectarray[] = $rowsub;
								echo'
								<th>'.$rowsub['subject_name'].'</th>';}
								echo'
								<th>Total Marks</th>
								<th>Obtained Marks</th>	
								<th>Percentage</th>
								<th>Status </th>
								<th width="40">Options </th>
							</tr>
						</thead>
						<tbody>';

    //-----------------------------------------
  $sqllmss	= $dblms->querylms("SELECT 
										s.std_id, s.std_firstname, s.std_lastname, s.std_status, s.id_class, s.id_campus
	 									FROM ".STUDENTS." s
	
										WHERE s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
	 									AND s.std_status = '1' 
	 									AND s.id_class = '1' ");
$srno = 0;
while($rooowsvalues = mysqli_fetch_array($sqllmss)){	
$srno++;	
  //-----------------------------------------				
	echo'
                              <tr>
								<td>'; 
									echo $srno;
								echo' 
								</td>
                                <td>
									 '.$rooowsvalues['std_firstname'].' '.$rooowsvalues['std_lastname'].'
								</td>';

$totalmarks = 0;
$obtmarks = 0;
foreach($subjectarray as $listsub) {
    //-----------------------------------------
  $sqllmsmarks	= $dblms->querylms("SELECT 	* 
  											FROM ".EXAM_MARKS_DETAILS." ed 
  											INNER JOIN ".EXAM_MARKS." e ON e.id = ed.id_setup 
	
	 								WHERE e.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
									AND e.id_class = '1' 
									AND e.id_subject = '".$listsub['subject_id']."'
									AND ed.id_std = '".$rooowsvalues['std_id']."' ");

$rowmarks = mysqli_fetch_array($sqllmsmarks);
	echo '<td>'.$rowmarks['obtain_marks'].'</td>';

$totalmarks = ($totalmarks + $rowmarks['max_marks']);
$obtmarks = ($obtmarks + $rowmarks['obtain_marks']);
}

$permarks = round((($obtmarks/$totalmarks) * 100), 2);
echo '
								<td>'.$totalmarks.'</td>
								<td>'.$obtmarks.'</td>
								<td class="hidden-xs hidden-sm center">
									<div class="progress progress-lg progress-squared light" style="margin: 6px;">
										<div class="progress-bar" role="progressbar" aria-valuenow="'.$permarks.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$permarks.'%;">
												'.$permarks.' %
                                    	</div>
									</div>
								</td>
								<td>Pass/Fail</td>
								<td>
									<a href="include/marks/marks_sheetprint.php" class="btn btn-primary btn-xs" target="include/marks/marks_sheetprint.php">
										<i class="fa fa-print"></i>
									</a>
								</td>
                              </tr>';
}				
echo'	
						</tbody>
					</table>
				</div>
			</div>
            ';
	}
echo'
	<div class="panel-footer">
		<div class="text-right">
			<a href="include/marks/marks_sheetprint.php" class="btn btn-sm btn-primary " target="include/marks/marks_sheetprint.php">
				<i class="glyphicon glyphicon-print"></i> Print
			</a>
		</div>
	</div>

</section>';
?>