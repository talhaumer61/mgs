<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(!isset($_POST['method']) && isset($_POST['id_class'])){
    $id_campus 		= ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])))? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);

    $condition = array(
                             'select'       =>  'cs.section_id, cs.section_name' 
                            ,'where'        =>  array(
                                                         'cs.is_deleted' 	=> 0
                                                        ,'cs.id_class'  	=> cleanvars($_POST['id_class'])
                                            )
                            ,'search_by'	=>	' AND cs.id_campus IN ('.$id_campus.')'
                            ,'return_type'  =>  'all'
    );
	$CLASS_SECTIONS = $dblms->getRows(CLASS_SECTIONS.' cs', $condition);
    if ($CLASS_SECTIONS) {
        echo'<option value="">Select</option>';
        foreach ($CLASS_SECTIONS AS $key => $val) {
            echo '<option value="'.$val['section_id'].'">'.$val['section_name'].'</option>';
        }
    } else {
        echo '<option value="">Please Add Section First</option>';
    }
}
if($_POST['method'] == 'attendance_marked_sections' && isset($_POST['id_class'])){
    $id_campus 		= ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])))? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);

    $condition = array(
                             'select'       =>  'cs.section_id, cs.section_name'
                            ,'join'			=>	'INNER JOIN '.CLASS_SECTIONS.' cs ON cs.section_id = et.id_section' 
                            ,'where'        =>  array(
                                                         'et.is_deleted' 	=> 0
														,'et.is_publish'	=> 1
                                                        ,'et.id_session'  	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                        ,'et.id_class'  	=> cleanvars($_POST['id_class'])
                                            )
                            ,'search_by'	=>	' AND et.id_campus IN ('.$id_campus.')'
                            ,'order_by'  	=>  'et.id DESC'
                            ,'group_by'  	=>  'cs.section_id DESC'
                            ,'return_type'  =>  'all'
    );
	$EXAM_ATTENDANCE = $dblms->getRows(EXAM_ATTENDANCE.' et', $condition);
    if ($EXAM_ATTENDANCE) {
        echo'<option value="">Select</option>';
        foreach ($EXAM_ATTENDANCE AS $key => $val) {
            echo '<option value="'.$val['section_id'].'" '.($val['section_id']==$section ? 'selected' : '').'>'.$val['section_name'].'</option>';
        }
    } else {
        echo '<option value="">No Record Found</option>';
    }
}
?>
