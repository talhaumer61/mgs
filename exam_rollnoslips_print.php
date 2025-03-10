<?php

use Illuminate\Support\Arr;

require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();
echo'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>'.moduleName(false).'</title>
    <link rel="shortcut icon" href="assets/images/favicon.png">
    <script src="assets/javascripts/qrcode.min.js"></script>

    <style type="text/css">
        .container {
            margin-left: auto;
            margin-right: auto;
            padding-left: 15px;
            padding-right: 15px;
            width: 100%;
            max-width: 1140px; /* You can adjust this value to your preference */
        }    
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
            background-color: #fff;
        }
        @media all {
            .page-break	{ display: none; }
        }
        @media print {
            .page-break	{ display: block; page-break-before: always; }
            @page { 
                size: A4 portrait;
                margin: 4mm 4mm 4mm 4mm; 
            }
            #printPageButton {
                display: none;
            }
            /* Adjustments for tables */
            table {
                max-width: calc(100% - 40px); /* Adjust as necessary */
                table-layout: fixed;
            }
            table td,
            table th {
                word-wrap: break-word;
            }
            /* Adjustments for images */
            img {
                max-width: 100%;
                height: auto;
            }
            /* Adjustments for other elements */
            .text-center {
                text-align: center;
            }
            .text-left {
                text-align: left;
            }
            .text-right {
                text-align: right;
            }
        }
        .table {
            display: table;
            text-indent: initial;
            width: 100%;
            background-color: transparent;
            border-collapse: collapse;
        }
        .table > thead > tr > th,
        .table > tbody > tr > th,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > tbody > tr > td,
        .table > tfoot > tr > td {
            border: 1px solid #000000;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body class="container">';
    if (!empty($_POST['id_exam']) && !empty($_POST['id_class'])) {
        // CAMPUS DETAIL
        $condition = array(
                                 'select'       =>  'campus_name, controller_exam_sign, campus_logo'
                                ,'where'        =>  array(
                                                             'is_deleted'           =>  0
                                                            ,'campus_status'        =>  1
                                                            ,'campus_id'            =>  cleanvars($_POST['id_campus'])
                                                        )
                                ,'return_type'  =>  'single'
        );
        $CAMPUS = $dblms->getRows(CAMPUS, $condition);

        // DATESHEET DETAIL
        $condition = array(
                                 'select'       =>  'ss.subject_name, dsd.dated, dsd.start_time, dsd.end_time, et.type_name'
                                ,'join'       	=>  'INNER JOIN '.DATESHEET_DETAIL.' AS dsd	ON dsd.id_setup = ds.id
                                                        INNER JOIN '.CLASS_SUBJECTS.' AS ss	ON ss.subject_id = dsd.id_subject
                                                        LEFT JOIN '.EXAM_TYPES.' AS et	ON et.type_id = ds.id_exam'
                                ,'where'        =>  array(
                                                                 'ds.is_deleted'    => 0
                                                                ,'ds.id_exam'       => cleanvars($_POST['id_exam'])
                                                )
                                ,'search_by'	=>	' AND (ds.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR ds.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
                                ,'order_by'  	=>  'dsd.dated'
                                ,'return_type'  =>  'all'
        );
        if (!empty($_POST['id_class'])) {
            $condition['where']['ds.id_class'] = cleanvars($_POST['id_class']);
        }
        $DATESHEET 	= $dblms->getRows(DATESHEET.' AS ds', $condition, $sql);
        $type_name = $DATESHEET[0]['type_name'];
        // EXAM INSTRUCTIONS
        $condition = array(
                                'select'       =>  'instructions'
                                ,'where'        =>  array(
                                                             'status'       => 1
                                                            ,'is_deleted'   => 0
                                                            ,'id_examtype'  => cleanvars($_POST['id_exam'])
                                                            ,'id_class'     => cleanvars($_POST['id_class'])
                                                        )
                                ,'search_by'	=>	' AND (id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
                                ,'return_type'  =>  'single'
        );
        $EXAM_INSTRUCTIONS 	= $dblms->getRows(EXAM_INSTRUCTIONS, $condition);
        // STUDENTS
        $condition = array(
                                'select'       =>  's.std_id, s.std_name, s.std_fathername, s.std_nic, s.std_rollno, s.std_regno, s.std_photo, sc.class_name, ss.section_name'
                                ,'join'       	=>  'LEFT JOIN '.CLASSES.' AS sc ON sc.class_id = s.id_class
                                                     LEFT JOIN '.CLASS_SECTIONS.' AS ss ON ss.section_id = s.id_section'
                                ,'where'        =>  array(
                                                             's.std_status' 	=> 1
                                                            ,'s.is_deleted' 	=> 0
                                                            ,'s.id_session' 	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                )
                                ,'search_by'	=>	' AND (s.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR s.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].' OR s.id_campus = '.cleanvars($_POST['id_campus']).')'
                                ,'order_by'     =>  's.std_id ASC'
                                ,'return_type'  =>  'all'
        );
        if (!empty($_POST['id_class'])) {
            $condition['where']['s.id_class'] = cleanvars($_POST['id_class']);
        }
        if (!empty($_POST['id_section'])) {
            $condition['where']['s.id_section'] = cleanvars($_POST['id_section']);
        }
        if (!empty($_POST['std_regno'])) {
            $condition['where']['s.std_regno'] = cleanvars($_POST['std_regno']);
        }
        $STUDENTS 	= $dblms->getRows(STUDENTS.' AS s', $condition);
        if ($STUDENTS && $DATESHEET)  {
            foreach ($STUDENTS AS $key => $val) {
                $photo = ((file_exists("uploads/images/students/".$val['std_photo']."") && !empty($val['std_photo'])) ? "uploads/images/students/".$val['std_photo']."" : "uploads/default-student.jpg");
                $campus_logo = (!empty($CAMPUS['campus_logo']) && file_exists("uploads/images/campus/".$CAMPUS['campus_logo']."") ? "uploads/images/campus/".$CAMPUS['campus_logo']."" : "uploads/logo.png");
                if($key != 0){
                    echo'<div class="page-break"></div>';
                }
                echo'
                <div class="rollnoslip">
                    <table width="100%">
                        <tr>
                            <td class="text-center" width="100">
                                <img src="'.$campus_logo.'" style="max-height : 100px; margin:5px;">
                            </td>
                            <td class="text-center">
                                <span style="font-size: 30px;">'.$CAMPUS['campus_name'].'</span>
                                <br>
                                <span style="font-size: 20px;">'.$type_name.', '.$_SESSION['userlogininfo']['ACA_SESSION_NAME'].'</span>
                                <br>
                                <span><strong>ROLL NUMBER SLIP</strong></span>
                            </td>
                        </tr>
                    </table>

                    <table width="100%">
                        <tr>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <th class="text-left" width="100">Roll no:</th>
                                        <td class="text-left"><u>'.$val['std_rollno'].'</u></td>
                                        <th class="text-left" width="100">Registration:</th>
                                        <td class="text-left"><u>'.$val['std_regno'].'</u></td>
                                    </tr>
                                    <tr>
                                        <th class="text-left">Name:</th>
                                        <td class="text-left"><u>'.$val['std_name'].'</u></td>
                                        <th class="text-left" width="100">Class:</th>
                                        <td class="text-left"><u>'.$val['class_name'].'</u></td>
                                    </tr>
                                    <tr>
                                        <th class="text-left" width="100">Father:</th>
                                        <td class="text-left"><u>'.$val['std_fathername'].'</u></td>
                                        <th class="text-left" width="100">Section:</th>
                                        <td class="text-left"><u>'.$val['section_name'].'</u></td>
                                    </tr>
                                    <tr>
                                        <th class="text-left" width="100">Father CNIC:</th>
                                        <td class="text-left"><u>'.$val['std_nic'].'</u></td>
                                        <th class="text-left" width="100"></th>
                                        <td class="text-left"></td>
                                    </tr>
                                </table>
                            </td>
                            <td class="text-right" width="130">
                                <img src="'.$photo.'" width="100" height="100" style="padding: 2px; border: 1px solid #A5A5A5;">
                            </td>
                        </tr>
                    </table>    

                    <table class="table">
                        <thead>	
                            <tr>
                                <th class="text-center" width="50">Sr.</th>
                                <th class="text-left">Subject</th>
                                <th class="text-center" width="200">Date </th>
                                <th class="text-center" width="200">Day</th>
                                <th class="text-center" width="200">Timing</th>
                            </tr>
                        </thead>
                        <tbody>';
                            if ($DATESHEET) {
                                foreach ($DATESHEET AS $inkey => $inval) {
                                    echo'
                                    <tr>
                                        <td class="text-center">'.($inkey+1).'</td>
                                        <td class="text-left">'.$inval['subject_name'].'</td>
                                        <td class="text-center">'.date("d F Y", strtotime($inval['dated'])).'</td>
                                        <td class="text-center">'.date("l", strtotime($inval['dated'])).'</td>
                                        <td class="text-center">'.$inval['start_time'].' to '.$inval['end_time'].'</td>
                                    </tr>';
                                }   
                            }
                            echo'            
                        </tbody>
                    </table>

                    <table width="100%">
                        <tr>
                            <td>
                                <h4 class="text-dark">Note:</h4>
                                <p class="text-dark">Designer at work who dont have any content for their product yet have the possibility to insert a dummy text into their design to judge on the arrangement of text on their site, on readability or on fonts and sizes.</p>
                            </td>
                        </tr>
                    </table>

                    <table width="100%">
                        <tr>
                            <td class="text-center" width="300">';
                                if(!empty($CAMPUS['controller_exam_sign']) && file_exists('uploads/images/controller_exam_sign/'.$CAMPUS['controller_exam_sign'].'')){
                                    echo'<img src="uploads/images/controller_exam_sign/'.$CAMPUS['controller_exam_sign'].'" style="width: 80px; height: 80px;">';
                                }
                                echo'
                                <p class="text-center" style="margin:5px;border-top:1px solid black;">Controller Exam Signature</p>
                            </td>
                            <td></td>
                            <td width="100" class="text-right">';
                                // GENERATE QR CODE
                                
                                // for json string code
                                $dataQR = array(
                                                     'id_exam'	    => $_POST['id_exam']
                                                    ,'id_class'		=> $_POST['id_class']
                                                    ,'id_std'		=> $val['std_id']
                                                    ,'std_regno'	=> $val['std_regno']
                                                    ,'std_rollno'	=> $val['std_rollno']
                                                );
                                $dataJSON = json_encode($dataQR);

                                // $dataJSON = $feercord['challan_no'].'-'.$feercord['id']; // for string code
                                echo'
                                <div id="qrcode_'.$val['std_id'].'_'.$key.'" title=""></div>
                                
                                <script>
                                    const qrcode_'.$val['std_id'].'_'.$key.' = new QRCode(document.getElementById(\'qrcode_'.$val['std_id'].'_'.$key.'\'), {
                                        text: \''.$dataJSON.'\',
                                        width: 100,
                                        height: 100,
                                        colorDark: "#000",
                                        colorLight: "#fff",
                                        correctLevel: QRCode.CorrectLevel.H
                                    });
                                    document.getElementById(\'qrcode_'.$val['std_id'].'_'.$key.'\').setAttribute("title", "");
                                </script>';
                                echo'
                            </td>
                        </tr>
                    </table>

                    <table width="100%">
                        <tr>
                            <td>
                                <h2 class="text-dark">Instructions:</h2>
                                <p class="text-dark" style="padding-left: 10px; padding-right: 10px;">'.html_entity_decode(html_entity_decode($EXAM_INSTRUCTIONS['instructions'])).'</p>
                            </td>
                        </tr>
                    </table>
                </div>
                <script>
                    window.print();
                </script>';

            }
        } else {
            if (!$DATESHEET_DETAIL)  {
                echo'<center><h1 style="color: red;">Date Sheet Not Created</h1></center>';
            }
            if (!$STUDENTS)  {
                echo'<center><h1 style="color: red;">No Student Found</h1></center>';
            }
        }
    } else {
        echo'<center><h1 style="color: red;">Please Select Exam Term And Class</h1></center>';
    }
    echo'
</body>
</html>';
?>