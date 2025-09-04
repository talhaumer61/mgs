<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

$id_campus = (isset($_POST['id_campus']) && !empty($_POST['id_campus'])) 
    ? $_POST['id_campus'] 
    : $_SESSION['userlogininfo']['LOGINCAMPUS'];

$response = ["nic" => "ok", "roll" => "ok"];

if (!empty($_POST['std_nic']) && $_POST['std_nic'] != '') {
    $std_nic = cleanvars($_POST['std_nic']);
    $checkNic = $dblms->querylms("
        SELECT std_id FROM ".STUDENTS." 
        WHERE id_campus = '".cleanvars($id_campus)."' 
        AND is_deleted = '0'
        AND std_nic = '".$std_nic."' 
        LIMIT 1
    ");
    if (mysqli_num_rows($checkNic)) {
        $response["nic"] = "exists";
    }
}

if (!empty($_POST['std_rollno']) && $_POST['std_rollno'] != '' ) {
    $std_rollno = cleanvars($_POST['std_rollno']);
    $checkRoll = $dblms->querylms("
        SELECT std_id FROM ".STUDENTS." 
        WHERE id_campus = '".cleanvars($id_campus)."' 
        AND is_deleted = '0'
        AND std_rollno = '".$std_rollno."' 
        LIMIT 1
    ");
    if (mysqli_num_rows($checkRoll)) {
        $response["roll"] = "exists";
    }
}

echo json_encode($response);