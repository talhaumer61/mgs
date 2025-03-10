<?php 
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
    
    $sql2 = "";
    $sql3 = "";
    
    $sqllmscampus  = $dblms->querylms("SELECT * 
                                        FROM ".CAMPUS." 
                                        WHERE campus_status = '1' 
                                        AND campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
                                        LIMIT 1");

    $value_campus = mysqli_fetch_array($sqllmscampus);

    $sql2 = (isset($_GET['id_class']) && !empty($_GET['id_class']))? " AND s.id_class = '".$_GET['id_class']."' ": "";
    $sql3 = (!empty($_GET['id']))? " AND s.std_id = '".$_GET['id']."' ": "";

    $sqllms	= $dblms->querylms("SELECT  s.std_id, s.std_status, s.std_name, s.std_fathername, s.std_gender, s.std_nic, s.std_phone, s.id_class, s.id_session, s.std_rollno, s.std_regno, s.std_photo, c.class_name, se.session_name, sec.section_name, cc.campus_logo, cc.campus_name, cc.campus_address, cc.campus_phone
                                    FROM ".STUDENTS." s
                                    INNER JOIN ".CLASSES." c  ON c.class_id = s.id_class
                                    INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
                                    LEFT JOIN ".CLASS_SECTIONS." sec ON sec.section_id = s.id_section
                                    LEFT JOIN ".CAMPUS." cc  ON cc.campus_id = s.id_campus
                                    WHERE s.std_id != '' 
                                    AND s.id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                    $sql2 $sql3
                                    ORDER BY s.std_id DESC
                                ");
    echo '
    <!doctype html>
    <html>
        <head>
            <meta charset="utf-8">  
            <title>Student Cards Print</title>
            <style type="text/css">
                body {
                    overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; 
                    margin: auto;
                }
                @media all {
                    .page-break	{ 
                        display: none; 
                    }
                }
                @media print {
                    .page-break	{ 
                        display: block; page-break-before: always; 
                    }
                    body {
                        -webkit-print-color-adjust: exact;
                    }
                    @page { 
                         
                    }
                    #printPageButton {
                        display: none;
                    }
                }
                body .btn-primary {
                    color: #ffffff;
                    text-shadow: 0 -1px 0 rgb(0 0 0 / 25%);
                    background-color: #cb3f44;
                    border-color: #cb3f44;
                }
                body .btn {
                    white-space: normal;
                }
                .ml-sm {
                    margin-left: 10px !important;
                }                
                .mt-sm {
                    margin-top: 10px !important;
                }               
                .mb-sm {
                    margin-bottom: 10px !important;
                }
                .mb-xs {
                    margin-bottom: 5px !important;
                }
                .mt-xs {
                    margin-top: 5px !important;
                }
                .pull-right {
                    float: right !important;
                }
                .btn {
                    margin-right:20px;
                    margin-top:20px;
                    display: inline-block;
                    padding: 6px 12px;
                    font-size: 14px;
                    font-weight: normal;
                    line-height: 1.42857143;
                    text-align: center;
                    vertical-align: middle;
                    touch-action: manipulation;
                    cursor: pointer;
                    user-select: none;
                    background-image: none;
                    border: 1px solid transparent;
                    border-radius: 4px;
                }
                .table1 { 
                    font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; background-image: url("assets/images/student_card/student_card.jpg");background-repeat: no-repeat; background-size: 100% 100%;
                    margin: 0px auto;
                }
                .table2 {
                    font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; background-image: url("assets/images/student_card/card_back.jpeg");background-repeat: no-repeat; background-size: 100% 100%;
                    }
                .line1 {
                    width:100%; margin-top:2px; margin-bottom:5px; 
                    }
                .center{
                    text-align: center;
                }
                    
                .studentCard { 
                        font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light"; 
                        background-image: url("assets/images/student_card/student_card.jpg");
                        background-repeat: no-repeat; 
                        background-size: 100% 100%;
                        margin: 0px auto;
                        width: 690px;
                        height: 480px;
                }
                .innerTable tr td { 
                    height: 19px;
                }
                .campus_title {
                    width: 200px; 
                    margin-top: 3px;
                }
                .stdImg {
                    width: 134px;
                    height: 134px;
                    border-radius: 50%;
                    margin-top: -6px;
                    margin-left: -4px;
                }
            </style>
            <link rel="shortcut icon" href="images/favicon/favicon.ico">
        </head>
        <body style="color: black;">';
        if (empty($_GET['std_id'])){
            while($rowsvalues = mysqli_fetch_array($sqllms)) {
                $photo = (file_exists('uploads/images/students/'.$rowsvalues['std_photo']))? 'uploads/images/students/'.$rowsvalues['std_photo'].'': 'uploads/default-student.jpg';
                echo'
                <div class="center" style="margin-bottom: 2rem;">
                    <table class="studentCard">
                        <tbody>
                            <tr>
                                <td class="center" width="300" valign="top">
                                    <table class="innerTable" width="300" style="padding: 0 1.2rem;" class="center">
                                        <tr><td class="center" width="300"><img src="'.$_SESSION['userlogininfo']['LOGINCAMPUSLOGO'].'" width="auto" height="25"></td></tr>
                                        <tr>
                                            <td class="center">
                                                <div style="height: 40px;">
                                                    <b>'.$rowsvalues['campus_name'].'</b>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr><td class="center" style="padding: 12px 0 0 0;"><img src="'.$photo.'" class="stdImg"></td></tr>
                                        <tr><td style="padding: 2px 4px 0 0;"><b>'.$rowsvalues['std_name'].'<b></td></tr>
                                        <tr><td style="padding: 0 4px 0 0;">'.$rowsvalues['class_name'].'</h4></td></tr>
                                        <tr><td style="padding: 0 4px 0 0;">'.$rowsvalues['section_name'].'</h4></td></tr>
                                        <tr><td style="padding: 14px 4px 0 0;"><div style="heigth: 33px;">'.$rowsvalues['std_rollno'].'</div></td></tr>
                                        <tr><td style="padding: 2px 0 0 35px;">'.$rowsvalues['std_fathername'].'</td></tr>
                                        <tr><td style="padding: 2px 11px 0 0;">'.$rowsvalues['std_phone'].'</td></tr>
                                        <tr><td style="padding: 2px 0 0 0;">'.$rowsvalues['std_c_address'].'</td></tr>
                                    </table>
                                </td>
                                <td></td>
                                <td class="center" width="300">
                                    <img src="'.$_SESSION['userlogininfo']['LOGINCAMPUSLOGO'].'" style="padding: 50px 0 0 5px;" width="100" height="auto">                          
                                    <table width="300" style="padding: 0 1.2rem;" class="center">
                                        <tr><td style="padding: 2px 4px 0 0;"><b>'.$rowsvalues['campus_name'].'<b></td></tr>
                                        <tr><td style="padding: 0 4px 0 0;"><br><br><br><br><br><br><br>'.$rowsvalues['campus_address'].'</h4></td></tr>
                                        <tr><td style="padding: 0 4px 0 0;">'.$rowsvalues['campus_phone'].'</h4></td></tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>';
            }
            echo '<br><button type="button" id="printPageButton" onClick="window.print();" class="modal-with-move-anim ml-sm mb-xs btn btn-primary btn-xs pull-right">Print</button>';
        } else {
            $rowsvalues = mysqli_fetch_array($sqllms);
                $photo = ($rowsvalues['std_photo'])? 'uploads/images/students/'.$rowsvalues['std_photo'].'': 'uploads/default-student.jpg';
                echo'
                <div class="center" style="margin-bottom: 2rem;">
                    <table class="studentCard">
                        <tbody>
                            <tr>
                                <td class="center" width="300" valign="top">
                                    <table width="300" style="padding: 0 1.2rem;" class="center innerTable">
                                        <tr><td class="center" width="300"><img src="'.$_SESSION['userlogininfo']['LOGINCAMPUSLOGO'].'" width="auto" height="25"></td></tr>
                                        <tr>
                                            <td class="center">
                                                <div style="height: 40px;">
                                                    <b>'.$rowsvalues['campus_name'].'</b>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr><td class="center" style="padding: 12px 4px 0 0;"><img src="'.$photo.'" class="stdImg"></td></tr>
                                        <tr><td style="padding: 2px 4px 0 0;"><b>'.$rowsvalues['std_name'].'<b></td></tr>
                                        <tr><td style="padding: 0 4px 0 0;">'.$rowsvalues['class_name'].'</h4></td></tr>
                                        <tr><td style="padding: 0 4px 0 0;">'.$rowsvalues['section_name'].'</h4></td></tr>
                                        <tr><td style="padding: 14px 4px 0 0;">'.$rowsvalues['std_rollno'].'</td></tr>
                                        <tr><td style="padding: 2px 0 0 35px;">'.$rowsvalues['std_fathername'].'</td></tr>
                                        <tr><td style="padding: 2px 11px 0 0;">'.$rowsvalues['std_phone'].'</td></tr>
                                        <tr><td style="padding: 2px 0 0 0;">'.$rowsvalues['std_c_address'].'</td></tr>
                                    </table>
                                </td>
                                <td></td>
                                <td class="center" width="300">
                                    <img src="'.$_SESSION['userlogininfo']['LOGINCAMPUSLOGO'].'" style="padding: 50px 0 0 5px;" width="100" height="auto">                          
                                    <table width="300" style="padding: 0 1.2rem;" class="center">
                                        <tr><td style="padding: 2px 4px 0 0;"><b>'.$rowsvalues['campus_name'].'<b></td></tr>
                                        <tr><td style="padding: 0 4px 0 0;"><br><br><br><br><br><br><br>'.$rowsvalues['campus_address'].'</h4></td></tr>
                                        <tr><td style="padding: 0 4px 0 0;">'.$rowsvalues['campus_phone'].'</h4></td></tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <script> window.print(); </script>
                ';
        }
            echo '
        </body>
    </html>';
?>
