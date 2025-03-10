<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";
$sqllms = array ( 
                    'select' 	    => '
                                          '.STUDENTS.'.std_id 
                                        , '.STUDENTS.'.std_status 
                                        , '.STUDENTS.'.std_name 
                                        , '.STUDENTS.'.std_fathername 
                                        , '.STUDENTS.'.id_class 
                                        , '.STUDENTS.'.std_rollno 
                                        , '.STUDENTS.'.std_photo 
                                        , '.CLASSES.'.class_name 
                                    ',
                    'join' 	        => 'INNER JOIN '.CLASSES.' ON '.CLASSES.'.class_id = '.STUDENTS.'.id_class ',
                    'where' 	    => array( 
                                                STUDENTS.'.is_deleted'    => '0'
                                            ),
                    'search_by'     => 'AND '.STUDENTS.'.id_campus IN ('.$_POST['id_campus'].')',
                    'order_by' 	    => STUDENTS.'.std_id DESC LIMIT 10',
                    'return_type' 	=> 'all',
                ); 
if(!empty($_POST['id_class'])):
    $sqllms['where'][STUDENTS.'.id_class'] = cleanvars($_POST['id_class']);
endif;
if(!empty($_POST['id_section'])):
    $sqllms['where'][STUDENTS.'.id_section'] = cleanvars($_POST['id_section']);
endif;
if(!empty($_POST['id_group'])):
    $sqllms['where'][STUDENTS.'.id_group'] = cleanvars($_POST['id_group']);
endif;
if(!empty($_POST['id_class']) || !empty($_POST['id_section']) || !empty($_POST['id_group'])) { 
    $ltsAdmissions  = $dblms->getRows(STUDENTS, $sqllms);
} else {
    $ltsAdmissions  = $dblms->getRows(STUDENTS, $sqllms);
}    
if ($ltsAdmissions) {
    echo '
    <thead>
        '.((!empty($_POST['id_class']))? '
        <tr>
            <td colspan="7" class="center">Class Name: <b>'.$ltsAdmissions[0]['class_name'].'</b></td>
        </tr>
        ': '').'
        <tr>
            <th class="center">#</th>
            <th class="center" width="40">Photo</th>
            <th>Student</th>
            <th class="center">Roll No</th>
            <th class="center">Class</th>
            <th width="70px;" class="center">Status</th>
            <th width="70px;" class="center">Options</th>
        </tr>
    </thead>
    <tbody>';
        $srno = 0;
        foreach($ltsAdmissions as $key => $val):
            $srno++;
            echo '
            <tr>
                <td class="center">'.$srno.'</td>
                <td class="center"><img src="'.((file_exists('uploads/images/students/'.$val['std_photo']))? 'uploads/images/students/'.$val['std_photo']: 'uploads/default-student.jpg').'" style="width:40px; height:40px;"></td>
                <td><p class="mb-none">'.$val['std_name'].'</p><span>('.$val['std_fathername'].')</span> </td>
                <td class="center">'.$val['std_rollno'].'</td>
                <td class="center">'.$val['class_name'].'</td>
                <td class="center">'.get_stdstatus($val['std_status']).'</td>
                <td class="center">
                '.(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'edit' => '1'))? '
                    <a class="btn btn-success btn-xs ml-xs" href="students.php?id='.$val['std_id'].'"> <i class="fa fa-user-circle-o"></i></a>
                ': '').'
                </td>
            </tr>';
        endforeach;
        echo '
    </tbody>';
} else {
    echo '
    <thead>
        <tr>
            <th class="text-danger center">No Record Found</th>
        </tr>
    </thead>';
}
?>