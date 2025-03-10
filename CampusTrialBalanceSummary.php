<?php

use Illuminate\Support\Arr;

    require_once("include/dbsetting/lms_vars_config.php");
    require_once("include/dbsetting/classdbconection.php");
    require_once("include/functions/functions.php");
    $dblms = new dblms();
    require_once("include/functions/login_func.php");
    checkCpanelLMSALogin();
    
    $id_campus          = (!empty($_POST['id_campus']))? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
    $start_date         = cleanvars($_POST['start_date']);
    $end_date           = cleanvars($_POST['end_date']);

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
    <body> 
        <table class="table-header table-border">
            <thead>
                <tr>
                    <th class="center" style="vertical-align: top;">
                        <img src="'.((file_exists($_SESSION['userlogininfo']['LOGINCAMPUSLOGO']))? $_SESSION['userlogininfo']['LOGINCAMPUSLOGO']: 'uploads/logo.png').'" height="130px">
                    </th>
                    <td style="vertical-align: top;">
                        <center>
                            <h2><u>'.$valCampus['campus_name'].'</u></h2>
                            <h5>'.$valCampus['campus_address'].'</h5>
                        </center>
                        <br>
                        <span style="padding-right: 15%;">Contact No: '.$valCampus['campus_phone'].'</span>
                        <span>Email: '.$valCampus['campus_email'].'</span>
                    </td>
                    <td style="vertical-align: top;">
                        <b>Detailed Trial Balance Sheet</b><br>
                        From: <b>'.date("d-m-Y", strtotime($start_date)).'</b><br>
                        To:  <b>'.date("d-m-Y", strtotime($end_date)).'</b>
                    </td>
                </tr>
            </thead>
        </table>
        <table class="table-header table-border">
            <thead>
                <tr>
                    <td>Campus Name: <b>'.$valCampus['campus_name'].'</b></td>
                </tr>
            </thead>
        </table>';
        $sqlDate = " AND (at.dated BETWEEN '".date('Y-m-d',strtotime(cleanvars($start_date)))."' AND '".date('Y-m-d',strtotime(cleanvars($end_date)))."') ";
        $sqlAt	= $dblms->querylms("SELECT at.trans_id, at.trans_title, at.voucher_no, at.id_head, at.dated
                                    FROM ".ACCOUNT_TRANS." at
                                    WHERE at.trans_status       = '1'
                                    AND at.is_deleted           = '0'
                                    $sqlDate
                                    AND at.id_campus            = '$id_campus'");
        $sqlAcHead = array ( 
							'select' 		=>	'head_id, head_name'
							,'where' 		=>	array( 
														 'is_deleted'	=> '0'
														,'head_status'	=> '1'
														,'id_campus'	=> $id_campus
													)
							,'return_type' 	=> 'all' 
						); 
	    if (mysqli_num_rows($sqlAt)) {
            echo '
            <table class="table-filter table-border">
                <thead>
                    <tr style="background-color: grey;">
                        <th colspan="4" class="center">Information</th>';
                        $sqlAcHead['where']['head_type'] = '1';
                        $rowAcHead  = $dblms->getRows(ACCOUNT_HEADS, $sqlAcHead);
                        echo '
                        <th colspan="'.count($rowAcHead).'" class="center">Credit Heads (Cr.)</th>
                        <th rowspan="2" class="center">Total Credit Amount</th>';
                        $sqlAcHead['where']['head_type'] = '2';
                        $rowAcHead  = $dblms->getRows(ACCOUNT_HEADS, $sqlAcHead);
                        echo '
                        <th colspan="'.count($rowAcHead).'" class="center">Debit Heads (Dr.)</th>
                        <th rowspan="2" class="center">Total Debit Amount</th>
                        <th rowspan="2" class="center">Balacce</th>
                    </tr>
                    <tr style="background-color: grey;">
                        <th class="center">Sr#</th>
                        <th class="center">Date</th>
                        <th class="center">Detail</th>
                        <th class="center">Ref No.</th>';
                        $sqlAcHead['where']['head_type'] = '1';
                        $rowAcHead  = $dblms->getRows(ACCOUNT_HEADS, $sqlAcHead);
                        foreach($rowAcHead as $val):
                            echo '<th class="center">'.get_firstLetterCap($val['head_name']).'</th>';
                        endforeach;
                        $sqlAcHead['where']['head_type'] = '2';
                        $rowAcHead  = $dblms->getRows(ACCOUNT_HEADS, $sqlAcHead);
                        foreach($rowAcHead as $val):
                            echo '<th class="center">'.get_firstLetterCap($val['head_name']).'</th>';
                        endforeach;
                        echo '
                    </tr>
                </thead>
                <tbody>';
                    $totalCrHead = 0;
                    $totalDrHead = 0;
                    $totalCr = array();
                    $totalDr = array();
                    $srno = 0;
                    $totalBalance = 0;
                    while($valAT = mysqli_fetch_array($sqlAt)) {
                        $srno++;
                        echo'
                        <tr>
                            <td class="center">'.$srno.'</td>
                            <td class="center">'.date("M d, Y",strtotime($valAT['dated'])).'</td>
                            <td class="center">'.$valAT['trans_title'].'</td>
                            <td class="center">'.$valAT['voucher_no'].'</td>';
                            $sqlAcHead['where']['head_type'] = '1';
                            $rowAcHead  = $dblms->getRows(ACCOUNT_HEADS, $sqlAcHead);
                            $AcCrHeadSmallTotal = 0;
                            foreach($rowAcHead as $key => $val):
                                if ($valAT['id_head'] == $val['head_id']) {
                                    $sqlAtGetter	= $dblms->querylms("SELECT at.trans_amount
                                                                        FROM ".ACCOUNT_TRANS." at
                                                                        WHERE at.trans_status       = '1'
                                                                        AND at.is_deleted           = '0'
                                                                        AND at.id_campus            = '$id_campus'
                                                                        AND (at.id_head = '".$valAT['id_head']."' AND at.id_head = '".$val['head_id']."')
                                                                        LIMIT 1");
                                    $valAtGetter = mysqli_fetch_array($sqlAtGetter);
                                    echo '<th class="right">'.number_format($valAtGetter['trans_amount']).'</th>';
                                    $AcCrHeadSmallTotal += $valAtGetter['trans_amount'];
                                    $totalCr[$key] += $AcCrHeadSmallTotal;
                                } else {
                                    echo '<th class="right">0</th>';
                                    $totalCr[$key] += 0;
                                }
                            endforeach;
                            echo '
                            <th class="right"><b>'.number_format($AcCrHeadSmallTotal).'</b></th>';
                            $totalCrHead += $AcCrHeadSmallTotal;
                            $sqlAcHead['where']['head_type'] = '2';
                            $rowAcHead  = $dblms->getRows(ACCOUNT_HEADS, $sqlAcHead);
                            $AcDrHeadSmallTotal = 0;
                            foreach($rowAcHead as $key => $val):
                                if ($valAT['id_head'] == $val['head_id']) {
                                    $sqlAtGetter	= $dblms->querylms("SELECT at.trans_amount
                                                                        FROM ".ACCOUNT_TRANS." at
                                                                        WHERE at.trans_status       = '1'
                                                                        AND at.is_deleted           = '0'
                                                                        AND at.id_campus            = '$id_campus'
                                                                        AND (at.id_head = '".$valAT['id_head']."' AND at.id_head = '".$val['head_id']."')
                                                                        LIMIT 1");
                                    $valAtGetter = mysqli_fetch_array($sqlAtGetter);
                                    echo '<th class="right">'.number_format($valAtGetter['trans_amount']).'</th>';
                                    $AcDrHeadSmallTotal += $valAtGetter['trans_amount'];
                                    $totalDr[$key] += $AcDrHeadSmallTotal;
                                } else {
                                    echo '<th class="right">0</th>';
                                    $totalDr[$key] += 0;
                                }
                                
                            endforeach;
                            echo '
                            <th class="right"><b>'.number_format($AcDrHeadSmallTotal).'</b></th>';
                            $totalDrHead += $AcDrHeadSmallTotal;
                            echo '
                            <th class="right"><b>'.number_format($AcCrHeadSmallTotal - $AcDrHeadSmallTotal).'</b></th>
                        </tr>';
                        $totalBalance += ($AcCrHeadSmallTotal - $AcDrHeadSmallTotal);
                    }
                    echo'
                    <tr style="background-color: grey;">
                        <th colspan="4" class="right">Total</th>';
                        foreach($totalCr as $val):
                            echo '
                            <th class="right">'.number_format($val).'</th>';
                        endforeach;
                        echo '
                        <th class="right">'.number_format($totalCrHead).'</th>';
                        foreach($totalDr as $val):
                            echo '
                            <th class="right">'.number_format($val).'</th>';
                        endforeach;
                        echo '
                        <th class="right">'.number_format($totalDrHead).'</th>
                        <th class="right">'.number_format($totalBalance).'</th>
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