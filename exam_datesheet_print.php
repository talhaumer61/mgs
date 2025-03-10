<?php
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();
$condition = array(
                         'select'       =>  'd.id, d.status, d.id_exam, d.id_session, d.id_class, d.id_section, d.id_campus, e.type_name, c.class_name'
                        ,'join'       	=>  'INNER JOIN '.EXAM_TYPES.' AS e	ON e.type_id = d.id_exam
                                             INNER JOIN '.CLASSES.' AS c ON c.class_id = d.id_class'
                        ,'where'        =>  array(
                                                     'd.is_deleted' 	=> 0
                                                    ,'d.id' 			=> cleanvars($_GET['routine'])
                                                )
                        ,'search_by'	=>	' AND (d.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR d.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
						,'order_by'  	=>  'd.id ASC'
                        ,'return_type'  =>  'single'
);
$DATESHEET 	= $dblms->getRows(DATESHEET.' AS d', $condition);
if($DATESHEET){
    echo'
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DateSheet Print</title>
        <link rel="shortcut icon" href="assets/images/favicon.png">
        <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="assets/stylesheets/theme.css" />
        <link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
        <script src="assets/vendor/jquery/jquery.js"></script>
    </head>
    <body>
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title"><i class="fa fa-clock-o"></i>'.$DATESHEET['type_name'].' Datesheet of <b>'.$DATESHEET['class_name'].'</b></h2>
            </header>		
            <div class="panel-body">
                <div class="table-responsive mt-sm mb-md">
                    <table class="table table-bordered table-striped table-condensed  mb-none">
                    <tbody>						
                        <tr>
                            <th class="center" width="50">Sr No. </th>
                            <th class="center" width="50">Code </th>
                            <th>Subject Name </th>
                            <th class="center" width="250">Day  </th>
                            <th class="center" width="250">Date </th>
                            <th class="center" width="250">Timing </th>
                        </tr>';
                        $condition = array(
                                                 'select'       =>  'd.dated, d.start_time, d.end_time, s.subject_name, s.subject_code'
                                                ,'join'       	=>  'INNER JOIN '.CLASS_SUBJECTS.' AS s ON s.subject_id = d.id_subject'
                                                ,'where'        =>  array(
                                                                            'd.id_setup' 	=> $DATESHEET['id']
                                                                        )
                                                ,'order_by'  	=>  'd.dated'
                                                ,'return_type'  =>  'all'
                        );
                        $DATESHEET_DETAIL = $dblms->getRows(DATESHEET_DETAIL.' AS d', $condition);
                        foreach ($DATESHEET_DETAIL as $key => $val) {
                            echo '					
                            <tr>
                                <td class="center">'.($key+1).'</td>
                                <td class="center">'.$val['subject_code'].'</td>
                                <td>'.$val['subject_name'].'</td>
                                <td class="center">'.date("l", strtotime($val['dated'])).'</td>
                                <td class="center">'.date("d F Y", strtotime($val['dated'])).'</td>
                                <td class="center">'.$val['start_time'].' to '.$val['end_time'].'</td>
                            </tr>';
                        }
                        echo'			
                    </tbody>
                </table>
            </div>
        </section>
        <script>window.print();</script>
    </body>
    </html>';
}
?>