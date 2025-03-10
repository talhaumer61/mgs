<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_campus']) && !isset($_POST['flag'])) {
    $id_campus = (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']); 
    echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label">Designation <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_designation" name="id_designation">';
                $sqlDesignation	= $dblms->querylms("SELECT designation_id, designation_name 
                                                        FROM ".DESIGNATIONS."
                                                        WHERE id_campus         = '".cleanvars($id_campus)."' 
                                                        AND is_deleted          = '0'
                                                        AND designation_status  = '1'
                                                        ORDER BY designation_name ASC");
                if(mysqli_num_rows($sqlDesignation) > 0){
                    echo'<option value="">Select</option>';
                    while($valDesignation = mysqli_fetch_array($sqlDesignation)) {
                        echo'<option value="'.$valDesignation['designation_id'].'">'.$valDesignation['designation_name'].'</option>';
                    }
                }else{
                    echo '<option value="">No Record Found</option>';
                }
                echo'
            </select>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label">Department <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_dept" name="id_dept">';
                $sqlDept	= $dblms->querylms("SELECT dept_id, dept_name 
                                                FROM ".DEPARTMENTS."
                                                WHERE id_campus = '".cleanvars($id_campus)."' 
                                                AND dept_status = '1'
                                                AND is_deleted  = '0'
                                                ORDER BY dept_name ASC");
                if(mysqli_num_rows($sqlDept) > 0){
                    echo'<option value="">Select</option>';
                    while($valDept = mysqli_fetch_array($sqlDept)) {
                        echo '<option value="'.$valDept['dept_id'].'">'.$valDept['dept_name'].'</option>';
                    }
                }else{
                    echo '<option value="">No Record Found</option>';
                }
                echo'
            </select>
        </div>
    </div>';
}
elseif(isset($_POST['id_campus']) && isset($_POST['flag'])){
    $id_campus = (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']); 
    echo'
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
    <div class="form-group">
        <label class="col-md-3 control-label">Designation <span class="required">*</span></label>
        <div class="col-md-8">
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_designation" name="id_designation">';
                $sqlDesignation	= $dblms->querylms("SELECT designation_id, designation_name 
                                                        FROM ".DESIGNATIONS."
                                                        WHERE id_campus         = '".cleanvars($id_campus)."' 
                                                        AND is_deleted          = '0'
                                                        AND designation_status  = '1'
                                                        ORDER BY designation_name ASC");
                if(mysqli_num_rows($sqlDesignation) > 0){
                    echo'<option value="">Select</option>';
                    while($valDesignation = mysqli_fetch_array($sqlDesignation)) {
                        echo'<option value="'.$valDesignation['designation_id'].'">'.$valDesignation['designation_name'].'</option>';
                    }
                }else{
                    echo '<option value="">No Record Found</option>';
                }
                echo '
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3 control-label">Department <span class="required">*</span></label>
        <div class="col-md-8">
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_dept" name="id_dept">';
                $sqlDept = $dblms->querylms("SELECT dept_id, dept_name 
                                                FROM ".DEPARTMENTS."
                                                WHERE id_campus = '".cleanvars($id_campus)."' 
                                                AND dept_status = '1'
                                                AND is_deleted  = '0'
                                                ORDER BY dept_name ASC");
                if(mysqli_num_rows($sqlDept) > 0){
                    echo'<option value="">Select</option>';
                    while($valDept = mysqli_fetch_array($sqlDept)) {
                        echo '<option value="'.$valDept['dept_id'].'">'.$valDept['dept_name'].'</option>';
                    }
                }else{
                    echo '<option value="">No Record Found</option>';
                }
                echo'
            </select>
        </div>
    </div>';
}
?>