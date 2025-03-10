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
$p_id_class                         = explode('|',$p_id_class)[0];
$_SESSION['PRINT']['id_section']    = (!empty(cleanvars($_POST['id_section'])))?cleanvars($_POST['id_section']):$_SESSION['PRINT']['id_section'];
$p_id_section                       = $_SESSION['PRINT']['id_section'];
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
                             'select'       =>  'ds.id, ss.subject_name, et.type_name, ea.id_subject, ea.id as ea_id, dd.total_marks, c.campus_name, c.campus_logo'
                            ,'join'       	=>  'INNER JOIN '.EXAM_ATTENDANCE.' ea ON ea.id_datesheet = ds.id AND ea.is_publish = 1 AND ea.is_deleted = 0
                                                 INNER JOIN '.CLASS_SUBJECTS.' ss ON ss.subject_id = ea.id_subject
                                                 LEFT JOIN '.EXAM_TYPES.' et ON et.type_id = ds.id_exam
                                                 LEFT JOIN '.DATESHEET_DETAIL.' dd ON dd.id_setup = ds.id AND dd.id_subject = ea.id_subject
                                                 LEFT JOIN '.CAMPUS.' c ON c.campus_id = ea.id_campus'
                            ,'where'        =>  array(
                                                             'ds.is_deleted'    => 0
                                                            ,'ds.id_exam'       => cleanvars($p_id_exam)
                                                            ,'ds.id_class'      => cleanvars($p_id_class)
                                            )
                            ,'search_by'	=>	' AND (ds.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR ds.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].')'
                            ,'order_by'     =>  'dd.dated'
                            ,'return_type'  =>  'all'
    );
    $DATESHEET 	= $dblms->getRows(DATESHEET.' ds', $condition);
    
    // STUDENTS
    $condition = array(
                             'select'       =>  's.std_id, s.std_name, s.std_fathername, s.std_nic, s.std_rollno, s.std_regno, s.std_photo, sc.class_name, ss.section_name'
                            ,'join'       	=>  'LEFT JOIN '.CLASSES.' AS sc ON sc.class_id = s.id_class
                                                 LEFT JOIN '.CLASS_SECTIONS.' AS ss ON ss.section_id = s.id_section'
                            ,'where'        =>  array(
                                                         's.std_status' 	=> 1
                                                        ,'s.is_deleted' 	=> 0
                                                        ,'s.id_class' 	    => cleanvars($p_id_class)
                                                        ,'s.id_section' 	=> cleanvars($p_id_section)
                                            )
                            ,'search_by'	=>	' AND (s.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR s.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].' OR s.id_campus = '.cleanvars($p_id_campus).')'
                            ,'order_by'     =>  's.std_id '.($_POST['order_by']?:'ASC').''
                            ,'return_type'  =>  'all'
    );
    
    if (!empty($_POST['is_hostel'])) {
        $condition['where']['s.is_hostel'] = cleanvars($_POST['is_hostel']);
    }

	$STUDENTS 	= $dblms->getRows(STUDENTS.' AS s', $condition);

    if ($DATESHEET) {
        if ($STUDENTS) {
            echo'
            <table width="100%">
                <tr>
                    <td class="text-center" width="100">
                        <img src="uploads/images/campus/'.$DATESHEET[0]['campus_logo'].'" style="max-height : 100px; margin:5px;">
                    </td>
                    <td class="text-center">
                        <span style="font-size: 30px;">'.$DATESHEET[0]['campus_name'].'</span>
                        <br>
                        <span style="font-size: 20px;">'.moduleName(false).' ('.$DATESHEET[0]['type_name'].')</span>
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
                        <b>Exam Type: <u>'.$DATESHEET[0]['type_name'].'</u></b>
                    </td>
                    <td class="text-center">
                        <b>Session: <u>'.$_SESSION['userlogininfo']['ACA_SESSION_NAME'].'</u></b>
                    </td>
                </tr>
            </table>

            <table class="table">
                <thead>
                    <tr>
                        <th rowspan="2">Sr. No</th>
                        <th rowspan="2">Roll No</th>
                        <th rowspan="2">Reg. No</th>
                        <th rowspan="2">Name</th>
                        <th rowspan="2">Father Name</th>';
                            foreach($DATESHEET AS $key => $val) {
                                echo'<th>'.$val['subject_name'].'</th>';
                            }
                            echo'
                        <th rowspan="2">%age</th>
                        <th rowspan="2">Grade</th>
                        <th rowspan="2">Remarks</th>
                    </tr>
                    <tr>';
                        foreach($DATESHEET AS $key => $val) {
                            echo'<th>'.$val['total_marks'].'</th>';
                        }
                        echo'
                    </tr>
                </thead>
                <tbody>';
                    if ($STUDENTS) {
                        $noStdWithFullMarks = array();
                        $subjectOMarks      = array();
                        $subjectTMarks      = array();
                        foreach ($STUDENTS AS $inkey => $inval) {
                            echo'
                            <tr>
                                <td class="text-center">'.($inkey+1).'</td>
                                <td class="text-center">'.$inval['std_rollno'].'</td>
                                <td class="text-center">'.$inval['std_regno'].'</td>
                                <td class="text-left">'.$inval['std_name'].'</td>
                                <td class="text-left">'.$inval['std_fathername'].'</td>';
                                $subObtain  = 0;
                                $subTotal   = 0;
                                foreach($DATESHEET AS $key => $val) {
                                    $condition = array(
                                                         'select'       =>  'ad.status'
                                                        ,'where'        =>  array(
                                                                                     'ad.id_setup' 	    =>  cleanvars($val['ea_id'])
                                                                                    ,'ad.id_std'        =>  cleanvars($inval['std_id'])
                                                                                )
                                                        ,'return_type'  =>  'single'
                                                    );
                                    $EXAM_ATTENDANCE_DETAIL = $dblms->getRows(EXAM_ATTENDANCE_DETAIL.' ad', $condition);
                                    if ($EXAM_ATTENDANCE_DETAIL['status'] != '2') {
                                        // STUDENTS
                                        $condition = array(
                                                                 'select'       =>  'em.id, emd.total_marks, emd.obtain_marks'
                                                                ,'join'         =>  'INNER JOIN '.EXAM_MARKS_DETAILS.' AS emd ON (em.id = emd.id_setup AND emd.id_std = '.$inval['std_id'].')'
                                                                ,'where'        =>  array(
                                                                                             'em.status' 	    => 1
                                                                                            ,'em.is_publish' 	=> 1
                                                                                            ,'em.is_deleted' 	=> 0
                                                                                            ,'em.id_exam' 	    => cleanvars($p_id_exam)
                                                                                            ,'em.id_class' 	    => cleanvars($p_id_class)
                                                                                            ,'em.id_section' 	=> cleanvars($p_id_section)
                                                                                            ,'em.id_subject' 	=> cleanvars($val['id_subject'])
                                                                                            ,'em.id_session' 	=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                                                )
                                                                ,'search_by'	=>	' AND (em.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR em.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].' OR em.id_campus = '.cleanvars($p_id_campus).')'
                                                                ,'return_type'  =>  'single'
                                        );
                                        $EXAM_MARKS = $dblms->getRows(EXAM_MARKS.' AS em', $condition, $sql);

                                        if ($EXAM_MARKS['obtain_marks'] != NULL) {
                                            $subObtain      += $EXAM_MARKS['obtain_marks'];
                                            if ($EXAM_MARKS['obtain_marks'] == $val['total_marks']) {
                                                $noStdWithFullMarks[$key] += 1;
                                            }
                                            $subjectOMarks[$key] += $EXAM_MARKS['obtain_marks'];
                                            echo'
                                            <td class="text-center">'.$EXAM_MARKS['obtain_marks'].'</td>';
                                        } else {
                                            echo'
                                            <td class="text-center">--</td>';
                                        }
                                    } else {
                                        $subjectOMarks[$key]    += 0;
                                        $subObtain              += 0;
                                        echo'
                                        <th class="text-center">Absent</th>';
                                    }
                                    $subjectTMarks[$key]    += $val['total_marks'];
                                    $subTotal               += $val['total_marks'];
                                }
                                $percentage = number_format((($subObtain/$subTotal)*100),2);
                                
                                echo'
                                <td class="text-center">'.$percentage.'%</td>';
                                // GRADESYSTEM
                                $condition = array(
                                                    'select'       =>  'g.grade_name, g.grade_comment'
                                                    ,'where'        =>  array(
                                                                                'g.grade_status' 	=> 1
                                                                                ,'g.is_deleted' 	=> 0
                                                                    )
                                                    ,'search_by'	=>	' AND (g.id_campus = '.$_SESSION['userlogininfo']['LOGINCAMPUS'].' OR g.id_campus = '.$_SESSION['userlogininfo']['PARENTCAMPUS'].' OR g.id_campus = '.cleanvars($p_id_campus).')
                                                                            AND '.$percentage.' BETWEEN g.grade_lowermark AND g.grade_uppermark'
                                                    ,'return_type'  =>  'single'
                                );
                                $GRADESYSTEM 	= $dblms->getRows(GRADESYSTEM.' AS g', $condition);
                                echo'
                                <td class="text-center">'.$GRADESYSTEM['grade_name'].'</td>
                                <td class="text-center"></td>
                            </tr>';
                        }                    
                        echo'
                        <tr>
                            <th colspan="5" class="text-center">Number Of Students With Full Marks</th>';
                            for ($i=0; $i < count($DATESHEET); $i++) { 
                                echo'
                                <th class="text-center">'.($noStdWithFullMarks[$i]?$noStdWithFullMarks[$i]:0).'</th>';
                            }
                            echo'
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="5" class="text-center">Subject Average</th>';
                            for ($i=0; $i < count($DATESHEET); $i++) { 
                                echo'
                                <th class="text-center">'.number_format(($subjectOMarks[$i]/count($STUDENTS)), 2).'</th>';
                            }
                            echo'
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="5" class="text-center">Subject Percentage</th>';
                            for ($i=0; $i < count($DATESHEET); $i++) {
                                echo'
                                <th class="text-center">'.number_format((($subjectOMarks[$i]/$subjectTMarks[$i])*100), 2).'%</th>';
                            }
                            echo'
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>';
                    }
                    echo'
                </tbody>
            </table>            

            <table width="100%" style="margin-top: 2rem;">
                <tr>
                    <th></th>
                    <th width="140" class="text-left">Class Teacher Sign:</th>
                    <td>_____________</td>
                    <th width="140" class="text-right">Principle Sign:</th>
                    <td>_____________</td>
                    <th></th>
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
            echo'
            <center>
                <h1 style="color: red;">No Student Found</h1>
            </center>';
        }
    } else {
        echo'
        <center>
            <h1 style="color: red;">No DateSheet Found</h1>
        </center>';
    }
    echo'
</body>
</html>';
?>