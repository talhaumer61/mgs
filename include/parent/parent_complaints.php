<?php 
//-----------------------------------------------
echo '
<title> Complaints & Suggestions | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Complaints & Suggestions </h2>
	</header>
<!-- INCLUDEING PAGE -->
<div class="row">
<div class="col-md-12">';
require_once("complaints_suggestions/query_complaint.php");
include_once("include/modals\complaint_suggestion\add.php");
	echo'
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
						'select'       => 'title, detail, status',
						'where'        => array( 
													 'is_deleted' => '0'
													,'status' => '1'
													,'id_complaint_by' => $_SESSION['userlogininfo']['LOGINIDA']
												)
						// ,'where_in'     => array( 
						// 							'e.id_campus' => array(0, $_SESSION['userlogininfo']['LOGINCAMPUS']) 
						// 						)
						,'return_type'  => 'all'
								);  
	$complaints = $dblms->getRows(COMPLAINTS, $condition);
echo '
<section class="panel panel-featured panel-featured-primary">
<header class="panel-heading">
	<a href="#make_complaint" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Complain / Suggestion</a>
	<h2 class="panel-title"><i class="fa fa-list"></i>  Complaints & Suggestions</h2>
</header>
<div class="panel-body">
<table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
<thead>
	<tr>
		<th style="text-align:center;">#</th>
		<th>Title </th>
		<th>Detail</th>
		<th>Satus</th>
	</tr>
</thead>
<tbody>';
$srno=1;
foreach ($complaints as $key => $complaint) {
echo '
	<tr>
		<td style="text-align:center;">'.$srno.'</td>
		<td>'.$complaint['title'].'</td>
		<td>'.$complaint['detail'].'</td>
		<td>'.get_complaint($complaint['status']).'</td>
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