<?php
    $id_campus 		= ((isset($_POST['id_campus']) && !empty($_POST['id_campus'])))? cleanvars($_POST['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
	$campus_flag 	= ((!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])))? 'col-md-3': 'col-md-4';    

    
    $sqlCampLevel = $dblms->querylms("SELECT GROUP_CONCAT(l.level_classes) campus_classes
										FROM ".CAMPUS." c
										LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
										WHERE campus_id IN (".$id_campus.") ");
    $valCampLevel = mysqli_fetch_array($sqlCampLevel);

	$id_campus_classes 		= ((isset($valCampLevel['campus_classes']) && !empty($valCampLevel['campus_classes'])))? cleanvars($valCampLevel['campus_classes']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUSCLASSES']);


    $array 			= explode('|', $_POST['id_class']);
	$id_class 		= (!empty($array[0]))? $array[0]: '';
	$class_name 	= (!empty($array[1]))? $array[1]: '';

	$array 			= explode('|', $_POST['id_section']);
	$id_section 	= (!empty($array[0]))? $array[0]: '';
	$section_name 	= (!empty($array[1]))? $array[1]: '';

	$start_date 	= (!empty($_POST['start_date']))? $_POST['start_date']	: '';
	$end_date 		= (!empty($_POST['end_date']))? $_POST['end_date']		: '';

    echo '
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title"><i class="fa fa-list"></i> Fee Defaulter List</h2>
        </header>
        <div class="panel-body">
            <form action="#" method="POST" autocomplete="off">
                <div class="form-group mb-sm">';
                    if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
                        echo'
                        <div class="'.$campus_flag.'">				
                            <label class="control-label">Sub Campus</label>
                            <select class="form-control" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" onchange="get_class(this.value)"> 
                                <option value="">Select</option>';
                                $sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
                                                                FROM ".CAMPUS." 
                                                                WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
                                                                AND campus_status	= '1'
                                                                AND is_deleted		= '0'
                                                                ORDER BY campus_id ASC");
                                while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
                                    echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $id_campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
                                }
                                echo'
                            </select>
                        </div>';
                    endif;
                    echo'
                    <div class="'.$campus_flag.'">
                        <label class="control-label">Class </label>
                        <select data-plugin-selectTwo data-width="100%" name="id_class" id="id_class" onchange="get_section(this.value)" class="form-control">
                            <option value="">Select</option>';
                            $sqllms	= $dblms->querylms("SELECT class_id, class_name
                                                        FROM ".CLASSES." 
                                                        WHERE class_status = '1' 
                                                        AND is_deleted = '0' 
                                                        AND class_id IN (".$id_campus_classes.")
                                                        ORDER BY class_id ASC");
                            while($rowsvalues = mysqli_fetch_array($sqllms)){
                                echo'<option value="'.$rowsvalues['class_id'].'|'.$rowsvalues['class_name'].'" '.($rowsvalues['class_id']==$id_class ? 'selected' : '').'>'.$rowsvalues['class_name'].'</option>';		
                            }
                            echo'
                        </select> 
                    </div>
                    <div class="'.$campus_flag.'">
                        <label class="control-label">Section </label>
                        <select data-plugin-selectTwo data-width="100%" name="id_section" id="id_section" class="form-control populate">						
                            <option value="">Select</option>';
                            $sqllms	= $dblms->querylms("SELECT section_id, section_name
                                                        FROM ".CLASS_SECTIONS."
                                                        WHERE id_campus     = '".$id_campus."'
                                                        AND id_class		= '".$id_class."'
                                                        AND section_status	= '1'
                                                        AND is_deleted		= '0'
                                                        ORDER BY section_name ASC");
                            while($rowsvalues = mysqli_fetch_array($sqllms)){
                                echo'<option value="'.$rowsvalues['section_id'].'|'.$rowsvalues['section_name'].'" '.($rowsvalues['section_id'] == $id_section ? 'selected' : '').'>'.$rowsvalues['section_name'].'</option>';
                            }
                            echo'
                        </select>
                    </div>
                    <div class="'.$campus_flag.'">
                        <label class=" control-label">Date <span class="required">*</span></label>
                        <div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input type="text" class="form-control" required title="Must Be Required" value="'.$start_date.'" name="start_date">
                            <span class="input-group-addon">to</span>
                            <input type="text" class="form-control" required title="Must Be Required" value="'.$end_date.'" name="end_date">
                        </div>
                    </div>
                    <center>
                        <button type="submit" name="show" class="btn btn-primary mt-md"><i class="fa fa-search"></i> Search Result</button>
                    </center>
                </div>
            </form>
        </div>
    </section>';
    if (isset($_POST['show'])) {
        echo '
        <section class="panel panel-featured panel-featured-primary">
            <header class="panel-heading">
                <h2 class="panel-title"><i class="fa fa-pie-chart"></i> Report View</h2>
            </header> 
            <div class="panel-body">
                <div id="printResult">';
                    $sqCampus	= $dblms->querylms("SELECT campus_id, campus_name, campus_address, campus_phone, campus_email
                                                    FROM ".CAMPUS."
                                                    WHERE is_deleted = '0'
                                                    AND campus_id = $id_campus LIMIT 1");
                    $valCampus = mysqli_fetch_array($sqCampus);
                    echo '
                    <div id="header" style="display: none;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="3" class="center align-middle"><img src="uploads/images/campus/'.((!empty($valCampus['campus_logo']))? $valCampus['campus_logo']: $_SESSION['userlogininfo']['LOGINCAMPUSLOGO']).'" width="130"></th>
                                    <th colspan="8">
                                        <center>
                                            <h2><u>'.$valCampus['campus_name'].'<u></h5>
                                            <h5>'.$valCampus['campus_address'].'</h5>
                                        </center>
                                        <br>
                                        <span style="padding-right: 30%;">Contact No: '.$valCampus['campus_phone'].'</span>
                                        <span>Email: '.$valCampus['campus_email'].'</span>
                                    </th>
                                    <th colspan="3">
                                        <h3>
                                            Class Defaulter<br>
                                            Sheet<br>
                                            From:'.$start_date.'<br>
                                            To: '.$end_date.'
                                        </h3>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>';
                    $sqlDate 		= "";
                    $sqlCampus 		= "";
                    $sqlClass 		= "";
                    $sqlSection 	= "";
                    if (!empty($start_date) && !empty($end_date)) {
                        $sqlDate 	= "AND (f.paid_date BETWEEN '".date('Y-m-d' , strtotime(cleanvars($start_date)))."' AND '".date('Y-m-d' , strtotime(cleanvars($end_date)))."') ";
                        $sqlDate 	= "AND (f.issue_date BETWEEN '".date('Y-m-d' , strtotime(cleanvars($start_date)))."' AND '".date('Y-m-d' , strtotime(cleanvars($end_date)))."') ";
                    }
                    $sqlCampus 		=  (!empty($id_campus))		? "AND f.id_campus 		= '$id_campus'" 	: "";
                    $sqlClass 		=  (!empty($id_class))		? "AND f.id_class 		= '$id_class'" 		: "";
                    $sqlSection 	=  (!empty($id_section))	? "AND f.id_section 	= '$id_section'" 	: "";

                    $sqllmsFeeDefaulter	= $dblms->querylms("SELECT f.id, f.status, f.id_month, f.challan_no, f.issue_date, f.due_date, f.paid_date, f.total_amount,
                                                            c.class_name, s.session_name, st.std_id, st.std_name, st.std_regno,
                                                            st.std_name, st.std_phone, st.std_whatsapp, st.std_rollno, st.std_regno, st.std_fathername, st.std_fathername, st.std_nic, st.std_dob, st.std_admissiondate, cs.section_name
                                                            FROM ".FEES." f				   
                                                            INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
                                                            INNER JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section
                                                            INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session							 
                                                            INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std
                                                            WHERE f.status = '2'
                                                            $sqlDate $sqlCampus $sqlClass $sqlSection
                                                            AND f.is_deleted != '1'
                                                            GROUP BY f.id_std
                                                            ORDER BY f.id DESC");
                    if(mysqli_num_rows($sqllmsFeeDefaulter) > 0){
                        echo '
                        <style>
                            .ttable th, td {
                                border: 1px solid grey;
                                padding: 5px;
                            }
                        </style>
                        <table class="ttable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="60px;" style="text-align:center;">Sr#</th>
                                    <th >Reg No</th>
                                    <th >Roll No</th>
                                    <th >Class</th>
                                    <th >Section</th>
                                    <th >Student</th>
                                    <th >Father</th>
                                    <th >Contact</th>
                                    <th >WhatsApp</th>
                                    <th class="center">Last Challan</th>
                                    <th class="center">Date Of Issue</th>
                                    <th class="center">Due Date</th>
                                    <th class="center">Total Challan Amount</th>
                                    <th class="center">Current Challan Arrears</th>
                                    <th class="center">Old Challan Arrears</th>
                                    <th class="center">Follow Up Status</th>
                                </tr>
                            </thead>
                            <tbody>';
                                $srno = 0;
                                $totalChallanAmount = 0;
                                $remaining_amount = 0;
                                $curRemaining_amount = 0;
                                while($valueFee = mysqli_fetch_array($sqllmsFeeDefaulter)) {
                                    $srno++;
                                    echo '
                                    <tr>
                                        <td class="center">'.$srno.'</td>
                                        <td class="center">'.$valueFee['std_regno'].'</td>
                                        <td class="center">'.$valueFee['std_rollno'].'</td>
                                        <td>'.$valueFee['class_name'].'</td>
                                        <td>'.$valueFee['section_name'].'</td>
                                        <td>'.$valueFee['std_name'].'</td>
                                        <td>'.$valueFee['std_fathername'].'</td>
                                        <td>'.$valueFee['std_phone'].'</td>
                                        <td>'.$valueFee['std_whatsapp'].'</td>';
                                            $sql = "FROM ".FEES." f				   
                                                    WHERE f.status = '2'
                                                    $sqlCampus $sqlClass $sqlSection
                                                    AND f.id_std        = '".$valueFee['std_id']."'
                                                    AND f.is_deleted    = '0'
                                                    ORDER BY f.id DESC LIMIT 1";
                                            $sqlStd	= $dblms->querylms("SELECT f.id, f.challan_no, f.issue_date, f.due_date, f.paid_date, f.total_amount, f.remaining_amount $sql");
                                            $valueStd = mysqli_fetch_array($sqlStd);
                                            $totalChallanAmount += $valueStd['total_amount'];
                                        echo '
                                        <td>'.$valueStd['challan_no'].'</td>
                                        <td>'.$valueStd['issue_date'].'</td>
                                        <td>'.$valueStd['due_date'].'</td>
                                        <td class="center">'.number_format($valueStd['total_amount']).'</td>
                                        <td class="center">'.number_format($valueStd['remaining_amount']).'</td>';
                                            $sqlold = "SELECT SUM(f.remaining_amount) remaining_amount
                                                        FROM ".FEES." f				   
                                                        WHERE f.status = '2'
                                                        $sqlCampus $sqlClass $sqlSection
                                                        AND f.id_std        = '".$valueFee['std_id']."'
                                                        AND f.is_deleted    = '0'
                                                        AND f.challan_no   != '".$valueStd['challan_no']."'
                                                        ORDER BY f.id DESC";
                                            $sqlStdold	= $dblms->querylms($sqlold);
                                            $valueStdold = mysqli_fetch_array($sqlStdold);
                                            $remaining_amount += $valueStd['remaining_amount'];
                                            $curRemaining_amount += $valueStdold['remaining_amount'];
                                        echo '
                                        <td class="center">'.number_format($valueStdold['remaining_amount']).'</td>
                                        <td class="center"></td>
                                    </tr>';
                                }
                                echo '
                                <tr>
                                    <th colspan="12"><span class="pull-right">Total</span></th>
                                    <th class="center">'.number_format($totalChallanAmount).'</th>
                                    <th class="center">'.number_format($remaining_amount).'</th>
                                    <th class="center">'.number_format($curRemaining_amount).'</th>
                                    <th></th>
                                </tr>
                            </tbody>
                        </table>
                        <span id="printfooter" style="display: none;">
                            <span>This report is generated by <b>'.$_SESSION['userlogininfo']['LOGINNAME'].'</b> on <b>'.date('d M,Y').'</b></span><br><br>
                            <span style="padding-right: 30%;"><b>Printed by</b> __________________________</span>
                            <span><b>Principal</b> __________________________</span>
                        <span>';
                    }else{
                        echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
                    }
                    echo'
                </div>
                <div class="mt-sm on-screen" id="printBtn">
                    <button onclick="print_report(\'printResult\')" class="mr-xs btn btn-primary pull-right"><i class="glyphicon glyphicon-print"></i></button>
                </div>
            </div>
        </section>';
    }
?>