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
    if($_POST['id_exam']){
        $array      = explode("|", $_POST['id_exam']);
        $id_exam    = $array[0];
        $type_name  = $array[1];
    }
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
    
    $condition = array(
                            'select'       	=>  'pc.paper_qty, pc.date_handover, pc.date_submission, em.emply_name, c.class_name, cs.section_name, s.subject_name'
                            ,'join'       	=>  'INNER JOIN '.EMPLOYEES.' em ON em.emply_id=pc.id_emply
                                                 INNER JOIN '.CLASSES.' c ON c.class_id = pc.id_class
                                                 INNER JOIN '.CLASS_SECTIONS.' cs ON cs.section_id = pc.id_section
                                                 INNER JOIN '.CLASS_SUBJECTS.' s ON s.subject_id = pc.id_subject'
                            ,'where'        =>  array(
                                                         'pc.is_deleted'			=> 0
                                                        ,'pc.id_exam'				=> cleanvars($id_exam)
                                                        ,'pc.id_campus'  			=> cleanvars($_POST['id_campus'])
                                                        ,'pc.id_session'  			=> cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])
                                                    )
                            ,'return_type'  =>  'all'
    );
    $EXAM_PAPER_CHECKER = $dblms->getRows(EXAM_PAPER_CHECKER.' pc', $condition);

    if($CAMPUS && $EXAM_PAPER_CHECKER){

        $campus_logo = (!empty($CAMPUS['campus_logo']) && file_exists("uploads/images/campus/".$CAMPUS['campus_logo']."") ? "uploads/images/campus/".$CAMPUS['campus_logo']."" : "uploads/logo.png");
        echo'
        <table width="100%">
            <tr>
                <td class="text-center" width="100">
                    <img src="'.$campus_logo.'" style="max-height : 100px; margin:5px;">
                </td>
                <td class="text-center">
                    <span style="font-size: 30px;">'.$CAMPUS['campus_name'].'</span>
                    <br>
                    <span style="font-size: 20px;">Paper Checker List ('.$type_name.')</span>
                </td>
            </tr>
        </table>
        <table class="table text-dark" style="margin-top: 1rem;">
            <thead>	
                <tr>
                    <th class="center" width="40">Sr.</th>
                    <th class="center">Class</th>
                    <th class="center">Section</th>
                    <th class="center">Subject</th>
                    <th class="center">Student Appear</th>
                    <th class="center">Paper Checker</th>
                    <th class="center">Quantity of paper</th>
                    <th class="center">Date of handing over</th>
                    <th class="center">Date of Submission</th>
                    <th class="center">Teacher Sign</th>
                    <th class="center">Cordinator Sign</th>
                    <th class="center">Principal Sign</th>
                </tr>
            </thead>
            <tbody>';
                $srno=0;
                foreach ($EXAM_PAPER_CHECKER as $key => $checker) {
                    $srno++;
                    echo'
                    <tr>
                        <td class="text-center">'.$srno.'</td>
                        <td>'.$checker['class_name'].'</td>
                        <td>'.$checker['section_name'].'</td>
                        <td>'.$checker['subject_name'].'</td>
                        <td class="text-center">'.$checker['paper_qty'].'</td>
                        <td>'.$checker['emply_name'].'</td>
                        <td class="text-center">'.$checker['paper_qty'].'</td>
                        <td class="text-center">'.$checker['date_handover'].'</td>
                        <td class="text-center">'.$checker['date_submission'].'</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>';
                }
                echo'
            </tbody>
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
    }else{
        echo'<h2 class="text-center" style="color: red;"> No Record Found...!</h2>';
    }
    echo'
</body>
</html>';
?>