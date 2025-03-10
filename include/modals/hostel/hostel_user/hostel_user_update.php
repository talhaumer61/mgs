<?php 
//---------------------------------------------------------
	include "../../../dbsetting/lms_vars_config.php";
	include "../../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../../functions/login_func.php";
	include "../../../functions/functions.php";
	checkCpanelLMSALogin();
//---------------------------------------------------------
	$sqllmsa	= $dblms->querylms("SELECT  ht.date_end, ht.id, ht.id_hostel, ht.id_room, h.hostel_name, r.room_name
								   		FROM ".HOSTEL_TRANSACTION." ht
										INNER JOIN ".HOSTELS." h ON h.hostel_id = ht.id_hostel
										INNER JOIN ".HOSTEL_ROOMS." r ON r.room_id = ht.id_room
										WHERE ht.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
										AND ht.id = '".cleanvars($_GET['id'])."' LIMIT 1");
	$rowsvaluesa = mysqli_fetch_array($sqllmsa);
//---------------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="hostels-user.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<input type="hidden" name="hostel_transctn_id" id="hostel_transctn_id" value="'.cleanvars($_GET['id']).'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Hostel User</h2>
		</header>
		<div class="panel-body">
						<div class="col-md-12 ">
							<div class="form-group">
								<label class="control-label">Hostel Name <span class="required">*</span></label>
								<select name="hstl_name" data-plugin-selectTwo data-width="100%" id="hstl_name" required title="Must Be Required" class="form-control populate">
                            
							<option value="">Select</option>';
                              
                                $sqllms	= $dblms->querylms("SELECT h.hostel_id, h.hostel_name
								   FROM ".HOSTELS." h  
								   WHERE h.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   ORDER BY h.hostel_name ASC");
								   while($rowsvalues = mysqli_fetch_array($sqllms)){
									  if($rowsvalues['hostel_id'] == $rowsvaluesa['id_hostel']){
										  echo'<option value="'.$rowsvalues['hostel_id'].'" selected>'.$rowsvalues['hostel_name'].'</option>';
										  }
										  else{
											  echo'<option value="'.$rowsvalues['hostel_id'].'">'.$rowsvalues['hostel_name'].'</option>';
											  }
									   					
								   }
																
												echo'			</select>
							</div>
						</div>
						<div class="col-md-12 mb-lg">
							<div class="form-group">
								<label class="control-label">Room Name <span class="required">*</span></label>
								<select name="room_name" data-plugin-selectTwo data-width="100%" id="room_name" required title="Must Be Required" class="form-control populate">
								
								
								<option value="">Select</option>';
                               
                                $sqllms	= $dblms->querylms("SELECT r.room_id, r.room_name
								   FROM ".HOSTEL_ROOMS." r  
								   WHERE r.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   ORDER BY r.room_name ASC");
								   while($rowsvalues = mysqli_fetch_array($sqllms)){
									   if($rowsvalues['room_id'] == $rowsvaluesa['id_room']){
										   echo'<option value="'.$rowsvalues['room_id'].'" selected>'.$rowsvalues['room_name'].'</option>';
										   }else{
											   echo'<option value="'.$rowsvalues['room_id'].'">'.$rowsvalues['room_name'].'</option>';
											   }							
								   }
											echo'					
															</select>
							</div>'; ?>
							
                           <div class="col-md-12 mb-lg">
							<div class="form-group">
							
								<label class="control-label">
									End Date <span class="required">*</span>
								</label>
							
							<div class="input-group">
							    <input type="text" class="form-control" required title="Must Be Required" <?php echo'data-plugin-datepicker data-plugin-options=\'{ "todayHighlight" : true , "format": "dd-mm-yyyy"}\'' ?> name="end_date"
								 value="<?php echo $rowsvaluesa['date_end']; ?>"/>
							    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
						</div>
                            
						<?php echo ' </div>
					</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="user_hostel_update" name="user_hostel_update">Update</button>
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</form>
</section>
</div>
</div>';
//---------------------------------------------------------