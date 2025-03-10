<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['id_dept'])) {
	$id_dept    = $_POST['id_dept']; 
    $id_campus  = (!empty($_POST['id_campus']))?$_POST['id_campus']:$_SESSION['userlogininfo']['LOGINCAMPUS'];
    echo'
    <select class="form-control populate" data-plugin-selectTwo data-width="100%" id="id_employe" name="id_employe" required title="Must Be Required" onchange="get_employeedetail(this.value)">
        <option value="">Select</option>';
        $sqllmsdept	= $dblms->querylms("SELECT emply_id, emply_name 
                                        FROM ".EMPLOYEES."
                                        WHERE id_campus     = '".cleanvars($id_campus)."'
                                        AND id_dept         = '".cleanvars($id_dept)."'
                                        AND emply_status    = '1'
                                        AND is_deleted      = '0'
                                        ORDER BY emply_name ASC");
        while($valuedept = mysqli_fetch_array($sqllmsdept)) {
            echo'<option value="'.$valuedept['emply_id'].'">'.$valuedept['emply_name'].'</option>';
        }
        echo'
    </select>';
}
else if(isset($_POST['id_campus'])) {
    $id_campus	= (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
   
    $condition = array(
                         'select'		=>  'emply_id, emply_name'
                        ,'where'        =>  array(
                                                        'emply_status'	=>	1
                                                    ,'is_deleted'  	=>	0
                                                    ,'is_ad'  		=>	0
                                                    ,'is_de'  		=>	0
                                                    ,'id_type' 		=>	1
                                                    ,'id_campus'  	=>	cleanvars($id_campus)
                                                )
                        ,'return_type'  =>  'all'
    );
    $EMPLOYEES = $dblms->getRows(EMPLOYEES, $condition);

    if($EMPLOYEES){
        echo'<option value="">Select</option>';
        foreach ($EMPLOYEES as $key => $val) {
            echo'<option value="'.$val['emply_id'].'">'.$val['emply_name'].'</option>';
        }
    }else{
        echo'<option value="">No Record Found</option>';
    }
}
?>