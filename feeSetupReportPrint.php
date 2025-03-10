<?php
    require_once("include/dbsetting/lms_vars_config.php");
    require_once("include/dbsetting/classdbconection.php");
    require_once("include/functions/functions.php");
    $dblms = new dblms();
    require_once("include/functions/login_func.php");
    checkCpanelLMSALogin();
    $prv_session        = cleanvars($_GET['prv_session']);
    $cur_session        = cleanvars($_GET['cur_session']);
    $id_class           = cleanvars($_GET['id_class']);
    $id_section         = cleanvars($_GET['id_section']);
    $id_campus          = cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
    $class_name         = cleanvars($_GET['class_name']);
    $section_name       = cleanvars($_GET['section_name']);
    $session_name       = cleanvars($_GET['session_name']);
    $session_startdate = cleanvars($_GET['session_startdate']);

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
                text-align: right;
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
                    <th>
                        <h3>
                            Class Wise Student Fee Structure<br>
                            Increase Report
                        </h3>
                    </th>
                </tr>
            </thead>
        </table>
        <table class="table-filter table-border">
            <thead>
                <tr>
                    <td>Class: <b>'.$class_name.'</b></td>
                    <td>Section: <b>'.$section_name.'</b></td>
                    <td>For Session: <b>'.$session_name.'</b></td>
                    <td>Start From(Date): <b>'.$session_startdate.'</b></td>
                </tr>
            </thead>
        </table>
        <table class="table-body table-border">
            <thead>
                <tr>
                    <th class="center" width="50">Sr.</th>
                    <th>Categories</th>
                    <th>Previous Amount (Session)</th>
                    <th>Increased Amount(%age/Lump sum)</th>
                    <th>New Amount(Session)</th>
                    <th class="center">Type</th>
                </tr>
            </thead>
            <tbody>';
                $sqllmsHead	            = $dblms->querylms("SELECT fc.cat_id, fc.cat_name
                                                            FROM ".FEE_CATEGORY." fc
                                                            WHERE fc.is_deleted     = '0'
                                                            AND fc.cat_status       = '1'
                                                            ORDER BY fc.cat_id");
                $sr = 0;
                while($rowHead = mysqli_fetch_array($sqllmsHead)):
                    $sr++;
                    echo '
                    <tr>
                        <td width="50" class="center">'.$sr.'</td>
                        <td>'.$rowHead['cat_name'].'</td>';
                        $sqllmsFee      = $dblms->querylms("SELECT fs.amount
                                                            FROM ".FEESETUP." f				   
                                                            INNER JOIN ".FEESETUPDETAIL." fs ON f.id = fs.id_setup
                                                            WHERE f.is_deleted  = '0'
                                                            AND f.id_class 		= '".$id_class."'
                                                            AND f.id_section 	= '".$id_section."'
                                                            AND f.id_session    = '".$prv_session."'
                                                            AND f.id_campus 	= '".$id_campus."' 
                                                            AND fs.id_cat 	    = '".$rowHead['cat_id']."' 
                                                            ORDER BY f.id DESC LIMIT 1");
                        $rowFee         = mysqli_fetch_array($sqllmsFee);
                        echo '
                        <td class="right">'.number_format($rowFee['amount']).'</td>';
                        $sqllmsFeeSet      = $dblms->querylms("SELECT fs.amount, fs.type
                                                            FROM ".FEESETUP." f				   
                                                            INNER JOIN ".FEESETUPDETAIL." fs ON f.id = fs.id_setup
                                                            WHERE f.is_deleted  = '0'
                                                            AND f.id_class 		= '".$id_class."'
                                                            AND f.id_section 	= '".$id_section."'
                                                            AND f.id_session    = '".$cur_session."'
                                                            AND f.id_campus 	= '".$id_campus."' 
                                                            AND fs.id_cat 	    = '".$rowHead['cat_id']."' 
                                                            ORDER BY f.id DESC LIMIT 1");
                        $rowFeeSet         = mysqli_fetch_array($sqllmsFeeSet);
                        echo '
                        <td class="right">'.number_format($rowFeeSet['amount'] - $rowFee['amount']).'</td>
                        <td class="right">'.number_format($rowFeeSet['amount']).'</td>
                        <td class="center">'.$rowFeeSet['type'].'</td>
                    </tr>';
                endwhile;
                echo '
            </tbody>
        </table>
        <span>This report is generated by <b>'.$_SESSION['userlogininfo']['LOGINNAME'].'</b> on <b>'.date("d/m/Y").'</b> </span><br><br>
        <span style="padding-right: 30%;"><b>Prepared by</b> __________________________</span>
        <span><b>Principal</b> __________________________</span>
        <script>window.print();</script>
    </body>
    </html>';
?>