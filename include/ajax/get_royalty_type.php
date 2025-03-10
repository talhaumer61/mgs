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
if(isset($_POST['royalty_type'])) {
	$royalty_type = $_POST['royalty_type']; 

    if($royalty_type == 1){
        echo'        
        <div class="form-group mt-sm">
            <label class="col-md-2 control-label"> For <span class="required">*</span></label>
            <div class="col-md-10">
                <select data-plugin-selectTwo data-width="100%" name="part_for" id="part_for" required title="Must Be Required" class="form-control populate">
                    <option value="">Select</option>';
                    foreach($rolyaltyFor as $for){
                        echo'<option  value="'.$for['id'].'">'.$for['name'].'</option>';
                    }
                    echo'
                </select>	
            </div>
        </div>
        <div id="get_amount_type"></div>';
    }else{}
}