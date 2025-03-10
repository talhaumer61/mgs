<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '84', 'view' => '1'))){
	
	$sql2 = "";
	$sql3 = "";
	$sql4 = "";
	$sql5 = "";
	$sql6 = "";
	$sql7 = "";
	$camp ="";
	$dated = "";
	$mnth_from = "";
	$mnth_to = "";
	$ad = "";
	$stat = "";
	$vdate = "";
	//--------- FIlters ----------
	if(isset($_GET['show']))
	{
		//  Campus
		if($_GET['camp'])
		{
			$camp = cleanvars($_GET['camp']);
			$sql2 = "AND per.id_campus = '".$camp."'";
		}
		// Visit Date
		if($_GET['dated'])
		{
			$dated = date('Y-m-d', strtotime(cleanvars($_GET['dated'])));
			$sql3 = "AND per.date_added LIKE '".$dated."%'";
		}
		// Visit Month
		if(!empty($_GET['mnth_from']) && empty($_GET['mnth_to']))
		{
			$mnth_from = $_GET['mnth_from'];
			$sql4 = "AND per.visit_month = '".$mnth_from."'";
		}elseif(empty($_GET['mnth_from']) && !empty($_GET['mnth_to'])){
			$mnth_to = $_GET['mnth_to'];
			$sql4 = "AND per.visit_month = '".$mnth_to."'";
		}elseif(!empty($_GET['mnth_from']) && !empty($_GET['mnth_to'])){
			$mnth_from = $_GET['mnth_from'];
			$mnth_to = $_GET['mnth_to'];
			$sql4 = "AND per.visit_month BETWEEN '".$mnth_from."' AND '".$mnth_to."'";
		}
		// AD
		if($_GET['ad'])
		{
			$ad = $_GET['ad'];
			$sql5 = "AND per.id_ad  = '".$_GET['ad']."'";
		}
		// Verfication Date
		if($_GET['vdate'])
		{
			$vdate = date('Y-m-d', strtotime(cleanvars($_GET['vdate'])));
			$sql76 = "AND per.verification_date LIKE '".$vdate."%'";
		}
		//  Status
		if($_GET['stat'])
		{
			$stat = $_GET['stat'];
			$sql7 = "AND per.status = '".$stat."'";
		}
	}

	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">';
			if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '84', 'add' => '1'))){
				echo'<a href="performa.php?view=add" class="btn btn-primary btn-xs pull-right">';
			}
			echo'
			<i class="fa fa-plus-square"></i> Add Performa</a>
			<h2 class="panel-title"><i class="fa fa-list"></i>  Performa List</h2>
		</header>
		<div class="panel-body">
			<form action="#" method="GET" autocomplete="off">
				<div class="form-group" style="margin-bottom:3px;">
					<div class="col-md-3">
						<label class="control-label">Visit Date </label>
						<input type="text" class="form-control" name="dated" id="dated" value="'.(isset($_GET['dated']) ? $_GET['dated'] : '').'" data-plugin-datepicker/>
					</div>
					<div class="col-md-3">
						<label class="control-label">Month From </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="mnth_from">
							<option value="">Select</option>';
							foreach($monthtypes as $month){
								echo '<option value="'.$month['id'].'"'; if($mnth_from == $month['id']){ echo'selected';} echo'>'.$month['name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-3">
						<label class="control-label">Month To </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="mnth_to">
							<option value="">Select</option>';
							foreach($monthtypes as $month){
								echo '<option value="'.$month['id'].'"'; if($mnth_to == $month['id']){ echo'selected';} echo'>'.$month['name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-3">
						<label class="control-label">Verification Date </label>
						<input type="text" class="form-control" name="vdate" id="vdate" value="'.$_GET['vdate'].'" data-plugin-datepicker/>
					</div>
				</div>
				<div class="form-group mb-xl">
					<div class="col-md-3">
						<label class="control-label">Campus </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="camp">
							<option value="">Select</option>';
								$sqllmsCamp	= $dblms->querylms("SELECT campus_id, campus_name 
													FROM ".CAMPUS." 
													WHERE campus_status = '1'
													AND is_deleted != '1'
													ORDER BY campus_name ASC");
								while($valueCamp = mysqli_fetch_array($sqllmsCamp)) {
									echo '<option value="'.$valueCamp['campus_id'].'"'; if($camp == $valueCamp['campus_id']){ echo'selected';} echo'>'.$valueCamp['campus_name'].'</option>';
								}
								echo '
						</select>
					</div>
					<div class="col-md-4">
						<label class="control-label">AD </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="ad">
							<option value="">Select</option>';
								$sqllmsEmply = $dblms->querylms("SELECT emply_id, emply_name 
																		FROM ".EMPLOYEES." 
																		WHERE emply_status = '1' AND (is_ad = '1' OR is_de = '1')
																		AND is_deleted != '1'
																		ORDER BY emply_name ASC");
								while($valueEmply = mysqli_fetch_array($sqllmsEmply)) {
									echo '<option value="'.$valueEmply['emply_id'].'"'; if($ad == $valueEmply['emply_id']){ echo'selected';} echo'>'.$valueEmply['emply_name'].'</option>';
								}
								echo '
						</select>
					</div>
					<div class="col-md-4">
						<label class="control-label">Status </label>
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="stat">
							<option value="">Select</option>';
							foreach($statusLeave as $status){
								echo '<option value="'.$status['id'].'"'; if($stat == $status['id']){ echo'selected';} echo'>'.$status['name'].'</option>';
							}
							echo'
						</select>
					</div>
					<div class="col-md-1">
						<div class="form-group mt-xl">
							<button type="submit" name="show" class="btn btn-primary" style="width: 75px;"><i class="fa fa-search"></i> Find</button>
						</div>
					</div>
				</div>
			</form>';
			
			//------------- Pagination ---------------------
			$sqlstring	    = "";
			$adjacents = 3;
			if(!($Limit)) 	{ $Limit = 10; } 
			if($page)		{ $start = ($page - 1) * $Limit; } else {	$start = 0;	}
			//------------------------------------------------
			$sqllms	= $dblms->querylms("SELECT per.id
											FROM ".CAMPUS_PERFORMA." per  
											INNER JOIN ".CAMPUS." c on c.campus_id = per.id_campus
											LEFT JOIN ".EMPLOYEES." e on e.emply_id = per.id_ad
											WHERE per.id != '' $sql2 $sql3 $sql4 $sql5 $sql6 $sql7
											ORDER BY visit_month ASC");
			//--------------------------------------------------
			$count = mysqli_num_rows($sqllms);
			if($page == 0) { $page = 1; }						//if no page var is given, def ault to 1.
			$prev 		    = $page - 1;							//previous page is page - 1
			$next 		    = $page + 1;							//next page is page + 1
			$lastpage  		= ceil($count/$Limit);					//lastpage is = total pages / items per page, rounded up.
			$lpm1 		    = $lastpage - 1;
			//-------------------------------------------------- 
			$sqllms	= $dblms->querylms("SELECT per.id, per.status, per.attach_file, per.visit_month, per.edit_count, per.verification_date, per.date_added, c.campus_name, e.emply_name
											FROM ".CAMPUS_PERFORMA." per  
											INNER JOIN ".CAMPUS." 	 c on c.campus_id = per.id_campus
											LEFT JOIN ".EMPLOYEES."  e on e.emply_id = per.id_ad
											WHERE per.id != '' $sql2 $sql3 $sql4 $sql5 $sql6 $sql7
											ORDER BY visit_month ASC  LIMIT ".($page-1)*$Limit .",$Limit");
											
			if(mysqli_num_rows($sqllms) > 0){
				echo'
				<table class="table table-bordered table-striped table-condensed mb-none">
					<thead>
						<tr>
							<th class="center" width="60">#</th>
							<th>Campus Name</th>
							<th>Visit Date</th>
							<th>Visit Month</th>
							<th>AD</th>
							<th>Verfication Date</th>
							<th width="70px;" class="center">Status</th>
							<th width="150" class="center">Options</th>
						</tr>
					</thead>
					<tbody>';
														
						$srno = 0;
						while($rowsvalues = mysqli_fetch_array($sqllms)) {

							$srno++;
							$visit_month = get_monthtypes($rowsvalues['visit_month']);

							echo '
							<tr>
								<td class="center">'.$srno.'</td>
								<td>'.$rowsvalues['campus_name'].'</td>
								<td>'.date('D d M Y' , strtotime($rowsvalues['date_added'])).'</td>
								<td>'.$visit_month.'</td>
								<td>'.$rowsvalues['emply_name'].'</td>
								<td>'; if($rowsvalues['status'] == 1 && $rowsvalues['verification_date'] != '0000-00-00 00:00:00') { echo date('D d M Y' , strtotime($rowsvalues['verification_date'])); } echo'</td>
								<td class="center">'.get_leave($rowsvalues['status']).'</td>
								<td class="text-center">
									<a href="performa_print.php?id='.$rowsvalues['id'].'" target="_blank" class="btn btn-primary btn-xs"><i class="el el-print"></i></a>
									<a href="performa.php?id='.$rowsvalues['id'].'"';
										if($rowsvalues['status'] == 1 && (($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '84', 'edit' => '1')))){
											echo'class="btn btn-info btn-xs mr-xs"> <i class="glyphicon glyphicon-eye-open"></i>';
										} else {
											echo'class="btn btn-primary btn-xs mr-xs"> <i class="glyphicon glyphicon-edit"></i>';
										}
										echo'
									</a>';
									if(!empty($rowsvalues['attach_file'])){
										echo'<a href="uploads/visit_perfoma/'.$rowsvalues['attach_file'].'" download="Visit Perfoma '.$rowsvalues['campus_name'].' '.get_monthtypes($rowsvalues['visit_month']).'" class="btn btn-success btn-xs mr-xs"><i class="glyphicon glyphicon-download"></i> </a>';
									}
									if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '84', 'delete' => '1'))){
										echo'<a href="#" class="btn btn-primary btn-xs" onclick="confirm_modal(\'performa.php?deleteid='.$rowsvalues['id'].'\');"><i class="el el-trash"></i></a>';
									}
									echo'
								</td>
							</tr>';
						}
						echo '
					</tbody>
				</table>';

				// Pagination
				if($count>$Limit) {
					echo '
					<div class="widget-foot">
					<!--WI_PAGINATION-->
					<ul class="pagination pull-right">';
					//--------------------------------------------------
					$current_page = strstr(basename($_SERVER['REQUEST_URI']), '.php', true);
					$filters = 'camp='.$camp.'&dated='.$dated.'&mnth_from='.$mnth_from.'&mnth_to='.$mnth_to.'&ad='.$ad.'&vdate='.$vdate.'&stat='.$stat.'&show';
					//--------------------------------------------------
					$pagination = "";
					if($lastpage > 1) { 
					//previous button
					if ($page > 1) {
						$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page='.$prev.$sqlstring.'"><span class="fa fa-chevron-left"></span></a></a></li>';
					}
					//pages 
					if ($lastpage < 7 + ($adjacents * 3)) { //not enough pages to bother breaking it up
						for ($counter = 1; $counter <= $lastpage; $counter++) {
							if ($counter == $page) {
								$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
							} else {
								$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
							}
						}
					} else if($lastpage > 5 + ($adjacents * 3)) { //enough pages to hide some
					//close to beginning; only hide later pages
						if($page < 1 + ($adjacents * 3)) {
							for ($counter = 1; $counter < 4 + ($adjacents * 3); $counter++) {
								if ($counter == $page) {
									$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
								} else {
									$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
								}
							}
							$pagination.= '<li><a href="#"> ... </a></li>';
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
					} else if($lastpage - ($adjacents * 3) > $page && $page > ($adjacents * 3)) { //in middle; hide some front and some back
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page=1'.$sqlstring.'">1</a></li>';
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page=2'.$sqlstring.'">2</a></li>';
							$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page=3'.$sqlstring.'">3</a></li>';
							$pagination.= '<li><a href="#"> ... </a></li>';
						for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
							if ($counter == $page) {
								$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
							} else {
								$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';                 
							}
						}
						$pagination.= '<li><a href="#"> ... </a></li>';
						$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
						$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
					} else { //close to end; only hide early pages
						$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page=1'.$sqlstring.'">1</a></li>';
						$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page=2'.$sqlstring.'">2</a></li>';
						$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page=3'.$sqlstring.'">3</a></li>';
						$pagination.= '<li><a href="#"> ... </a></li>';
						for ($counter = $lastpage - (3 + ($adjacents * 3)); $counter <= $lastpage; $counter++) {
							if ($counter == $page) {
								$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
							} else {
								$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';                 
							}
						}
					}
					}
					//next button
					if ($page < $counter - 1) {
						$pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page='.$next.$sqlstring.'"><span class="fa fa-chevron-right"></span></a></li>';
					} else {
						$pagination.= "";
					}
						echo $pagination;
					}
					echo '
					</ul>
					<!--WI_PAGINATION-->
						<div class="clearfix"></div>
					</div>';
				}
			}
			else{
				echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
			}
			echo'
		</div>
	</section>';
}
else{
	header("Location: dashboard.php");
}
?>