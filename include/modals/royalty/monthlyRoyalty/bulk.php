<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'edit' => '1'))){ 
echo '
<!-- Add Modal Box -->
<div id="make_bulk_challans" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="royalty.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" autocomplete="off" accept-charset="utf-8">
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Bulk Royalty Challan</h2>
			</header>
			<div class="panel-body">		
				<div class="form-group">
				    <div class="col-md-4">
						<label class="control-label">Month <span class="required">*</span></label>
						<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_month" name="id_month">
							<option value="">Select</option>';
							foreach($monthtypes as $month) {
                                if($month['id'] == $month_id){
                                    echo '<option value="'.$month['id'].'" selected >'.$month['name'].'</option>';
                                }
                                else{
                                    echo '<option value="'.$month['id'].'" >'.$month['name'].'</option>';
                                }
							}
							echo '
						</select>
					</div>
					<div class="col-md-4">
						<label class="control-label">Issue Date <span class="required">*</span></label>
						<input type="text" class="form-control" name="issue_date" id="issue_date" data-plugin-datepicker required title="Must Be Required"/>
					</div>
					<div class="col-md-4">
						<label class="control-label">Due Date <span class="required">*</span></label>
						<input type="text" class="form-control" name="due_date" id="due_date" data-plugin-datepicker required title="Must Be Required"/>
					</div>	
				</div>

				<div class="form-group">
					<div class="col-md-12">
						<label class="control-label">Note</label>
						<textarea class="form-control" rows="2" name="roy_detail" id="roy_detail"></textarea>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="bulk_challans_generate" name="bulk_challans_generate">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
}
?>