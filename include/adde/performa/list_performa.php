<?php 
echo '
<section class="panel panel-featured panel-featured-primary">
	<header class="panel-heading">
		<a href="performa.php?view=add" class="btn btn-primary btn-xs pull-right">
			<i class="fa fa-plus-square"></i> Add Performa
		</a>
		<h2 class="panel-title"><i class="fa fa-list"></i>  Performa List</h2>
	</header>
	<div class="panel-body">';
		  //------------- Pagination ---------------------
			$sqlstring	    = "";
			$adjacents = 3;
			if(!($Limit)) 	{ $Limit = 10; } 
			if($page)		{ $start = ($page - 1) * $Limit; } else {	$start = 0;	}
			//------------------------------------------------
			$sqllms	= $dblms->querylms("SELECT per.id
												FROM ".CAMPUS_PERFORMA." per  
												INNER JOIN ".CAMPUS." c on c.campus_id = per.id_campus
												WHERE c.campus_status = '1' 
												AND (per.id_ad = '".$value_emp['emply_id']."' OR per.id_de = '".$value_emp['emply_id']."')
												ORDER BY visit_month ASC");
			//--------------------------------------------------
			$count = mysqli_num_rows($sqllms);
			if($page == 0) { $page = 1; }						//if no page var is given, def ault to 1.
			$prev 		    = $page - 1;							//previous page is page - 1
			$next 		    = $page + 1;							//next page is page + 1
			$lastpage  		= ceil($count/$Limit);					//lastpage is = total pages / items per page, rounded up.
			$lpm1 		    = $lastpage - 1;
			//-------------------------------------------------- 
			$sqllms	= $dblms->querylms("SELECT *
												FROM ".CAMPUS_PERFORMA." per  
												INNER JOIN ".CAMPUS." c on c.campus_id = per.id_campus
												WHERE c.campus_status = '1' 
												AND (per.id_ad = '".$value_emp['emply_id']."' OR per.id_de = '".$value_emp['emply_id']."')
												ORDER BY visit_month ASC LIMIT ".($page-1)*$Limit .",$Limit");
			if(mysqli_num_rows($sqllms) > 0){
				echo '
				<table class="table table-bordered table-striped table-condensed mb-none">
					<thead>
						<tr>
							<th class="center" width="60">#</th>
							<th>Campus Name</th>
							<th>Visit Month</th>
							<th width="70px;" class="center">Status</th>
							<th width="100" class="center">Options</th>
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
								<td>'.$visit_month.'</td>
								<td class="center">'.get_leave($rowsvalues['status']).'</td>
								<td class="text-center">
								<a href="performa_print.php?id='.$rowsvalues['id'].'" target="_blank" class="btn btn-primary btn-xs"><i class="el el-print"></i></a>
									<a href="performa.php?id='.$rowsvalues['id'].'"';
										if($rowsvalues['edit_count'] >= 1 || $rowsvalues['status'] == 1){
											echo'class="btn btn-info btn-xs mr-xs"> <i class="glyphicon glyphicon-eye-open"></i>';
										}
										else{
											echo'class="btn btn-primary btn-xs mr-xs"> <i class="glyphicon glyphicon-edit"></i>';
										}
										echo'
									</a>';
									if(!empty($rowsvalues['attach_file'])){
										echo'<a href="uploads/visit_perfoma/'.$rowsvalues['attach_file'].'" download="Visit Perfoma '.$rowsvalues['campus_name'].' '.get_monthtypes($rowsvalues['visit_month']).'" class="btn btn-success btn-xs mr-xs"><i class="glyphicon glyphicon-download"></i> </a>';
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
					//--------------------------------------------------
					$pagination = "";
					if($lastpage > 1) { 
						//previous button
						if ($page > 1) {
							$pagination.= '<li><a href="'.$current_page.'.php?'.'&page='.$prev.$sqlstring.'"><span class="fa fa-chevron-left"></span></a></a></li>';
						}
						//pages 
						if ($lastpage < 7 + ($adjacents * 3)) { //not enough pages to bother breaking it up
							for ($counter = 1; $counter <= $lastpage; $counter++) {
								if ($counter == $page) {
									$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
								} else {
									$pagination.= '<li><a href="'.$current_page.'.php?'.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
								}
							}
						} else if($lastpage > 5 + ($adjacents * 3)) { //enough pages to hide some
							//close to beginning; only hide later pages
							if($page < 1 + ($adjacents * 3)) {
								for ($counter = 1; $counter < 4 + ($adjacents * 3); $counter++) {
									if ($counter == $page) {
										$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
									} else {
										$pagination.= '<li><a href="'.$current_page.'.php?'.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
									}
								}
								$pagination.= '<li><a href="#"> ... </a></li>';
								$pagination.= '<li><a href="'.$current_page.'.php?'.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
								$pagination.= '<li><a href="'.$current_page.'.php?'.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
							} else if($lastpage - ($adjacents * 3) > $page && $page > ($adjacents * 3)) { //in middle; hide some front and come back
								$pagination.= '<li><a href="'.$current_page.'.php?'.'&page=1'.$sqlstring.'">1</a></li>';
								$pagination.= '<li><a href="'.$current_page.'.php?'.'&page=2'.$sqlstring.'">2</a></li>';
								$pagination.= '<li><a href="'.$current_page.'.php?'.'&page=3'.$sqlstring.'">3</a></li>';
								$pagination.= '<li><a href="#"> ... </a></li>';
								for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
									if ($counter == $page) {
										$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
									} else {
										$pagination.= '<li><a href="'.$current_page.'.php?'.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';                 
									}
								}
								$pagination.= '<li><a href="#"> ... </a></li>';
								$pagination.= '<li><a href="'.$current_page.'.php?'.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
								$pagination.= '<li><a href="'.$current_page.'.php?'.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
							} else { //close to end; only hide early pages
								$pagination.= '<li><a href="'.$current_page.'.php?'.'&page=1'.$sqlstring.'">1</a></li>';
								$pagination.= '<li><a href="'.$current_page.'.php?'.'&page=2'.$sqlstring.'">2</a></li>';
								$pagination.= '<li><a href="'.$current_page.'.php?'.'&page=3'.$sqlstring.'">3</a></li>';
								$pagination.= '<li><a href="#"> ... </a></li>';
								for ($counter = $lastpage - (3 + ($adjacents * 3)); $counter <= $lastpage; $counter++) {
									if ($counter == $page) {
										$pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
									} else {
										$pagination.= '<li><a href="'.$current_page.'.php?'.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';                 
									}
								}
							}
						}
						//next button
						if ($page < $counter - 1) {
							$pagination.= '<li><a href="'.$current_page.'.php?'.'&page='.$next.$sqlstring.'"><span class="fa fa-chevron-right"></span></a></li>';
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
			echo '
	</div>
</section>';
?>