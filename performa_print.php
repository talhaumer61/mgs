<?php 
	require_once("include/dbsetting/lms_vars_config.php");
	require_once("include/dbsetting/classdbconection.php");
	require_once("include/functions/functions.php");
	$dblms = new dblms();
	require_once("include/functions/login_func.php");
	checkCpanelLMSALogin();
//------------------------------------------------
$sqllmscampus  = $dblms->querylms("SELECT p.visit_month, p.date_added, c.campus_name, e.emply_name
                                        FROM ".CAMPUS_PERFORMA." p
                                        INNER JOIN ".CAMPUS." c on c.campus_id = p.id_campus 
                                        LEFT JOIN ".CAMPUS_BIOGRAPHY." b ON b.id_campus = c.campus_id
                                        LEFT JOIN ".EMPLOYEES." e on e.emply_id = b.id_ad
                                        WHERE c.campus_status = '1' AND p.id = '".cleanvars($_GET['id'])."' 
                                        ORDER BY b.bio_id DESC LIMIT 1");
$value_campus = mysqli_fetch_array($sqllmscampus);
//------------------------------------------------
echo '
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

<body>';
    if(isset($_GET['id'])) {
        echo '
        <table width="99%" border="0" class="page " cellpadding="10" cellspacing="15" align="center" style="border-collapse:collapse; margin-top:0px;">
            <tr>
                <td width="341" valign="top">
                    <div class="row">
                        <img src="'.$_SESSION['userlogininfo']['LOGINCAMPUSLOGO'].'" class="img-fluid" style="width: 50px; height: 50px;">
                        <h2 style="text-align: center;">MGS Daily School Visit Report</h2>
                    </div>
                    <div class="line1"></div>
                        <div style="font-size:14px;">
                            <table style="border-collapse:collapse;" width="100%" border="0">
                                <tr style="text-align: left;">
                                    <td style="text-align:left; width: 80px;">ADE / DDE :</td>
                                    <td style="text-align:left;">'.$value_campus['emply_name'].'</td>
                                    <td style="text-align:right;">For Month:</td>
                                    <td style="text-align:right; text-decoration:underline;  width: 140px;">'.get_monthtypes($value_campus['visit_month']).'</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;">School :</td>
                                    <td style="text-align:left;">'.$value_campus['campus_name'].'</td>
                                    <td style="text-align:right;">Entry Date & Time:</td>
                                    <td style=" text-align:right; text-decoration:underline;">'.$dated = date('D d M Y H:i A' , strtotime($value_campus['date_added'])).'</td>	
                                </tr>
                            </table>
                        </div>
                    <div style="font-size:12px; margin-top:5px;">
                        <table style="border-collapse:collapse; border:1px solid #666;" cellpadding="2" cellspacing="2" border="1" width="100%">
                            <tr>
                                <td style="text-align:center; with: 50px;"><b>Sr</b></td>
                                <td style="text-align:center;"><b>Questions</b></td>
                                <td style="text-align:center;"><b>Marks</b></td>
                                <td style="text-align:center;"><b>Results</b></td>
                            </tr>';
                            
                            $totalMarks = 0;
                            $totalObt = 0;

                            // Question Cats                        
                            $sqllmscats	= $dblms->querylms("SELECT cat_name,cat_id
                                                                FROM ".FACILITY_CATS."	 
                                                                WHERE cat_status = '1' 
                                                                ORDER BY cat_ordering ASC");
                            if(mysqli_num_rows($sqllmscats) >0) {
                                $catSr = 0;
                                while($rowCats = mysqli_fetch_array($sqllmscats)) {
                                    $catSr++;
                                    
                                    echo '
                                    <tr>
                                        <td style="text-align:center;"><b>'.$catSr.'</b></td>
                                        <td colspan="2"><b>'.$rowCats['cat_name'].'</b></td>
                                        <td style="text-align:center;">Category Result</td>
                                    </tr>';
                                        
                                    // Question & Obtained Marks
                                    $sqllmsQuest = $dblms->querylms("SELECT  q.question_name, d.rating
                                                                            FROM ".FACILITY_QESTIONS." q
                                                                            INNER JOIN 	".CAMPUS_PERFORMA_DET." d ON d.id_question = q.question_id 
                                                                            WHERE q.question_status = '1'
                                                                            AND q.id_cat = '".cleanvars($rowCats['cat_id'])."' 
                                                                            AND d.id_setup = '".cleanvars($_GET['id'])."' 
                                                                            AND d.is_applicable = '1'
                                                                            ORDER BY q.question_id ASC");                               

                                    if(mysqli_num_rows($sqllmsQuest) > 0) {
                                        $qestSr1 = 0;
                                        $questions = array();
                                        $catTotal = 0;
                                        $catObt = 0;
                                        while($rowQuest	= mysqli_fetch_array($sqllmsQuest)) {
                                            $questions[] = $rowQuest;
                                            $qestSr1++;
                                            $catObt = $catObt + $rowQuest['rating'];
                                        }

                                        $catTotal = 5 * $qestSr1;
                                        
                                        $onLine = round($qestSr1 / 2);

                                        $perAch = round(($catObt / $catTotal ) * 100);

                                        $qestSr2 = 0;
                                        foreach($questions as $question) {
                                            $qestSr2++;
                                            echo '
                                            <tr style="font-size: 14px; text-align:center;">
                                                <td>'.$catSr.'.'.$qestSr2.'</td>
                                                <td style="text-align:left;">'.$question['question_name'].'</td>
                                                <td>'.$question['rating'].'</td>';
                                                if($qestSr2 == 1) {
                                                    echo'
                                                    <td rowspan="'.$qestSr1.'">
                                                        <center>
                                                            <table style="border-collapse:collapse; border:1px solid #666;" cellpadding="2" cellspacing="2" border="1" width="80%">
                                                                <tr>
                                                                    <td>Obtain Marks</td>
                                                                    <td style="text-align: right;">'.$catObt.'</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Total Marks</td>
                                                                    <td style="text-align: right;">'.$catTotal.'</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Acheivement</td>
                                                                    <td style="text-align: right;">'.$perAch.'%</td>
                                                                </tr>
                                                            </table>
                                                        </center>
                                                    </td>';
                                                }
                                                echo'
                                            </tr>';
                                        }
                                        // Grand Totals
                                        $totalMarks = $totalMarks + $catTotal;
                                        $totalObt = $totalObt + $catObt;

                                    }
                                }
                            }
                            // Grand Percentage
                            $totalPercentage = round(($totalObt / $totalMarks) * 100);

                            echo '
                        </table>
                        <table style="border-collapse:collapse; border:1px solid #666; margin-top: 3px; margin-right: 0px; margin-left: auto; right; font-size: 16px;" cellpadding="2" cellspacing="2" border="1" width="20%">
                            <tr>
                                <td>Overall Percentage</td>
                                <td style="text-align: center; font-weight: bold;">'.$totalPercentage.'%</td>    
                            </tr>
                            <tr>
                                <td>Overall Obt. Marks</td>
                                <td style="text-align: center; font-weight: bold;">'.$totalObt.'</td>
                            </tr>
                            <tr>
                                <td>Overall Total Marks</td>
                                <td style="text-align: center; font-weight: bold;">'.$totalMarks.'</td>
                            </tr>
                        </table>
                        <span style="font-size:9px;">issue by: '.cleanvars($_SESSION['userlogininfo']['LOGINNAME']).'</span>
                        <span style="font-size:9px; float:right; margin-top:3px;">issue Date: '.date("m/d/Y").'</span>
                    </div>

                    <div style="clear:both;"></div>
                </td>
            </tr>
        </table>';
    }
    echo '
</body>
<script type="text/javascript" language="javascript1.2">
<!--
 //Do print the page
if (typeof(window.print) != "undefined") {
    window.print();
}
-->
</script>
</html>';
?>