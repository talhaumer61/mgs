<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'view' => '1'))){ 
echo'
<section class="panel panel-featured panel-featured-primary">
<header class="panel-heading">';
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '20', 'add' => '1'))){ 
	echo'
	<a href="#make_bio" class="modal-with-move-anim btn btn-primary btn-xs pull-right">
	<i class="fa fa-plus-square"></i> Make Campus Biography </a>';
}
echo'
	<h2 class="panel-title"><i class="fa fa-list"></i> Campus Biography List</h2>
</header>
<div class="panel-body">
<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
<thead>
	<tr>
		<th class="center" width="50">#</th>
		<th width="70">Logo</th>
		<th>Reg NO#</th>
		<th>Camous Name</th>
		<th>Principal</th>
		<th width="40">Option</th>
	</tr>
</thead>
<tbody>';
//-----------------------------------------------------
$sqllms	= $dblms->querylms("SELECT MAX(b.bio_id) as bio_id, b.bio_status, b.principal_name, c.campus_regno, c.campus_code, c.campus_name, c.campus_logo
								   FROM ".CAMPUS_BIOGRAPHY." b
								   INNER JOIN ".CAMPUS." c ON c.campus_id = b.id_campus
								   GROUP BY b.id_campus
								   ORDER BY b.bio_id DESC");
$srno = 0;
//-----------------------------------------------------
while($rowsvalues = mysqli_fetch_array($sqllms)) {
//-----------------------------------------------------
$srno++;
//-----------------------------------------------------
if($rowsvalues['campus_logo']) 
{
	$logo = "uploads/images/campus/".$rowsvalues['campus_logo'];
}
else
{
	$logo = "uploads/logo.png";
}
//-----------------------------------------------------
echo '
<tr>
	<td class="center">'.$srno.'</td>
	<td class="center"><img src="'.$logo.'" style="width:40px; height:40px;"></td>
	<td>'.$rowsvalues['campus_regno'].'</td>
	<td>'.$rowsvalues['campus_name'].'</td>
	<td>'.$rowsvalues['principal_name'].'</td>
	<td class="center"><a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/campus_biography/update.php?bio_id='.$rowsvalues['bio_id'].'\');"><i class="glyphicon glyphicon-eye-open"></i> </a></td>
</tr>';
//-----------------------------------------------------
}
//-----------------------------------------------------
echo '
</tbody>
</table>
</div>
</section>';
}
else{
	header("Location: dashboard.php");
}
?>