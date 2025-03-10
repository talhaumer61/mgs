<?php
require_once("profile/query_profile.php");
echo'
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
<script src="https://unpkg.com/cropperjs"></script>
<title> Control Profile | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">';
	if($view=='campus'){
		if($_SESSION['userlogininfo']['LOGINTYPE']  == 1){
			echo'
			<header class="page-header">
				<h2> Campus Profile</h2>
			</header>
			<div class="row appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">';
				include_once("campus_profile/detail.php");
				echo'
				<div class="col-md-8">
					<div class="tabs tabs-primary">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#edit" data-toggle="tab"><i class="fa fa-user"></i> <span class="hidden-xs"> Profile</span></a>
							</li>
							<li>
								<a href="#biography" data-toggle="tab"><i class="fa fa-link"></i> <span class="hidden-xs">Campus Biography</span></a>
							</li>
							<li>
								<a href="#utilities" data-toggle="tab"><i class="fa fa-link"></i> <span class="hidden-xs">Campus Utilities</span></a>
							</li>
							<li>
								<a href="#royalty" data-toggle="tab"><i class="fa fa-link"></i> <span class="hidden-xs">Campus Royalty</span></a>
							</li>
							<!-- <li>
								<a href="#resetpass" data-toggle="tab"><i class="fa fa-lock"></i> <span class="hidden-xs"> Change Password</span></a>
							</li> -->
						</ul>
						<div class="tab-content">';
							include_once("campus_profile/edit_profile.php");
							include_once("campus_profile/biography.php");
							include_once("campus_profile/utilities.php");
							include_once("campus_profile/royalty.php");
							echo'
						</div>
					</div>
				</div>
			</div>';
		}
	}else{
		echo'
		<header class="page-header">
			<h2> My Profile</h2>
		</header>
		<div class="row appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">';
			include_once("profile/detail.php");
			echo '
			<div class="col-md-8">
				<div class="tabs tabs-primary">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#edit" data-toggle="tab"><i class="fa fa-user"></i> <span class="hidden-xs">My Profile</span></a>
						</li>
						<li>
							<a href="#resetpass" data-toggle="tab"><i class="fa fa-lock"></i> <span class="hidden-xs">Change Password</span></a>
						</li>
					</ul>
					<div class="tab-content">';
						include_once("profile/edit_profile.php");
						include_once("profile/change_password.php");
						echo '
					</div>
				</div>
			</div>
		</div>';
	}
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
		<?php 
			if(isset($_SESSION['msg'])) { 
				echo 'new PNotify({
						title	: "'.$_SESSION['msg']['title'].'"	,
						text	: "'.$_SESSION['msg']['text'].'"	,
						type	: "'.$_SESSION['msg']['type'].'"	,
						hide	: true	,
						buttons: {
							closer	: true	,
							sticker	: false
						}
					});';
				unset($_SESSION['msg']);
			}
		?>	
		});

		// Crop Image
		function getCropped(){
			var $modal = $('#modal');
			var image = document.getElementById('sample_image');
			var cropper;

			$('#upload_image').change(function(event){
				var files = event.target.files;

				var done = function(url){
					image.src = url;
					$('#crop').attr('disabled',false);
					$('#crop').html('Crop');
					$modal.modal('show');
				};
				if(files && files.length > 0)
				{
					reader = new FileReader();
					reader.onload = function(event)
					{
						done(reader.result);
					};
					reader.readAsDataURL(files[0]);
				}
			});

			$modal.on('shown.bs.modal', function() {
				cropper = new Cropper(image, {
					aspectRatio: 1,
					viewMode: 3,
					preview:'.preview'
				});
			}).on('hidden.bs.modal', function(){
				cropper.destroy();
				cropper = null;
			});

			$('#crop').click(function(){
				$('#crop').attr('disabled','disabled');
				$('#crop').html('<i class="fa fa-circle-o-notch fa-spin"></i> Wait...');
				canvas = cropper.getCroppedCanvas({
					width:400,
					height:400
				});

				canvas.toBlob(function(blob){
					url = URL.createObjectURL(blob);
					var reader = new FileReader();
					reader.readAsDataURL(blob);
					reader.onloadend = function(){
						var base64data = reader.result;
						console.log(base64data);
						$modal.modal('hide');

						$('#uploaded_image').attr('src', base64data);
						$('#adm_photo').val(base64data);
					};
				});
			});
		};
	</script>
	<?php 
	echo'
</section>
	
<!-- Image Crop Model Start -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg panel panel-featured panel-featured-primary" role="document">
		<div class="panel-heading">
			<h4 class="panel-title"><i class="fa fa-crop"></i> Crop Image Before Upload
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</h4>
		</div>
		<div class="panel-body">
			<div class="img-container">
				<div class="row">
					<div class="col-md-8">
						<img src="" id="sample_image" width="100%"/>
					</div>
					<div class="col-md-4">
						<div class="preview"></div>
					</div>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button type="button" id="crop" class="btn btn-primary">Crop</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</footer>
	</div>
</div>';
?>