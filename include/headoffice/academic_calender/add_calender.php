<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '68', 'add' => '1'))){   
  echo '
	  <section class="panel panel-featured panel-featured-primary">
	  
			  <form action="academic-calender.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" >
				  <header class="panel-heading">
					  <h2 class="panel-title"><i class="fa fa-plus-square"></i>	Make Academic Calendar</h2>
				  </header>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-md-6">
								<label class="control-label">Session <span class="required">*</span></label>
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_session">
									<option value="">Select</option>';
									$sqllmscls	= $dblms->querylms("SELECT session_id, session_name 
																FROM ".SESSIONS."
																WHERE session_id != '' 
																ORDER BY session_id DESC");
									while($valuecls = mysqli_fetch_array($sqllmscls)) {
									echo '<option value="'.$valuecls['session_id'].'">'.$valuecls['session_name'].'</option>';
									}
								echo '
								</select>
							</div>
							<div class="col-md-6">
								<label class="control-label">Province <span class="required">*</span></label>
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_prov">
									<option value="">Select</option>';
										$sqllmsprov	= $dblms->querylms("SELECT prov_id, prov_name
															FROM ".PROVINCES."
															WHERE prov_id != '' AND prov_status = '1'
															AND is_deleted != '1'
															ORDER BY prov_ordering ASC");
										while($valueprov = mysqli_fetch_array($sqllmsprov)) {
									echo '<option value="'.$valueprov['prov_id'].'">'.$valueprov['prov_name'].'</option>';
									}
								echo '
								</select>
							</div>
						</div>
						<div class="form-group">

							<div class="col-md-4">
								<label class="control-label">Publish <span class="required">*</span></label>
								<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="published">
									<option value="">Select</option>
									<option value="1">Yes</option>
									<option value="2">No</option>
								</select>
							</div>
							<div class="col-md-4">
								<label class="control-label">Start <span class="required">*</span></label>
								<input type="text" class="form-control" name="term_start" id="term_start" autocomplete="off" data-plugin-datepicker/>
							</div>
							<div class="col-md-4">
								<label class="control-label">End <span class="required">*</span></label>
								<input type="text" class="form-control" name="term_end" id="term_end" autocomplete="off" data-plugin-datepicker/>
							</div>
						</div>

					  <div class="form-group">
						  <label class="col-sm-1 control-label">Status <span class="required">*</span></label>
						  <div class="col-md-11">
							  <div class="radio-custom radio-inline">
								  <input type="radio" id="status" name="status" value="1" checked>
								  <label for="radioExample1">Active</label>
							  </div>
							  <div class="radio-custom radio-inline">
								  <input type="radio" id="status" name="status" value="2">
								  <label for="radioExample2">Inactive</label>
							  </div>
						  </div>
					  </div>
					  
					  <br>
					  
					  <table class="table table-hover table-striped table-condensed mb-none">
						  <thead>
							  <tr>
								  <th class="text-center">Category</th>
								  <th class="text-center">Start Date</th>
								  <th class="text-center">End Date</th>
								  <th class="text-center">Remarks</th>
							  </tr>
						  </thead>
						  <tbody>';
							//-----------------------------------------------------
							$sqllms	= $dblms->querylms("SELECT cat_id, cat_name
																FROM ".ACADEMIC_PARTICULARS."												 
																WHERE cat_status = '1' ORDER BY cat_name ASC");
							$srno = 0;
							//-----------------------------------------------------
							while($rowsvalues = mysqli_fetch_array($sqllms)) {
							//-----------------------------------------------------
							$srno++;
							//-----------------------------------------------------
							echo '
							<input type="hidden" name="id_cat['.$srno.']" id="id_cat['.$srno.']" value="'.$rowsvalues['cat_id'].'">
							  <tr>
								  <td >'.$rowsvalues['cat_name'].'</td>
								 <td>
									  <div class="form-group mt-sm">
										  <div class="col-md-12">
										  		<input type="text" class="form-control" name="date_start['.$srno.']" id="date_start['.$srno.']" autocomplete="off" data-plugin-datepicker/>
										  </div>
									  </div>
								  </td>
								  <td>
									  <div class="form-group mt-sm">
										  <div class="col-md-12">
										  	  <input type="text" class="form-control" name="date_end['.$srno.']" id="date_end['.$srno.']" autocomplete="off" data-plugin-datepicker/>
										  </div>
									  </div>
								  </td>
								  <td>
									  <div class="form-group mt-sm">
										  <div class="col-md-12">
										  		<input type="text" class="form-control" name="remarks['.$srno.']" id="remarks['.$srno.']" autocomplete="off"/>
										  </div>
									  </div>
								  </td>
							  </tr>';
  //-----------------------------------------------------
  }
  //-----------------------------------------------------
  echo '
					  </tbody>
			  </table>
					  
				  </div>
		  
				  <footer class="panel-footer">
					  <div class="row">
						  <div class="col-md-12 text-right">
							  <button type="submit" class="btn btn-primary" id="submit_calendar" name="submit_calendar">Save</button>
							  <button class="btn btn-default modal-dismiss">Cancel</button>
						  </div>
					  </div>
				  </footer>
		  		</div>
			</form>
	  </section>';
}
else{
	header("Location: academic-calender.php");
}
?>
