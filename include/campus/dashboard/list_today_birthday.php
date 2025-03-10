<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))) {
	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i> Birthday</h2>
		</header>';
		$TodayDate = date('m-d');
		$sqllmsStd	= $dblms->querylms("SELECT s.std_id, s.std_name, s.std_fathername, s.std_gender, s.std_phone, s.std_regno, s.std_photo
										FROM ".STUDENTS."	s
										WHERE s.std_id != '' AND s.is_deleted != '1' AND s.std_status = '1'
										AND s.std_dob LIKE '%".$TodayDate."'
										AND s.id_campus IN (".$id_campus.") ");
		
		$sqllmsEmp	= $dblms->querylms("SELECT emply_id, emply_name, emply_nic, emply_gender, emply_phone, emply_regno, emply_photo
										FROM ".EMPLOYEES."
										WHERE emply_id != '' AND is_deleted != '1' AND emply_status = '1'
										AND emply_dob LIKE '%".$TodayDate."'
										AND id_campus IN (".$id_campus.") ");
		echo'
		<div class="panel-body accordion">
			<div class="row">
				<div class="col-md-6">
					<table class="table table-condensed mt-xs" id ="table_export">
						<thead>
							<tr>
								<th colspan="2" style="border-bottom: 1px solid #ddd;">Students</th>
							</tr>
						</thead>
						<tbody>';
						if(mysqli_num_rows($sqllmsStd) > 0){
							$valuesStd = mysqli_fetch_array($sqllmsStd);
							if($valuesStd['std_photo']) { 
								$photo = "uploads/images/students/".$valuesStd['std_photo']."";
							}
							else{
								$photo = "uploads/default-student.jpg";
							}
							echo '
							<tr>
								<td width="40"><img src="'.$photo.'" style="width:40px; height:40px;"></td>
								<td><b>'.$valuesStd['std_name'].'</b></td>
							</tr>';
						}else{
							echo'
							<tr>
								<th class="center text-danger">No Birthday for today</th>
							</tr>';
						}
						echo'
						</tbody>
					</table>
				</div>
				<div class="col-md-6">
					<table class="table table-condensed mt-xs" id ="table_export">
						<thead>
							<tr>
								<th colspan="2" style="border-bottom: 1px solid #ddd;">Employees</th>
							</tr>
						</thead>
						<tbody>';
						if(mysqli_num_rows($sqllmsEmp) > 0){
							$valuesEmp = mysqli_fetch_array($sqllmsEmp);
							if($valuesEmp['emply_photo']) { 
								$photo = "uploads/images/employees/".$valuesEmp['emply_photo']."";
							}
							else{
								$photo = "uploads/default.png";
							}
							echo '
							<tr>
								<td width="40"><img src="'.$photo.'" style="width:40px; height:40px;"></td>
								<td><b>'.$valuesEmp['emply_name'].'</b></td>
							</tr>';
						}else{
							echo'
							<tr>
								<th class="center text-danger">No Birthday for today</th>
							</tr>';
						}
						echo'
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="panel-body panelacc">
			<div class="row">
				<div class="col-md-6">
					<table class="table table-condensed mt-xs" id ="table_export">
						<tbody>';
						if(mysqli_num_rows($sqllmsStd) > 0){
							while($valuesStd = mysqli_fetch_array($sqllmsStd)):
								if($valuesStd['std_photo']) { 
									$photo = "uploads/images/students/".$valuesStd['std_photo']."";
								}
								else{
									$photo = "uploads/default-student.jpg";
								}
								echo '
								<tr>
									<td width="40"><img src="'.$photo.'" style="width:40px; height:40px;"></td>
									<td><b>'.$valuesStd['std_name'].'</b></td>
								</tr>';
							endwhile;
						}
						echo'
						</tbody>
					</table>
				</div>
				<div class="col-md-6">
					<table class="table table-condensed mt-xs" id ="table_export">
						<tbody>';
						if(mysqli_num_rows($sqllmsEmp) > 0){
							while($valuesEmp = mysqli_fetch_array($sqllmsEmp)):
								if($valuesEmp['emply_photo']) { 
									$photo = "uploads/images/employees/".$valuesEmp['emply_photo']."";
								}
								else{
									$photo = "uploads/default.png";
								}
								echo '
								<tr>
									<td width="40"><img src="'.$photo.'" style="width:40px; height:40px;"></td>
									<td><b>'.$valuesEmp['emply_name'].'</b></td>
								</tr>';
							endwhile;
						}
						echo'
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
	<script>
		var acc = document.getElementsByClassName("accordion");
		var i;

		for (i = 0; i < acc.length; i++) {
			acc[i].addEventListener("click", function() {
				this.classList.toggle("active");
				var panelacc = this.nextElementSibling;
				console.log(panelacc.style.maxHeight)
				if (panelacc.style.maxHeight) {
				panelacc.style.maxHeight = null;
				} else {
				panelacc.style.maxHeight = panelacc.scrollHeight + "px";
				} 
			});
		}
	</script>';
}
?>