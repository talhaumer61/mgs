<?php

use Illuminate\Support\Arr;

    require_once("include/dbsetting/lms_vars_config.php");
    require_once("include/dbsetting/classdbconection.php");
    require_once("include/functions/functions.php");
    $dblms = new dblms();
    require_once("include/functions/login_func.php");
    checkCpanelLMSALogin();

    if (isset($_POST['view_report'])) {
		$tmp1 			= array();
		$tmp2 			= array();
		$id_head 		= '';
		$head_name 		= '';

		foreach($_POST['id_head'] as $key => $val):
			$array = explode('|',$val);
			array_push($tmp1, $array[0]);
			array_push($tmp2, $array[1]);
		endforeach;
        
		$id_head 		= implode(',',$tmp1);
		$head_name 		= implode(',',$tmp2);

		$paid_status 	= (!empty($_POST['paid_status']))? $_POST['paid_status']	: '';
		$start_date 	= (!empty($_POST['start_date']))? $_POST['start_date']	: '';
		$end_date 		= (!empty($_POST['end_date']))? $_POST['end_date']		: '';

		$array 			= explode('|', $_POST['id_class']);
		$id_class 		= (!empty($array[0]))? $array[0]: '';
		$class_name 	= (!empty($array[1]))? $array[1]: '';

		$array 			= explode('|', $_POST['id_section']);
		$id_section 	= (!empty($array[0]))? $array[0]: '';
		$section_name 	= (!empty($array[1]))? $array[1]: '';
	}
    
    $start_date         = $_POST['start_date'];
    $end_date           = $_POST['end_date'];
    $is_hostel          = (isset($_POST['is_hostel']))? '1': '0,2';
    
    // CAMPUS CHECK
    if(!empty($_POST['id_campus'])){
        $id_campus  =   $_POST['id_campus'];
    }else{
        $id_campus  =   $_SESSION['userlogininfo']['LOGINCAMPUS'];
    }

    
    $sqlDate = " AND (f.issue_date BETWEEN '".date('Y-m-d' , strtotime(cleanvars($start_date)))."' AND '".date('Y-m-d' , strtotime(cleanvars($end_date)))."') ";
    $sqlClass 		= (!empty($sqlClass))		? ' AND s.id_class 		= '.$id_class.''	: '';
    $sqlSection 	= (!empty($sqlSection))		? ' AND s.id_section 	= '.$id_section.''	: '';
    // Student Check
    if(!empty($_POST['id_std'])){
        $array  = explode("|",$_POST['id_std']);
        $sqlStd = " AND s.std_id = '".$array[0]."'";
    }else{
        $sqlStd = '';
    }

    // CAMPUS INFO
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
        <title>Fee Setup Increasing Report | '.TITLE_HEADER.'</title>
        <link rel="shortcut icon" href="assets/images/favicon.png">
        <style>
            .table-border th, td {
                font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";
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
        // STUDENT FEE
        $sqlLMSStd	= $dblms->querylms("SELECT s.std_id, s.std_regno, s.std_rollno, s.std_name, s.std_fathername, s.std_phone, s.std_whatsapp,f.challan_no, f.total_amount, f.issue_date, f.due_date, f.id
                                        FROM ".STUDENTS." s
                                        INNER JOIN ".FEES." f ON f.id_std 	= s.std_id
                                        WHERE s.std_status 				= '1'
                                        AND s.is_deleted				= '0'			
                                        AND f.is_deleted				= '0'	
                                        AND s.is_hostel                IN (".$is_hostel.")						
                                        AND s.id_campus 				= '$id_campus'
                                        $sqlClass $sqlSection $sqlDate
                                        $sqlStd 
                                        ORDER BY s.std_id ASC");
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
                            Head Wise Collection & Arrear<br>
                            Report<br>
                            From: <b>'.date("d-m-Y", strtotime($start_date)).'</b><br>
                            To:  <b>'.date("d-m-Y", strtotime($end_date)).'</b>
                        </hd>
                    </th>
                </tr>
            </thead>
        </table>';
        if (mysqli_num_rows($sqlLMSStd)) {
            if(!empty($class_name) && !empty($section_name)){
                echo'                
                <table class="table-filter table-border">
                    <thead>
                        <tr>
                            <td>Class:              <b>'.$class_name.'</b></td>
                            <td>Section:            <b>'.$section_name.'</b></td>
                            <td>Total Students:     <b></b></td>
                            <td>Class In Charge:    <b></b></td>
                        </tr>
                    </thead>
                </table>';
            }
            echo '
            <table class="table-filter table-border">
                <thead>
                    <tr>
                        <th colspan="10" class="center">Student Information</th>
                        <th rowspan="2" class="center">Total Challan Amount</th>
                        <th colspan="'.count(explode(',',$head_name)).'" class="center">Collected Income</th>
                        <th rowspan="2" class="center">Total Collected Amount</th>
                        <th colspan="'.count(explode(',',$head_name)).'" class="center">Arrears</th>
                    </tr>
                    <tr>
                        <th class="center">Sr#</th>
                        <th class="center">Reg No</th>
                        <th class="center">Roll No</th>
                        <th>Student Name</th>
                        <th>Father Name</th>
                        <th class="center">Contact Number</th>
                        <th class="center">WhatsApp Number</th>
                        <th class="center">Challan</th>
                        <th class="center">Date Of Issue</th>
                        <th class="center">Due Date</th>';
                        foreach(explode(',',$head_name) as $valHead):
                            echo '<th class="center">'.$valHead.'</th>';
                        endforeach;
                        foreach(explode(',',$head_name) as $valHead):
                            echo '<th class="center">'.$valHead.'</th>';
                        endforeach;
                        echo '
                    </tr>
                </thead>
                <tbody>';
                    $srno = 0;
                    $TotalChallanAmount	= 0;
                    $TotalCollectedAmount = 0;
                    $headCollectedArray	= array();
                    $headPandingArray	= array();
                    while($value_fee = mysqli_fetch_array($sqlLMSStd)) {
                        $srno++;
                        echo'
                        <tr>
                            <td class="center">'.$srno.'</td>
                            <td class="center">'.$value_fee['std_regno'].'</td>
                            <td class="center">'.$value_fee['std_rollno'].'</td>
                            <td>'.$value_fee['std_name'].'</td>
                            <td>'.$value_fee['std_fathername'].'</td>
                            <td class="center">'.$value_fee['std_phone'].'</td>
                            <td class="center">'.$value_fee['std_whatsapp'].'</td>
                            <td class="center">'.$value_fee['challan_no'].'</td>
                            <td class="center">'.$value_fee['issue_date'].'</td>
                            <td class="center">'.$value_fee['due_date'].'</td>';
                            $AllFeeChallanTotal = 0;
                            echo '<td class="right">'.number_format($value_fee['total_amount']).'</td>';

                            // COLLECTED INCOMES
                            $TotalChallanAmount += $value_fee['total_amount'];
                            $feeHeadCollectedAmount = 0;
                            foreach(explode(',',$id_head) as $key => $idHead):
                                $toChallanPaid = $dblms->querylms("SELECT SUM(fp.paid_amount) total_head_sum
                                                                    FROM ".FEE_PARTICULARSPAID." fp
                                                                    WHERE fp.id_fee		=  ".$value_fee['id']."
                                                                    AND fp.id_cat       = '".$idHead."'");
                                $ValtoChallanPaid = mysqli_fetch_array($toChallanPaid);
                                echo ' <td class="right">'.number_format($ValtoChallanPaid['total_head_sum']).'</td>';
                                $feeHeadCollectedAmount += $ValtoChallanPaid['total_head_sum'];
                                $headCollectedArray[$key] += $feeHeadCollectedAmount;
                            endforeach;
                            echo '
                            <td class="right">'.number_format($feeHeadCollectedAmount).'</td>';

                            // ARREARS INCOMES
                            $TotalCollectedAmount += $feeHeadCollectedAmount;
                            $feeHeadPending = 0;
                            $grandHeadPending = 0;
                            foreach(explode(',',$id_head) as $key => $idHead):
                                    $sqlnarPart  = $dblms->querylms("SELECT f.scholarship, f.concession, fp.amount, SUM(fpp.paid_amount) paid_amount
                                                                        FROM ".FEES." f
                                                                        INNER JOIN ".FEE_PARTICULARS." fp ON fp.id_fee = f.id
                                                                        LEFT JOIN ".FEE_PARTICULARSPAID." fpp ON fpp.id_fee = fp.id_fee AND fpp.id_cat =  fp.id_cat
                                                                        WHERE f.id          = ".$value_fee['id']."
                                                                        AND fp.id_cat       = '".cleanvars($idHead)."'
                                                                        AND f.id_std        = '".cleanvars($value_fee['std_id'])."'
                                                                        LIMIT 1");

                                    $arrearsChallan = mysqli_fetch_array($sqlnarPart);

                                    // SCHOLARSHIP SEPERATE ID, AMOUNT
                                    $slrArray 			= explode(',',$arrearsChallan['scholarship']);
                                    $scholarship 		= $slrArray[0]; 
                                    $id_scholarship 	= $slrArray[1];
                                    // CONCESSION SEPERATE ID, AMOUNT
                                    $conArray 			= explode(',',$arrearsChallan['concession']);
                                    $concession 		= $conArray[0];
                                    $id_concession 		= $conArray[1];

                                    if ($arrearsChallan['amount']) {
                                        if ($id_scholarship == $arrearsChallan['id_cat'] || $id_scholarship == 0) {
                                            $arrearsChallan['amount'] -= $scholarship;
                                        }
                                        if ($id_concession == $arrearsChallan['id_cat'] || $id_concession == 0) {
                                            $arrearsChallan['amount'] -= $concession;
                                        }
                                    }                                
                                    $feeHeadPending = $arrearsChallan['amount'] - $arrearsChallan['paid_amount'];
                                $grandHeadPending += $feeHeadPending; 
                                echo '
                                <td class="right">'.number_format($feeHeadPending).'</td>';
                                $headPandingArray[$key] += $feeHeadPending;
                            endforeach;
                            echo '
                        </tr>';
                    }
                    echo'
                    </tr>
                        <th class="right" colspan="10">Total</th>
                        <th class="right">'.number_format($TotalChallanAmount).'</th>';
                        foreach($headCollectedArray as $val):
                            echo '
                            <th class="right">'.number_format($val).'</th>';
                        endforeach;
                        echo '
                        <th class="right">'.number_format($TotalCollectedAmount).'</th>';
                        foreach($headPandingArray as $val):
                            echo '
                            <th class="right">'.number_format($val).'</th>';
                        endforeach;
                        echo '
                    <tr>
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