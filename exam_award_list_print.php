<?php
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
            padding-left: 4px; 
            padding-right: 4px; 
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
    // EXAM MARKS, CAMPUS AND OTHER DETAIL
    $condition = array(
                            'select'		 => 'em.*, t.type_name, cm.campus_name, cm.campus_logo, dd.total_marks, dd.passing_marks, c.class_name, cs.section_name, sb.subject_name
                                                ,COUNT(CASE WHEN md.status = 1 THEN 1 ELSE NULL END) totalPass
                                                ,COUNT(CASE WHEN md.status = 2 THEN 1 ELSE NULL END) totalFail'
                            ,'join'			 =>	'INNER JOIN '.DATESHEET_DETAIL.' dd ON dd.id_setup = em.id_datesheet AND dd.id_subject = '.cleanvars($_POST['id_subject']).'
                                                 INNER JOIN '.CAMPUS.' cm ON cm.campus_id = em.id_campus
                                                 INNER JOIN '.CLASSES.' c ON c.class_id = em.id_class
                                                 INNER JOIN '.CLASS_SECTIONS.' cs ON cs.section_id = em.id_section
                                                 INNER JOIN '.CLASS_SUBJECTS.' sb ON sb.subject_id = em.id_subject
                                                 INNER JOIN '.EXAM_TYPES.' t ON t.type_id = em.id_exam
                                                 INNER JOIN '.EXAM_MARKS_DETAILS.' md ON md.id_setup = em.id'
                            ,'where'         =>  array(
                                                             'em.status'            =>	1
                                                            ,'em.is_publish'		=>	1
                                                            ,'em.is_deleted'		=>	0
                                                            ,'em.id_subject'		=>	cleanvars($_POST['id_subject'])
                                                            ,'em.id_campus'			=>	cleanvars($_POST['id_campus'])
                                                            ,'em.id_class'			=>	cleanvars($_POST['id_class'])
                                                            ,'em.id_section'        =>	cleanvars($_POST['id_section'])
                                                            ,'em.id_exam'           =>	cleanvars($_POST['id_exam'])
                                                            ,'em.id_session'        =>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    )
                            ,'return_type'   =>  'single'
    );
    $EXAM_MARKS = $dblms->getRows(EXAM_MARKS.' em', $condition);

    // EXAM MARKS, CAMPUS AND OTHER DETAIL
    $condition = array(
                             'select'		 => 'COUNT(CASE WHEN ad.status = 1 THEN 1 ELSE NULL END) totalPresent, COUNT(CASE WHEN ad.status = 2 THEN 1 ELSE NULL END) totalAbsent'
                            ,'join'			 =>	'INNER JOIN '.EXAM_ATTENDANCE_DETAIL.' ad ON ad.id_setup = ea.id'
                            ,'where'         =>  array(
                                                             'ea.status'            =>	1
                                                            ,'ea.is_deleted'		=>	0
                                                            ,'ea.id_subject'		=>	cleanvars($_POST['id_subject'])
                                                            ,'ea.id_campus'			=>	cleanvars($_POST['id_campus'])
                                                            ,'ea.id_class'			=>	cleanvars($_POST['id_class'])
                                                            ,'ea.id_section'        =>	cleanvars($_POST['id_section'])
                                                            ,'ea.id_exam'           =>	cleanvars($_POST['id_exam'])
                                                            ,'ea.id_session'        =>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    )
                            ,'return_type'   =>  'single'
    );
    $EXAM_ATTENDANCE = $dblms->getRows(EXAM_ATTENDANCE.' ea', $condition);

    // STUDENTS FROM ATTENDANCE
    $condition = array(
                         'select'		=>  's.std_id, s.std_rollno, s.std_regno, s.std_name, s.std_fathername, s.std_photo, s.id_section, md.obtain_marks, md.total_marks, md.status, md.remarks'
                        ,'join'			=>	'INNER JOIN '.EXAM_ATTENDANCE_DETAIL.' ad ON ad.id_setup = ea.id AND ad.status = 1
                                             INNER JOIN '.STUDENTS.' s ON s.std_id = ad.id_std AND s.std_status = 1 AND s.is_deleted = 0
                                             LEFT JOIN '.EXAM_MARKS_DETAILS.' md ON md.id_std = s.std_id AND md.id_setup = '.$EXAM_MARKS['id'].''
                        ,'where'        =>  array(
                                                     'ea.status'		=>	1
                                                    ,'ea.is_publish'  	=>	1
                                                    ,'ea.is_deleted'  	=>	0
                                                    ,'ea.id_campus'		=>	cleanvars($_POST['id_campus'])
                                                    ,'ea.id_session'	=>	cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    ,'ea.id_exam'		=>	cleanvars($_POST['id_exam'])
                                                    ,'ea.id_class'		=>	cleanvars($_POST['id_class'])
                                                    ,'ea.id_section'	=>	cleanvars($_POST['id_section'])
                                                    ,'ea.id_subject'	=>	cleanvars($_POST['id_subject'])
                                                )
                        ,'order_by'		=>	' s.std_id ASC'
                        ,'return_type'  =>  'all'
    );
    $STUDENTS = $dblms->getRows(EXAM_ATTENDANCE.' ea', $condition);
    
    // CAMPUS LOGO
    $campus_logo = (!empty($EXAM_MARKS['campus_logo']) && file_exists('uploads/images/campus/'.$EXAM_MARKS['campus_logo'].'') ? 'uploads/images/campus/'.$EXAM_MARKS['campus_logo'].'' : 'uploads/logo.png');
    echo'
    <div class="page-break"></div>
    <table width="100%">
        <tr>
            <td class="text-center" width="100">
                <img src="'.$campus_logo.'" style="max-height : 100px; margin:5px;">
            </td>
            <td class="text-center">
                <span style="font-size: 30px;">'.$EXAM_MARKS['campus_name'].'</span>
                <br>
                <span style="font-size: 20px;">Award List ('.$EXAM_MARKS['type_name'].')</span>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td class="text-left">
                <b>Class: </b> <u style="margin-left: 0.5rem;">'.$EXAM_MARKS['class_name'].'</u>
            </td>
            <td class="text-left">
                <b>Section: </b> <u style="margin-left: 0.5rem;">'.$EXAM_MARKS['section_name'].'</u>
            </td>
            <td class="text-left">
                <b>Class Incharge: </b> <u style="margin-left: 0.5rem;"></u>
            </td>
            <td class="text-left">
                <b>Subject: </b> <u style="margin-left: 0.5rem;">'.$EXAM_MARKS['subject_name'].'</u>
            </td>
            <td class="text-left">
                <b>Session: </b> <u style="margin-left: 0.5rem;">'.$_SESSION['userlogininfo']['ACA_SESSION_NAME'].'</u>
            </td>
        </tr>
        <tr>
            <td class="text-left">
                <b>Checker: </b><u></u>
            </td>
            <td class="text-left">
                <b>Rechecker: </b><u></u>
            </td>
            <td class="text-left">
                <b>Total Marks: </b><u>'.$EXAM_MARKS['total_marks'].'</u>
            </td>
            <td class="text-left">
                <b>Passing Marks: </b><u>'.$EXAM_MARKS['passing_marks'].'</u>
            </td>
        </tr>
    </table>
    <table class="table">
        <tr>
            <th class="text-center" width="30">Sr.</th>
            <th class="text-center" width="50">Roll No</th>
            <th class="text-left">Student Name</th>
            <th class="text-left">Father Name</th>
            <th class="text-center" width="150">O.M</th>
            <th class="text-center" width="150">T.M</th>
        </tr>';
        if ($STUDENTS) {
            $srno = 0;
            foreach ($STUDENTS as $student) {
                $srno++;
                echo'
                <tr>
                    <td class="text-center">'.$srno.'</td>
                    <td class="text-center">'.$student['rollno'].'</td>
                    <td class="text-left">'.$student['std_name'].'</td>
                    <td class="text-left">'.$student['std_fathername'].'</td>
                    <td class="text-right">'.$student['obtain_marks'].'</td>
                    <td class="text-right">'.$student['total_marks'].'</td>
                </tr>';
            }                    
        }
        echo'
    </table>
    <table class="table" width="100%">
        <tr>
            <td class="text-center">
                <b>Total Students: </b> <u>'.($EXAM_ATTENDANCE['totalPresent'] + $EXAM_ATTENDANCE['totalAbsent']).'</u>
            </td>
            <td class="text-center">
                <b>Appear: </b> <u>'.$EXAM_ATTENDANCE['totalPresent'].'</u>
            </td>
            <td class="text-center">
                <b>Absent: </b> <u>'.$EXAM_ATTENDANCE['totalAbsent'].' </u>
            </td>
            <td class="text-center">
                <b>Pass: </b> <u>'.$EXAM_MARKS['totalPass'].'</u>
            </td>
            <td class="text-center">
                <b>Fail: </b> <u>'.$EXAM_MARKS['totalFail'].'</u>
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-top: 1rem;">
        <tr>
            <td class="text-center">
                <b>Sign Teacher: <u></u></b>
            </td>
            <td class="text-center">
                <b>Sign Cordinator: <u></u></b>
            </td>
            <td class="text-center">
                <b>Sign Principal: <u></u></b>
            </td>
        </tr>
    </table>
    <script>
        document.addEventListener("keydown", function (e) {
            if (e.ctrlKey && e.key === "u") {
                e.preventDefault();
            }
            if (e.ctrlKey && e.key === "s") {
                e.preventDefault();
            }
            if (e.ctrlKey && e.shiftKey && e.key === "C") {
                e.preventDefault();
            }
        });
        document.addEventListener("contextmenu", function (e) {
            e.preventDefault();
        });
        document.addEventListener("dragstart", function(e) {
            e.preventDefault();
        });
        document.addEventListener("mousedown", function(e) {
            e.preventDefault();
        });
        document.addEventListener("selectstart", function(e) {
            e.preventDefault();
        });
        window.print();
    </script>
</body>
</html>';
?>