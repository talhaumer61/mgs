<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if (isset($_POST['id_class']) && isset($_POST['id_section']) && empty($_POST['id_campus'])) { 
    $array          =   explode('|',$_POST['id_class']);
    $id_class       =   $array[0];
    $id_campus      =   $_SESSION['userlogininfo']['LOGINCAMPUS']; 
    $id_section     =   $_POST['id_section']; 
}

if(isset($_POST['id_class']) && isset($_POST['id_section'])  && !empty($_POST['id_campus'])) { 
    $array          =   explode('|',$_POST['id_class']);
    $id_class       = $array[0];
    $id_section     = $_POST['id_section'];
    $id_campus      = $_POST['id_campus']; 
}

if(!empty($_POST['is_hostel'])) { 
    $sqlHostel = "AND is_hostel   = '".$_POST['is_hostel']."'";
}else{
    
    $sqlHostel = "";
}

$sqlStudent = $dblms->querylms("SELECT std_id, std_name, std_fathername, id_class, id_section, is_hostel
                                    FROM ".STUDENTS."
                                    WHERE id_campus = '".$id_campus."'
                                    AND std_status  = '1'
                                    AND id_class    = '".$id_class."'
                                    AND id_section  = '".$id_section."'
                                    $sqlHostel
                                    AND is_deleted  = '0'
                                    ORDER BY std_name ASC");

if(mysqli_num_rows($sqlStudent) > 0){
    echo '<option value="">Select</option>';
    while($valStudent = mysqli_fetch_array($sqlStudent)){
        echo '<option value="'.$valStudent['std_id'].'|'.$valStudent['id_class'].'|'.$valStudent['id_section'].'|'.$id_campus.'|'.$valStudent['is_hostel'].'">'.$valStudent['std_name'].' ('.$valStudent['std_fathername'].')</option>';
    }
}else{
    echo '<option value="">No Record Found</option>';
}
?>