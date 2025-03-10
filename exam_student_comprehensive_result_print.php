<?php
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();
$_SESSION['PRINT']['id_campus']     = (!empty(cleanvars($_POST['id_campus'])))?cleanvars($_POST['id_campus']):$_SESSION['PRINT']['id_campus'];
$p_id_campus                        = $_SESSION['PRINT']['id_campus'];
$_SESSION['PRINT']['id_class']      = (!empty(cleanvars($_POST['id_class'])))?cleanvars($_POST['id_class']):$_SESSION['PRINT']['id_class'];
$p_id_class                         = $_SESSION['PRINT']['id_class'];
$p_id_class                         = explode('|',$p_id_class)[0];
$_SESSION['PRINT']['id_section']    = (!empty(cleanvars($_POST['id_section'])))?cleanvars($_POST['id_section']):$_SESSION['PRINT']['id_section'];
$p_id_section                       = $_SESSION['PRINT']['id_section'];
$_SESSION['PRINT']['id_std']        = (!empty(cleanvars($_POST['id_std'])))?cleanvars($_POST['id_std']):$_SESSION['PRINT']['id_std'];
$p_id_std                           = $_SESSION['PRINT']['id_std'];
$p_id_std                           = explode('|',$p_id_std)[0];
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
                size: A4 landscape;
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
    // DATESHEET
    $condition = array(
                             'select'       =>  'DISTINCT et.type_name, ds.id_exam'
                            ,'join'       	=>  'INNER JOIN '.EXAM_ATTENDANCE.' ea ON ea.id_datesheet = ds.id AND ea.is_publish = 1 AND ea.is_deleted = 0
                                                 INNER JOIN '.EXAM_TYPES.' AS et	ON et.type_id = ds.id_exam'
                            ,'where'        =>  array(
                                                             'ds.is_deleted'    => 0 
                                                            ,'ds.id_session' 	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                            ,'ds.id_class'  	=> cleanvars($p_id_class)
                                            )
                            ,'search_by'	=>	' AND (ds.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR ds.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].' OR ds.id_campus = '.cleanvars($p_id_campus).')'
                            ,'order_by'     =>  ' ds.id_exam'
                            ,'return_type'  =>  'all'
    );
    $DATESHEET 	= $dblms->getRows(DATESHEET.' AS ds', $condition );

    // STUDENTS
    $condition = array(
                             'select'       =>  's.std_id, s.std_name, s.std_fathername, s.std_nic, s.std_rollno, s.std_regno, s.std_photo, sc.class_name, ss.section_name, c.campus_name, c.campus_logo'
                            ,'join'       	=>  'LEFT JOIN '.CLASSES.' AS sc ON sc.class_id = s.id_class
                                                 LEFT JOIN '.CLASS_SECTIONS.' AS ss ON ss.section_id = s.id_section
                                                 LEFT JOIN '.CAMPUS.' c ON c.campus_id = s.id_campus'
                            ,'where'        =>  array(
                                                         's.std_status' 	=> 1
                                                        ,'s.is_deleted' 	=> 0
                                                        ,'s.id_session' 	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                        ,'s.std_id' 	    => cleanvars($p_id_std)
                                            )
                            ,'search_by'	=>	' AND (s.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR s.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].' OR s.id_campus = '.cleanvars($p_id_campus).')'
                            ,'order_by'     =>  's.std_id ASC'
                            ,'return_type'  =>  'single'
    );
    $STUDENTS 	= $dblms->getRows(STUDENTS.' AS s', $condition);

    if ($DATESHEET) {
        if ($STUDENTS) {
            echo'
            <table width="100%">
                <tr>
                    <td class="text-center" width="100">
                        <img src="uploads/images/campus/'.$STUDENTS['campus_logo'].'" style="max-height : 100px; margin:5px;">
                    </td>
                    <td class="text-center">
                        <span style="font-size: 30px;">'.$STUDENTS['campus_name'].'</span>
                        <br>
                        <span style="font-size: 20px;">Result Card (All Terms) ('.$_SESSION['userlogininfo']['ACA_SESSION_NAME'].')</span>
                    </td>
                </tr>
            </table>

            <table width="100%" style="margin-top: 1rem;">
                <tr>
                    <td class="text-center">
                        <b>Student Name: <u>'.$STUDENTS['std_name'].'</u></b>
                    </td>
                    <td class="text-center">
                        <b>Father Name: <u>'.$STUDENTS['std_fathername'].'</u></b>
                    </td>
                    <td class="text-center">
                        <b>Roll No: <u>'.$STUDENTS['std_rollno'].'</u></b>
                    </td>
                    <td class="text-center">
                        <b>Registration: <u>'.$STUDENTS['std_regno'].'</u></b>
                    </td>
                    <td class="text-center">
                        <b>Class: <u>'.$STUDENTS['class_name'].'</u></b>
                    </td>
                    <td class="text-center">
                        <b>Section: <u>'.$STUDENTS['section_name'].'</u></b>
                    </td>
                </tr>
            </table>

            <table width="100%" style="margin-top: 1rem;">
                <tr>';
                    $OverAllObtain  = 0;
                    $OverAllTotal   = 0;
                    foreach ($DATESHEET AS $outkey => $outval) {
                        echo'
                        <td style="vertical-align: top;">
                            <table class="table">
                                <tr>
                                    <th class="text-center" colspan="5">'.$outval['type_name'].'</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Subject</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Obtained</th>
                                    <th class="text-center">Percentage</th>
                                    <th class="text-center">Grade</th>
                                </tr>';
                                $termObtain     = 0;
                                $termTotal      = 0;
                                // DATESHEET DETAIL
                                $condition = array(
                                                         'select'       =>  'ds.id, ss.subject_name, ea.id_subject, ea.id as ea_id, dd.total_marks'
                                                        ,'join'       	=>  'INNER JOIN '.EXAM_ATTENDANCE.' ea ON ea.id_datesheet = ds.id AND ea.is_publish = 1 AND ea.is_deleted = 0
                                                                             INNER JOIN '.CLASS_SUBJECTS.' ss ON ss.subject_id = ea.id_subject
                                                                             LEFT JOIN '.DATESHEET_DETAIL.' dd ON dd.id_setup = ds.id AND dd.id_subject = ea.id_subject'
                                                        ,'where'        =>  array(
                                                                                     'ds.is_deleted'    => 0 
                                                                                    ,'ds.id_exam' 	    => cleanvars($outval['id_exam'])
                                                                                    ,'ds.id_session' 	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                                                    ,'ds.id_class' 	    => cleanvars($p_id_class)
                                                                                )
                                                        ,'search_by'	=>	' AND (ds.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR ds.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
                                                        ,'order_by'  	=>  'dd.dated'
                                                        ,'return_type'  =>  'all'
                                );
                                $SUB_DATESHEET 	= $dblms->getRows(DATESHEET.' ds', $condition);
                                if ($SUB_DATESHEET) {
                                    foreach ($SUB_DATESHEET AS $key => $val) {
                                        $Percentage = 0;
                                        echo'
                                        <tr>                                            
                                            <td class="text-left">'.$val['subject_name'].'</td>
                                            <td class="text-center">'.$val['total_marks'].'</td>';
                                            // ATTENDANCE
                                            $condition = array(
                                                                     'select'       =>  'ad.status'
                                                                    ,'where'        =>  array(
                                                                                                 'ad.id_setup' 	    =>  cleanvars($val['ea_id'])
                                                                                                ,'ad.id_std'        =>  cleanvars($p_id_std)
                                                                                            )
                                                                    ,'return_type'  =>  'single'
                                            );
                                            $EXAM_ATTENDANCE_DETAIL = $dblms->getRows(EXAM_ATTENDANCE_DETAIL.' ad', $condition);
                                            if ($EXAM_ATTENDANCE_DETAIL['status'] != '2') {
                                                $condition = array(
                                                                         'select'       =>  'em.id, emd.total_marks, emd.obtain_marks'
                                                                        ,'join'         =>  'LEFT JOIN '.EXAM_MARKS_DETAILS.' AS emd ON (em.id = emd.id_setup AND emd.id_std = '.$p_id_std.')'
                                                                        ,'where'        =>  array(
                                                                                                    'em.status' 	    => 1
                                                                                                    ,'em.is_publish' 	=> 1
                                                                                                    ,'em.is_deleted' 	=> 0
                                                                                                    ,'em.id_datesheet'  => cleanvars($val['id'])
                                                                                                    ,'em.id_exam' 	    => cleanvars($outval['id_exam'])
                                                                                                    ,'em.id_class' 	    => cleanvars($p_id_class)
                                                                                                    ,'em.id_section' 	=> cleanvars($p_id_section)
                                                                                                    ,'em.id_subject' 	=> cleanvars($val['id_subject'])
                                                                                                    ,'em.id_session' 	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                                                        )
                                                                        ,'search_by'	=>	' AND (em.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR em.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].' OR em.id_campus = '.cleanvars($p_id_campus).')'
                                                                        ,'return_type'  =>  'single'
                                                );
                                                $EXAM_MARKS = $dblms->getRows(EXAM_MARKS.' AS em', $condition);
                                                if ($EXAM_MARKS['obtain_marks'] != NULL) {
                                                    echo'
                                                    <td class="text-center">'.$EXAM_MARKS['obtain_marks'].'</td>';
                                                    $Percentage = (($EXAM_MARKS['obtain_marks']/$EXAM_MARKS['total_marks'])*100);
                                                    echo'
                                                    <td class="text-center">'.number_format($Percentage, 2).'.%</td>';
                                                    // GRADESYSTEM
                                                    $condition = array(
                                                                            'select'       =>  'g.grade_name, g.grade_comment'
                                                                            ,'where'        =>  array(
                                                                                                        'g.grade_status' 	=> 1
                                                                                                        ,'g.is_deleted' 	=> 0
                                                                                            )
                                                                            ,'search_by'	=>	' AND (g.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR g.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].' OR g.id_campus = '.cleanvars($p_id_campus).')
                                                                                                    AND '.$Percentage.' BETWEEN g.grade_lowermark AND g.grade_uppermark'
                                                                            ,'return_type'  =>  'single'
                                                    );
                                                    $GRADESYSTEM 	= $dblms->getRows(GRADESYSTEM.' AS g', $condition);
                                                    $termObtain    += $EXAM_MARKS['obtain_marks'];
                                                    echo'
                                                    <td class="text-center">'.$GRADESYSTEM['grade_name'].'</td>';
                                                }else{
                                                    echo'
                                                    <td class="text-center">--</td>
                                                    <td class="text-center">--</td>
                                                    <td class="text-center">--</td>';
                                                }
                                            } else {
                                                $totalObtain    += 0;                                
                                                echo'
                                                <th colspan="3" class="text-center">Absent</th>';
                                            }
                                            // total of the term
                                            $termTotal += $val['total_marks'];
                                            echo'
                                        </tr>';
                                    }     
                                    echo'
                                    <tr>
                                        <th class="text-right">Total</th>
                                        <th class="text-center">'.$termTotal.'</th>
                                        <th class="text-center">'.$termObtain.'</th>
                                        <th class="text-center">'.number_format((($termObtain/$termTotal)*100), 2).'%</th>
                                        <th class="text-center"></th>
                                    </tr>';
                                    $OverAllObtain  += $termObtain;
                                    $OverAllTotal   += $termTotal;
                                }
                                echo'
                            </table>
                        </td>';
                    }
                    echo'
                </tr>
            </table>

            <table width="100%" style="margin-top: 1rem;">
                <tr>
                    <td class="text-center">
                        <b>Over all Marks: <u>'.$OverAllObtain.' / '.$OverAllTotal.'</u></b>
                    </td>
                    <td class="text-center">';
                        $totalPercentage = (($OverAllObtain/$OverAllTotal)*100);
                        echo'
                        <b>Percentage: <u>'.number_format((($OverAllObtain/$OverAllTotal)*100), 2).'%</u></b>
                    </td>
                    <td class="text-center">';
                        // GRADESYSTEM
                        $condition = array(
                                            'select'       =>  'g.grade_name, g.grade_comment'
                                            ,'where'        =>  array(
                                                                        'g.grade_status' 	=> 1
                                                                        ,'g.is_deleted' 	=> 0
                                                            )
                                            ,'search_by'	=>	' AND (g.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR g.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].' OR g.id_campus = '.cleanvars($p_id_campus).')
                                                                    AND '.$totalPercentage.' BETWEEN g.grade_lowermark AND g.grade_uppermark'
                                            ,'return_type'  =>  'single'
                        );
                        $GRADESYSTEM 	= $dblms->getRows(GRADESYSTEM.' AS g', $condition);
                        echo'
                        <b>Overall Grade: <u>'.$GRADESYSTEM['grade_name'].'</u></b>
                    </td>
                    <td class="text-center">
                        <b>Rank: <u>'.$DATESHEET['dated'].'</u></b>
                    </td>
                </tr>
            </table>

            <table width="100%" style="margin-top: 1rem;">
                <tr>
                    <th class="text-left">Teacher Feed Back:</th>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid;"><div style="height: 20px;"></div></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid;"><div style="height: 20px;"></div></td>
                </tr>
            </table>

            <table width="100%" style="margin-top: 2rem;">
                <tr>
                    <th width="140" class="text-left">Class Teacher Sign:</th>
                    <td>_____________</td>
                    <th width="140" class="text-right">Principal Sign:</th>
                    <td>_____________</td>
                    <th width="140" class="text-right">Parent Sign:</th>
                    <td>_____________</td>
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
            </script>';
        } else {
            echo'<h1 style="color: red;" class="text-cneter">No Student Found</h1>';
        }
    } else {
        echo'<h1 style="color: red;" class="text-cneter">No Datesheet Found</h1>';
    }
    echo'
</body>
</html>';
?>