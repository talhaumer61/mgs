  <?php
  echo '
  <div id="utilities" class="tab-pane ">
  <form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
   <input type="hidden" name="campus_id" id="campus_id" value="'.cleanvars($_GET['id']).'">
   <fieldset class="mt-lg">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-bordered table-condensed table-striped mb-none">
					<thead>
						<tr>
							<th class="center">#</th>
							<th>Title</th>
							<th class="center">Option</th>
						</tr>
					</thead>
					<tbody>';
						$sqllms	= $dblms->querylms("SELECT *
								   					FROM ".CAMPUS_UTILITIES." 
								   					WHERE id_campus = '".$_GET['id']."' 
								   					ORDER BY id DESC LIMIT 1 ");
						$srno = 0;
						$rowsvalues = mysqli_fetch_array($sqllms);
						echo'
						<tr>
							<td class="center" width="50">1</td>
							<td>School Library</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="library" name="library" value="1" '.(isset($rowsvalues['library']) && $rowsvalues['library'] ==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="library" name="library" value="2" '.(isset($rowsvalues['library']) && $rowsvalues['library']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">2</td>
							<td>Science Lab</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="science_lab" name="science_lab" value="1" '.(isset($rowsvalues['science_lab']) && $rowsvalues['science_lab']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="science_lab" name="science_lab" value="2" '.(isset($rowsvalues['science_lab']) && $rowsvalues['science_lab']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">3</td>
							<td>Computer Lab</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="computer_lab" name="computer_lab" value="1" '.(isset($rowsvalues['computer_lab']) && $rowsvalues['computer_lab']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="computer_lab" name="computer_lab" value="2" '.(isset($rowsvalues['computer_lab']) && $rowsvalues['computer_lab']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">4</td>
							<td>Security Armaments / Metal Detector</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="security_armaments" name="security_armaments" value="1" '.(isset($rowsvalues['security_armaments']) && $rowsvalues['security_armaments']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="security_armaments" name="security_armaments" value="2" '.(isset($rowsvalues['security_armaments']) && $rowsvalues['security_armaments']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">4</td>
							<td>Fire Extinguisher</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="fire_extinguisher" name="fire_extinguisher" value="1" '.(isset($rowsvalues['fire_extinguisher']) && $rowsvalues['fire_extinguisher']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="fire_extinguisher" name="fire_extinguisher" value="2" '.(isset($rowsvalues['fire_extinguisher']) && $rowsvalues['fire_extinguisher']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">5</td>
							<td>Student Hostel Facility </td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="student_hostel" name="student_hostel" value="1" '.(isset($rowsvalues['student_hostel']) && $rowsvalues['student_hostel']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="student_hostel" name="student_hostel" value="2" '.(isset($rowsvalues['student_hostel']) && $rowsvalues['student_hostel']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">6</td>
							<td>Power Backup (UPS, Generator or Solar System)</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="power_backup" name="power_backup" value="1" '.(isset($rowsvalues['power_backup']) && $rowsvalues['power_backup']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="power_backup" name="power_backup" value="2" '.(isset($rowsvalues['power_backup']) && $rowsvalues['power_backup']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">7</td>
							<td>Sound System</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="sound_system" name="sound_system" value="1" '.(isset($rowsvalues['sound_system']) && $rowsvalues['sound_system']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="sound_system" name="sound_system" value="2" '.(isset($rowsvalues['sound_system']) && $rowsvalues['sound_system']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">8</td>
							<td>Water Filters & Water Cooler</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="water_filter_cooler" name="water_filter_cooler" value="1" '.(isset($rowsvalues['water_filter_cooler']) && $rowsvalues['water_filter_cooler']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="water_filter_cooler" name="water_filter_cooler" value="2" '.(isset($rowsvalues['water_filter_cooler']) && $rowsvalues['water_filter_cooler']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">9</td>
							<td>First Aid Box & User Guide</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="firstaid_box" name="firstaid_box" value="1" '.(isset($rowsvalues['firstaid_box']) && $rowsvalues['firstaid_box']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="firstaid_box" name="firstaid_box" value="2" '.(isset($rowsvalues['firstaid_box']) && $rowsvalues['firstaid_box']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">10</td>
							<td>Printer & Photocopier</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="printer_photocopier" name="printer_photocopier" value="1" '.(isset($rowsvalues['printer_photocopier']) && $rowsvalues['printer_photocopier']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="printer_photocopier" name="printer_photocopier" value="2" '.(isset($rowsvalues['printer_photocopier']) && $rowsvalues['printer_photocopier']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">11</td>
							<td>MontessOri Kit & Educational Toys</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="montessori_kit" name="montessori_kit" value="1" '.(isset($rowsvalues['montessori_kit']) && $rowsvalues['montessori_kit']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="montessori_kit" name="montessori_kit" value="2" '.(isset($rowsvalues['montessori_kit']) && $rowsvalues['montessori_kit']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">12</td>
							<td>Internet & CCTV Camera / LCD</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="internet_cctv" name="internet_cctv" value="1" '.(isset($rowsvalues['internet_cctv']) && $rowsvalues['internet_cctv']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="internet_cctv" name="internet_cctv" value="2" '.(isset($rowsvalues['internet_cctv']) && $rowsvalues['internet_cctv']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">13</td>
							<td>Large LCD / projector</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="lcd_projector" name="lcd_projector" value="1" '.(isset($rowsvalues['lcd_projector']) && $rowsvalues['lcd_projector']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="lcd_projector" name="lcd_projector" value="2" '.(isset($rowsvalues['lcd_projector']) && $rowsvalues['lcd_projector']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
						<tr>
							<td class="center" width="50">14</td>
							<td>Sport Kit/Facilities</td>
							<td width="170" class="center">
								<div class="radio-custom radio-inline center">
									<input type="radio" id="sport_kits" name="sport_kits" value="1" '.(isset($rowsvalues['sport_kits']) && $rowsvalues['sport_kits']==1 ? 'checked' : '').'>
									<label for="radioExample1">Yes</label>
								</div>
								<div class="radio-custom radio-inline center">
									<input type="radio" id="sport_kits" name="sport_kits" value="2" '.(isset($rowsvalues['sport_kits']) && $rowsvalues['sport_kits']==2 ? 'checked' : '').'>
									<label for="radioExample2">No</label>
								</div>	
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</fieldset>
	<div class="panel-footer">
		<div class="row text-center">
			<div class="col-sm-12">
				<button type="submit" name="add_utilities" id="add_utilities" class="btn btn-primary">Add Utilities</button>
			</div>
		</div>
	</div>
	</form>
  </div>';
  ?>