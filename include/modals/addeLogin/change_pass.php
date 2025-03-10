<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
//---------------------------------------------------------
//---------------------------------------------------------
$sqllms	= $dblms->querylms("SELECT adm_id, adm_status, adm_username, adm_fullname, adm_email, adm_phone, adm_photo 
FROM ".ADMINS." 
WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
AND adm_id = '".cleanvars($_GET['id'])."' LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);
//---------------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>

<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-primary">
            <form action="addeLogin.php" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">
                <input type="hidden" name="adm_id" id="adm_id" value="'.cleanvars($_GET['id']).'">
                <header class="panel-heading">
                    <h2 class="panel-title"><i class="glyphicon glyphicon-lock"></i> Update Passsword</h2>
                </header>
                <div class="panel-body">
                    <div class="form-group mt-sm">
                        <label class="col-md-3 control-label">Full Name <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="adm_type" id="adm_type" required title="Must Be Required" value="'.cleanvars($rowsvalues['adm_fullname']).'" readonly/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Username <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="adm_username" id="adm_username" value="'.cleanvars($rowsvalues['adm_username']).'" required title="Must Be Required" readonly/>
                        </div>
                    </div>
                    <div class="form-group mb-sm">
                        <label class="col-md-3 control-label">Password <span class="required">*</span></label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="adm_userpass" id="adm_userpass" required title="Must Be Required" />
                        </div>
                    </div>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary" id="update_pass" name="update_pass">Update</button>
                            <button class="btn btn-default modal-dismiss">Cancel</button>
                        </div>
                    </div>
                </footer>
            </form>
        </section>
    </div>
</div>';
//---------------------------------------------------------
?>