<?php

use Illuminate\Support\Arr;

    require_once("include/dbsetting/lms_vars_config.php");
    require_once("include/dbsetting/classdbconection.php");
    require_once("include/functions/functions.php");
    $dblms = new dblms();
    require_once("include/functions/login_func.php");
    checkCpanelLMSALogin();
    
    $start_date         = cleanvars($_POST['start_date']);
    $end_date           = cleanvars($_POST['end_date']);
    
    $id_campus          = cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
    $sqCampus	= $dblms->querylms("SELECT campus_id, campus_name, campus_address, campus_phone, campus_email, campus_logo
                                    FROM ".CAMPUS."
                                    WHERE is_deleted = '0'
                                    AND campus_id = $id_campus LIMIT 1");
    $valCampus = mysqli_fetch_array($sqCampus);
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Trial Balance Summary Report | '.TITLE_HEADER.'</title>
        <link rel="shortcut icon" href="assets/images/favicon.png">
        <style>
            body {
                font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";
            }
            .table-border th, td {
                padding: 5px;
                text-align: left;
                border: 1px solid #000000;
            }
            .center {
                text-align: center !important;
            }
            .right {
                text-align: right !important;
            }
            .left {
                text-align: left !important;
            }
            .table-header{
                border-collapse: collapse;
                width:100%;
                margin-bottom: 5px;
            }
            .table-body{
                border-collapse: collapse;
                width:100%;
            }
            .table-filter{
                border-collapse: collapse;
                width:100%;
                margin-bottom: 5px;
            }
            @media print{
                @page {
                    size: A4 landscape
                }
            }
        </style>
    </head>
    <body> ';
        $sqlSubCampus	= $dblms->querylms("SELECT c.campus_id , c.campus_name, cl.level_classes
                                            FROM ".CAMPUS." c
                                            LEFT JOIN ".CAMPUS_LEVELS." cl ON cl.level_id = c.id_level
                                            WHERE c.campus_status = '1'
                                            AND c.is_deleted = '0'
                                            AND ( c.parent_campus = '".$id_campus."' OR c.campus_id = '".$id_campus."' )");
        echo '
        <table class="table-header table-border">
            <thead>
                <tr>
                    <th class="center">
                        <img src="'.((file_exists($_SESSION['userlogininfo']['LOGINCAMPUSLOGO']))? $_SESSION['userlogininfo']['LOGINCAMPUSLOGO']: 'uploads/logo.png').'" height="130px">
                    </th>
                    <td>
                        <center>
                            <h2><u>'.$valCampus['campus_name'].'</u></h2>
                            <h5>'.$valCampus['campus_address'].'</h5>
                        </center>
                        <br>
                        <span style="padding-right: 15%;">Contact No: '.$valCampus['campus_phone'].'</span>
                        <span>Email: '.$valCampus['campus_email'].'</span>
                    </td>
                    <td>
                        <hd>
                            Head Wise Trial Balance Summary<br>
                            Report<br>
                            From: <b>'.date("d-m-Y", strtotime($start_date)).'</b><br>
                            To:  <b>'.date("d-m-Y", strtotime($end_date)).'</b>
                        </hd>
                    </th>
                </tr>
            </thead>
        </table>';
        if (mysqli_num_rows($sqlSubCampus)) {
            echo '
            <table class="table-filter table-border">
                <thead>
                    <tr style="background-color: grey;">
                        <th colspan="5" class="center">Campus Information</th>
                        <th class="center">Credit (Cr.)</th>
                        <th rowspan="2" class="center">Total Credit Amount</th>
                        <th class="center">Debit (Dr.)</th>
                        <th rowspan="2" class="center">Total Debit Amount</th>
                        <th rowspan="2" class="center">Balacce</th>
                    </tr>
                    <tr style="background-color: grey;">
                        <th class="center">Sr#</th>
                        <th class="center">Sub Campus</th>
                        <th class="center">Total Classes</th>
                        <th class="center">Total Sections</th>
                        <th class="center">Total Strength</th>
                        <th class="center">Heads</th>
                        <th class="center">Heads</th>
                    </tr>
                </thead>
                <tbody>';
                    $totalCrHead = 0;
                    $totalDrHead = 0;
                    $totalCr = 0;
                    $totalDr = 0;
                    while($valSubCam = mysqli_fetch_array($sqlSubCampus)) {
                        $srno++;
                        echo'
                        <tr>
                            <td class="center">'.$srno.'</td>
                            <td class="center">'.$valSubCam['campus_name'].'</td>';
                            $CampusClass	            = $dblms->querylms("SELECT COUNT(c.class_id) class_count
                                                                            FROM ".CLASSES." c
                                                                            WHERE c.class_status     = '1'
                                                                            AND c.is_deleted         = '0'
                                                                            AND c.class_id          IN (".$valSubCam['level_classes'].")");
                            $ValCampusClass = mysqli_fetch_array($CampusClass);
                            echo '
                            <td class="right">'.$ValCampusClass['class_count'].'</td>';
                            $ClassSection	            = $dblms->querylms("SELECT COUNT(cs.section_id ) section_count, GROUP_CONCAT(cs.section_id ) sectionComma
                                                                            FROM ".CLASS_SECTIONS." cs
                                                                            WHERE cs.section_status     = '1'
                                                                            AND cs.is_deleted           = '0'
                                                                            AND cs.id_campus            = '".$valSubCam['campus_id']."'
                                                                            AND cs.id_class            IN (".$valSubCam['level_classes'].")");
                            $ValClassSection = mysqli_fetch_array($ClassSection);
                            echo '
                            <td class="right">'.$ValClassSection['section_count'].'</td>';
                            $SqlStd	                    = $dblms->querylms("SELECT COUNT(s.std_id ) std_count
                                                                            FROM ".STUDENTS." s
                                                                            WHERE s.std_status     = '1'
                                                                            AND s.is_deleted           = '0'
                                                                            AND s.id_section          IN (".$ValClassSection['sectionComma'].")
                                                                            AND s.id_campus            = '".$valSubCam['campus_id']."'
                                                                            AND s.id_class            IN (".$valSubCam['level_classes'].")");
                            $ValStd = mysqli_fetch_array($SqlStd);
                            echo '
                            <td class="right">'.$ValStd['std_count'].'</td>
                            <td style="vertical-align: top;">';
                                $sqlDate = " AND (at.dated BETWEEN '".date('Y-m-d',strtotime(cleanvars($start_date)))."' AND '".date('Y-m-d',strtotime(cleanvars($end_date)))."') ";
                                $TotalCreditAmount = 0;
                                $AcHead	                = $dblms->querylms("SELECT ah.head_id, ah.head_name, SUM(at.trans_amount) sumCrHead
                                                                            FROM ".ACCOUNT_HEADS." ah
                                                                            LEFT JOIN ".ACCOUNT_TRANS." at ON at.id_head = ah.head_id
                                                                            WHERE ah.head_status    = '1'
                                                                            AND ah.is_deleted       = '0'
                                                                            AND ah.head_type        = '1'
                                                                            $sqlDate
                                                                            AND ah.id_campus        = '".$valSubCam['campus_id']."'
                                                                            GROUP BY ah.head_id");
                                while($ValAcHead = mysqli_fetch_array($AcHead)) {
                                    echo '
                                    <b>'.ucfirst(strtolower($ValAcHead['head_name'])).'</b>: <span style="float: right;">'.number_format($ValAcHead['sumCrHead']).'</span><br>';
                                    $TotalCreditAmount += $ValAcHead['sumCrHead'];
                                    $totalCrHead += $ValAcHead['sumCrHead'];
                                }
                                $totalCr += $TotalCreditAmount;
                                echo '
                            </td>
                            <td style="vertical-align: top;" class="right">'.number_format($TotalCreditAmount).'</td>
                            <td style="vertical-align: top;">';
                                $TotalDebitAmount = 0;
                                $AcHead	                = $dblms->querylms("SELECT ah.head_id, ah.head_name, SUM(at.trans_amount) sumCrHead
                                                                            FROM ".ACCOUNT_HEADS." ah
                                                                            LEFT JOIN ".ACCOUNT_TRANS." at ON at.id_head = ah.head_id
                                                                            WHERE ah.head_status    = '1'
                                                                            AND ah.is_deleted       = '0'
                                                                            AND ah.head_type        = '2'
                                                                            $sqlDate
                                                                            AND ah.id_campus        = '".$valSubCam['campus_id']."'
                                                                            GROUP BY ah.head_id");
                                while($ValAcHead = mysqli_fetch_array($AcHead)) {
                                    echo '
                                    <b>'.ucfirst(strtolower($ValAcHead['head_name'])).'</b>: <span style="float: right;">'.number_format($ValAcHead['sumCrHead']).'</span><br>';
                                    $TotalDebitAmount += $ValAcHead['sumCrHead'];
                                    $totalDrHead += $ValAcHead['sumCrHead'];
                                }
                                $totalDr += $TotalDebitAmount;
                                echo '
                            </td>
                            <td style="vertical-align: top;" class="right">'.number_format($TotalDebitAmount).'</td>
                            <td style="vertical-align: top;" class="right">'.number_format($TotalCreditAmount - $TotalDebitAmount).'</td>
                        </tr>';
                    }
                    echo'
                    <tr>
                        <th colspan="5" class="right">Total</th>
                        <th class="right">'.number_format($totalCrHead).'</th>
                        <th class="right">'.number_format($totalCr).'</th>
                        <th class="right">'.number_format($totalDrHead).'</th>
                        <th class="right">'.number_format($totalDr).'</th>
                        <th class="right">'.number_format($totalDrHead).'</th>
                    </tr>
                </tbody>
            </table>
            <span>This report is generated by <b>'.$_SESSION['userlogininfo']['LOGINNAME'].'</b> on <b>'.date("d/m/Y").'</b> </span><br><br>
            <span style="padding-right: 30%;"><b>Prepared by</b> __________________________</span>
            <span><b>Principal</b> __________________________</span>
            <script>window.print();</script>';
        } else {
            echo '<h1 class="center" style="font-size: 200%; color: red;"> * * * No Record Found * * * </h1>';
        }
        echo '
    </body>
    </html>';
?>