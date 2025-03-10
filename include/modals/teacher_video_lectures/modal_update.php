<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
//----------------------------------------------------- 
$sqllms	= $dblms->querylms("SELECT  id, status, thumbnail, title, facebook_code, youtube_code, id_class, id_subject, id_session
								   FROM ".VIDEO_LECTURE." 
								   WHERE id = '".cleanvars($_GET['edit_id'])."' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
//-----------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
<div class="col-md-12">
<section class="panel panel-featured panel-featured-primary">
	<form action="" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="video_id" id="video_id" value="'.cleanvars($_GET['edit_id']).'">
		<input type="hidden" id="id_subject" name="id_subject" value="'.$_GET['id'].'">
		<header class="panel-heading">
			<h2 class="panel-title"><i class="glyphicon glyphicon-edit"></i> Edit Announcement</h2>
		</header>
		<div class="panel-body">
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Title <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="title" name="title" value="'.$rowsvalues['title'].'" required title="Must Be Required">
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Youtube Code <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="youtube_code" name="youtube_code" value="'.$rowsvalues['youtube_code'].'" required title="Must Be Required">
				</div>
			</div>
			<div class="form-group mb-md">
				<label class="col-md-3 control-label">Facebook Code <span class="required">*</span></label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="facebook_code" name="facebook_code" value="'.$rowsvalues['facebook_code'].'" required title="Must Be Required">
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Thumbnail</label>
				<div class="col-md-9">
					<input type="file" class="form-control" accept="image/*" name="thumbnail" id="thumbnail" title="Must Be Required"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Status <span class="required">*</span></label>
				<div class="col-md-9">
					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" value="1"'; if($rowsvalues['status'] == 1) {echo ' checked';} echo '>
						<label for="radioExample1">Active</label>
					</div>
					<div class="radio-custom radio-inline">
						<input type="radio" id="status" name="status" value="2"'; if($rowsvalues['status'] == 2) {echo ' checked';} echo '>
						<label for="radioExample2">Inactive</label>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-primary" id="changes_video_lecture" name="changes_video_lecture">Update</button>
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</form>
</section>
</div>
</div>';
?>