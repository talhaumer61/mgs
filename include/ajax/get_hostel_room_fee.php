<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_room'])){    
    $array          = explode('|', cleanvars($_POST['id_room']));
    $id_room 		= cleanvars($array[0]);
    $room_size 		= cleanvars($array[1]);
    $room_fee 		= cleanvars($array[2]);
    echo'
    <div class="form-group">
        <label class="col-md-3 control-label">Fee <span class="required">*</span></label>
        <div class="col-md-9">
            <input type="number" class="form-control" id="fee" name="fee" value="'.$room_fee.'"/>
        </div>
    </div>';
}
?>