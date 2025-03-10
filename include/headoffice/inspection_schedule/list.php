<?php
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '85', 'view' => '1'))) { 
	
	$sql2 = "";
	$sql3 = "";
	$month_id = 0;
	$adde = '';

	//--------- FIlters ----------
	if(isset($_GET['show']))
	{
		//Months
		if($_GET['month'])
		{
			$sql2 = "AND schedule_month = '".$_GET['month']."'";
			$month_id = $_GET['month'];
		}
		// ADE / DDE
		if($_GET['adde'])
		{
			$sql3 = "AND id_adde = '".$_GET['adde']."'";
			$adde = $_GET['adde'];
		}
	}

	echo '
	<section class="panel panel-featured panel-featured-primary">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="fa fa-list"></i> Inspection Schedule List</h2>
		</header>
		<div class="panel-body">
			<div class="row form-group mb-md">
				<div class="col-sm-6 col-sm-offset-6">
					<form action="#" class="form-horizontal" id="form" enctype="multipart/form-data" method="get" accept-charset="utf-8">
						<div class="input-group">
							<div class="row">
								<div class="col-sm-6 p-0">
									<select class="form-control" data-plugin-selectTwo data-width="100%" name="month">
										<option value="">Select Month</option>';
										foreach($monthtypes as $month){
											echo '<option value="'.$month['id'].'"'; if($month_id == $month['id']){ echo'selected';} echo'>'.$month['name'].'</option>';
										}
										echo'
									</select>
								</div>
								<div class="col-sm-6 p-0">
									<select class="form-control" data-plugin-selectTwo data-width="100%" name="adde">
										<option value="">Select ADE / DDE</option>';
										$sqllmEmply	= $dblms->querylms("SELECT emply_id, emply_name
																			FROM ".EMPLOYEES."
																			WHERE emply_status = '1' AND (is_ad = '1' OR is_de = '1' )
																			AND is_deleted != '1'
																			ORDER BY emply_name ASC");
										while($valueEmply = mysqli_fetch_array($sqllmEmply)) {
											echo '<option value="'.$valueEmply['emply_id'].'"'; if($adde == $valueEmply['emply_id']){ echo'selected';} echo'>'.$valueEmply['emply_name'].'</option>';
										}
										echo'
									</select>
								</div>
							</div>
							<div class="input-group-addon" style="padding: 0 !important; border: 0 !important;">
								<button type="submit" name="show" class="btn btn-primary"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</form>
				</div>
			</div>';
			//------------- Pagination ---------------------
			$sqlstring	    = "";
			$adjacents = 3;
			if(!($Limit)) 	{ $Limit = 50; } 
			if($page)		{ $start = ($page - 1) * $Limit; } else {	$start = 0;	}
			//------------------------------------------------
			$sqllms	= $dblms->querylms("SELECT s.schedule_id 
											FROM ".INSPECTION_SCHEDULE." s
											INNER JOIN ".EMPLOYEES." e ON e.emply_id = s.id_adde
											WHERE s.is_deleted != '1' $sql2 $sql3
											ORDER BY s.schedule_month DESC");
			//--------------------------------------------------
			$count = mysqli_num_rows($sqllms);
			if($page == 0) { $page = 1; }						//if no page var is given, default to 1.
			$prev 		    = $page - 1;							//previous page is page - 1
			$next 		    = $page + 1;							//next page is page + 1
			$lastpage  		= ceil($count/$Limit);					//lastpage is = total pages / items per page, rounded up.
			$lpm1 		    = $lastpage - 1;
			//--------------------------------------------------  


			//-----------------------------------------------------
			$sqllms	= $dblms->querylms("SELECT s.schedule_id, s.schedule_satus, s.schedule_approval, s.schedule_month, e.emply_name
											FROM ".INSPECTION_SCHEDULE." s
											INNER JOIN ".EMPLOYEES." e ON e.emply_id = s.id_adde
											WHERE s.is_deleted != '1' $sql2 $sql3
											ORDER BY s.schedule_month  LIMIT ".($page-1)*$Limit .",$Limit");
			//-----------------------------------------------------
			if(mysqli_num_rows($sqllms) > 0){
				echo'
				<div class="table-responsive">
					<table class="table table-bordered table-striped mb-none">
						<thead>
							<tr>
								<th class="center" width="40">Sr.</th>
								<th>Month </th>
								<th>ADE / DDE </th>
								<th width="70" class="center">Approval</th>
								<th width="70" class="center">Status</th>
								<th width="100" class="center">Options</th>
							</tr>
						</thead>
						<tbody>';
							//-----------------------------------------------------
							$srno = 0;
							$totalConcessionScholarship = 0;
							$totFine = 0;
							$totRemaining = 0;
							$totPayable = 0;
							//-----------------------------------------------------
							while($rowsvalues = mysqli_fetch_array($sqllms)) {
								$srno++;
								echo '
								<tr>
									<td class="center">'.$srno.'</td>
									<td>'.get_monthtypes($rowsvalues['schedule_month']).'</td>
									<td>'.$rowsvalues['emply_name'].'</td>
									<td class="center">'.get_leave($rowsvalues['schedule_approval']).'</td>
									<td class="center">'.get_leave($rowsvalues['schedule_satus']).'</td>
									<td class="center">';
										if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '85', 'edit' => '1'))) { 
											echo'<a href="inspectionSchedule.php?id='.$rowsvalues['schedule_id'].'"';
												if($rowsvalues['schedule_satus'] == 1) {
													echo'class="btn btn-info btn-xs mr-xs"> <i class="glyphicon glyphicon-eye-open"></i>';
												} else{
													echo'class="btn btn-primary btn-xs mr-xs"> <i class="glyphicon glyphicon-edit"></i> Edit';
												}
												echo'
											</a>';
										}
										echo'
									</td>
								</tr>';
							}
							echo '
						</tbody>
					</table>
				</div>';
				//-------------- Pagination ------------------
				if($count>$Limit) {
					echo '
					<div class="widget-foot">
					<!--WI_PAGINATION-->
					<ul class="pagination pull-right">';
					//--------------------------------------------------
					$current_page = strstr(basename($_SERVER['REQUEST_URI']), '.php', true);
					$filters = 'month='.$month_id.'&adde='.$adde.'&show';
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
				//------------------------------------------------
			}
			else{
				echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
			}
			echo'
		</div>
	</section>';

} else {
	header("Location: dashboard.php");
}
?>