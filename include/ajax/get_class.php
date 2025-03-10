<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_campus']) && !isset($_POST['campus_flag']) && !isset($_POST['id_exam'])){
    if(isset($_POST['id_campus']) && !empty($_POST['id_campus'])){
        $id_campus = $_POST['id_campus'];
    }else{
        $id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
    }

    $sqlCampLevel = $dblms->querylms("SELECT GROUP_CONCAT(l.level_classes) campus_classes
                                        FROM ".CAMPUS." c
                                        LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
                                        WHERE campus_id IN (".$id_campus.") ");
    $valCampLevel = mysqli_fetch_array($sqlCampLevel);

    $sqllmscls	= $dblms->querylms("SELECT class_id, class_name
                                        FROM ".CLASSES."
                                        WHERE is_deleted != '1'
                                        AND class_id != '' AND class_status = '1'
                                        AND class_id IN (".$valCampLevel['campus_classes'].")
                                        ORDER BY class_id ASC");
    if(mysqli_num_rows($sqllmscls) > 0){
        echo'<option value="">Select</option>';
        while($valuecls = mysqli_fetch_array($sqllmscls)) {
            echo '<option value="'.$valuecls['class_id'].'|'.$id_campus.'">'.$valuecls['class_name'].'</option>';
        }
    }else{
        echo '<option value="">No Record Found</option>';
    }
}

elseif(isset($_POST['id_campus']) && isset($_POST['campus_flag']) && !isset($_POST['id_exam'])) {
    if(isset($_POST['id_campus']) && !empty($_POST['id_campus'])){
        $cps_array = explode('|', $_POST['id_campus']);
        $id_campus = cleanvars($cps_array[0]);
        $campus_name = cleanvars($cps_array[1]);
    }else{				
        $id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
        $campus_name = '';
    }
    $sqlclsFrom	= $dblms->querylms("SELECT sp.class_from, c.class_id, c.class_name 
                                    FROM ".STD_PROMOTE_LOG." sp
                                    INNER JOIN ".CLASSES." c ON c.class_id = sp.class_from
                                    WHERE c.class_status    = '1' 
                                    AND c.is_deleted        = '0'
                                    AND sp.id_campus IN (".$id_campus.")
                                    GROUP BY sp.class_from
                                    ORDER BY sp.class_from ASC");
    if(mysqli_num_rows($sqlclsFrom) > 0){
        echo'<option value="">Select</option>';
        while($valclsFrom = mysqli_fetch_array($sqlclsFrom)) {
            echo '<option value="'.$valclsFrom['class_id'].'" '.($valclsFrom['class_id'] == $class_from ? 'selected' : '').'>'.$valclsFrom['class_name'].'</option>';
        }
    } else {
        echo '<option value="">No Record Founddsada</option>';
    }
}

elseif(!isset($_POST['method']) && isset($_POST['id_exam']) && isset($_POST['id_campus'])){
    $id_campus = (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

    // CAMPUS CLASSES
	$condition = array(
						 'select'       =>  'GROUP_CONCAT(l.level_classes) campus_classes'
						,'join'			=>	'LEFT JOIN '.CAMPUS_LEVELS.' l ON l.level_id = c.id_level'
						,'where'        =>  array(
													 'c.is_deleted'	=> 0
                                                    ,'c.campus_id'  =>  cleanvars($id_campus)
												)
						,'return_type'  =>  'single'
	);
	$CAMPUS_CLASSES = $dblms->getRows(CAMPUS.' c', $condition);

    // CLASSES
	$condition = array(
						 'select'       =>  'DISTINCT c.class_id, c.class_status, c.class_name'
						,'join'			=>	'INNER JOIN '.DATESHEET.' d on d.id_class = c.class_id'
						,'where'        =>  array(
													 'c.class_status'	=> 1
													,'c.is_deleted'  	=> 0
                                                    ,'d.id_exam'        => cleanvars($_POST['id_exam'])
												)
						,'search_by'	=>	'AND (d.id_campus = '.$id_campus.' OR d.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR d.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')
                                             AND c.class_id IN ('.$CAMPUS_CLASSES['campus_classes'].')'
						,'return_type'  =>  'all'
	);
	$CLASSES = $dblms->getRows(CLASSES.' c', $condition);
    if($CLASSES){
        echo '<option value="">Select</option>';
        foreach ($CLASSES as $key => $value) {
            echo '<option value="'.$value['class_id'].'">'.$value['class_name'].'</option>';
        }
    }else{
        echo '<option value="">No Record Found</option>';
    }
}

elseif($_POST['method'] == 'attendance_marked_classes' && isset($_POST['id_exam']) && isset($_POST['id_campus'])){
    $id_campus = (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

    // CAMPUS CLASSES
	$condition = array(
						 'select'       =>  'GROUP_CONCAT(l.level_classes) campus_classes'
						,'join'			=>	'LEFT JOIN '.CAMPUS_LEVELS.' l ON l.level_id = c.id_level'
						,'where'        =>  array(
													 'c.is_deleted'	=> 0
                                                    ,'c.campus_id'  =>  cleanvars($id_campus)
												)
						,'return_type'  =>  'single'
	);
	$CAMPUS_CLASSES = $dblms->getRows(CAMPUS.' c', $condition);

    // CLASSES
	$condition = array(
						 'select'       =>  'DISTINCT c.class_id, c.class_status, c.class_name'
						,'join'			=>	'INNER JOIN '.DATESHEET.' d on d.id_class = c.class_id
                                                INNER JOIN '.EXAM_ATTENDANCE.' ea on ea.id_class = c.class_id AND ea.is_publish = 1 AND ea.id_exam = '.cleanvars($_POST['id_exam']).' AND (ea.id_campus = '.$id_campus.' OR ea.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR ea.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
						,'where'        =>  array(
													 'c.class_status'	=> 1
													,'c.is_deleted'  	=> 0
													,'d.id_session'		=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'d.id_exam'        => cleanvars($_POST['id_exam'])
												)
						,'search_by'	=>	'AND (d.id_campus = '.$id_campus.' OR d.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR d.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')
                                             AND c.class_id IN ('.$CAMPUS_CLASSES['campus_classes'].')'
						,'return_type'  =>  'all'
	);
	$CLASSES = $dblms->getRows(CLASSES.' c', $condition);
    if($CLASSES){
        echo '<option value="">Select</option>';
        foreach ($CLASSES as $key => $value) {
            echo '<option value="'.$value['class_id'].'">'.$value['class_name'].'</option>';
        }
    }else{
        echo '<option value="">No Record Found</option>';
    }
}
?>