<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '68', 'view' => '1'))){ 
    echo'
    <!doctype html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>MGS Visit Perfoma</title>
            <style type="text/css">
                body {overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";  }
                @media all {
                    .page-break	{ display: none; }
                }

                @media print {
                    .page-break	{ display: block; page-break-before: always; }
                    @page { 
                        size: A4 landscape;
                    margin: 4mm 4mm 4mm 4mm; 
                    }
                }
                h1 { text-align:left; margin:0; margin-top:0; margin-bottom:0px; font-size:26px; font-weight:700; text-transform:uppercase; }
                .spanh1 { font-size:14px; font-weight:normal; text-transform:none; text-align:right; float:right; margin-top:10px; }
                h2 { text-align:left; margin:0; margin-top:0; margin-bottom:1px; font-size:24px; font-weight:700; text-transform:uppercase; }
                .spanh2 { font-size:20px; font-weight:700; text-transform:none; }
                h3 { text-align:center; margin:0; margin-top:0; margin-bottom:1px; font-size:18px; font-weight:700; text-transform:uppercase; }
                h4 { 
                    text-align:center; margin:0; margin-bottom:1px; font-weight:normal; font-size:15px; font-weight:700; word-spacing:0.1em;  
                }
                td { padding-bottom:4px; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; }
                .line1 { border:1px solid #333; width:100%; margin-top:2px; margin-bottom:5px; }
                .payable { border:2px solid #000; padding:2px; text-align:center; font-size:14px; }

                .paid:after
                {
                    content:"PAID";
                    
                    position:absolute;
                    top:30%;
                    left:20%;
                    z-index:1;
                    font-family:Arial,sans-serif;
                    -webkit-transform: rotate(-5deg); /* Safari */
                    -moz-transform: rotate(-5deg); /* Firefox */
                    -ms-transform: rotate(-5deg); /* IE */
                    -o-transform: rotate(-5deg); /* Opera */
                    transform: rotate(-5deg);
                    font-size:250px;
                    color:green;
                    background:#fff;
                    border:solid 4px yellow;
                    padding:5px;
                    border-radius:5px;
                    zoom:1;
                    filter:alpha(opacity=50);
                    opacity:0.1;
                    -webkit-text-shadow: 0 0 2px #c00;
                    text-shadow: 0 0 2px #c00;
                    box-shadow: 0 0 2px #c00;
                }
            </style>
            <link rel="shortcut icon" href="images/favicon/favicon.ico">
        </head>
        <body>
            <table width="99%" border="0" class="page " cellpadding="10" cellspacing="15" align="center" style="border-collapse:collapse; margin-top:0px;">
                <tr>
                    <td width="341" valign="top">
                        <div class="row" style="text-align: center;">
                            <img src="'.$_SESSION['userlogininfo']['LOGINCAMPUSLOGO'].'" class="img-fluid" width="70" height="70">
                            <h2 style="text-align: center;">'.SCHOOL_NAME.'</h2>
                            <br>
                            <h3 style="text-align: center;">MGS Academic Calender</h3>
                        </div>
                        <div class="line1"></div>
                        <div style="font-size:14px;">
                            <table style="border-collapse:collapse;" width="100%" border="0">
                                <tr>
                                    <td style="text-align:left;">School : '.$_SESSION['userlogininfo']['LOGINCAMPUSNAME'].'</td>
                                </tr>
                            </table>
                        </div>
                        <div style="font-size:12px; margin-top:5px;">
                            <table style="border-collapse:collapse; border:1px solid #666;" cellpadding="2" cellspacing="2" border="1" width="100%">
                                <tr>
                                    <th style="text-align:center; with: 50px;"><b>Sr</b></th>
                                    <th style="text-align:center;">Category </th>
                                    <th style="text-align:center;">Start Date </th>
                                    <th style="text-align:center;">End Date </th>
                                    <th style="text-align:center;">Remarks</th>
                                </tr>';                                
                                $sqllms	= $dblms->querylms("SELECT d.date_start, d.date_end, d.remarks, p.cat_name
                                                            FROM ".ACADEMIC_DETAIL." d
                                                            INNER JOIN ".ACADEMIC_PARTICULARS." p ON p.cat_id = d.id_cat 
                                                            WHERE d.id_setup = '".$_GET['academic_id']."' AND p.is_deleted != '1'
                                                            ORDER BY p.cat_ordering ASC");
                                $srno = 0;
                                while($rowsvalues = mysqli_fetch_array($sqllms)) {
                                $srno++; 
                                echo '
                                <tr>
                                    <td style="text-align:center;">'.$srno.'</td>
                                    <td style="text-align:center;">'.$rowsvalues['cat_name'].'</td>
                                    <td style="text-align:center;">'.date("d, F Y", strtotime($rowsvalues['date_start'])).'</td>
                                    <td style="text-align:center;">'.date("d, F Y", strtotime($rowsvalues['date_end'])).'</td>
                                    <td style="text-align:center;">'.$rowsvalues['remarks'].'</td>
                                </tr>';
                                }
                                echo '
                            </table>
                            <span style="font-size:9px;">issue by: '.cleanvars($_SESSION['userlogininfo']['LOGINNAME']).'</span>
                            <span style="font-size:9px; float:right; margin-top:3px;">issue Date: '.date("m/d/Y").'</span>
                        </div>

                        <div style="clear:both;"></div>
                    </td>
                </tr>
            </table>
        </body>
    </html>
    <script type="text/javascript" language="javascript1.2">
        //Do print the page
        if (typeof(window.print) != "undefined") {
            window.print();
        }
    </script>';
}
?>