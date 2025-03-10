<?php 
echo '
<!-- Add Modal Box -->
<div id="make_vlecture" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel panel-featured panel-featured-primary">
		<form action="" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<input type="hidden" id="id_subject" name="id_subject" value="'.$_GET['id'].'">
			<input type="hidden" id="id_section" name="id_section" value="'.$_GET['section'].'">
			<input type="hidden" id="id_class" name="id_class" value="'.$_GET['class'].'">	
			<input type="hidden" id="id_session" name="id_session" value="'.$_SESSION['userlogininfo']['ACADEMICSESSION'].'">	
			<header class="panel-heading">
				<h2 class="panel-title"><i class="fa fa-plus-square"></i>  Make Video Lecture</h2>
			</header>
			<div class="panel-body">
				
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Title <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="title" name="title" required title="Must Be Required">
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Youtube Code <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="youtube_code" name="youtube_code" required title="Must Be Required">
					</div>
				</div>
				<div class="form-group mb-md">
					<label class="col-md-3 control-label">Facebook Code <span class="required">*</span></label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="facebook_code" name="facebook_code" required title="Must Be Required">
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
							<input type="radio" id="status" name="status" value="1" checked>
							<label for="radioExample1">Active</label>
						</div>
						<div class="radio-custom radio-inline">
							<input type="radio" id="status" name="status" value="2">
							<label for="radioExample2">Inactive</label>
						</div>
					</div>
				</div>

			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button type="submit" class="btn btn-primary" id="submit_video_lecture" name="submit_video_lecture">Save</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</form>
	</section>
</div>';
?>