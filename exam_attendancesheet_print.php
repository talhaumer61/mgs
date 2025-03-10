<?php
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();
$_SESSION['PRINT']['id_campus']     = (!empty(cleanvars($_POST['id_campus'])))?cleanvars($_POST['id_campus']):$_SESSION['PRINT']['id_campus'];
$p_id_campus                        = $_SESSION['PRINT']['id_campus'];
$_SESSION['PRINT']['id_exam']       = (!empty(cleanvars($_POST['id_exam'])))?cleanvars($_POST['id_exam']):$_SESSION['PRINT']['id_exam'];
$p_id_exam                          = $_SESSION['PRINT']['id_exam'];
$_SESSION['PRINT']['id_class']      = (!empty(cleanvars($_POST['id_class'])))?cleanvars($_POST['id_class']):$_SESSION['PRINT']['id_class'];
$p_id_class                         = $_SESSION['PRINT']['id_class'];
$_SESSION['PRINT']['id_section']    = (!empty(cleanvars($_POST['id_section'])))?cleanvars($_POST['id_section']):$_SESSION['PRINT']['id_section'];
$p_id_section                       = $_SESSION['PRINT']['id_section'];
$_SESSION['PRINT']['id_subject']    = (!empty(cleanvars($_POST['id_subject'])))?cleanvars($_POST['id_subject']):$_SESSION['PRINT']['id_subject'];
$p_id_subject                       = $_SESSION['PRINT']['id_subject'];
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
    $id_campus = (!empty($p_id_campus) ? $p_id_campus : $_SESSION['userlogininfo']['LOGINCAMPUS']);

    // CAMPUS DETAIL
    $condition = array(
                         'select'       =>  'campus_name, controller_exam_sign, campus_logo'
                        ,'where'        =>  array(
                                                     'is_deleted'           =>  0
                                                    ,'campus_status'        =>  1
                                                    ,'campus_id'            =>  cleanvars($id_campus)
                                                )
                        ,'return_type'  =>  'single'
    );
    $CAMPUS = $dblms->getRows(CAMPUS, $condition);

    // DATESHEET
    $condition = array(
                             'select'       =>  'ss.subject_name, dsd.dated, et.type_name'
                            ,'join'       	=>  'INNER JOIN '.DATESHEET_DETAIL.' AS dsd	ON dsd.id_setup = ds.id
                                                 INNER JOIN '.CLASS_SUBJECTS.' AS ss	ON ss.subject_id = dsd.id_subject
                                                 LEFT JOIN '.EXAM_TYPES.' AS et	ON et.type_id = ds.id_exam'
                            ,'where'        =>  array(
                                                             'ds.is_deleted'    => 0
                                                            ,'ds.id_exam'       => cleanvars($p_id_exam)
                                                            ,'dsd.id_subject'   => cleanvars($p_id_subject)
                                            )
                            ,'search_by'	=>	' AND (ds.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR ds.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
                            ,'order_by'  	=>  'dsd.dated'
                            ,'return_type'  =>  'single'
    );
    if (!empty($p_id_class)) {
        $condition['where']['ds.id_class'] = cleanvars($p_id_class);
    }
    $DATESHEET 	= $dblms->getRows(DATESHEET.' AS ds', $condition, $sql);

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
                            ,'search_by'	=>	' AND (s.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR s.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].' OR s.id_campus = '.cleanvars($p_id_campus).')'
                            ,'order_by'     =>  's.std_id ASC'
                            ,'return_type'  =>  'all'
    );
    if (!empty($p_id_class)) {
        $condition['where']['s.id_class'] = cleanvars($p_id_class);
    }
    if (!empty($p_id_section)) {
        $condition['where']['s.id_section'] = cleanvars($p_id_section);
    }
    if (!empty($_POST['std_regno'])) {
        $condition['where']['s.std_regno'] = cleanvars($_POST['std_regno']);
    }
    $STUDENTS 	= $dblms->getRows(STUDENTS.' AS s', $condition);
    $campus_logo = (!empty($CAMPUS['campus_logo']) && file_exists("uploads/images/campus/".$CAMPUS['campus_logo']."") ? "uploads/images/campus/".$CAMPUS['campus_logo']."" : "uploads/logo.png");
    echo'
    '.(($key%2)==1?'
    <div class="page-break"></div>':'').'
    <div class="rollnoslip">
        <table width="100%">
            <tr>
                <td class="text-center" width="100">
                    <img src="'.$campus_logo.'" style="max-height : 100px; margin:5px;">
                </td>
                <td class="text-center">
                    <span style="font-size: 30px;">'.$CAMPUS['campus_name'].'</span>
                    <br>
                    <span style="font-size: 20px;">Exam Attendance Sheet ('.$DATESHEET['type_name'].')</span>
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td class="text-center">
                    <b>Class: <u>'.$STUDENTS[0]['class_name'].'</u></b>
                </td>
                <td class="text-center">
                    <b>Section: <u>'.$STUDENTS[0]['section_name'].'</u></b>
                </td>
                <td class="text-center">
                    <b>Subject: <u>'.$DATESHEET['subject_name'].'</u></b>
                </td>
                <td class="text-center">
                    <b>Date: <u>'.$DATESHEET['dated'].'</u></b>
                </td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <th class="text-center" width="30">Sr.</th>
                <th class="text-center" width="50">Roll No</th>
                <th class="text-left">Student Name</th>
                <th class="text-left">Father Name</th>
                <th class="text-center" width="150">Signature</th>
            </tr>';
            if ($STUDENTS) {
                foreach ($STUDENTS AS $inkey => $inval) {
                    echo'
                    <tr>
                        <td class="text-center">'.($inkey+1).'</td>
                        <td class="text-center">'.$inval['std_rollno'].'</td>
                        <td class="text-left">'.$inval['std_name'].'</td>
                        <td class="text-left">'.$inval['std_fathername'].'</td>
                        <td></td>
                    </tr>';
                }                    
            }
            echo'
        </table>

        <table width="100%">
            <tr>
                <td class="text-right" style="padding-top: 20px;">
                    <b>Invigilator Sign: <u>____________________</u></b>
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
    </div>
</body>
</html>';
?>