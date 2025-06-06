<?php 
$sqllms	= $dblms->querylms("SELECT a.adm_id, a.adm_status, a.adm_username, a.adm_fullname,
								a.adm_email, a.adm_phone, a.adm_photo
								FROM ".ADMINS." a  
								WHERE a.adm_id = '".$_SESSION['userlogininfo']['LOGINIDA']."'
								LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);
if($rowsvalues['adm_photo']){
	$photo = 'uploads/images/admins/'.$rowsvalues['adm_photo'];
}
else{
	$photo = 'uploads/default-student.jpg';
}	
echo '
<div id="edit" class="tab-pane active">
<form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<fieldset class="mt-lg">
		<div class="form-group">
			<input type="hidden" name="adm_id" value="'.$rowsvalues['adm_id'].'">
			<label class="col-sm-3 control-label">Photo</label>
			<div class="col-md-8">
				<div class="image_area">
					<label for="upload_image">
						<img src="'.$photo.'" id="uploaded_image" class="img-responsive thumbnail mb-none" width="200"/>
						<div class="overlay">
							<div class="text-overlay"><h6>Click to Change Profile Image</h6></div>
						</div>
						<input type="file" name="image" class="image" id="upload_image" accept="image/*" oninput="getCropped()" style="display:none;"/>
						<input type="hidden" name="adm_photo" id="adm_photo">
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Name <span class="required">*</span></label>
			<div class="col-md-8">
				<input type="text" class="form-control" required name="adm_fullname" value="'.$rowsvalues['adm_fullname'].'">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Username <span class="required">*</span></label>
			<div class="col-md-8">
				<input type="text" class="form-control" name="adm_username" value="'.$rowsvalues['adm_username'].'" readonly />
			</div>
		</div>	
		<div class="form-group">
			<label class="col-sm-3 control-label">Phone <span class="required">*</span></label>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-phone"></i></span>
					<input type="text" class="form-control" name="adm_phone" required value="'.$rowsvalues['adm_phone'].'" />
				</div>
			</div>
		</div>
		<div class="form-group mb-md">
			<label class="col-sm-3 control-label">Email <span class="required">*</span></label>
			<div class="col-md-8">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					<input type="email" class="form-control" name="adm_email" id="adm_email" value="'.$rowsvalues['adm_email'].'"/>
				</div>
			</div>
		</div>
	</fieldset>

	<div class="panel-footer">
		<div class="row">
			<div class="col-sm-offset-3 col-sm-5">
				<button type="submit" class="btn btn-primary" name="changes_profile">Update Profile</button>
			</div>
		</div>
	</div>
</form>
</div>';
?>