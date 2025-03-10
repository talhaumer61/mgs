<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_type']) && $_POST['id_type'] == '2'){
    echo'    
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>
    <label class="control-label">Parent Campus <span class="required">*</span></label>
    <select class="form-control" data-plugin-selectTwo data-width="100%" id="parent_campus" name="parent_campus" required>';
        $sqlParentCampus = $dblms->querylms("SELECT campus_id, campus_name, campus_code
                                                FROM ".CAMPUS."
                                                WHERE id_type       = '1'
                                                AND campus_status   = '1'
                                                AND is_deleted      = '0'
                                                AND parent_campus   = '0'
                                                ORDER BY campus_id ASC");
        if(mysqli_num_rows($sqlParentCampus) > 0){
            echo'<option value="">Select</option>';
            while($valParentCampus = mysqli_fetch_array($sqlParentCampus)) {
                echo '<option value="'.$valParentCampus['campus_id'].'">'.$valParentCampus['campus_name'].' - '.$valParentCampus['campus_code'].'</option>';
            }
        }else{
            echo '<option value="">No Record Found</option>';
        }
        echo'
    </select>';
}

elseif(isset($_POST['campus_id']) && isset($_POST['id_type_edit']) && $_POST['id_type_edit'] == '2'){
    echo'    
	<script src="assets/javascripts/user_config/forms_validation.js"></script>
	<script src="assets/javascripts/theme.init.js"></script>    
    <div class="form-group">
        <label class="col-sm-3 control-label">Parent Campus <span class="required">*</span></label>
        <div class="col-md-8">
            <select class="form-control" data-plugin-selectTwo data-width="100%" id="parent_campus" name="parent_campus" required>';
                $sqlParentCampus = $dblms->querylms("SELECT campus_id, campus_name, campus_code
                                                        FROM ".CAMPUS."
                                                        WHERE id_type       = '1'
                                                        AND campus_status   = '1'
                                                        AND is_deleted      = '0'
                                                        AND parent_campus   = '0'
                                                        AND campus_id      != '".cleanvars($_POST['campus_id'])."'
                                                        ORDER BY campus_id ASC");
                if(mysqli_num_rows($sqlParentCampus) > 0){
                    echo'<option value="">Select</option>';
                    while($valParentCampus = mysqli_fetch_array($sqlParentCampus)) {
                        echo '<option value="'.$valParentCampus['campus_id'].'">'.$valParentCampus['campus_name'].' - '.$valParentCampus['campus_code'].'</option>';
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