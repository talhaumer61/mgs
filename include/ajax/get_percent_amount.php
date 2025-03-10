<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['concession_type'])){
    if($_POST['c_s'] == '1'){
        $label = 'Concession';
    }else{
        $label = 'Scholarship';
    }
    if($_POST['concession_type'] == '1'){
        echo'
        <label class="col-md-3 control-label">'.$label.' (Rs.) <span class="required">*</span></label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="amount" id="amount" required title="Must Be Required"/>
        </div>';
    }elseif($_POST['concession_type'] == '2'){
        echo'
        <label class="col-md-3 control-label">'.$label.' (%) <span class="required">*</span></label>
        <div class="col-md-9">
            <input type="text" class="form-control" placeholder="0-100" name="percent" id="percent" required title="Must Be Required" min="0" max="100"/>
        </div>';
    }
}
?>