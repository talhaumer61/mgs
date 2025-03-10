<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

$id_class	= $_GET['id_class'];
$start_date = ((isset($_GET['start_date']) && !empty($_GET['start_date']))? $_GET['start_date'] : '');
$end_date   = ((isset($_GET['end_date']) && !empty($_GET['end_date']))? $_GET['end_date'] : '');

$sql1 = '';
$sql2 = '';
$id_section = '';

if(isset($_GET['id_section']) && !empty($_GET['id_section'])){
    $id_section = $_GET['id_section'];
    $sql1 = 'AND a.id_section = '.$id_section.'';
}

if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
    $sql2 = " AND (a.dated BETWEEN '".date('Y-m-d' , strtotime($start_date))."' AND '".date('Y-m-d' , strtotime($end_date))."') ";
}

echo'
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Studnt Attendance Report Print</title>
        <style type="text/css">
            body {overflow: -moz-scrollbars-vertical; margin:0; font-family: Arial, Helvetica, sans-serif, Calibri, "Calibri Light";  }
            @media all {
                .page-break	{ display: none; }
            }

            @media print {
                .page-break	{ display: block; page-break-before: always; }
                @page {
                    margin: 4mm 4mm 4mm 4mm; 
                    size: A4 landscape;
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
        </style>
        <link rel="shortcut icon" href="images/favicon/favicon.ico">
    </head>
    <body>
        <table width="99%" border="0" class="page " cellpadding="10" cellspacing="15" align="center" style="border-collapse:collapse; margin-top:0px;">
            <tr>
                <td width="341" valign="top">
                    <center style="text-align: center;">
                        <img src="'.$_SESSION['userlogininfo']['LOGINCAMPUSLOGO'].'" class="img-fluid" width="100">
                        <br>
                        <h3><b>'.$_SESSION['userlogininfo']['LOGINCAMPUSNAME'].'</b></h3>
                        <h4>Students Attendance Report</h4>
                        <br>
                    </center>';
					$sqllmsattendance	= $dblms->querylms("SELECT c.class_name, s.section_name, st.std_name, st.std_rollno, st.std_phone, st.std_whatsapp, COUNT(CASE WHEN ad.status = '2' THEN ad.id_std END) as absents
																FROM ".STUDENT_ATTENDANCE." a
                                                                INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." ad ON ad.id_setup = a.id 
                                                                INNER JOIN ".STUDENTS." st ON st.std_id = ad.id_std
																INNER JOIN ".CLASSES." c ON c.class_id = a.id_class
																INNER JOIN ".CLASS_SECTIONS." s ON s.section_id = a.id_section
																WHERE a.status    = '1'
																AND a.id_campus   = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
																AND a.id_session  = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
																AND a.id_class IN (".$id_class.")
																$sql1
																$sql2
                                                                GROUP BY ad.id_std 
																ORDER BY a.dated DESC
															");
                    // ATTENDANCE REPORT
					if(mysqli_num_rows($sqllmsattendance)>0){						
						echo'
						<div style="font-size:12px; margin-top:10px;">
							<table style="border-collapse:collapse; border:1px solid #666; margin-top:10px;" cellpadding="2" border="1" width="100%">
								<thead>
                                    <tr>
                                        <th width="40" style="text-align: center;">Sr.</th>
                                        <th width="50">Roll#</th>
                                        <th width="200">Name</th>
                                        <th style="text-align: center; width:100px;">Section</th>
                                        <th style="text-align: center; width:100px;">Total Absences</th>
                                        <th style="text-align: center; width:100px;">Mobile</th>
                                        <th style="text-align: center;">Remarks</th>
                                    </tr>
								</thead>
								<tbody>';
									$sratt = 0;
									while($value_att = mysqli_fetch_assoc($sqllmsattendance)) { 
										$sratt ++;
										echo'
										<tr>
											<td style="text-align: center;">'.$sratt.'</td>
											<td>'.$value_att['std_rollno'].'</td>
											<td>'.$value_att['std_name'].'</td>
											<td style="text-align: center;">'.$value_att['section_name'].'</td>
											<td style="text-align: center;">'.$value_att['absents'].'</td>
											<td style="text-align: center;">'.$value_att['std_phone'].', '.$value_att['std_whatsapp'].'</td>
											<td style="text-align: center;"></td>
										</tr>';
									}
									echo'
								</tbody>
							</table>
						</div>
						<!--<div class="page-break"></div>-->';
					}
                    else{
                        echo'<h2 style="text-align: center; color: red; margin-top: 50px;">No Record Found</h2>';
                    }
                    echo'
                    <span style="font-size:9px;">issue by: '.cleanvars($_SESSION['userlogininfo']['LOGINNAME']).'</span>
                    <span style="font-size:9px; float:right; margin-top:3px;">Print Date: '.date("m/d/Y").'</span>
                </td>
            </tr>
        </table>
    </body>
    <script type="text/javascript" language="javascript1.2">
        if (typeof(window.print) != "undefined") {
            window.print();
        }
    </script>
</html>';
?>