<?php 
//-----------------------------------------------
echo '
<title> Events | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Events List </h2>
	</header>
<!-- INCLUDEING PAGE -->
<div class="row">
<div class="col-md-12">
	<style>
	.card{
		padding: 20px;
		font-size: 30px;
		border-radius:10px;
		margin-left: 4%;
		margin-right: 4%;
		}
	.val{
		font-size: 20px;
		text-vertical-align: center;
		margin-left: 18%;
		}
	.span{
		font-size:14px;
		}
	</style>';

	$condition = array( 
						'select'       => 'e.id, e.status, e.subject, e.detail, e.date_from, e.date_to, e.event_to, e.id_campus',
						'join'         => 'LEFT JOIN '.CAMPUS.' c ON c.campus_id = e.id_campus',
						'where'        => array( 
													'e.is_deleted' => '0'
													,'e.id_campus' => $_SESSION['userlogininfo']['LOGINCAMPUS']
												)
						// ,'where_in'     => array( 
						// 							'e.id_campus' => array(0, $_SESSION['userlogininfo']['LOGINCAMPUS']) 
						// 						)
						,'order_by'  => ' e.date_from DESC'
						,'return_type'  => 'all'
								);  
	$events = $dblms->getRows(EVENTS.' e', $condition);
echo '
<section class="panel panel-featured panel-featured-primary">
<header class="panel-heading">
	<h2 class="panel-title"><i class="fa fa-list"></i>  Events List / History</h2>
</header>
<div class="panel-body">
<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
<thead>
	<tr>
		<th style="text-align:center;">#</th>
		<th>Subject </th>
		<th>Date From</th>
		<th>Date to</th>
		<th>Title</th>
	</tr>
</thead>
<tbody>';
$srno=1;
foreach ($events as $key => $event) {
echo '
	<tr>
		<td style="text-align:center;">'.$srno.'</td>
		<td>'.$event['subject'].'</td>
		<td>'.$event['date_from'].'</td>
		<td>'.$event['date_to'].'</td>
		<td>'.$event['event_to'].'</td>
	</tr>';
	$srno++;
}
echo '
</tbody>
</table>
</div>
</section>
</div>
</div>';
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
	var datatable = $('#table_export').dataTable({
				bAutoWidth : false,
				ordering: false,
			});
	});
</script>
<?php 
//------------------------------------
echo '
</section>
</div>
</section>';
//-----------------------------------------------
?>