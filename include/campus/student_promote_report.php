<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))) {

    if(isset($_POST['class_from'])){$class_from = $_POST['class_from'];}else{$class_from = "";}
    if(isset($_POST['class_to'])){$class_to = $_POST['class_to'];}else{$class_to = "";}
    if(isset($_POST['start_date'])){$start_date = $_POST['start_date'];}else{$start_date = "";}
    if(isset($_POST['end_date'])){$end_date = $_POST['end_date'];}else{$end_date = "";}

    if(isset($_POST['id_campus']) && !empty($_POST['id_campus'])){
        $cps_array = explode('|', $_POST['id_campus']);
        $id_campus = cleanvars($cps_array[0]);
        $campus_name = cleanvars($cps_array[1]);
    }else{				
        $id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
        $campus_name = '';
    }

    echo'
    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
    <title>Student Promote Report| '.TITLE_HEADER.'</title>
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Student Promote Report</h2>
        </header>
        <section class="panel panel-featured panel-featured-primary">
            <header class="panel-heading">
                <h2 class="panel-title"><i class="fa fa-list"></i>  Select </h2>
            </header>
            <form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <div class="panel-body">
                    <div class="row mb-lg justify-content-md-center">';
                        if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
                            echo'
                            <div class="col-md-3">
                                <label class="control-label">Sub Campus</label>
                                <select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required">
                                    <option value="">Select</option>';
                                    $sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
                                                                    FROM ".CAMPUS." 
                                                                    WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
                                                                    AND campus_status	= '1'
                                                                    AND is_deleted		= '0'
                                                                    ORDER BY campus_id ASC");
                                    while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
                                        echo '<option value="'.$valSubCampus['campus_id'].'|'.$valSubCampus['campus_name'].'" '.($valSubCampus['campus_id'] == $id_campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
                                    }
                                    echo'
                                </select>
                            </div>';
                        endif;
                        echo'
                        <div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-3' : 'col-md-4').'">
                            <div class="form-group">
                                <label class=" control-label">Date <span class="required" aria-required="true">*</span></label>
                                <div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" required title="Must Be Required" name="start_date" value="'.$start_date.'">
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="form-control" required title="Must Be Required" name="end_date" value="'.$end_date.'">
                                </div>
                            </div>
                        </div>
                        <div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-3' : 'col-md-4').'">
                            <label class="control-label">Class From</label>
                            <select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="class_from" name="class_from">
                                <option value="">Select</option>';
                                $sqlclsFrom	= $dblms->querylms("SELECT sp.class_from, c.class_id, c.class_name 
                                                                FROM ".STD_PROMOTE_LOG." sp
                                                                INNER JOIN ".CLASSES." c ON c.class_id = sp.class_from
                                                                WHERE c.class_status    = '1' 
                                                                AND c.is_deleted        = '0'
                                                                GROUP BY sp.class_from
                                                                ORDER BY sp.class_from ASC");
                                while($valclsFrom = mysqli_fetch_array($sqlclsFrom)) {
                                    echo '<option value="'.$valclsFrom['class_id'].'" '.($valclsFrom['class_id'] == $class_from ? 'selected' : '').'>'.$valclsFrom['class_name'].'</option>';
                                }
                                echo'
                            </select>
                        </div>
                        <div class="'.(!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-3' : 'col-md-4').'">
                            <label class="control-label">Class To</label>
                            <select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="class_to" name="class_to">
                                <option value="">Select</option>';
                                $sqlclsTo	= $dblms->querylms("SELECT sp.class_to, c.class_id, c.class_name 
                                                                FROM ".STD_PROMOTE_LOG." sp
                                                                INNER JOIN ".CLASSES." c ON c.class_id = sp.class_to
                                                                WHERE c.class_status    = '1' 
                                                                AND c.is_deleted        = '0'
                                                                GROUP BY sp.class_to
                                                                ORDER BY sp.class_to ASC");
                                while($valclsTo = mysqli_fetch_array($sqlclsTo)) {
                                    echo '<option value="'.$valclsTo['class_id'].'" '.($valclsTo['class_id'] == $class_to ? 'selected' : '').'>'.$valclsTo['class_name'].'</option>';
                                }
                                echo'
                            </select>
                        </div>
                    </div>
                    <center>
                        <button type="submit" name="view_students" id="view_students" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
                    </center>
                </div>
            </form>
        </section>';

        if(isset($_POST['view_students'])){
            $sql1 = ((!empty($_POST['class_from']))  ? "AND sp.class_from   = ".$_POST['class_from']."": '');
            $sql2 = ((!empty($_POST['class_to']))    ? "AND sp.class_to     = ".$_POST['class_to']."": '');
            $sqllmsstudent	= $dblms->querylms("SELECT s.std_name, s.std_phone, s.std_whatsapp, s.std_rollno, s.std_regno, s.std_admissiondate, c.class_name, se.session_name, sp.*
                                                FROM ".STD_PROMOTE_LOG." sp
                                                INNER JOIN ".STUDENTS." s ON s.std_id = sp.id_std
                                                INNER JOIN ".CLASSES." c ON c.class_id = sp.class_from
                                                INNER JOIN ".SESSIONS." se ON se.session_id = sp.session_from
                                                WHERE s.std_status  = '1' 
                                                AND s.is_deleted    = '0' 
                                                AND sp.id_campus    = '".cleanvars($id_campus)."'
                                                AND (sp.dated BETWEEN '".date('Y-m-d' , strtotime(cleanvars($_POST['start_date'])))."' AND '".date('Y-m-d' , strtotime(cleanvars($_POST['end_date'])))."') 
                                                $sql1 $sql2");
            echo '
            <section class="panel panel-featured panel-featured-primary">
                <header class="panel-heading">
                    <h2 class="panel-title"><i class="fa fa-list"></i> Student Promote List From </h2>
                </header>
                <div class="panel-body">';  
                    if(mysqli_num_rows($sqllmsstudent) > 0){
                        echo '
                                <div id="printResult">
                                    <div class="invoice mt-md">
                                        <div class="table-responsive">  
                                            <div id="header" style="display:none;">
                                                <h2 style="text-align: center;"> <img src="'.$_SESSION['userlogininfo']['LOGINCAMPUSLOGO'].'" class="img-fluid" style="width: 60px;">  <span>
                                                    <b>'.$campus_name.'</b></span> 
                                                </h2>
                                                <h4 style="text-align: center;"><b>Student Promote Report</b></h4> <br>
                                            <div>
                                            <h5 class="mb-md"> '.(($class && $section) ? '<b>Class: </b>'.$rowsvalues['class_name'].' ('.$rowsvalues['section_name'].')' : '').' <span class="pull-right">'.($rowsvalues['id_session'] ? '<b>Session: </b>'.$_SESSION['userlogininfo']['ACA_SESSION_NAME'] : '').'</span> </h5>
                                        </div>
                                    </div>
                                    <table class="table invoice-items">
                                        <thead>
                                            <tr class="h5 text-dark">
                                                <th width="40">sr.</th>
                                                <th>Date</th>
                                                <th>Name</th>
                                                <th>Roll #</th>
                                                <th>Class From</th>
                                                <th>Class To</th>
                                                <th>Session From</th>
                                                <th>Session To</th>
                                                <th>Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                            $srno = 0;
                                            while($value_stu = mysqli_fetch_array($sqllmsstudent)){
                                                $sqlClsSess	= $dblms->querylms("SELECT c.class_name, se.session_name, sp.*
                                                                                        FROM ".STD_PROMOTE_LOG." sp
                                                                                        INNER JOIN ".CLASSES." c ON c.class_id = sp.class_to
                                                                                        INNER JOIN ".SESSIONS." se ON se.session_id = sp.session_to
                                                                                        AND sp.id_campus    = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                                                                        AND sp.id           = '".cleanvars($value_stu['id'])."'
                                                                                    ");
                                                $valClsSess = mysqli_fetch_array($sqlClsSess);
                                                $srno++;
                                                echo'
                                                <tr>
                                                    <td>'.$srno.'</td>
                                                    <td>'.date('d, M Y' , strtotime($value_stu['dated'])).'</td>
                                                    <td>'.$value_stu['std_name'].'</td>
                                                    <td>'.$value_stu['std_rollno'].'</td>
                                                    <td>'.$value_stu['class_name'].'</td>
                                                    <td>'.$valClsSess['class_name'].'</td>
                                                    <td>'.$value_stu['session_name'].'</td>
                                                    <td>'.$valClsSess['session_name'].'</td>
                                                    <td>'.$value_stu['reason'].'</td>
                                                </tr>';
                                            }
                                            echo '
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="text-right mr-lg on-screen">
                            <button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary"><i class="glyphicon glyphicon-print"></i></button>
                        </div>';
                    }
                    else{
                        echo '<h2 class="center">No Record Found</h2>';
                    }
                    echo'
                </div>
            </section>';
        }
        echo'
    </section>';
}
else{
	header("Location: dashboard.php");
}
?>
<script type="text/javascript">
    function print_report(printResult) {
		document.getElementById('header').style.display = 'block';
		var printContents = document.getElementById(printResult).innerHTML;
		var originalContents = document.body.innerHTML;
		document.body.innerHTML = printContents;
		var css = `@media print {
									@page {
										size: landscape;
										margin: 0;
									}
	
								}
				`,
		head = document.head || document.getElementsByTagName('head')[0],
		style = document.createElement('style');
		style.type = 'text/css';
		style.media = 'print';
		if (style.styleSheet){
		style.styleSheet.cssText = css;
		} else {
		style.appendChild(document.createTextNode(css));
		}
		head.appendChild(style);
		window.print();
		document.body.innerHTML = originalContents;
		document.getElementById('header').style.display = 'none';
	}
    jQuery(document).ready(function($) {	
        var datatable = $('#table_export').dataTable({
            bAutoWidth : false,
            ordering: false,
        });
    });
    function get_class(id_campus) {  
        $("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
        $.ajax({  
            type: "POST",  
            url: "include/ajax/get_class.php",  
            data: {
                      'id_campus'     : id_campus
                    , 'campus_flag'   : 1
                },
            success: function(msg){  
                $("#class_from").html(msg); 
                $("#class_to").html(msg); 
                $("#loading").html(''); 
            }
        });  
    }
</script>