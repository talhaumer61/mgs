<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_type'])){

    // On the basis of type get the Employees
    if($_POST['id_type'] == 1){
        $sql2 = "AND is_ad = '1'";
    }elseif($_POST['id_type'] == 2){
        $sql2 = "AND is_de = '1'";
    }else{
        $sql2 = "";
    }

    echo'<option value="">Select</option>';
    $sqllmsEmplys	= $dblms->querylms("SELECT emply_id, emply_name 
                                        FROM ".EMPLOYEES."
                                        WHERE id_campus     = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                        AND emply_status    = '1'
                                        AND is_deleted      = '0'
                                        $sql2
                                        ORDER BY emply_name ASC");
    if(mysqli_num_rows($sqllmsEmplys)>0){
        while($valueEmply = mysqli_fetch_array($sqllmsEmplys)){
            echo '<option value="'.$valueEmply['emply_id'].'">'.$valueEmply['emply_name'].'</option>';
        }
    }else{
        echo'<option value="">No Information Found</option>';
    }
}
?>