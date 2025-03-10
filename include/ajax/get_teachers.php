<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_campus'])){
    if(isset($_POST['id_campus']) && !empty($_POST['id_campus'])){
        $id_campus = $_POST['id_campus'];
    }else{
        $id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
    }

    $sqllms	= $dblms->querylms("SELECT emply_id, emply_name
                                FROM ".EMPLOYEES." 
                                WHERE emply_status = '1' AND is_deleted != '1' AND id_type = '1'
                                AND id_campus = '".$id_campus."'
                                ORDER BY emply_name ASC");
    if(mysqli_num_rows($sqllms) > 0){
        echo'<option value="">Select</option>';
        while($rowsvalues = mysqli_fetch_array($sqllms)){
            echo'<option value="'.$rowsvalues['emply_id'].'|'.$rowsvalues['emply_name'].'"'; if($teacher_id == $rowsvalues['emply_id']){echo'selected';} echo'>'.$rowsvalues['emply_name'].'</option>';
        }
    }else{
        echo '<option value="">No Record Found</option>';
    }
}
?>