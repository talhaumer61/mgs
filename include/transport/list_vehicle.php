<?php 
echo '
<section class="panel panel-featured panel-featured-primary">
<header class="panel-heading">
<a href="#make_vehicle" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
<i class="fa fa-plus-square"></i> Make Vehicle</a>
<h2 class="panel-title"><i class="fa fa-list"> </i> Transport Vehicle List</h2>
</header>

<div class="panel-body">
<div class="table-responsive mt-md mb-md">
<table class="table table-bordered table-striped table-condensed mb-none">
<thead>
<tr>
	<th style="text-align:center;">#</th>
	<th>Route Name </th>
	<th>Vehicle No </th>
	<th>Maximum Allowed </th>
	<th>Driver Name </th>
	<th>Driver Phone </th>
	<th>Driver License </th>
	<th width="70px;" style="text-align:center;">Status</th>
	<th width="100px;" style="text-align:center;">Actions</th>
</tr>
</thead>
<tbody>';
//-----------------------------------------------------
$sqllms	= $dblms->querylms("SELECT v.vehicle_id, v.vehicle_status, v.vehicle_capacity, v.vehicle_no, v.vehicle_driver, 
								   v.id_route, v.vehicle_driverphone, v.vehicle_driverlicense, r.route_name 
								   FROM ".VEHICLES." v   
								   INNER JOIN ".ROUTES." r ON r.route_id = v.id_route  
								   WHERE v.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'  
								   ORDER BY v.vehicle_no ASC");
$srno = 0;
//-----------------------------------------------------
while($rowsvalues = mysqli_fetch_array($sqllms)) {
//-----------------------------------------------------
$srno++;
//-----------------------------------------------------
echo '
<tr>
	<td style="text-align:center;">'.$srno.'</td>
	<td>'.$rowsvalues['route_name'].'</td>
	<td>'.$rowsvalues['vehicle_no'].'</td>
	<td>'.$rowsvalues['vehicle_capacity'].'</td>
	<td>'.$rowsvalues['vehicle_driver'].'</td>
	<td>'.$rowsvalues['vehicle_driverphone'].'</td>
	<td>'.$rowsvalues['vehicle_driverlicense'].'</td>
	<td style="text-align:center;">'.get_status($rowsvalues['vehicle_status']).'</td>
	<td>
		<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/transport/modal_vehicle_update.php?id='.$rowsvalues['vehicle_id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a>
		<a href="#" class="btn btn-danger btn-xs" onclick="confirm_modal(\'vehicles.php?deleteid='.$rowsvalues['vehicle_id'].'\');"><i class="el el-trash"></i></a>
	</td>
</tr>';
//-----------------------------------------------------
}
//-----------------------------------------------------
echo '
</tbody>
</table>
</div>
</div>
</section>';
