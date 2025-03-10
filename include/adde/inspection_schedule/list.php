<?php

$sql2 = "";
$month_id = 0;

// FILTERS
if(isset($_GET['show'])){
	if($_GET['month']){
		$sql2 = "AND schedule_month = '".$_GET['month']."'";
		$month_id = $_GET['month'];
	}
}

echo'
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<a href="inspectionSchedule.php?view=add" class="btn btn-primary ml-sm btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Schedule</a>
		<h2 class="panel-title"><i class="fa fa-list"></i> Inspection Schedule List</h2>
	</header>
	<div class="panel-body">	
		<div class="row form-group mb-md">
			<div class="col-sm-3 col-sm-offset-9">
				<form action="#" class="form-horizontal" id="form" enctype="multipart/form-data" method="get" accept-charset="utf-8">
					<div class="input-group">
						<select class="form-control" data-plugin-selectTwo data-width="100%" name="month">
							<option value="">Select Month</option>';
							foreach($monthtypes as $month){
								echo '<option value="'.$month['id'].'"'; if($month_id == $month['id']){ echo'selected';} echo'>'.$month['name'].'</option>';
							}
							echo'
						</select>
						<div class="input-group-addon" style="padding: 0 !important; border: 0 !important;">
							<button type="submit" name="show" class="btn btn-primary"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</form>
			</div>
		</div>';
		//------------- Pagination ---------------------
		$sqlstring	    = "";
		$adjacents		= 3;
		if(!($Limit)) 	{ $Limit = 50; } 
		if($page)		{ $start = ($page - 1) * $Limit; } else {	$start = 0;	}
		//------------------------------------------------
		$sqllmsPg	= $dblms->querylms("SELECT schedule_id 
										FROM ".INSPECTION_SCHEDULE."
										WHERE id_adde = '".$value_emp['emply_id']."'
										AND is_deleted != '1' $sql2
										ORDER BY schedule_month DESC");
		//--------------------------------------------------
		$count = mysqli_num_rows($sqllmsPg);
		if($page == 0) { $page = 1; }						//if no page var is given, default to 1.
		$prev 		    = $page - 1;							//previous page is page - 1
		$next 		    = $page + 1;							//next page is page + 1
		$lastpage  		= ceil($count/$Limit);					//lastpage is = total pages / items per page, rounded up.
		$lpm1 		    = $lastpage - 1;
		//--------------------------------------------------  


		//-----------------------------------------------------
		$sqllmSch	= $dblms->querylms("SELECT schedule_id, schedule_satus, schedule_approval, schedule_month, id_adde
										FROM ".INSPECTION_SCHEDULE."
										WHERE id_adde = '".$value_emp['emply_id']."'
										AND is_deleted != '1' $sql2
										ORDER BY schedule_month  LIMIT ".($page-1)*$Limit .",$Limit");
		//-----------------------------------------------------
		if(mysqli_num_rows($sqllmSch) > 0){
			echo'
			<div class="table-responsive">
				<table class="table table-bordered table-striped mb-none">
					<thead>
						<tr>
							<th class="center" width="40">Sr.</th>
							<th>Month </th>
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
						while($valueSch = mysqli_fetch_array($sqllmSch)) {
							$srno++;
							echo '
							<tr>
								<td class="center">'.$srno.'</td>
								<td>'.get_monthtypes($valueSch['schedule_month']).'</td>
								<td class="center">'.get_leave($valueSch['schedule_approval']).'</td>
								<td class="center">'.get_leave($valueSch['schedule_satus']).'</td>
								<td class="center">
									<a href="inspectionSchedule.php?id='.$valueSch['schedule_id'].'"';
										if($valueSch['schedule_approval'] == 1) {
											echo'class="btn btn-info btn-xs mr-xs"> <i class="glyphicon glyphicon-eye-open"></i>';
										} else{
											echo'class="btn btn-primary btn-xs mr-xs"> <i class="glyphicon glyphicon-edit"></i> Edit';
										}
										echo'
									</a>
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
				$filters = 'month='.$month_id.'&show';
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
?>