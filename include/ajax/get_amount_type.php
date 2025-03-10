<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

echo' 
<!-- THEME CUSTOM -->
<script src="assets/javascripts/theme.custom.js"></script>

<!-- THEME INITIALIZATION FILES -->
<script src="assets/javascripts/theme.init.js"></script>';
if(isset($_POST['part_for'])) {
	$part_for = $_POST['part_for']; 

    if($part_for == 1){
        echo'
        <div class="form-group mt-sm">
            <label class="col-md-2 control-label"> Amount <span class="required">*</span></label>
            <div class="col-md-10">
                <select data-plugin-selectTwo data-width="100%" name="part_amount_type" id="part_amount_type" required title="Must Be Required" class="form-control populate">
                    <option value="">Select</option>';
                    foreach($rolyaltyAmount as $amount){
                        echo'<option  value="'.$amount['id'].'">'.$amount['name'].'</option>';
                    }
                    echo'
                </select>	
            </div>
        </div>';
    }else{}
}