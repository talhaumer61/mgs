<?php
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/login_func.php";
	include "../functions/functions.php";
    if(isset($_POST['id_campus'])) {
        $id_campus  = (!empty($_POST['id_campus']))?$_POST['id_campus']:$_SESSION['userlogininfo']['LOGINCAMPUS'];
        $sqllmsdept	= $dblms->querylms("SELECT DISTINCT d.dept_id, d.dept_name 
                                        FROM ".DEPARTMENTS." AS d
                                        INNER JOIN ".EMPLOYEES." AS e ON (e.id_dept = d.dept_id AND e.id_type = 1)
                                        WHERE d.id_campus = '".cleanvars($id_campus)."' 
                                        ORDER BY d.dept_name ASC");
        while($valuedept = mysqli_fetch_array($sqllmsdept)) {
            echo '<option value="'.$valuedept['dept_id'].'">'.$valuedept['dept_name'].'</option>';
        }
    }
?>