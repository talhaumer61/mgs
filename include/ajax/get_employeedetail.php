<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_employee'])){
	$id_employee = $_POST['id_employee']; 
    $sqllmsemp	= $dblms->querylms("SELECT emply_id, emply_name, emply_phone, emply_email, emply_regno
                                    FROM ".EMPLOYEES."
                                    WHERE id_campus     = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                    AND emply_status    = '1'
                                    AND is_deleted      = '0'
                                    AND emply_id        = '".$id_employee."' LIMIT 1");
    if(mysqli_num_rows($sqllmsemp) == 1){
        $value_emp = mysqli_fetch_array($sqllmsemp);
        echo'
        <div class="form-group mt-sm">
            <label class="col-md-3 control-label"> Full Name <span class="required">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="adm_fullname" name="adm_fullname" value="'.$value_emp['emply_name'].'" readonly/>
            </div>
        </div>
        <div class="form-group mt-sm">
            <label class="col-md-3 control-label"> Email <span class="required">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="adm_email" name="adm_email" value="'.$value_emp['emply_email'].'" readonly/>
            </div>
        </div>
        <div class="form-group mt-sm">
            <label class="col-md-3 control-label"> Phone </label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="adm_phone" name="adm_phone" value="'.$value_emp['emply_phone'].'" readonly/>
            </div>
        </div>
        <div class="form-group mt-sm">
            <label class="col-md-3 control-label"> Username <span class="required">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="adm_username" name="adm_username" value="'.$value_emp['emply_regno'].'" readonly/>
            </div>
        </div>';
    }else{
        echo'
        <div class="form-group mt-sm">
            <label class="col-md-3 control-label"> Full Name <span class="required">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="adm_fullname" name="adm_fullname" required title="Must Be Required"/>
            </div>
        </div>
        <div class="form-group mt-sm">
            <label class="col-md-3 control-label"> Email <span class="required">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="adm_email" name="adm_email" required title="Must Be Required"/>
            </div>
        </div>
        <div class="form-group mt-sm">
            <label class="col-md-3 control-label"> Phone </label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="adm_phone" name="adm_phone"/>
            </div>
        </div>        
        <div class="form-group mt-sm">
            <label class="col-md-3 control-label"> Username <span class="required">*</span></label>
            <div class="col-md-9">
                <input type="text" class="form-control" id="adm_username" name="adm_username" />
            </div>
        </div>';
    }
}

elseif(isset($_POST['id_emply'])){
    $sqlEmpl	= $dblms->querylms("SELECT emply_id, emply_name, emply_phone, emply_email, emply_regno
                                    FROM ".EMPLOYEES."
                                    WHERE id_campus     = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                    AND emply_status    = '1'
                                    AND is_deleted      = '0'
                                    AND id_type         = '2'
                                    AND emply_id        = '".cleanvars($_POST['id_emply'])."' LIMIT 1");
    if(mysqli_num_rows($sqlEmpl) == 1){
        $valEmp = mysqli_fetch_array($sqlEmpl);
        echo'
        <div class="form-group">
            <div class="col-sm-6">
                <label class="control-label">Full Name <span class="required">*</span></label>
                <input type="text" class="form-control" name="adm_fullname" id="adm_fullname" value="'.$valEmp['emply_name'].'" required readonly title="Must Be Required">
            </div>
            <div class="col-sm-6">
                <label class="control-label">User Name <span class="required">*</span></label>
                <input type="text" class="form-control" name="adm_username" id="adm_username" value="'.$valEmp['emply_regno'].'" required readonly title="Must Be Required" >
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-6">
                <label class="control-label">Email <span class="required">*</span></label>
                <input type="text" class="form-control" name="adm_email" id="adm_email" value="'.$valEmp['emply_email'].'" required readonly title="Must Be Required">
            </div>
            <div class="col-sm-6">
                <label class="control-label">Phone <span class="required">*</span></label>
                <input type="text" class="form-control" name="adm_phone" id="adm_phone" value="'.$valEmp['emply_phone'].'" required readonly title="Must Be Required">
            </div>
        </div>';
    }
}
?>