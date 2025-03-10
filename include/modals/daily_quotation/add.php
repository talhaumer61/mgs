<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '86', 'add' => '1'))){ 
	echo'
	<div id="make_quotation" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel panel-featured panel-featured-primary">
			<form action="daily_quotation.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<header class="panel-heading">
					<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Quotation </h2>
				</header>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-3 control-label">Date <span class="required">*</span></label>
						<div class="col-md-9">
							<input type="text" required class="form-control valid" name="date" id="date" autocomplete="off" data-plugin-datepicker="" aria-invalid="false">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Type <span class="required">*</span></label>
						<div class="col-md-9">
							<select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="quote_type" name="quote_type">
								<option value="">Select</option>';
									foreach(get_Quotation() as $key => $val):
										echo '<option value="'.$key.'">'.$val.'</option>';
									endforeach;
									echo '
							</select>
						</div>
					</div>
					<div class="form-group mb-md">
						<label class="col-md-3 control-label">Quotation <span class="required">*</span></label>
						<div class="col-md-9">
							<textarea class="form-control" rows="6" required name="quote_msg" id="quote_msg"></textarea>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-primary" id="add_quote" name="add_quote">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			</form>
		</section>
	</div>';
}
?>