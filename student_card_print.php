<?php 
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
    
    $sql2 = "";
    $sql3 = "";
    
    $sqllmscampus  = $dblms->querylms("SELECT campus_name, campus_logo
                                        FROM ".CAMPUS." 
                                        WHERE campus_status = '1' 
                                        AND campus_id = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."' 
                                        LIMIT 1");
    $value_campus = mysqli_fetch_array($sqllmscampus);

    $sql2 = (isset($_GET['id_class']) && !empty($_GET['id_class']))? " AND s.id_class = '".$_GET['id_class']."' ": "";
    $sql3 = (!empty($_GET['id']))? " AND s.std_id = '".$_GET['id']."' ": "";

    $sqllms	= $dblms->querylms("SELECT  s.std_name, s.std_fathername, s.std_address, s.std_phone, s.std_rollno, s.std_photo, c.class_name, se.session_name, sec.section_name, cc.campus_logo, cc.campus_name, cc.campus_address, cc.campus_phone
                                    FROM ".STUDENTS." s
                                    INNER JOIN ".CLASSES." c  ON c.class_id = s.id_class
                                    INNER JOIN ".SESSIONS." se ON se.session_id = s.id_session
                                    LEFT JOIN ".CLASS_SECTIONS." sec ON sec.section_id = s.id_section
                                    LEFT JOIN ".CAMPUS." cc  ON cc.campus_id = s.id_campus
                                    WHERE s.std_id != '' 
                                    AND s.is_deleted = '0'
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
                }
                .CardDiv {
                    background-image: url("assets/images/student_card/'.(($_GET['flag'] == 2)? 'front.jpg': 'back.jpg').'");
                    background-repeat: no-repeat; 
                    background-size: 100% 100%;
                    margin: 5x; 
                    width: 210px;
                    height: 321px;
                    text-align: center; 
                    float: left;
                }
                .break {
                    page-break-before: always;
                }
                .td-width {
                    padding: 0px;
                    width: 54px;
                }
                .td-mid-width {
                    padding: 0px;
                    width: 106px;
                }
            </style>
            <link rel="shortcut icon" href="images/favicon/favicon.ico">
        </head>
    <body>';
        $i = 0;
        while($rowsvalues = mysqli_fetch_array($sqllms)):
            $i++;
            if(!empty($value_campus['campus_logo']) && file_exists("uploads/images/campus/".$value_campus['campus_logo']) ){
                $campusPhoto = "uploads/images/campus/".$value_campus['campus_logo']." ";
            }else{
                $campusPhoto = "uploads/logo.png";
            }
            $emp     = '<span style="color: #fff;">_</span>';
            $StudentPhoto   = ((file_exists("uploads/images/students/".$rowsvalues['std_photo']) && !empty($rowsvalues['std_photo'])) ? 'uploads/images/students/'.$rowsvalues['std_photo']: 'uploads/default-student.jpg');
            echo '
            <div class="CardDiv '.(($i == 10)? 'break':'').'">
                <div>
                    <table>
                        <thead>
                            <tr>
                                <td class="td-width"></td>
                                <td class="td-mid-width">';
                                    if ($_GET['flag'] == 2)  {
                                        echo '
                                        <table>
                                            <tr>
                                                <td><img src="'.$campusPhoto.'" style="height: 20px;"></td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 6px; font-weight: bold;">'.((!empty($value_campus['campus_name']))? $value_campus['campus_name']: $emp).'</td>
                                            </tr>
                                            <tr>
                                                <td><img src="'.$StudentPhoto.'" style="margin-top: 9px; margin-left: 1px;  width: 90px; height: 88px; border-radius: 50px;"></td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 10px; font-weight: bold;">'.((!empty($rowsvalues['std_name']))? $rowsvalues['std_name']: $emp).'</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 8px; font-weight: bold;">'.((!empty($rowsvalues['class_name']))? $rowsvalues['class_name']: $emp).'</td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 6px; font-weight: bold;">'.((!empty($rowsvalues['section_name']))? $rowsvalues['section_name']: $emp).'</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 16px; font-size: 7px;">'.((!empty($rowsvalues['std_rollno']))? $rowsvalues['std_rollno']: $emp).'</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 6px; font-size: 7px;">'.((!empty($rowsvalues['std_fathername']))? $rowsvalues['std_fathername']: $emp).'</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 4px; font-size: 7px;">'.((!empty($rowsvalues['std_phone']))? $rowsvalues['std_phone']: $emp).'</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 5px; font-size: 7px;">'.((!empty($rowsvalues['std_address']))? $rowsvalues['std_address']: $emp).'</td>
                                            </tr>
                                        </table>';
                                    } else {
                                        echo '
                                        <table>
                                            <tr>
                                                <td><img src="'.$campusPhoto.'" style="margin-top: 70px; width: 80px; height: 80px;"></td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 10px; font-weight: bold;">'.((!empty($value_campus['campus_name']))? $value_campus['campus_name']: $emp).'</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 55px; font-size: 8px; font-weight: bold;"">'.((!empty($rowsvalues['campus_address']))? $rowsvalues['campus_address']: $emp).'</td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 2px; font-size: 7px; font-weight: bold;"">'.((!empty($rowsvalues['campus_phone']))? $rowsvalues['campus_phone']: $emp).'</td>
                                            </tr>
                                        </table>';
                                    }
                                    echo '
                                </td>
                                <td class="td-width"></td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>';
        endwhile;
            echo '  
        <script>window.print();</script>      
    </body>
    </html>
';
?>