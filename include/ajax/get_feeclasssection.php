<?php 
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if (isset($_POST['id_class']) && empty($_POST['id_campus'])) { 
    $aray		    = explode('|', $_POST['id_class']);
    $id_class	    = $aray[0];
    $id_campus      = cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']); 
}

if(isset($_POST['id_class']) && !empty($_POST['id_campus'])) { 
    $aray		    = explode('|', $_POST['id_class']);
    $id_class	    = $aray[0];
    $id_campus      = cleanvars($_POST['id_campus']); 
}
$sqllms	= $dblms->querylms("SELECT section_id, section_name
                            FROM ".CLASS_SECTIONS."
                            WHERE id_campus     = '".$id_campus."'
                            AND id_class        = '".$id_class."'
                            AND section_status  = '1'
                            AND is_deleted      = '0'
                            ORDER BY section_name ASC");
if(mysqli_num_rows($sqllms) > 0){
    echo '<option value="">Select</option>';
    while($rowsvalues = mysqli_fetch_array($sqllms)){
        echo'<option value="'.$rowsvalues['section_id'].'|'.$rowsvalues['section_name'].'">'.$rowsvalues['section_name'].'</option>';
    }
}else{
    echo '<option value="">No Record Found</option>';
}
?>