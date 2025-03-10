<?php 
//error_reporting(0);
//session_start();
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/login_func.php";
	include "../functions/functions.php";
if(isset($_POST['id_level'])) {
	$id_level = $_POST['id_level']; 
    $sqllmsLevel	= $dblms->querylms("SELECT level_classes
                                        FROM ".CAMPUS_LEVELS."
                                        WHERE level_status = '1'
                                        AND is_deleted = '0'
                                        AND level_id = '".$id_level."'
                                        ORDER BY level_id ASC");
    $valLevel 		= mysqli_fetch_array($sqllmsLevel);
    $level_classes	= $valLevel['level_classes'];
    $sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
                        FROM ".CLASSES."
                        WHERE class_status  = '1' 
                        AND is_deleted      = '0'
                        AND FIND_IN_SET(class_id,'.$level_classes.')
                        ORDER BY class_id ASC");
    if(mysqli_num_rows($sqllmscls) > 0){
        echo'<option value="">Select</option>';
        while($valuecls = mysqli_fetch_array($sqllmscls)) {
            echo '<option value="'.$valuecls['class_id'].'" '.($valuecls['class_id']==$id_class ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
        }
    }else{
        echo '<option value="">No Record Found</option>';
    }

}
?>